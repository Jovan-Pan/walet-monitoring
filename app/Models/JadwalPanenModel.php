<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPanenModel extends Model
{
    protected $table            = 'jadwal_panen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'rumah_walet_id', 'tanggal_rencana', 'periode', 'estimasi_hasil_kg',
        'jenis_panen_rencana', 'catatan', 'status', 'hasil_panen_id',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getWithRelations(array $filters = [])
    {
        $builder = $this->builder();
        $builder->select('jadwal_panen.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama')
            ->join('rumah_walet', 'rumah_walet.id = jadwal_panen.rumah_walet_id', 'left')
            ->where('jadwal_panen.deleted_at IS NULL');

        if (! empty($filters['rumah_walet_id'])) {
            $builder->where('jadwal_panen.rumah_walet_id', $filters['rumah_walet_id']);
        }
        if (! empty($filters['status'])) {
            $builder->where('jadwal_panen.status', $filters['status']);
        }
        if (! empty($filters['dari'])) {
            $builder->where('jadwal_panen.tanggal_rencana >=', $filters['dari']);
        }
        if (! empty($filters['sampai'])) {
            $builder->where('jadwal_panen.tanggal_rencana <=', $filters['sampai']);
        }

        return $builder->orderBy('jadwal_panen.tanggal_rencana', 'DESC')->get()->getResultArray();
    }
}
