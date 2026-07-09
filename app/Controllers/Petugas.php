<?php

namespace App\Controllers;

use App\Models\PetugasModel;
use App\Models\UserModel;
use App\Models\RumahWaletModel;

class Petugas extends BaseController
{
    public function index()
    {
        $model = new PetugasModel();
        $q = $this->request->getGet('q');

        if (! empty($q)) {
            $model->groupStart()
                ->like('nip', $q)
                ->orLike('nama', $q)
                ->orLike('no_hp', $q)
            ->groupEnd();
        }

        $data = [
            'title'   => 'Master Petugas',
            'petugas' => $model->getWithRelations(),
            'pager'   => $model->pager,
            'q'       => $q,
        ];
        return $this->render('petugas/index', $data);
    }

    public function create()
    {
        $rumahModel = new RumahWaletModel();
        return $this->render('petugas/create', [
            'title'    => 'Tambah Petugas',
            'rumahList'=> $rumahModel->getAktif(),
        ]);
    }

    public function store()
    {
        $rules = [
            'nip'            => 'required|is_unique[petugas.nip]',
            'nama'           => 'required|min_length[3]',
            'jenis_kelamin'  => 'required|in_list[L,P]',
            'tanggal_masuk'  => 'required|valid_date',
            'status'         => 'required|in_list[aktif,nonaktif]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new PetugasModel();
        $data = [
            'nip'           => $this->request->getPost('nip'),
            'nama'          => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat'        => $this->request->getPost('alamat'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'email'         => $this->request->getPost('email'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'status'        => $this->request->getPost('status'),
        ];
        $model->insert($data);

        // Tambahkan penugasan petugas-rumah jika dipilih
        $rumahWaletId = $this->request->getPost('rumah_walet_id');
        if (! empty($rumahWaletId)) {
            $petugasId = $model->getInsertID();
            $db = \Config\Database::connect();
            $db->table('petugas_rumah')->insert([
                'petugas_id'      => $petugasId,
                'rumah_walet_id'  => $rumahWaletId,
                'tanggal_mulai'   => date('Y-m-d'),
            ]);
        }

        $this->notifikasi('success', 'Data petugas berhasil ditambahkan');
        return redirect()->to('/petugas');
    }

    public function edit(int $id)
    {
        $model = new PetugasModel();
        $rumahModel = new RumahWaletModel();
        $petugas = $model->find($id);
        if (! $petugas) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/petugas');
        }

        // Penugasan aktif
        $penugasan = $model->getRumahDitugaskan($id);

        return $this->render('petugas/edit', [
            'title'      => 'Edit Petugas',
            'petugas'    => $petugas,
            'penugasan'  => $penugasan,
            'rumahList'  => $rumahModel->getAktif(),
        ]);
    }

    public function update(int $id)
    {
        $model = new PetugasModel();
        $petugas = $model->find($id);
        if (! $petugas) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/petugas');
        }

        $rules = [
            'nama'           => 'required|min_length[3]',
            'jenis_kelamin'  => 'required|in_list[L,P]',
            'tanggal_masuk'  => 'required|valid_date',
            'status'         => 'required|in_list[aktif,nonaktif]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nip'           => $this->request->getPost('nip'),
            'nama'          => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'  => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'alamat'        => $this->request->getPost('alamat'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'email'         => $this->request->getPost('email'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'status'        => $this->request->getPost('status'),
        ];
        $model->update($id, $data);
        $this->notifikasi('success', 'Data petugas berhasil diperbarui');
        return redirect()->to('/petugas');
    }

    public function delete(int $id)
    {
        $model = new PetugasModel();
        $model->delete($id);
        $this->notifikasi('success', 'Data petugas berhasil dihapus');
        return redirect()->to('/petugas');
    }
}
