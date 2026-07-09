<?php

namespace App\Controllers;

use App\Models\AudioWaletModel;
use App\Models\RumahWaletModel;

class AudioWalet extends BaseController
{
    public function index()
    {
        $model = new AudioWaletModel();
        $filters = [
            'rumah_walet_id' => $this->request->getGet('rumah_walet_id'),
            'dari'           => $this->request->getGet('dari'),
            'sampai'         => $this->request->getGet('sampai'),
        ];

        $rumahModel = new RumahWaletModel();

        return $this->render('audio_walet/index', [
            'title'       => 'Audio Walet',
            'audioList'   => $model->getWithRelations($filters),
            'rumahList'   => $rumahModel->getAktif(),
            'filters'     => $filters,
            'jenis_suara_list' => [
                'panggilan_dewasa' => 'Panggilan Dewasa',
                'panggilan_piyik'  => 'Panggilan Piyik',
                'suara_sarang'     => 'Suara Sarang',
                'kombinasi'        => 'Kombinasi',
            ],
        ]);
    }

    public function create()
    {
        $rumahModel = new RumahWaletModel();
        return $this->render('audio_walet/create', [
            'title'          => 'Input Audio Walet',
            'rumahList'      => $rumahModel->getAktif(),
            'tanggalHariIni' => date('Y-m-d'),
            'jenis_suara_list' => [
                'panggilan_dewasa' => 'Panggilan Dewasa',
                'panggilan_piyik'  => 'Panggilan Piyik',
                'suara_sarang'     => 'Suara Sarang',
                'kombinasi'        => 'Kombinasi',
            ],
        ]);
    }

    public function store()
    {
        $rules = [
            'rumah_walet_id'    => 'required|integer',
            'tanggal'           => 'required|valid_date',
            'jenis_suara'       => 'required|in_list[panggilan_dewasa,panggilan_piyik,suara_sarang,kombinasi]',
            'jam_nyala'         => 'required',
            'jam_mati'          => 'required',
            'volume'            => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'kondisi_speaker'   => 'required|in_list[baik,rusak_sebagian,rusak_total]',
            'kondisi_amplifier' => 'required|in_list[baik,rusak]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'rumah_walet_id'      => $this->request->getPost('rumah_walet_id'),
            'tanggal'             => $this->request->getPost('tanggal'),
            'jenis_suara'         => $this->request->getPost('jenis_suara'),
            'jam_nyala'           => $this->request->getPost('jam_nyala'),
            'jam_mati'            => $this->request->getPost('jam_mati'),
            'volume'              => $this->request->getPost('volume'),
            'kondisi_speaker'     => $this->request->getPost('kondisi_speaker'),
            'jumlah_speaker_aktif'=> $this->request->getPost('jumlah_speaker_aktif') ?: 0,
            'kondisi_amplifier'   => $this->request->getPost('kondisi_amplifier'),
            'catatan'             => $this->request->getPost('catatan'),
            'input_by'            => session()->get('id'),
        ];

        $model = new AudioWaletModel();
        $model->insert($data);
        $this->notifikasi('success', 'Catatan audio walet berhasil disimpan');
        return redirect()->to('/audio-walet');
    }

    public function view(int $id)
    {
        $model = new AudioWaletModel();
        $audio = $model->find($id);
        if (! $audio) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/audio-walet');
        }
        return $this->render('audio_walet/view', ['title' => 'Detail Audio Walet', 'audio' => $audio]);
    }

    public function edit(int $id)
    {
        $model = new AudioWaletModel();
        $rumahModel = new RumahWaletModel();
        $audio = $model->find($id);
        if (! $audio) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/audio-walet');
        }
        return $this->render('audio_walet/edit', [
            'title'     => 'Edit Audio Walet',
            'audio'     => $audio,
            'rumahList' => $rumahModel->getAktif(),
            'jenis_suara_list' => [
                'panggilan_dewasa' => 'Panggilan Dewasa',
                'panggilan_piyik'  => 'Panggilan Piyik',
                'suara_sarang'     => 'Suara Sarang',
                'kombinasi'        => 'Kombinasi',
            ],
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'rumah_walet_id'    => 'required|integer',
            'tanggal'           => 'required|valid_date',
            'jenis_suara'       => 'required|in_list[panggilan_dewasa,panggilan_piyik,suara_sarang,kombinasi]',
            'jam_nyala'         => 'required',
            'jam_mati'          => 'required',
            'volume'            => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'kondisi_speaker'   => 'required|in_list[baik,rusak_sebagian,rusak_total]',
            'kondisi_amplifier' => 'required|in_list[baik,rusak]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'rumah_walet_id'      => $this->request->getPost('rumah_walet_id'),
            'tanggal'             => $this->request->getPost('tanggal'),
            'jenis_suara'         => $this->request->getPost('jenis_suara'),
            'jam_nyala'           => $this->request->getPost('jam_nyala'),
            'jam_mati'            => $this->request->getPost('jam_mati'),
            'volume'              => $this->request->getPost('volume'),
            'kondisi_speaker'     => $this->request->getPost('kondisi_speaker'),
            'jumlah_speaker_aktif'=> $this->request->getPost('jumlah_speaker_aktif') ?: 0,
            'kondisi_amplifier'   => $this->request->getPost('kondisi_amplifier'),
            'catatan'             => $this->request->getPost('catatan'),
        ];

        $model = new AudioWaletModel();
        $model->update($id, $data);
        $this->notifikasi('success', 'Audio walet diperbarui');
        return redirect()->to('/audio-walet');
    }

    public function delete(int $id)
    {
        $model = new AudioWaletModel();
        $model->delete($id);
        $this->notifikasi('success', 'Audio walet dihapus');
        return redirect()->to('/audio-walet');
    }
}
