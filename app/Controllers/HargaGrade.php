<?php

namespace App\Controllers;

use App\Models\HargaGradeModel;

class HargaGrade extends BaseController
{
    public function index()
    {
        $model = new HargaGradeModel();
        $hargaList = $model->orderBy('periode', 'DESC')
            ->orderBy('grade', 'ASC')
            ->orderBy('jenis_panen', 'ASC')
            ->findAll();

        // Group by periode
        $grouped = [];
        foreach ($hargaList as $h) {
            $grouped[$h['periode']][] = $h;
        }
        krsort($grouped);

        return $this->render('harga_grade/index', [
            'title'    => 'Master Harga per Grade',
            'grouped'  => $grouped,
        ]);
    }

    public function create()
    {
        return $this->render('harga_grade/create', [
            'title' => 'Tambah Master Harga',
        ]);
    }

    public function store()
    {
        $rules = [
            'grade'         => 'required|in_list[A,B,C]',
            'jenis_panen'   => 'required|in_list[urat,sarang_utuh,kecil]',
            'periode'       => 'required|regex_match[/^\d{4}-\d{2}$/]',
            'harga_min'     => 'required|decimal|greater_than[0]',
            'harga_max'     => 'required|decimal|greater_than[0]',
            'harga_default' => 'required|decimal|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $min = (float) $this->request->getPost('harga_min');
        $max = (float) $this->request->getPost('harga_max');
        $def = (float) $this->request->getPost('harga_default');

        if ($min > $max) {
            return redirect()->back()->withInput()->with('error', 'Harga minimum tidak boleh lebih besar dari harga maksimum');
        }
        if ($def < $min || $def > $max) {
            return redirect()->back()->withInput()->with('error', 'Harga default harus di antara min dan max');
        }

        $data = [
            'grade'         => $this->request->getPost('grade'),
            'jenis_panen'   => $this->request->getPost('jenis_panen'),
            'periode'       => $this->request->getPost('periode'),
            'harga_min'     => $min,
            'harga_max'     => $max,
            'harga_default' => $def,
        ];

        $model = new HargaGradeModel();

        // Cek duplikat
        $exists = $model->where('grade', $data['grade'])
            ->where('jenis_panen', $data['jenis_panen'])
            ->where('periode', $data['periode'])
            ->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Harga untuk kombinasi grade/jenis_panen/periode sudah ada. Edit saja yang existing.');
        }

        $model->insert($data);
        $this->notifikasi('success', 'Master harga ditambahkan');
        return redirect()->to('/harga-grade');
    }

    public function edit(int $id)
    {
        $model = new HargaGradeModel();
        $harga = $model->find($id);
        if (! $harga) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/harga-grade');
        }
        return $this->render('harga_grade/edit', [
            'title' => 'Edit Master Harga',
            'harga' => $harga,
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'harga_min'     => 'required|decimal|greater_than[0]',
            'harga_max'     => 'required|decimal|greater_than[0]',
            'harga_default' => 'required|decimal|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $min = (float) $this->request->getPost('harga_min');
        $max = (float) $this->request->getPost('harga_max');
        $def = (float) $this->request->getPost('harga_default');

        if ($min > $max) {
            return redirect()->back()->withInput()->with('error', 'Harga minimum tidak boleh lebih besar dari harga maksimum');
        }
        if ($def < $min || $def > $max) {
            return redirect()->back()->withInput()->with('error', 'Harga default harus di antara min dan max');
        }

        $model = new HargaGradeModel();
        $model->update($id, [
            'harga_min'     => $min,
            'harga_max'     => $max,
            'harga_default' => $def,
        ]);

        $this->notifikasi('success', 'Master harga diperbarui');
        return redirect()->to('/harga-grade');
    }

    public function delete(int $id)
    {
        $model = new HargaGradeModel();
        $model->delete($id);
        $this->notifikasi('success', 'Master harga dihapus');
        return redirect()->to('/harga-grade');
    }
}
