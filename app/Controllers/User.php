<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $q = $this->request->getGet('q');

        $builder = $model;
        if (! empty($q)) {
            $builder = $model->groupStart()
                ->like('nama', $q)
                ->orLike('username', $q)
                ->orLike('email', $q)
            ->groupEnd();
        }
        $data = [
            'title' => 'Manajemen User',
            'users' => $builder->orderBy('id', 'ASC')->paginate(10, 'users'),
            'pager' => $model->pager,
            'q'     => $q,
        ];
        return $this->render('user/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah User'];
        return $this->render('user/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,petugas,owner]',
            'email'    => 'permit_empty|valid_email',
            'status'   => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
            'no_hp'    => $this->request->getPost('no_hp'),
            'status'   => $this->request->getPost('status'),
        ];

        $model->insert($data);
        $this->notifikasi('success', 'User berhasil ditambahkan');
        return redirect()->to('/user');
    }

    public function edit(int $id)
    {
        $model = new UserModel();
        $user = $model->find($id);
        if (! $user) {
            $this->notifikasi('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        return $this->render('user/edit', [
            'title' => 'Edit User',
            'user'  => $user,
        ]);
    }

    public function update(int $id)
    {
        $model = new UserModel();
        $user = $model->find($id);
        if (! $user) {
            $this->notifikasi('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        $rules = [
            'nama'   => 'required|min_length[3]',
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
            'role'   => 'required|in_list[admin,petugas,owner]',
            'email'  => 'permit_empty|valid_email',
            'status' => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'   => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email'  => $this->request->getPost('email'),
            'role'   => $this->request->getPost('role'),
            'no_hp'  => $this->request->getPost('no_hp'),
            'status' => $this->request->getPost('status'),
        ];

        // Password optional saat update
        $password = $this->request->getPost('password');
        if (! empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter');
            }
            $data['password'] = $password;
        }

        $model->update($id, $data);
        $this->notifikasi('success', 'User berhasil diperbarui');
        return redirect()->to('/user');
    }

    public function delete(int $id)
    {
        $model = new UserModel();
        $user = $model->find($id);

        if (! $user) {
            $this->notifikasi('error', 'User tidak ditemukan');
            return redirect()->to('/user');
        }

        // Cegah hapus diri sendiri
        if ($user['id'] == session()->get('id')) {
            $this->notifikasi('error', 'Tidak dapat menghapus akun sendiri');
            return redirect()->to('/user');
        }

        $model->delete($id);
        $this->notifikasi('success', 'User berhasil dihapus');
        return redirect()->to('/user');
    }
}
