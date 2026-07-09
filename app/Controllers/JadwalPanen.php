<?php

namespace App\Controllers;

use App\Models\JadwalPanenModel;
use App\Models\RumahWaletModel;
use App\Models\PetugasModel;

class JadwalPanen extends BaseController
{
    public function index()
    {
        $model  = new JadwalPanenModel();
        $status = $this->request->getGet('status');
        $periode = $this->request->getGet('periode');

        if (! empty($status)) {
            $model->where('jadwal_panen.status', $status);
        }
        if (! empty($periode)) {
            $model->where('jadwal_panen.periode', $periode);
        }

        $data = [
            'title'  => 'Jadwal Panen',
            'jadwal' => $model->getWithRelations(),
            'status' => $status,
            'periode'=> $periode,
        ];
        return $this->render('jadwal_panen/index', $data);
    }

    public function create()
    {
        $rumahModel   = new RumahWaletModel();
        $petugasModel = new PetugasModel();

        return $this->render('jadwal_panen/create', [
            'title'       => 'Tambah Jadwal Panen',
            'rumahList'   => $rumahModel->where('status', 'aktif')->findAll(),
            'petugasList' => $petugasModel->where('status', 'aktif')->findAll(),
            'tanggal_min' => date('Y-m-d'),
        ]);
    }

    public function store()
    {
        $rules = [
            'rumah_walet_id'  => 'required|integer',
            'tanggal_rencana' => 'required|valid_date',
            'estimasi_hasil_kg' => 'permit_empty|numeric',
            'status'          => 'required|in_list[terjadwal,selesai,ditunda,batal]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tanggalRencana = $this->request->getPost('tanggal_rencana');
        $periode = date('Y-m', strtotime($tanggalRencana));

        $data = [
            'rumah_walet_id'   => $this->request->getPost('rumah_walet_id'),
            'petugas_id'       => $this->request->getPost('petugas_id') ?: null,
            'tanggal_rencana'  => $tanggalRencana,
            'periode'          => $periode,
            'estimasi_hasil_kg'=> $this->request->getPost('estimasi_hasil_kg') ?: 0,
            'status'           => $this->request->getPost('status'),
            'catatan'          => $this->request->getPost('catatan'),
            'tanggal_aktual'   => $this->request->getPost('tanggal_aktual') ?: null,
            'created_by'       => session()->get('id'),
        ];

        $model = new JadwalPanenModel();
        $model->insert($data);
        $this->notifikasi('success', 'Jadwal panen berhasil dibuat');
        return redirect()->to('/jadwal-panen');
    }

    public function edit(int $id)
    {
        $model        = new JadwalPanenModel();
        $rumahModel   = new RumahWaletModel();
        $petugasModel = new PetugasModel();
        $jadwal       = $model->find($id);
        if (! $jadwal) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/jadwal-panen');
        }
        return $this->render('jadwal_panen/edit', [
            'title'       => 'Edit Jadwal Panen',
            'jadwal'      => $jadwal,
            'rumahList'   => $rumahModel->where('status', 'aktif')->findAll(),
            'petugasList' => $petugasModel->where('status', 'aktif')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $model = new JadwalPanenModel();
        $jadwal = $model->find($id);
        if (! $jadwal) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/jadwal-panen');
        }

        $rules = [
            'rumah_walet_id'  => 'required|integer',
            'tanggal_rencana' => 'required|valid_date',
            'estimasi_hasil_kg' => 'permit_empty|numeric',
            'status'          => 'required|in_list[terjadwal,selesai,ditunda,batal]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tanggalRencana = $this->request->getPost('tanggal_rencana');
        $periode = date('Y-m', strtotime($tanggalRencana));

        $data = [
            'rumah_walet_id'   => $this->request->getPost('rumah_walet_id'),
            'petugas_id'       => $this->request->getPost('petugas_id') ?: null,
            'tanggal_rencana'  => $tanggalRencana,
            'periode'          => $periode,
            'estimasi_hasil_kg'=> $this->request->getPost('estimasi_hasil_kg') ?: 0,
            'status'           => $this->request->getPost('status'),
            'catatan'          => $this->request->getPost('catatan'),
            'tanggal_aktual'   => $this->request->getPost('tanggal_aktual') ?: null,
        ];
        $model->update($id, $data);
        $this->notifikasi('success', 'Jadwal panen berhasil diperbarui');
        return redirect()->to('/jadwal-panen');
    }

    public function updateStatus(int $id, string $status)
    {
        $model = new JadwalPanenModel();
        $jadwal = $model->find($id);
        if (! $jadwal) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/jadwal-panen');
        }
        if (! in_array($status, ['terjadwal', 'selesai', 'ditunda', 'batal'])) {
            $this->notifikasi('error', 'Status tidak valid');
            return redirect()->to('/jadwal-panen');
        }

        $updateData = ['status' => $status];
        if ($status === 'selesai') {
            $updateData['tanggal_aktual'] = date('Y-m-d');
        }
        $model->update($id, $updateData);
        $this->notifikasi('success', 'Status jadwal panen diperbarui menjadi: ' . ucfirst($status));
        return redirect()->to('/jadwal-panen');
    }

    public function delete(int $id)
    {
        $model = new JadwalPanenModel();
        $model->delete($id);
        $this->notifikasi('success', 'Jadwal panen berhasil dihapus');
        return redirect()->to('/jadwal-panen');
    }
}
