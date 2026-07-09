<?php

namespace App\Models;

use CodeIgniter\Model;

class PetugasModel extends Model
{
    protected $table            = 'petugas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'nip', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat', 'no_hp', 'email', 'tanggal_masuk', 'user_id', 'status', 'foto',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getWithRelations()
    {
        return $this->select('petugas.*, users.username, users.role, users.status AS user_status')
            ->join('users', 'users.id = petugas.user_id', 'left')
            ->where('petugas.deleted_at IS NULL')
            ->orderBy('petugas.nama', 'ASC')
            ->findAll();
    }

    /**
     * Get RWs assigned to a petugas
     */
    public function getRumahDitugaskan(int $petugasId): array
    {
        $db = \Config\Database::connect();
        return $db->table('petugas_rumah pr')
            ->select('rw.id, rw.kode, rw.nama, pr.tanggal_mulai, pr.tanggal_selesai')
            ->join('rumah_walet rw', 'rw.id = pr.rumah_walet_id')
            ->where('pr.petugas_id', $petugasId)
            ->where('rw.deleted_at IS NULL')
            ->where('rw.status', 'aktif')
            ->orderBy('rw.kode', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Find petugas by user_id (untuk auto-fill saat input inspeksi)
     */
    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }
}
