<?php

namespace App\Controllers;

use App\Models\PengeluaranModel;
use App\Models\RumahWaletModel;
use Config\Constants;

class Pengeluaran extends BaseController
{
    public function index()
    {
        $model    = new PengeluaranModel();
        $filters = [
            'kategori'         => $this->request->getGet('kategori'),
            'dari'             => $this->request->getGet('dari'),
            'sampai'           => $this->request->getGet('sampai'),
            'approval_status'  => $this->request->getGet('approval_status'),
        ];

        $data = [
            'title'              => 'Pengeluaran Operasional',
            'pengeluaran'        => $model->getWithRelations($filters),
            'kategori_list'      => Constants::KATEGORI_PENGELUARAN,
            'kategori'           => $filters['kategori'],
            'dari'               => $filters['dari'],
            'sampai'             => $filters['sampai'],
            'approval_status'    => $filters['approval_status'],
            'approval_status_list' => [
                'draft'         => 'Draft',
                'pending'       => 'Pending Approval',
                'approved'      => 'Approved',
                'rejected'      => 'Rejected',
                'auto_approved' => 'Auto Approved',
            ],
        ];

        // Tampilkan badge pending approval count untuk owner/admin
        $pendingCount = count($model->getPendingApprovals());
        $data['pending_count'] = $pendingCount;

        return $this->render('pengeluaran/index', $data);
    }

    public function create()
    {
        $rumahModel = new RumahWaletModel();
        return $this->render('pengeluaran/create', [
            'title'             => 'Tambah Pengeluaran',
            'rumahList'         => $rumahModel->getAktif(),
            'kategori_list'     => Constants::KATEGORI_PENGELUARAN,
            'approval_threshold'=> Constants::APPROVAL_THRESHOLD,
            'tanggalHariIni'    => date('Y-m-d'),
        ]);
    }

    public function store()
    {
        $kategoriList = array_keys(Constants::KATEGORI_PENGELUARAN);

        $rules = [
            'tanggal'    => 'required|valid_date',
            'kategori'   => 'required|in_list[' . implode(',', $kategoriList) . ']',
            'keterangan' => 'required|min_length[3]',
            'jumlah'     => 'required|decimal|greater_than[0]',
        ];

        // P0-3: Validation bukti hanya jika file diupload
        $file = $this->request->getFile('bukti');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $rules['bukti'] = 'max_size[bukti,2048]|ext_in[bukti,jpg,jpeg,png,pdf]|mime_in[bukti,image/jpeg,image/png,application/pdf]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // P0-3: Upload bukti dengan validasi MIME/size/ext
        $bukti = null;
        $file  = $this->request->getFile('bukti');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);
            $bukti = $newName;
        }

        $kategori = $this->request->getPost('kategori');
        $jumlah   = (float) $this->request->getPost('jumlah');

        // P2-6: Approval logic per kategori
        $threshold = Constants::APPROVAL_THRESHOLD[$kategori] ?? 5000000;
        $approvalStatus = ($threshold > 0 && $jumlah >= $threshold)
            ? Constants::APPROVAL_PENDING
            : Constants::APPROVAL_AUTO_APPROVED;

        $data = [
            'tanggal'          => $this->request->getPost('tanggal'),
            'rumah_walet_id'   => $this->request->getPost('rumah_walet_id') ?: null,
            'kategori'         => $kategori,
            'keterangan'       => $this->request->getPost('keterangan'),
            'jumlah'           => $jumlah,
            'bukti'            => $bukti,
            'input_by'         => session()->get('id'),
            'approval_status'  => $approvalStatus,
        ];

        $model = new PengeluaranModel();
        $pengeluaranId = $model->insert($data);

        // P1-1: Auto-alokasi gaji ke RW berdasarkan proporsi kapasitas
        if ($kategori === 'gaji' && empty($data['rumah_walet_id'])) {
            $model->autoAlokasiGaji($pengeluaranId);
        }

        $msg = $approvalStatus === Constants::APPROVAL_PENDING
            ? 'Pengeluaran dicatat sebagai DRAFT - menunggu approval owner (jumlah di atas threshold kategori)'
            : 'Pengeluaran berhasil dicatat';

        $this->notifikasi('success', $msg);
        return redirect()->to('/pengeluaran');
    }

    public function edit(int $id)
    {
        $model      = new PengeluaranModel();
        $rumahModel = new RumahWaletModel();
        $pengeluaran = $model->find($id);
        if (! $pengeluaran) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/pengeluaran');
        }
        return $this->render('pengeluaran/edit', [
            'title'             => 'Edit Pengeluaran',
            'pengeluaran'       => $pengeluaran,
            'rumahList'         => $rumahModel->getAktif(),
            'kategori_list'     => Constants::KATEGORI_PENGELUARAN,
            'approval_threshold'=> Constants::APPROVAL_THRESHOLD,
        ]);
    }

    public function update(int $id)
    {
        $model = new PengeluaranModel();
        $pengeluaran = $model->find($id);
        if (! $pengeluaran) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/pengeluaran');
        }

        $kategoriList = array_keys(Constants::KATEGORI_PENGELUARAN);

        $rules = [
            'tanggal'    => 'required|valid_date',
            'kategori'   => 'required|in_list[' . implode(',', $kategoriList) . ']',
            'keterangan' => 'required|min_length[3]',
            'jumlah'     => 'required|decimal|greater_than[0]',
        ];

        $file = $this->request->getFile('bukti');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $rules['bukti'] = 'max_size[bukti,2048]|ext_in[bukti,jpg,jpeg,png,pdf]|mime_in[bukti,image/jpeg,image/png,application/pdf]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bukti = $pengeluaran['bukti'];
        $file  = $this->request->getFile('bukti');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            if (! empty($pengeluaran['bukti']) && file_exists(FCPATH . 'uploads/' . $pengeluaran['bukti'])) {
                unlink(FCPATH . 'uploads/' . $pengeluaran['bukti']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);
            $bukti = $newName;
        }

        $kategori = $this->request->getPost('kategori');
        $jumlah   = (float) $this->request->getPost('jumlah');

        // Re-evaluate approval status
        $threshold = Constants::APPROVAL_THRESHOLD[$kategori] ?? 5000000;
        $approvalStatus = ($threshold > 0 && $jumlah >= $threshold)
            ? Constants::APPROVAL_PENDING
            : Constants::APPROVAL_AUTO_APPROVED;

        $data = [
            'tanggal'          => $this->request->getPost('tanggal'),
            'rumah_walet_id'   => $this->request->getPost('rumah_walet_id') ?: null,
            'kategori'         => $kategori,
            'keterangan'       => $this->request->getPost('keterangan'),
            'jumlah'           => $jumlah,
            'bukti'            => $bukti,
            'approval_status'  => $approvalStatus,
        ];

        // Jika kategori berubah ke gaji dan rumah_walet_id NULL, hapus alokasi lama & bikin baru
        if ($kategori === 'gaji' && empty($data['rumah_walet_id'])) {
            $db = \Config\Database::connect();
            $db->table('pengeluaran_alokasi')->where('pengeluaran_id', $id)->delete();
        }

        $model->update($id, $data);

        // Auto-alokasi gaji ulang jika perlu
        if ($kategori === 'gaji' && empty($data['rumah_walet_id'])) {
            $model->autoAlokasiGaji($id);
        }

        $this->notifikasi('success', 'Pengeluaran berhasil diperbarui');
        return redirect()->to('/pengeluaran');
    }

    public function delete(int $id)
    {
        $model = new PengeluaranModel();
        $pengeluaran = $model->find($id);
        if ($pengeluaran && ! empty($pengeluaran['bukti'])) {
            if (file_exists(FCPATH . 'uploads/' . $pengeluaran['bukti'])) {
                unlink(FCPATH . 'uploads/' . $pengeluaran['bukti']);
            }
        }
        // Soft delete - juga hapus alokasi terkait
        $db = \Config\Database::connect();
        $db->table('pengeluaran_alokasi')->where('pengeluaran_id', $id)->delete();
        $model->delete($id);

        $this->notifikasi('success', 'Pengeluaran berhasil dihapus');
        return redirect()->to('/pengeluaran');
    }

    /**
     * Approval flow (P2-6) - hanya owner yang bisa approve
     */
    public function approve(int $id, string $action = 'view')
    {
        if (session()->get('role') !== 'owner' && session()->get('role') !== 'admin') {
            $this->notifikasi('error', 'Hanya owner/admin yang bisa approve pengeluaran');
            return redirect()->to('/pengeluaran');
        }

        $model = new PengeluaranModel();
        $pengeluaran = $model->find($id);
        if (! $pengeluaran) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/pengeluaran');
        }

        if ($pengeluaran['approval_status'] !== Constants::APPROVAL_PENDING) {
            $this->notifikasi('warning', 'Pengeluaran ini sudah diproses');
            return redirect()->to('/pengeluaran');
        }

        if ($action === 'view') {
            return $this->render('pengeluaran/approve', [
                'title'       => 'Approve Pengeluaran',
                'pengeluaran' => $pengeluaran,
            ]);
        }

        return redirect()->to('/pengeluaran');
    }

    public function approveAction(int $id)
    {
        if (session()->get('role') !== 'owner' && session()->get('role') !== 'admin') {
            $this->notifikasi('error', 'Hanya owner/admin yang bisa approve pengeluaran');
            return redirect()->to('/pengeluaran');
        }

        $model = new PengeluaranModel();
        $pengeluaran = $model->find($id);
        if (! $pengeluaran) {
            $this->notifikasi('error', 'Data tidak ditemukan');
            return redirect()->to('/pengeluaran');
        }

        $action = $this->request->getPost('action'); // 'approve' atau 'reject'
        $note   = $this->request->getPost('approval_note');

        $newStatus = ($action === 'approve')
            ? Constants::APPROVAL_APPROVED
            : Constants::APPROVAL_REJECTED;

        $model->update($id, [
            'approval_status' => $newStatus,
            'approved_by'     => session()->get('id'),
            'approval_date'   => date('Y-m-d H:i:s'),
            'approval_note'   => $note,
        ]);

        // Jika approve & kategori gaji & rumah NULL → auto alokasi
        if ($action === 'approve' && $pengeluaran['kategori'] === 'gaji' && empty($pengeluaran['rumah_walet_id'])) {
            $model->autoAlokasiGaji($id);
        }

        $this->notifikasi('success', 'Pengeluaran telah ' . ($action === 'approve' ? 'di-APPROVE' : 'di-REJECT'));
        return redirect()->to('/pengeluaran');
    }
}
