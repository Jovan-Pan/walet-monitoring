<?php

namespace App\Controllers;

use App\Models\RumahWaletModel;
use App\Models\HasilPanenModel;
use App\Models\PengeluaranModel;
use App\Models\InspeksiModel;

class RumahWalet extends BaseController
{
    public function index()
    {
        $model = new RumahWaletModel();
        $q = $this->request->getGet('q');

        if (! empty($q)) {
            $model->groupStart()
                ->like('kode', $q)
                ->orLike('nama', $q)
                ->orLike('lokasi', $q)
            ->groupEnd();
        }

        $data = [
            'title'  => 'Master Rumah Walet',
            'rumah'  => $model->orderBy('kode', 'ASC')->paginate(10, 'rumah'),
            'pager'  => $model->pager,
            'q'      => $q,
        ];
        return $this->render('rumah_walet/index', $data);
    }

    public function create()
    {
        $model = new RumahWaletModel();
        return $this->render('rumah_walet/create', [
            'title' => 'Tambah Rumah Walet',
            'kode_otomatis' => $model->generateKode(),
        ]);
    }

    public function store()
    {
        $rules = [
            'nama'           => 'required|min_length[3]',
            'lokasi'         => 'permit_empty',
            'luas'           => 'permit_empty|numeric',
            'jumlah_lantai'  => 'permit_empty|integer',
            'kapasitas_panen_kg' => 'permit_empty|numeric',
            'kondisi'        => 'required|in_list[baik,sedang,buruk]',
            'status'         => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new RumahWaletModel();
        $kode = $this->request->getPost('kode') ?: $model->generateKode();

        $data = [
            'kode'              => $kode,
            'nama'              => $this->request->getPost('nama'),
            'lokasi'            => $this->request->getPost('lokasi'),
            'latitude'          => $this->request->getPost('latitude') ?: null,
            'longitude'         => $this->request->getPost('longitude') ?: null,
            'luas'              => $this->request->getPost('luas') ?: null,
            'jumlah_lantai'     => $this->request->getPost('jumlah_lantai') ?: 1,
            'tahun_dibangun'    => $this->request->getPost('tahun_dibangun') ?: null,
            'kapasitas_panen_kg'=> $this->request->getPost('kapasitas_panen_kg') ?: null,
            'kondisi'           => $this->request->getPost('kondisi'),
            'tanggal_berdiri'   => $this->request->getPost('tanggal_berdiri') ?: null,
            'keterangan'        => $this->request->getPost('keterangan'),
            'status'            => $this->request->getPost('status'),
        ];

        $model->insert($data);
        $this->notifikasi('success', 'Rumah Walet berhasil ditambahkan');
        return redirect()->to('/rumah-walet');
    }

    public function edit(int $id)
    {
        $model = new RumahWaletModel();
        $rumah = $model->find($id);
        if (! $rumah) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/rumah-walet');
        }
        return $this->render('rumah_walet/edit', [
            'title' => 'Edit Rumah Walet',
            'rumah' => $rumah,
        ]);
    }

    public function update(int $id)
    {
        $model = new RumahWaletModel();
        $rumah = $model->find($id);
        if (! $rumah) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/rumah-walet');
        }

        $rules = [
            'nama'           => 'required|min_length[3]',
            'kondisi'        => 'required|in_list[baik,sedang,buruk]',
            'status'         => 'required|in_list[aktif,nonaktif]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'              => $this->request->getPost('nama'),
            'lokasi'            => $this->request->getPost('lokasi'),
            'latitude'          => $this->request->getPost('latitude') ?: null,
            'longitude'         => $this->request->getPost('longitude') ?: null,
            'luas'              => $this->request->getPost('luas') ?: null,
            'jumlah_lantai'     => $this->request->getPost('jumlah_lantai') ?: 1,
            'tahun_dibangun'    => $this->request->getPost('tahun_dibangun') ?: null,
            'kapasitas_panen_kg'=> $this->request->getPost('kapasitas_panen_kg') ?: null,
            'kondisi'           => $this->request->getPost('kondisi'),
            'tanggal_berdiri'   => $this->request->getPost('tanggal_berdiri') ?: null,
            'keterangan'        => $this->request->getPost('keterangan'),
            'status'            => $this->request->getPost('status'),
        ];

        $model->update($id, $data);
        $this->notifikasi('success', 'Rumah Walet berhasil diperbarui');
        return redirect()->to('/rumah-walet');
    }

    public function delete(int $id)
    {
        $model = new RumahWaletModel();
        $model->delete($id);
        $this->notifikasi('success', 'Rumah Walet berhasil dihapus');
        return redirect()->to('/rumah-walet');
    }

    public function detail(int $id)
    {
        $model = new RumahWaletModel();
        $rumah = $model->getDetailWithStats($id);
        if (! $rumah) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/rumah-walet');
        }

        // Riwayat panen 12 bulan terakhir
        $panenModel = new HasilPanenModel();
        $db = \Config\Database::connect();
        $riwayatPanen = $db->table('hasil_panen')
            ->select('hasil_panen.*, petugas.nama AS petugas_nama')
            ->join('petugas', 'petugas.id = hasil_panen.petugas_id', 'left')
            ->where('hasil_panen.rumah_walet_id', $id)
            ->orderBy('hasil_panen.tanggal_panen', 'DESC')
            ->limit(20)
            ->get()->getResultArray();

        // Riwayat inspeksi
        $inspeksiModel = new InspeksiModel();
        $riwayatInspeksi = $inspeksiModel->select('inspeksi.*, petugas.nama AS petugas_nama')
            ->join('petugas', 'petugas.id = inspeksi.petugas_id')
            ->where('inspeksi.rumah_walet_id', $id)
            ->orderBy('inspeksi.tanggal_inspeksi', 'DESC')
            ->limit(10)
            ->findAll();

        // Riwayat pengeluaran
        $pengeluaranModel = new PengeluaranModel();
        $riwayatPengeluaran = $pengeluaranModel
            ->where('rumah_walet_id', $id)
            ->orderBy('tanggal', 'DESC')
            ->limit(10)
            ->findAll();

        return $this->render('rumah_walet/detail', [
            'title'             => 'Detail Rumah Walet',
            'rumah'             => $rumah,
            'riwayatPanen'      => $riwayatPanen,
            'riwayatInspeksi'   => $riwayatInspeksi,
            'riwayatPengeluaran'=> $riwayatPengeluaran,
        ]);
    }
}
