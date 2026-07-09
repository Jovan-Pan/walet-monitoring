<?php

namespace App\Controllers;

use App\Models\HasilPanenModel;
use App\Models\PengeluaranModel;
use App\Models\RumahWaletModel;
use Config\Constants;

class Laporan extends BaseController
{
    public function index()
    {
        return $this->render('laporan/index', ['title' => 'Laporan']);
    }

    public function panen()
    {
        $dari   = $this->request->getGet('dari') ?? date('Y-01-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');
        $grade  = $this->request->getGet('grade');
        $rumah  = $this->request->getGet('rumah_walet_id');

        $db = \Config\Database::connect();
        $builder = $db->table('hasil_panen')
            ->select('hasil_panen.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama')
            ->join('rumah_walet', 'rumah_walet.id = hasil_panen.rumah_walet_id')
            ->join('petugas', 'petugas.id = hasil_panen.petugas_id')
            ->where('hasil_panen.tanggal_panen >=', $dari)
            ->where('hasil_panen.tanggal_panen <=', $sampai);

        if (! empty($grade)) $builder->where('hasil_panen.grade', $grade);
        if (! empty($rumah)) $builder->where('hasil_panen.rumah_walet_id', $rumah);

        $rows = $builder->orderBy('hasil_panen.tanggal_panen', 'DESC')->get()->getResultArray();

        $rumahModel = new RumahWaletModel();
        return $this->render('laporan/panen', [
            'title'    => 'Laporan Hasil Panen',
            'data'     => $rows,
            'dari'     => $dari,
            'sampai'   => $sampai,
            'grade'    => $grade,
            'rumah_id' => $rumah,
            'rumahList'=> $rumahModel->findAll(),
            'total_kg' => array_sum(array_column($rows, 'berat_kg')),
            'total_nilai' => array_sum(array_map(function($r) { return $r['berat_kg'] * $r['harga_per_kg']; }, $rows)),
        ]);
    }

    public function pengeluaran()
    {
        $dari     = $this->request->getGet('dari') ?? date('Y-01-01');
        $sampai   = $this->request->getGet('sampai') ?? date('Y-m-d');
        $kategori = $this->request->getGet('kategori');

        $db = \Config\Database::connect();
        $builder = $db->table('pengeluaran')
            ->select('pengeluaran.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, users.nama AS input_nama')
            ->join('rumah_walet', 'rumah_walet.id = pengeluaran.rumah_walet_id', 'left')
            ->join('users', 'users.id = pengeluaran.input_by', 'left')
            ->where('pengeluaran.tanggal >=', $dari)
            ->where('pengeluaran.tanggal <=', $sampai);

        if (! empty($kategori)) $builder->where('pengeluaran.kategori', $kategori);

        $rows = $builder->orderBy('pengeluaran.tanggal', 'DESC')->get()->getResultArray();
        $rumahModel = new RumahWaletModel();

        return $this->render('laporan/pengeluaran', [
            'title'         => 'Laporan Pengeluaran',
            'data'          => $rows,
            'dari'          => $dari,
            'sampai'        => $sampai,
            'kategori'      => $kategori,
            'kategori_list' => Constants::KATEGORI_PENGELUARAN,
            'rumahList'     => $rumahModel->findAll(),
            'total'         => array_sum(array_column($rows, 'jumlah')),
        ]);
    }

    public function produktivitas()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));
        $db = \Config\Database::connect();

        $rows = $db->table('rumah_walet')
            ->select('rumah_walet.kode, rumah_walet.nama, rumah_walet.kapasitas_panen_kg, rumah_walet.kondisi,
                      COALESCE(SUM(hasil_panen.berat_kg),0) AS total_panen,
                      COALESCE(SUM(hasil_panen.berat_kg * hasil_panen.harga_per_kg),0) AS total_nilai,
                      COUNT(DISTINCT hasil_panen.id) AS jumlah_panen,
                      COALESCE(SUM(pengeluaran.jumlah),0) AS total_pengeluaran')
            ->join('hasil_panen', 'hasil_panen.rumah_walet_id = rumah_walet.id AND YEAR(hasil_panen.tanggal_panen) = ' . $tahun, 'left')
            ->join('pengeluaran', 'pengeluaran.rumah_walet_id = rumah_walet.id AND YEAR(pengeluaran.tanggal) = ' . $tahun, 'left')
            ->where('rumah_walet.status', 'aktif')
            ->groupBy('rumah_walet.id')
            ->orderBy('total_panen', 'DESC')
            ->get()->getResultArray();

        foreach ($rows as &$r) {
            $kapasitasTahunan = (float) ($r['kapasitas_panen_kg'] ?? 0) * 12;
            $r['persentase_kapasitas'] = $kapasitasTahunan > 0 
                ? min(100, ($r['total_panen'] / $kapasitasTahunan) * 100) : 0;
            $r['estimasi_keuntungan'] = $r['total_nilai'] - $r['total_pengeluaran'];
        }

        return $this->render('laporan/produktivitas', [
            'title' => 'Laporan Produktivitas',
            'data'  => $rows,
            'tahun' => $tahun,
        ]);
    }

    /* =============================================================
     * EXPORT METHODS (PDF & Excel)
     * ============================================================= */

    public function panenPDF()
    {
        $dari   = $this->request->getPost('dari') ?? date('Y-01-01');
        $sampai = $this->request->getPost('sampai') ?? date('Y-m-d');

        $db = \Config\Database::connect();
        $rows = $db->table('hasil_panen')
            ->select('hasil_panen.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama')
            ->join('rumah_walet', 'rumah_walet.id = hasil_panen.rumah_walet_id')
            ->join('petugas', 'petugas.id = hasil_panen.petugas_id')
            ->where('hasil_panen.tanggal_panen >=', $dari)
            ->where('hasil_panen.tanggal_panen <=', $sampai)
            ->orderBy('hasil_panen.tanggal_panen', 'DESC')
            ->get()->getResultArray();

        $html = view('laporan/pdf/panen', [
            'data'   => $rows,
            'dari'   => $dari,
            'sampai' => $sampai,
            'total_kg' => array_sum(array_column($rows, 'berat_kg')),
            'total_nilai' => array_sum(array_map(fn($r) => $r['berat_kg'] * $r['harga_per_kg'], $rows)),
        ]);

        return $this->generatePDF('Laporan_Hasil_Panen_' . date('Ymd'), $html);
    }

    public function panenExcel()
    {
        $dari   = $this->request->getPost('dari') ?? date('Y-01-01');
        $sampai = $this->request->getPost('sampai') ?? date('Y-m-d');

        $db = \Config\Database::connect();
        $rows = $db->table('hasil_panen')
            ->select('hasil_panen.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama')
            ->join('rumah_walet', 'rumah_walet.id = hasil_panen.rumah_walet_id')
            ->join('petugas', 'petugas.id = hasil_panen.petugas_id')
            ->where('hasil_panen.tanggal_panen >=', $dari)
            ->where('hasil_panen.tanggal_panen <=', $sampai)
            ->orderBy('hasil_panen.tanggal_panen', 'DESC')
            ->get()->getResultArray();

        $dataExport = [];
        $no = 1;
        $total_kg = 0; $total_nilai = 0;
        foreach ($rows as $r) {
            $dataExport[] = [
                $no++,
                format_tanggal($r['tanggal_panen']),
                $r['rw_kode'] . ' - ' . $r['rw_nama'],
                $r['petugas_nama'],
                $r['grade'],
                $r['berat_kg'],
                $r['harga_per_kg'],
                $r['berat_kg'] * $r['harga_per_kg'],
                $r['kualitas'] ?? '-',
                $r['catatan'] ?? '-',
            ];
            $total_kg += $r['berat_kg'];
            $total_nilai += $r['berat_kg'] * $r['harga_per_kg'];
        }

        $headers = ['No','Tanggal','Rumah Walet','Petugas','Grade','Berat (kg)','Harga/kg','Total Nilai','Kualitas','Catatan'];
        $dataExport[] = ['','','','','','TOTAL:', $total_kg, $total_nilai, '', ''];

        return $this->generateExcel('Laporan_Hasil_Panen', $headers, $dataExport, [
            'title' => 'LAPORAN HASIL PANEN SARANG WALET',
            'periode' => 'Periode: ' . format_tanggal($dari) . ' s/d ' . format_tanggal($sampai),
        ]);
    }

    public function pengeluaranPDF()
    {
        $dari   = $this->request->getPost('dari') ?? date('Y-01-01');
        $sampai = $this->request->getPost('sampai') ?? date('Y-m-d');

        $db = \Config\Database::connect();
        $rows = $db->table('pengeluaran')
            ->select('pengeluaran.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, users.nama AS input_nama')
            ->join('rumah_walet', 'rumah_walet.id = pengeluaran.rumah_walet_id', 'left')
            ->join('users', 'users.id = pengeluaran.input_by', 'left')
            ->where('pengeluaran.tanggal >=', $dari)
            ->where('pengeluaran.tanggal <=', $sampai)
            ->orderBy('pengeluaran.tanggal', 'DESC')
            ->get()->getResultArray();

        $html = view('laporan/pdf/pengeluaran', [
            'data'   => $rows,
            'dari'   => $dari,
            'sampai' => $sampai,
            'total'  => array_sum(array_column($rows, 'jumlah')),
        ]);

        return $this->generatePDF('Laporan_Pengeluaran_' . date('Ymd'), $html);
    }

    public function pengeluaranExcel()
    {
        $dari   = $this->request->getPost('dari') ?? date('Y-01-01');
        $sampai = $this->request->getPost('sampai') ?? date('Y-m-d');

        $db = \Config\Database::connect();
        $rows = $db->table('pengeluaran')
            ->select('pengeluaran.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama')
            ->join('rumah_walet', 'rumah_walet.id = pengeluaran.rumah_walet_id', 'left')
            ->where('pengeluaran.tanggal >=', $dari)
            ->where('pengeluaran.tanggal <=', $sampai)
            ->orderBy('pengeluaran.tanggal', 'DESC')
            ->get()->getResultArray();

        $dataExport = [];
        $no = 1; $total = 0;
        foreach ($rows as $r) {
            $dataExport[] = [
                $no++,
                format_tanggal($r['tanggal']),
                $r['rw_kode'] ? $r['rw_kode'] . ' - ' . $r['rw_nama'] : 'Umum',
                kategori_label($r['kategori']),
                $r['keterangan'],
                $r['jumlah'],
            ];
            $total += $r['jumlah'];
        }
        $dataExport[] = ['','','','','TOTAL:', $total];

        $headers = ['No','Tanggal','Rumah Walet','Kategori','Keterangan','Jumlah (Rp)'];
        return $this->generateExcel('Laporan_Pengeluaran', $headers, $dataExport, [
            'title' => 'LAPORAN PENGELUARAN OPERASIONAL',
            'periode' => 'Periode: ' . format_tanggal($dari) . ' s/d ' . format_tanggal($sampai),
        ]);
    }

    public function produktivitasPDF()
    {
        $tahun = (int) ($this->request->getPost('tahun') ?? date('Y'));
        $db = \Config\Database::connect();
        $rows = $db->table('rumah_walet')
            ->select('rumah_walet.kode, rumah_walet.nama, rumah_walet.kapasitas_panen_kg, rumah_walet.kondisi,
                      COALESCE(SUM(hasil_panen.berat_kg),0) AS total_panen,
                      COALESCE(SUM(hasil_panen.berat_kg * hasil_panen.harga_per_kg),0) AS total_nilai,
                      COALESCE(SUM(pengeluaran.jumlah),0) AS total_pengeluaran')
            ->join('hasil_panen', 'hasil_panen.rumah_walet_id = rumah_walet.id AND YEAR(hasil_panen.tanggal_panen) = ' . $tahun, 'left')
            ->join('pengeluaran', 'pengeluaran.rumah_walet_id = rumah_walet.id AND YEAR(pengeluaran.tanggal) = ' . $tahun, 'left')
            ->where('rumah_walet.status', 'aktif')
            ->groupBy('rumah_walet.id')
            ->orderBy('total_panen', 'DESC')
            ->get()->getResultArray();

        foreach ($rows as &$r) {
            $kapasitasTahunan = (float) ($r['kapasitas_panen_kg'] ?? 0) * 12;
            $r['persentase_kapasitas'] = $kapasitasTahunan > 0 ? min(100, ($r['total_panen'] / $kapasitasTahunan) * 100) : 0;
            $r['estimasi_keuntungan'] = $r['total_nilai'] - $r['total_pengeluaran'];
        }

        $html = view('laporan/pdf/produktivitas', ['data' => $rows, 'tahun' => $tahun]);
        return $this->generatePDF('Laporan_Produktivitas_' . $tahun, $html);
    }

    public function produktivitasExcel()
    {
        $tahun = (int) ($this->request->getPost('tahun') ?? date('Y'));
        $db = \Config\Database::connect();
        $rows = $db->table('rumah_walet')
            ->select('rumah_walet.kode, rumah_walet.nama, rumah_walet.kapasitas_panen_kg,
                      COALESCE(SUM(hasil_panen.berat_kg),0) AS total_panen,
                      COALESCE(SUM(hasil_panen.berat_kg * hasil_panen.harga_per_kg),0) AS total_nilai,
                      COALESCE(SUM(pengeluaran.jumlah),0) AS total_pengeluaran')
            ->join('hasil_panen', 'hasil_panen.rumah_walet_id = rumah_walet.id AND YEAR(hasil_panen.tanggal_panen) = ' . $tahun, 'left')
            ->join('pengeluaran', 'pengeluaran.rumah_walet_id = rumah_walet.id AND YEAR(pengeluaran.tanggal) = ' . $tahun, 'left')
            ->where('rumah_walet.status', 'aktif')
            ->groupBy('rumah_walet.id')
            ->orderBy('total_panen', 'DESC')
            ->get()->getResultArray();

        $dataExport = []; $no = 1;
        foreach ($rows as $r) {
            $kapasitasTahunan = (float) ($r['kapasitas_panen_kg'] ?? 0) * 12;
            $persentase = $kapasitasTahunan > 0 ? min(100, ($r['total_panen'] / $kapasitasTahunan) * 100) : 0;
            $dataExport[] = [
                $no++,
                $r['kode'],
                $r['nama'],
                $r['kapasitas_panen_kg'] ?? 0,
                $r['total_panen'],
                $r['total_nilai'],
                $r['total_pengeluaran'],
                $r['total_nilai'] - $r['total_pengeluaran'],
                round($persentase, 2) . '%',
            ];
        }

        $headers = ['No','Kode','Nama Rumah Walet','Kapasitas/Bln (kg)','Total Panen (kg)','Total Nilai (Rp)','Total Pengeluaran (Rp)','Estimasi Keuntungan (Rp)','% Kapasitas'];
        return $this->generateExcel('Laporan_Produktivitas_' . $tahun, $headers, $dataExport, [
            'title' => 'LAPORAN PRODUKTIVITAS RUMAH WALET',
            'periode' => 'Tahun: ' . $tahun,
        ]);
    }

    /* =============================================================
     * LAPORAN PENJUALAN (P1-2)
     * ============================================================= */
    public function penjualan()
    {
        $dari        = $this->request->getGet('dari') ?? date('Y-01-01');
        $sampai      = $this->request->getGet('sampai') ?? date('Y-m-d');
        $status_bayar = $this->request->getGet('status_bayar') ?? '';

        $db = \Config\Database::connect();

        $builder = $db->table('penjualan')
            ->select('penjualan.*,
                      (SELECT COUNT(*) FROM penjualan_detail WHERE penjualan_detail.penjualan_id = penjualan.id) AS jumlah_item,
                      (SELECT SUM(berat_kg) FROM penjualan_detail WHERE penjualan_detail.penjualan_id = penjualan.id) AS total_berat_kg')
            ->where('penjualan.tanggal >=', $dari)
            ->where('penjualan.tanggal <=', $sampai);

        if ($status_bayar !== '') {
            $builder->where('penjualan.status_bayar', $status_bayar);
        }

        $rows = $builder->orderBy('penjualan.tanggal', 'DESC')
            ->orderBy('penjualan.no_invoice', 'DESC')
            ->get()->getResultArray();

        // Compute summary
        $summary = [
            'total_invoice'    => count($rows),
            'total_nilai'      => 0,
            'total_lunas'      => 0,
            'total_belum_bayar'=> 0,
            'total_dp'         => 0,
            'total_berat'      => 0,
        ];
        foreach ($rows as $r) {
            $summary['total_nilai']       += (float) $r['total_nilai'];
            $summary['total_berat']       += (float) ($r['total_berat_kg'] ?? 0);
            if ($r['status_bayar'] === 'lunas') {
                $summary['total_lunas'] += (float) $r['total_nilai'];
            } elseif ($r['status_bayar'] === 'belum_bayar') {
                $summary['total_belum_bayar'] += (float) $r['total_nilai'];
            } elseif ($r['status_bayar'] === 'dp') {
                $summary['total_dp'] += (float) ($r['total_nilai'] - ($r['jumlah_bayar'] ?? 0));
            }
        }

        $status_list = [
            'belum_bayar' => 'Belum Bayar',
            'dp'          => 'DP',
            'lunas'       => 'Lunas',
        ];

        return $this->render('laporan/penjualan', [
            'title'        => 'Laporan Penjualan',
            'penjualan'    => $rows,
            'summary'      => $summary,
            'dari'         => $dari,
            'sampai'       => $sampai,
            'status_bayar' => $status_bayar,
            'status_list'  => $status_list,
        ]);
    }

    public function penjualanPDF()
    {
        $dari   = $this->request->getPost('dari') ?? date('Y-01-01');
        $sampai = $this->request->getPost('sampai') ?? date('Y-m-d');

        $db = \Config\Database::connect();
        $rows = $db->table('penjualan')
            ->select('penjualan.*,
                      (SELECT COUNT(*) FROM penjualan_detail WHERE penjualan_detail.penjualan_id = penjualan.id) AS jumlah_item,
                      (SELECT SUM(berat_kg) FROM penjualan_detail WHERE penjualan_detail.penjualan_id = penjualan.id) AS total_berat_kg')
            ->where('penjualan.tanggal >=', $dari)
            ->where('penjualan.tanggal <=', $sampai)
            ->orderBy('penjualan.tanggal', 'DESC')
            ->get()->getResultArray();

        $total_nilai = array_sum(array_column($rows, 'total_nilai'));
        $total_berat = array_sum(array_column($rows, 'total_berat_kg'));

        $html = '<h2 style="text-align:center;">LAPORAN PENJUALAN SARANG WALET</h2>'
              . '<p style="text-align:center;">Periode: ' . format_tanggal($dari) . ' s/d ' . format_tanggal($sampai) . '</p>'
              . '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">'
              . '<thead><tr style="background:#4F81BD; color:#fff;">'
              . '<th>No Invoice</th><th>Tanggal</th><th>Pembeli</th>'
              . '<th>Item</th><th>Berat (kg)</th><th>Total Nilai</th><th>Status</th>'
              . '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $html .= '<tr>'
                  . '<td>' . esc($r['no_invoice']) . '</td>'
                  . '<td>' . format_tanggal($r['tanggal'], 'd/m/Y') . '</td>'
                  . '<td>' . esc($r['pembeli_nama']) . '</td>'
                  . '<td style="text-align:right;">' . ($r['jumlah_item'] ?? 0) . '</td>'
                  . '<td style="text-align:right;">' . number_format($r['total_berat_kg'] ?? 0, 3) . '</td>'
                  . '<td style="text-align:right;">Rp ' . number_format($r['total_nilai'], 0, ',', '.') . '</td>'
                  . '<td>' . ucfirst(str_replace('_', ' ', $r['status_bayar'])) . '</td>'
                  . '</tr>';
        }
        $html .= '</tbody><tfoot><tr style="background:#f0f0f0; font-weight:bold;">'
              . '<td colspan="4" align="right">TOTAL</td>'
              . '<td style="text-align:right;">' . number_format($total_berat, 3) . ' kg</td>'
              . '<td style="text-align:right;">Rp ' . number_format($total_nilai, 0, ',', '.') . '</td>'
              . '<td></td>'
              . '</tr></tfoot></table>';
        $html .= '<p style="margin-top:15px; font-size:10px; color:#666;">Dicetak: ' . date('d/m/Y H:i:s') . '</p>';

        return $this->generatePDF('Laporan_Penjualan_' . date('Ymd'), $html);
    }

    public function penjualanExcel()
    {
        $dari   = $this->request->getPost('dari') ?? date('Y-01-01');
        $sampai = $this->request->getPost('sampai') ?? date('Y-m-d');

        $db = \Config\Database::connect();
        $rows = $db->table('penjualan')
            ->select('penjualan.*,
                      (SELECT COUNT(*) FROM penjualan_detail WHERE penjualan_detail.penjualan_id = penjualan.id) AS jumlah_item,
                      (SELECT SUM(berat_kg) FROM penjualan_detail WHERE penjualan_detail.penjualan_id = penjualan.id) AS total_berat_kg')
            ->where('penjualan.tanggal >=', $dari)
            ->where('penjualan.tanggal <=', $sampai)
            ->orderBy('penjualan.tanggal', 'DESC')
            ->get()->getResultArray();

        $dataExport = []; $no = 1;
        $total_nilai = 0; $total_berat = 0;
        foreach ($rows as $r) {
            $dataExport[] = [
                $no++,
                $r['no_invoice'],
                format_tanggal($r['tanggal'], 'd/m/Y'),
                $r['pembeli_nama'],
                $r['pembeli_kontak'] ?? '-',
                $r['jumlah_item'] ?? 0,
                $r['total_berat_kg'] ?? 0,
                $r['total_nilai'],
                ucfirst(str_replace('_', ' ', $r['status_bayar'])),
                ! empty($r['tanggal_bayar']) ? format_tanggal($r['tanggal_bayar'], 'd/m/Y') : '-',
            ];
            $total_nilai += $r['total_nilai'];
            $total_berat += $r['total_berat_kg'] ?? 0;
        }
        $dataExport[] = ['','','','','','TOTAL:', $total_berat, $total_nilai, '', ''];

        $headers = ['No','No Invoice','Tanggal','Pembeli','Kontak','Item','Berat (kg)','Total Nilai (Rp)','Status','Tgl Bayar'];
        return $this->generateExcel('Laporan_Penjualan', $headers, $dataExport, [
            'title'   => 'LAPORAN PENJUALAN SARANG WALET',
            'periode' => 'Periode: ' . format_tanggal($dari) . ' s/d ' . format_tanggal($sampai),
        ]);
    }

    /* =============================================================
     * HELPER: Generate PDF (TCPDF)
     * ============================================================= */
    private function generatePDF(string $filename, string $html)
    {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Monitoring Walet');
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle($filename);
        $pdf->SetHeaderData('', 0, 'Sistem Monitoring Walet', date('d/m/Y H:i'));
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 10, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($filename . '.pdf', 'D');
        exit;
    }

    /* =============================================================
     * HELPER: Generate Excel (PhpSpreadsheet)
     * ============================================================= */
    private function generateExcel(string $filename, array $headers, array $data, array $meta = [])
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Meta info
        $row = 1;
        if (! empty($meta['title'])) {
            $sheet->setCellValue('A' . $row, $meta['title']);
            $sheet->mergeCells('A' . $row . ':' . chr(64 + count($headers)) . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
            $row++;
        }
        if (! empty($meta['periode'])) {
            $sheet->setCellValue('A' . $row, $meta['periode']);
            $sheet->mergeCells('A' . $row . ':' . chr(64 + count($headers)) . $row);
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
            $row++;
        }
        $row++; // spacer

        // Header
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $row, $h);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('4F81BD');
            $sheet->getStyle($col . $row)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal('center');
            $col++;
        }
        $row++;

        // Data
        foreach ($data as $dataRow) {
            $col = 'A';
            foreach ($dataRow as $val) {
                $sheet->setCellValue($col . $row, $val);
                $col++;
            }
            $row++;
        }

        // Auto size columns
        foreach (range('A', chr(64 + count($headers))) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // Output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
}
