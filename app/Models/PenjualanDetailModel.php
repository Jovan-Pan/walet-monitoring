<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanDetailModel extends Model
{
    protected $table            = 'penjualan_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'penjualan_id', 'hasil_panen_id', 'rumah_walet_id', 'grade', 'jenis_panen',
        'berat_kg', 'harga_per_kg', 'catatan',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    public function getByPenjualanId(int $penjualanId): array
    {
        return $this->select('penjualan_detail.*, rw.kode AS rw_kode, rw.nama AS rw_nama, hp.no_batch')
            ->join('rumah_walet rw', 'rw.id = penjualan_detail.rumah_walet_id', 'left')
            ->join('hasil_panen hp', 'hp.id = penjualan_detail.hasil_panen_id', 'left')
            ->where('penjualan_id', $penjualanId)
            ->findAll();
    }
}
