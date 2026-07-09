<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\HasilPanenModel;
use App\Models\RumahWaletModel;
use App\Models\StokSarangModel;

class Penjualan extends BaseController
{
    public function index()
    {
        $model = new PenjualanModel();
        $filters = [
            'dari'        => $this->request->getGet('dari'),
            'sampai'      => $this->request->getGet('sampai'),
            'status_bayar'=> $this->request->getGet('status_bayar'),
            'pembeli'     => $this->request->getGet('pembeli'),
        ];

        $penjualanList = $model->getAllFiltered($filters);

        // Ambil details untuk setiap penjualan
        $detailModel = new PenjualanDetailModel();
        foreach ($penjualanList as &$p) {
            $p['details'] = $detailModel->getByPenjualanId($p['id']);
            $p['jumlah_item'] = count($p['details']);
        }

        return $this->render('penjualan/index', [
            'title'         => 'Penjualan / Invoice',
            'penjualanList' => $penjualanList,
            'filters'       => $filters,
            'status_list'   => [
                'belum_bayar' => 'Belum Bayar',
                'dp'          => 'DP',
                'lunas'       => 'Lunas',
            ],
        ]);
    }

    public function create()
    {
        $stokModel = new StokSarangModel();
        $stokTersedia = $stokModel->where('status_stok', 'tersedia')
            ->where('deleted_at IS NULL')
            ->orderBy('tanggal_masuk', 'ASC')
            ->findAll();

        // Group by RW + grade untuk display
        $stokGrouped = [];
        foreach ($stokTersedia as $s) {
            $key = $s['rumah_walet_id'] . '-' . $s['grade'] . '-' . $s['jenis_panen'];
            if (! isset($stokGrouped[$key])) {
                $stokGrouped[$key] = $s;
                $stokGrouped[$key]['items'] = [];
            }
            $stokGrouped[$key]['items'][] = $s;
            $stokGrouped[$key]['total_berat'] = ($stokGrouped[$key]['total_berat'] ?? 0) + $s['berat_kg'];
        }

        $model = new PenjualanModel();

        return $this->render('penjualan/create', [
            'title'         => 'Buat Invoice Penjualan',
            'stokGrouped'   => array_values($stokGrouped),
            'no_invoice'    => $model->generateNoInvoice(),
            'tanggalHariIni'=> date('Y-m-d'),
        ]);
    }

    public function store()
    {
        $rules = [
            'tanggal'      => 'required|valid_date',
            'pembeli_nama' => 'required|min_length[3]',
            'status_bayar' => 'required|in_list[belum_bayar,dp,lunas]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $items = $this->request->getPost('items'); // array of {hasil_panen_id, grade, jenis_panen, berat, harga}
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal 1 item stok untuk dijual');
        }

        $model = new PenjualanModel();
        $detailModel = new PenjualanDetailModel();
        $stokModel = new StokSarangModel();
        $hasilModel = new HasilPanenModel();

        // Calculate totals
        $totalBerat = 0;
        $totalNilai = 0;
        foreach ($items as $item) {
            if (empty($item['hasil_panen_id']) || empty($item['berat']) || empty($item['harga'])) continue;
            $totalBerat += (float) $item['berat'];
            $totalNilai += (float) $item['berat'] * (float) $item['harga'];
        }

        if ($totalBerat <= 0) {
            return redirect()->back()->withInput()->with('error', 'Total berat harus > 0');
        }

        // P1-8: Transaction - insert penjualan + details + update stok + update hasil_panen
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $noInvoice = $model->generateNoInvoice();
            $tanggal = $this->request->getPost('tanggal');

            $penjualanId = $model->insert([
                'no_invoice'      => $noInvoice,
                'tanggal'         => $tanggal,
                'pembeli_nama'    => $this->request->getPost('pembeli_nama'),
                'pembeli_kontak'  => $this->request->getPost('pembeli_kontak'),
                'pembeli_alamat'  => $this->request->getPost('pembeli_alamat'),
                'total_berat_kg'  => $totalBerat,
                'total_nilai'     => $totalNilai,
                'status_bayar'    => $this->request->getPost('status_bayar'),
                'tanggal_bayar'   => $this->request->getPost('status_bayar') === 'lunas' ? $tanggal : null,
                'metode_bayar'    => $this->request->getPost('metode_bayar'),
                'catatan'         => $this->request->getPost('catatan'),
                'input_by'        => session()->get('id'),
            ]);

            // Insert details + update stok
            foreach ($items as $item) {
                if (empty($item['hasil_panen_id'])) continue;

                $hp = $hasilModel->find($item['hasil_panen_id']);

                $detailModel->insert([
                    'penjualan_id'   => $penjualanId,
                    'hasil_panen_id' => $item['hasil_panen_id'],
                    'rumah_walet_id' => $hp['rumah_walet_id'] ?? null,
                    'grade'          => $item['grade'],
                    'jenis_panen'    => $item['jenis_panen'],
                    'berat_kg'       => $item['berat'],
                    'harga_per_kg'   => $item['harga'],
                    'catatan'        => $item['catatan'] ?? null,
                ]);

                // Update stok jadi terjual
                $stokModel->markAsSold($item['hasil_panen_id'], $penjualanId, $tanggal);

                // Update hasil_panen.pembeli_id dan status_stok
                $hasilModel->update($item['hasil_panen_id'], [
                    'pembeli_id'  => $penjualanId,
                    'status_stok' => 'terjual',
                ]);
            }

            $db->transCommit();

            $this->notifikasi('success', "Invoice {$noInvoice} berhasil dibuat. Total: Rp " . number_format($totalNilai, 0, ',', '.'));
            return redirect()->to('/penjualan/view/' . $penjualanId);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Penjualan::store gagal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function view(int $id)
    {
        $model = new PenjualanModel();
        $penjualan = $model->getWithDetails($id);
        if (! $penjualan) {
            $this->notifikasi('error', 'Invoice tidak ditemukan');
            return redirect()->to('/penjualan');
        }

        return $this->render('penjualan/view', [
            'title'     => 'Detail Invoice ' . $penjualan['no_invoice'],
            'penjualan' => $penjualan,
        ]);
    }

    /**
     * Mark invoice as paid (lunas)
     */
    public function markPaid(int $id)
    {
        $model = new PenjualanModel();
        $penjualan = $model->find($id);
        if (! $penjualan) {
            $this->notifikasi('error', 'Invoice tidak ditemukan');
            return redirect()->to('/penjualan');
        }

        $tanggalBayar = $this->request->getPost('tanggal_bayar') ?: date('Y-m-d');
        $metodeBayar  = $this->request->getPost('metode_bayar') ?: 'transfer';

        $model->update($id, [
            'status_bayar'  => 'lunas',
            'tanggal_bayar' => $tanggalBayar,
            'metode_bayar'  => $metodeBayar,
        ]);

        $this->notifikasi('success', 'Invoice ditandai LUNAS');
        return redirect()->to('/penjualan/view/' . $id);
    }

    /**
     * Cetak invoice PDF (P1-2: cetak invoice)
     */
    public function invoicePDF(int $id)
    {
        $model = new PenjualanModel();
        $penjualan = $model->getWithDetails($id);
        if (! $penjualan) {
            $this->notifikasi('error', 'Invoice tidak ditemukan');
            return redirect()->to('/penjualan');
        }

        $tcpdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->SetCreator('Sistem Monitoring Walet');
        $tcpdf->SetAuthor('Walet Pro');
        $tcpdf->SetTitle('Invoice ' . $penjualan['no_invoice']);
        $tcpdf->SetPrintHeader(false);
        $tcpdf->SetPrintFooter(false);
        $tcpdf->AddPage();

        $html = view('penjualan/invoice_pdf', ['penjualan' => $penjualan]);
        $tcpdf->writeHTML($html, true, false, true, false, '');
        $tcpdf->Output('Invoice-' . $penjualan['no_invoice'] . '.pdf', 'I');
        exit;
    }

    public function edit(int $id)
    {
        // Untuk simplicity, edit invoice tidak diimplement di phase awal
        // Bisa hapus + buat baru
        $this->notifikasi('warning', 'Edit invoice belum tersedia. Hapus dan buat baru jika perlu.');
        return redirect()->to('/penjualan');
    }

    public function update(int $id)
    {
        return redirect()->to('/penjualan');
    }

    public function delete(int $id)
    {
        $model = new PenjualanModel();
        $detailModel = new PenjualanDetailModel();
        $stokModel = new StokSarangModel();
        $hasilModel = new HasilPanenModel();

        $penjualan = $model->find($id);
        if (! $penjualan) {
            $this->notifikasi('error', 'Invoice tidak ditemukan');
            return redirect()->to('/penjualan');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Restore stok: kembalikan status jadi tersedia
            $details = $detailModel->where('penjualan_id', $id)->findAll();
            foreach ($details as $d) {
                if (! empty($d['hasil_panen_id'])) {
                    $stokModel->where('hasil_panen_id', $d['hasil_panen_id'])
                        ->where('penjualan_id', $id)
                        ->update(null, [
                            'penjualan_id'   => null,
                            'tanggal_keluar' => null,
                            'status_stok'    => 'tersedia',
                        ]);

                    $hasilModel->update($d['hasil_panen_id'], [
                        'pembeli_id'  => null,
                        'status_stok' => 'di_gudang_rw',
                    ]);
                }
            }

            // Hapus details
            $detailModel->where('penjualan_id', $id)->delete();
            // Soft delete penjualan
            $model->delete($id);

            $db->transCommit();
            $this->notifikasi('success', 'Invoice dihapus, stok dikembalikan');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Penjualan::delete gagal: ' . $e->getMessage());
            $this->notifikasi('error', 'Gagal hapus invoice: ' . $e->getMessage());
        }

        return redirect()->to('/penjualan');
    }
}
