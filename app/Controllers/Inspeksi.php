<?php

namespace App\Controllers;

use App\Models\InspeksiModel;
use App\Models\RumahWaletModel;
use App\Models\PetugasModel;
use Config\Constants;

class Inspeksi extends BaseController
{
    public function index()
    {
        $model = new InspeksiModel();
        $filters = [
            'rumah_walet_id' => $this->request->getGet('rumah_walet_id'),
            'dari'           => $this->request->getGet('dari'),
            'sampai'         => $this->request->getGet('sampai'),
        ];

        // P1-7: Pagination
        $allData = $model->getWithRelations($filters);
        $perPage = 20;
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $total = count($allData);
        $offset = ($page - 1) * $perPage;

        $rumahModel = new RumahWaletModel();

        return $this->render('inspeksi/index', [
            'title'        => 'Inspeksi Rumah Walet',
            'inspeksiList' => array_slice($allData, $offset, $perPage),
            'rumahList'    => $rumahModel->getAktif(),
            'filters'      => $filters,
            'fase_list'    => [
                'kosong'       => 'Kosong',
                'pembentukan'  => 'Pembentukan Sarang',
                'bertelur'     => 'Bertelur',
                'menetas'      => 'Menetas',
                'piyik'        => 'Piyik',
                'siap_panen'   => 'Siap Panen',
            ],
            'currentPage'  => $page,
            'totalPages'   => (int) ceil($total / $perPage),
            'total'        => $total,
        ]);
    }

    public function create()
    {
        $rumahModel   = new RumahWaletModel();
        $petugasModel = new PetugasModel();

        // Auto-fill petugas dari user yang login
        $currentUserId = session()->get('id');
        $currentPetugas = $petugasModel->findByUserId($currentUserId);

        return $this->render('inspeksi/create', [
            'title'          => 'Tambah Inspeksi',
            'rumahList'      => $rumahModel->getAktif(),
            'petugasList'    => $petugasModel->where('status', 'aktif')->findAll(),
            'currentPetugas' => $currentPetugas,
            'tanggalHariIni' => date('Y-m-d'),
            'fase_list'      => [
                'kosong'       => 'Kosong',
                'pembentukan'  => 'Pembentukan Sarang',
                'bertelur'     => 'Bertelur',
                'menetas'      => 'Menetas',
                'piyik'        => 'Piyik',
                'siap_panen'   => 'Siap Panen',
            ],
            'predator_list'  => Constants::PREDATOR_LIST,
            'tingkat_list'   => Constants::TINGKAT_INFESTASI,
        ]);
    }

    public function store()
    {
        $rules = [
            'rumah_walet_id'    => 'required|integer',
            'petugas_id'        => 'required|integer',
            'tanggal_inspeksi'  => 'required|valid_date',
            'kondisi_bangunan'  => 'required|in_list[baik,sedang,buruk]',
            'kondisi_sarang'    => 'required|in_list[baik,sedang,buruk]',
            'kebersihan'        => 'required|in_list[baik,sedang,buruk]',
            'populasi_walet'    => 'permit_empty|integer',
            'suhu'              => 'permit_empty|decimal',
            'kelembaban'        => 'permit_empty|decimal',
            'fase_sarang'       => 'permit_empty|in_list[kosong,pembentukan,bertelur,menetas,piyik,siap_panen]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'rumah_walet_id'      => $this->request->getPost('rumah_walet_id'),
            'petugas_id'          => $this->request->getPost('petugas_id'),
            'tanggal_inspeksi'    => $this->request->getPost('tanggal_inspeksi'),
            'kondisi_bangunan'    => $this->request->getPost('kondisi_bangunan'),
            'kondisi_sarang'      => $this->request->getPost('kondisi_sarang'),
            'kebersihan'          => $this->request->getPost('kebersihan'),
            'populasi_walet'      => $this->request->getPost('populasi_walet') ?: 0,
            'suhu'                => $this->request->getPost('suhu') ?: null,
            'kelembaban'          => $this->request->getPost('kelembaban') ?: null,
            'fase_sarang'         => $this->request->getPost('fase_sarang') ?: 'kosong',
            'cahaya_lux'          => $this->request->getPost('cahaya_lux') ?: null,
            'ketinggian_sarang_cm'=> $this->request->getPost('ketinggian_sarang_cm') ?: null,
            'humidifier_status'   => $this->request->getPost('humidifier_status'),
            'audio_player_status' => $this->request->getPost('audio_player_status'),
            'catatan'             => $this->request->getPost('catatan'),
        ];

        // Auto-calculate status
        $model = new InspeksiModel();
        $data['status'] = $model->hitungStatus($data);

        // Predator data (P2-3)
        $predators = $this->request->getPost('predators') ?? [];
        $predatorData = [];
        foreach ($predators as $p) {
            if (empty($p['jenis_predator'])) continue;
            $predatorData[] = [
                'jenis_predator'    => $p['jenis_predator'],
                'tingkat_infestasi' => $p['tingkat_infestasi'] ?? 'ringan',
                'lokasi'            => $p['lokasi'] ?? null,
                'tindakan'          => $p['tindakan'] ?? null,
                'tgl_tindakan'      => $p['tgl_tindakan'] ?: null,
                'tgl_follow_up'     => $p['tgl_follow_up'] ?: null,
                'hasil_follow_up'   => $p['hasil_follow_up'] ?? 'pending',
                'catatan'           => $p['catatan'] ?? null,
            ];
        }

        // P1-8: DB Transaction (insert inspeksi + update kondisi rumah + insert predator records)
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $inspeksiId = $model->insert($data);

            // Insert predator records (P2-3)
            if (! empty($predatorData)) {
                foreach ($predatorData as &$pd) {
                    $pd['inspeksi_id'] = $inspeksiId;
                }
                $db->table('predator_inspeksi')->insertBatch($predatorData);
            }

            // Update kondisi rumah walet
            $model->updateKondisiRumah((int) $data['rumah_walet_id'], $data['status']);

            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Inspeksi::store gagal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        $this->notifikasi('success', 'Inspeksi berhasil dicatat. Status rumah walet ter-update otomatis.');
        return redirect()->to('/inspeksi');
    }

    public function view(int $id)
    {
        $model = new InspeksiModel();
        $inspeksi = $model->select('inspeksi.*, rw.kode AS rw_kode, rw.nama AS rw_nama, p.nama AS petugas_nama')
            ->join('rumah_walet rw', 'rw.id = inspeksi.rumah_walet_id', 'left')
            ->join('petugas p', 'p.id = inspeksi.petugas_id', 'left')
            ->find($id);

        if (! $inspeksi) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/inspeksi');
        }

        $predators = $model->getPredators($id);

        return $this->render('inspeksi/view', [
            'title'      => 'Detail Inspeksi',
            'inspeksi'   => $inspeksi,
            'predators'  => $predators,
            'predator_list' => Constants::PREDATOR_LIST,
            'tingkat_list'  => Constants::TINGKAT_INFESTASI,
        ]);
    }

    public function edit(int $id)
    {
        $model = new InspeksiModel();
        $rumahModel = new RumahWaletModel();
        $petugasModel = new PetugasModel();

        $inspeksi = $model->find($id);
        if (! $inspeksi) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/inspeksi');
        }

        $predators = $model->getPredators($id);

        return $this->render('inspeksi/edit', [
            'title'          => 'Edit Inspeksi',
            'inspeksi'       => $inspeksi,
            'rumahList'      => $rumahModel->getAktif(),
            'petugasList'    => $petugasModel->where('status', 'aktif')->findAll(),
            'predators'      => $predators,
            'fase_list'      => [
                'kosong'       => 'Kosong',
                'pembentukan'  => 'Pembentukan Sarang',
                'bertelur'     => 'Bertelur',
                'menetas'      => 'Menetas',
                'piyik'        => 'Piyik',
                'siap_panen'   => 'Siap Panen',
            ],
            'predator_list'  => Constants::PREDATOR_LIST,
            'tingkat_list'   => Constants::TINGKAT_INFESTASI,
        ]);
    }

    public function update(int $id)
    {
        $model = new InspeksiModel();
        $inspeksi = $model->find($id);
        if (! $inspeksi) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/inspeksi');
        }

        $rules = [
            'rumah_walet_id'    => 'required|integer',
            'petugas_id'        => 'required|integer',
            'tanggal_inspeksi'  => 'required|valid_date',
            'kondisi_bangunan'  => 'required|in_list[baik,sedang,buruk]',
            'kondisi_sarang'    => 'required|in_list[baik,sedang,buruk]',
            'kebersihan'        => 'required|in_list[baik,sedang,buruk]',
            'fase_sarang'       => 'permit_empty|in_list[kosong,pembentukan,bertelur,menetas,piyik,siap_panen]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'rumah_walet_id'      => $this->request->getPost('rumah_walet_id'),
            'petugas_id'          => $this->request->getPost('petugas_id'),
            'tanggal_inspeksi'    => $this->request->getPost('tanggal_inspeksi'),
            'kondisi_bangunan'    => $this->request->getPost('kondisi_bangunan'),
            'kondisi_sarang'      => $this->request->getPost('kondisi_sarang'),
            'kebersihan'          => $this->request->getPost('kebersihan'),
            'populasi_walet'      => $this->request->getPost('populasi_walet') ?: 0,
            'suhu'                => $this->request->getPost('suhu') ?: null,
            'kelembaban'          => $this->request->getPost('kelembaban') ?: null,
            'fase_sarang'         => $this->request->getPost('fase_sarang') ?: 'kosong',
            'cahaya_lux'          => $this->request->getPost('cahaya_lux') ?: null,
            'ketinggian_sarang_cm'=> $this->request->getPost('ketinggian_sarang_cm') ?: null,
            'humidifier_status'   => $this->request->getPost('humidifier_status'),
            'audio_player_status' => $this->request->getPost('audio_player_status'),
            'catatan'             => $this->request->getPost('catatan'),
        ];

        $data['status'] = $model->hitungStatus($data);

        // Predator data
        $predators = $this->request->getPost('predators') ?? [];
        $predatorData = [];
        foreach ($predators as $p) {
            if (empty($p['jenis_predator'])) continue;
            $predatorData[] = [
                'inspeksi_id'       => $id,
                'jenis_predator'    => $p['jenis_predator'],
                'tingkat_infestasi' => $p['tingkat_infestasi'] ?? 'ringan',
                'lokasi'            => $p['lokasi'] ?? null,
                'tindakan'          => $p['tindakan'] ?? null,
                'tgl_tindakan'      => $p['tgl_tindakan'] ?: null,
                'tgl_follow_up'     => $p['tgl_follow_up'] ?: null,
                'hasil_follow_up'   => $p['hasil_follow_up'] ?? 'pending',
                'catatan'           => $p['catatan'] ?? null,
            ];
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $model->update($id, $data);

            // Hapus predator lama, insert baru
            $db->table('predator_inspeksi')->where('inspeksi_id', $id)->delete();
            if (! empty($predatorData)) {
                $db->table('predator_inspeksi')->insertBatch($predatorData);
            }

            // Update kondisi rumah walet
            $model->updateKondisiRumah((int) $data['rumah_walet_id'], $data['status']);

            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Inspeksi::update gagal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }

        $this->notifikasi('success', 'Inspeksi diperbarui');
        return redirect()->to('/inspeksi');
    }

    public function delete(int $id)
    {
        $model = new InspeksiModel();
        $model->delete($id);  // Soft delete
        $this->notifikasi('success', 'Inspeksi dihapus');
        return redirect()->to('/inspeksi');
    }
}
