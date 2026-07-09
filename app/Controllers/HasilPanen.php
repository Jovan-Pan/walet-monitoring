<?php

namespace App\Controllers;

use App\Models\HasilPanenModel;
use App\Models\RumahWaletModel;
use App\Models\PetugasModel;
use App\Models\JadwalPanenModel;
use App\Models\HargaGradeModel;
use App\Models\StokSarangModel;
use Config\Constants;

class HasilPanen extends BaseController
{
    public function index()
    {
        $model = new HasilPanenModel();
        $filters = [
            'rumah_walet_id' => $this->request->getGet('rumah_walet_id'),
            'grade'          => $this->request->getGet('grade'),
            'jenis_panen'    => $this->request->getGet('jenis_panen'),
            'dari'           => $this->request->getGet('dari'),
            'sampai'         => $this->request->getGet('sampai'),
        ];

        // P1-7: Pagination
        $perPage = 20;
        $allData = $model->getAllWithRelations($filters);
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $page    = max(1, $page);
        $total   = count($allData);
        $offset  = ($page - 1) * $perPage;
        $paged   = array_slice($allData, $offset, $perPage);

        $rumahModel = new RumahWaletModel();

        $data = [
            'title'         => 'Hasil Panen',
            'hasilPanen'    => $paged,
            'rumahList'     => $rumahModel->getAktif(),
            'filters'       => $filters,
            'jenis_panen_list' => [
                'urat'        => 'Urat (Maret-April)',
                'sarang_utuh' => 'Sarang Utuh (Jul-Sep)',
                'kecil'       => 'Kecil (Nov-Des)',
            ],
            'currentPage' => $page,
            'totalPages'  => (int) ceil($total / $perPage),
            'total'       => $total,
        ];

        return $this->render('hasil_panen/index', $data);
    }

    public function create()
    {
        $rumahModel   = new RumahWaletModel();
        $petugasModel = new PetugasModel();

        // Auto-fill petugas dari user yang login
        $currentUserId = session()->get('id');
        $currentPetugas = $petugasModel->findByUserId($currentUserId);

        // Generate periode dari tanggal hari ini
        $periode = date('Y-m');

        // Get harga default untuk pre-fill
        $hargaModel = new HargaGradeModel();

        return $this->render('hasil_panen/create', [
            'title'           => 'Input Hasil Panen',
            'rumahList'       => $rumahModel->getAktif(),
            'petugasList'     => $petugasModel->where('status', 'aktif')->findAll(),
            'currentPetugas'  => $currentPetugas,
            'tanggalHariIni'  => date('Y-m-d'),
            'periode'         => $periode,
            'jenis_panen_list'=> [
                'urat'        => 'Urat (Maret-April)',
                'sarang_utuh' => 'Sarang Utuh (Jul-Sep)',
                'kecil'       => 'Kecil (Nov-Des)',
            ],
            'harga_default' => [
                'A' => $hargaModel->getHarga('A', 'sarang_utuh', $periode),
                'B' => $hargaModel->getHarga('B', 'sarang_utuh', $periode),
                'C' => $hargaModel->getHarga('C', 'sarang_utuh', $periode),
            ],
        ]);
    }

    /**
     * P1-4: Batch input - 1 form, 3 baris grade A/B/C, 1 transaction
     */
    public function batchCreate()
    {
        $rumahModel   = new RumahWaletModel();
        $petugasModel = new PetugasModel();
        $currentUserId = session()->get('id');
        $currentPetugas = $petugasModel->findByUserId($currentUserId);
        $periode = date('Y-m');
        $hargaModel = new HargaGradeModel();

        // Get jadwal panen terjadwal untuk pre-select
        $jadwalModel = new JadwalPanenModel();
        $jadwalTerjadwal = $jadwalModel->where('status', 'terjadwal')
            ->where('deleted_at IS NULL')
            ->orderBy('tanggal_rencana', 'ASC')
            ->findAll();

        return $this->render('hasil_panen/batch_create', [
            'title'           => 'Batch Input Hasil Panen (3 Grade Sekaligus)',
            'rumahList'       => $rumahModel->getAktif(),
            'petugasList'     => $petugasModel->where('status', 'aktif')->findAll(),
            'currentPetugas'  => $currentPetugas,
            'tanggalHariIni'  => date('Y-m-d'),
            'periode'         => $periode,
            'jadwalTerjadwal' => $jadwalTerjadwal,
            'jenis_panen_list'=> [
                'urat'        => 'Urat (Maret-April)',
                'sarang_utuh' => 'Sarang Utuh (Jul-Sep)',
                'kecil'       => 'Kecil (Nov-Des)',
            ],
            'harga_default' => [
                'A' => $hargaModel->getHarga('A', 'sarang_utuh', $periode),
                'B' => $hargaModel->getHarga('B', 'sarang_utuh', $periode),
                'C' => $hargaModel->getHarga('C', 'sarang_utuh', $periode),
            ],
        ]);
    }

    public function store()
    {
        $rules = [
            'rumah_walet_id' => 'required|integer',
            'petugas_id'      => 'required|integer',
            'tanggal_panen'   => 'required|valid_date',
            'grade'           => 'required|in_list[A,B,C]',
            'jenis_panen'     => 'required|in_list[urat,sarang_utuh,kecil]',
            'berat_kg'        => 'required|decimal|greater_than[0]',
            'harga_per_kg'    => 'required|decimal|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // P1-3: Validate harga within range
        $grade       = $this->request->getPost('grade');
        $jenisPanen  = $this->request->getPost('jenis_panen');
        $tanggalPanen= $this->request->getPost('tanggal_panen');
        $periode     = date('Y-m', strtotime($tanggalPanen));
        $hargaInput  = (float) $this->request->getPost('harga_per_kg');

        $hargaModel = new HargaGradeModel();
        $harga = $hargaModel->getHarga($grade, $jenisPanen, $periode);

        if ($harga && ($hargaInput < $harga['harga_min'] || $hargaInput > $harga['harga_max'])) {
            return redirect()->back()->withInput()
                ->with('error', "Harga Rp " . number_format($hargaInput, 0, ',', '.') . " di luar range master harga (Rp " . number_format($harga['harga_min'], 0, ',', '.') . " - Rp " . number_format($harga['harga_max'], 0, ',', '.') . " untuk Grade {$grade} {$jenisPanen} periode {$periode}). Hubungi admin untuk override.");
        }

        $data = [
            'jadwal_panen_id'    => $this->request->getPost('jadwal_panen_id') ?: null,
            'rumah_walet_id'     => $this->request->getPost('rumah_walet_id'),
            'petugas_id'         => $this->request->getPost('petugas_id'),
            'tanggal_panen'      => $tanggalPanen,
            'periode'            => $periode,
            'grade'              => $grade,
            'jenis_panen'        => $jenisPanen,
            'berat_kg'           => $this->request->getPost('berat_kg'),
            'berat_basah_kg'     => $this->request->getPost('berat_basah_kg') ?: null,
            'berat_kering_kg'    => $this->request->getPost('berat_kering_kg') ?: null,
            'kadar_air_pct'      => $this->request->getPost('kadar_air_pct') ?: null,
            'kadar_kotoran_pct'  => $this->request->getPost('kadar_kotoran_pct') ?: null,
            'no_batch'           => $this->request->getPost('no_batch') ?: null,
            'harga_per_kg'       => $hargaInput,
            'status_pengeringan' => $this->request->getPost('status_pengeringan') ?: 'basah',
            'kualitas'           => $this->request->getPost('kualitas'),
            'catatan'            => $this->request->getPost('catatan'),
        ];

        $model = new HasilPanenModel();
        $stokModel = new StokSarangModel();

        // P1-8: DB Transaction (insert panen + update jadwal status + create stok)
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $hasilId = $model->insert($data);

            // Auto-create stok record
            $stokModel->createFromHasilPanen($hasilId);

            // Update jadwal status jadi selesai
            if (! empty($data['jadwal_panen_id'])) {
                $model->updateJadwalStatus((int) $data['jadwal_panen_id'], 'selesai');
            }

            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'HasilPanen::store gagal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        $this->notifikasi('success', 'Hasil panen berhasil dicatat');
        return redirect()->to('/hasil-panen');
    }

    /**
     * P1-4: Batch store - proses 3 grade sekaligus dalam 1 transaction
     */
    public function batchStore()
    {
        $rules = [
            'rumah_walet_id' => 'required|integer',
            'petugas_id'      => 'required|integer',
            'tanggal_panen'   => 'required|valid_date',
            'jenis_panen'     => 'required|in_list[urat,sarang_utuh,kecil]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tanggalPanen = $this->request->getPost('tanggal_panen');
        $periode      = date('Y-m', strtotime($tanggalPanen));
        $jenisPanen   = $this->request->getPost('jenis_panen');

        // Kumpulkan data 3 grade
        $grades = ['A', 'B', 'C'];
        $toInsert = [];
        $stokModel = new StokSarangModel();
        $hargaModel = new HargaGradeModel();

        foreach ($grades as $g) {
            $berat = (float) $this->request->getPost("berat_{$g}");
            if ($berat <= 0) continue; // Skip grade yang kosong

            $harga = (float) $this->request->getPost("harga_{$g}");
            if ($harga <= 0) {
                // Auto-fill dari master harga
                $masterHarga = $hargaModel->getHarga($g, $jenisPanen, $periode);
                $harga = $masterHarga['harga_default'] ?? 0;
            }

            // Validate range
            $masterHarga = $hargaModel->getHarga($g, $jenisPanen, $periode);
            if ($masterHarga && ($harga < $masterHarga['harga_min'] || $harga > $masterHarga['harga_max'])) {
                return redirect()->back()->withInput()
                    ->with('error', "Harga Grade {$g} di luar range master (Rp " . number_format($masterHarga['harga_min'], 0, ',', '.') . " - Rp " . number_format($masterHarga['harga_max'], 0, ',', '.') . "). Hubungi admin.");
            }

            $toInsert[] = [
                'jadwal_panen_id'    => $this->request->getPost('jadwal_panen_id') ?: null,
                'rumah_walet_id'     => $this->request->getPost('rumah_walet_id'),
                'petugas_id'         => $this->request->getPost('petugas_id'),
                'tanggal_panen'      => $tanggalPanen,
                'periode'            => $periode,
                'grade'              => $g,
                'jenis_panen'        => $jenisPanen,
                'berat_kg'           => $berat,
                'berat_basah_kg'     => $this->request->getPost("berat_basah_{$g}") ?: null,
                'berat_kering_kg'    => $this->request->getPost("berat_kering_{$g}") ?: null,
                'kadar_air_pct'      => $this->request->getPost("kadar_air_{$g}") ?: null,
                'kadar_kotoran_pct'  => $this->request->getPost("kadar_kotoran_{$g}") ?: null,
                'no_batch'           => $this->request->getPost('no_batch') ?: ($periode . '-' . $this->request->getPost('rumah_walet_id') . '-' . $g),
                'harga_per_kg'       => $harga,
                'status_pengeringan' => $this->request->getPost('status_pengeringan') ?: 'basah',
                'kualitas'           => $this->request->getPost("kualitas_{$g}"),
                'catatan'            => $this->request->getPost('catatan'),
            ];
        }

        if (empty($toInsert)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada grade yang diisi. Isi minimal 1 grade (A, B, atau C).');
        }

        $model = new HasilPanenModel();

        // P1-8: DB Transaction untuk semua insert + stok + jadwal
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $insertedCount = 0;
            foreach ($toInsert as $data) {
                $hasilId = $model->insert($data);
                $stokModel->createFromHasilPanen($hasilId);
                $insertedCount++;
            }

            // Update jadwal status
            if (! empty($toInsert[0]['jadwal_panen_id'])) {
                $model->updateJadwalStatus((int) $toInsert[0]['jadwal_panen_id'], 'selesai');
            }

            $db->transCommit();

            $this->notifikasi('success', "Batch input berhasil! {$insertedCount} grade dicatat dalam 1 transaksi.");
            return redirect()->to('/hasil-panen');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'HasilPanen::batchStore gagal: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal batch input: ' . $e->getMessage());
        }
    }

    /**
     * API: get harga by grade + jenis_panen + periode (untuk pre-fill form via JS)
     */
    public function getHargaByGrade(string $grade, string $jenisPanen)
    {
        $periode = $this->request->getGet('periode') ?: date('Y-m');
        $hargaModel = new HargaGradeModel();
        $harga = $hargaModel->getHarga($grade, $jenisPanen, $periode);

        return $this->response->setJSON([
            'status' => $harga ? 'ok' : 'not_found',
            'data'   => $harga,
        ]);
    }

    public function view(int $id)
    {
        $model = new HasilPanenModel();
        $panen = $model->getWithRelations($id);
        if (! $panen) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/hasil-panen');
        }
        return $this->render('hasil_panen/view', [
            'title' => 'Detail Hasil Panen',
            'panen' => $panen,
        ]);
    }

    public function edit(int $id)
    {
        $model = new HasilPanenModel();
        $panen = $model->find($id);
        if (! $panen) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/hasil-panen');
        }

        $rumahModel   = new RumahWaletModel();
        $petugasModel = new PetugasModel();

        return $this->render('hasil_panen/edit', [
            'title'           => 'Edit Hasil Panen',
            'panen'           => $panen,
            'rumahList'       => $rumahModel->getAktif(),
            'petugasList'     => $petugasModel->where('status', 'aktif')->findAll(),
            'jenis_panen_list'=> [
                'urat'        => 'Urat (Maret-April)',
                'sarang_utuh' => 'Sarang Utuh (Jul-Sep)',
                'kecil'       => 'Kecil (Nov-Des)',
            ],
        ]);
    }

    public function update(int $id)
    {
        $model = new HasilPanenModel();
        $panen = $model->find($id);
        if (! $panen) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/hasil-panen');
        }

        $rules = [
            'rumah_walet_id' => 'required|integer',
            'petugas_id'      => 'required|integer',
            'tanggal_panen'   => 'required|valid_date',
            'grade'           => 'required|in_list[A,B,C]',
            'jenis_panen'     => 'required|in_list[urat,sarang_utuh,kecil]',
            'berat_kg'        => 'required|decimal|greater_than[0]',
            'harga_per_kg'    => 'required|decimal|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tanggalPanen = $this->request->getPost('tanggal_panen');
        $data = [
            'rumah_walet_id'     => $this->request->getPost('rumah_walet_id'),
            'petugas_id'         => $this->request->getPost('petugas_id'),
            'tanggal_panen'      => $tanggalPanen,
            'periode'            => date('Y-m', strtotime($tanggalPanen)),
            'grade'              => $this->request->getPost('grade'),
            'jenis_panen'        => $this->request->getPost('jenis_panen'),
            'berat_kg'           => $this->request->getPost('berat_kg'),
            'berat_basah_kg'     => $this->request->getPost('berat_basah_kg') ?: null,
            'berat_kering_kg'    => $this->request->getPost('berat_kering_kg') ?: null,
            'kadar_air_pct'      => $this->request->getPost('kadar_air_pct') ?: null,
            'kadar_kotoran_pct'  => $this->request->getPost('kadar_kotoran_pct') ?: null,
            'no_batch'           => $this->request->getPost('no_batch') ?: null,
            'harga_per_kg'       => $this->request->getPost('harga_per_kg'),
            'status_pengeringan' => $this->request->getPost('status_pengeringan') ?: 'basah',
            'kualitas'           => $this->request->getPost('kualitas'),
            'catatan'            => $this->request->getPost('catatan'),
        ];

        $model->update($id, $data);
        $this->notifikasi('success', 'Hasil panen berhasil diperbarui');
        return redirect()->to('/hasil-panen');
    }

    public function delete(int $id)
    {
        $model = new HasilPanenModel();
        $model->delete($id);  // Soft delete
        $this->notifikasi('success', 'Hasil panen berhasil dihapus');
        return redirect()->to('/hasil-panen');
    }
}
