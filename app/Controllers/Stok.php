<?php

namespace App\Controllers;

use App\Models\StokSarangModel;
use App\Models\RumahWaletModel;

class Stok extends BaseController
{
    public function index()
    {
        $model = new StokSarangModel();
        $filters = [
            'status_stok'    => $this->request->getGet('status_stok') ?: 'tersedia',
            'lokasi_gudang'  => $this->request->getGet('lokasi_gudang'),
            'rumah_walet_id' => $this->request->getGet('rumah_walet_id'),
            'grade'          => $this->request->getGet('grade'),
        ];

        $stokList = $model->getWithRelations($filters);
        $summary  = $model->getSummary();

        // Rekap per grade + lokasi
        $rekapTersedia = ['A' => 0, 'B' => 0, 'C' => 0];
        $rekapTerjual  = ['A' => 0, 'B' => 0, 'C' => 0];
        $totalNilaiTersedia = 0;

        foreach ($stokList as $s) {
            if ($s['status_stok'] === 'tersedia') {
                $rekapTersedia[$s['grade']] += $s['berat_kg'];
            } elseif ($s['status_stok'] === 'terjual') {
                $rekapTerjual[$s['grade']] += $s['berat_kg'];
            }
        }

        $rumahModel = new RumahWaletModel();

        return $this->render('stok/index', [
            'title'             => 'Stok Sarang Walet',
            'stokList'          => $stokList,
            'summary'           => $summary,
            'rekapTersedia'     => $rekapTersedia,
            'rekapTerjual'      => $rekapTerjual,
            'totalNilaiTersedia'=> $totalNilaiTersedia,
            'rumahList'         => $rumahModel->getAktif(),
            'filters'           => $filters,
        ]);
    }

    /**
     * Move stok antar gudang (RW → Pusat)
     */
    public function moveForm(int $id)
    {
        $model = new StokSarangModel();
        $stok = $model->find($id);
        if (! $stok) {
            $this->notifikasi('error', 'Stok tidak ditemukan');
            return redirect()->to('/stok');
        }
        return $this->render('stok/move', [
            'title' => 'Pindah Stok Antar Gudang',
            'stok'  => $stok,
        ]);
    }

    public function move(int $id)
    {
        $model = new StokSarangModel();
        $stok = $model->find($id);
        if (! $stok) {
            $this->notifikasi('error', 'Stok tidak ditemukan');
            return redirect()->to('/stok');
        }

        $lokasi = $this->request->getPost('lokasi_gudang');
        if (! in_array($lokasi, ['gudang_rw', 'gudang_pusat'])) {
            $this->notifikasi('error', 'Lokasi gudang tidak valid');
            return redirect()->back();
        }

        $model->update($id, [
            'lokasi_gudang' => $lokasi,
            'status_stok'   => $lokasi === 'gudang_pusat' ? 'pindah_gudang' : 'tersedia',
            'catatan'       => $this->request->getPost('catatan'),
        ]);

        $this->notifikasi('success', 'Stok berhasil dipindahkan ke ' . ($lokasi === 'gudang_pusat' ? 'Gudang Pusat' : 'Gudang RW'));
        return redirect()->to('/stok');
    }

    /**
     * Stock opname - listing fisik vs sistem
     */
    public function opname()
    {
        $model = new StokSarangModel();
        $stokTersedia = $model->where('status_stok', 'tersedia')
            ->where('deleted_at IS NULL')
            ->orderBy('rumah_walet_id', 'ASC')
            ->orderBy('tanggal_masuk', 'ASC')
            ->findAll();

        return $this->render('stok/opname', [
            'title'        => 'Stock Opname',
            'stokTersedia' => $stokTersedia,
        ]);
    }
}
