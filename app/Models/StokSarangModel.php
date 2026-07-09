<?php

namespace App\Models;

use CodeIgniter\Model;

class StokSarangModel extends Model
{
    protected $table            = 'stok_sarang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'hasil_panen_id', 'rumah_walet_id', 'grade', 'jenis_panen',
        'berat_kg', 'lokasi_gudang', 'tanggal_masuk', 'tanggal_keluar',
        'penjualan_id', 'status_stok', 'catatan',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getWithRelations(array $filters = [])
    {
        $builder = $this->builder();
        $builder->select('stok_sarang.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, p.no_invoice')
            ->join('rumah_walet', 'rumah_walet.id = stok_sarang.rumah_walet_id', 'left')
            ->join('penjualan p', 'p.id = stok_sarang.penjualan_id', 'left')
            ->where('stok_sarang.deleted_at IS NULL');

        if (! empty($filters['status_stok'])) {
            $builder->where('stok_sarang.status_stok', $filters['status_stok']);
        }
        if (! empty($filters['lokasi_gudang'])) {
            $builder->where('stok_sarang.lokasi_gudang', $filters['lokasi_gudang']);
        }
        if (! empty($filters['rumah_walet_id'])) {
            $builder->where('stok_sarang.rumah_walet_id', $filters['rumah_walet_id']);
        }
        if (! empty($filters['grade'])) {
            $builder->where('stok_sarang.grade', $filters['grade']);
        }

        return $builder->orderBy('stok_sarang.tanggal_masuk', 'DESC')->get()->getResultArray();
    }

    /**
     * Auto-create stok record setelah input hasil panen (P1-2)
     */
    public function createFromHasilPanen(int $hasilPanenId): void
    {
        $db = \Config\Database::connect();
        $hp = $db->table('hasil_panen')->where('id', $hasilPanenId)->get()->getRowArray();
        if (! $hp) return;

        $this->insert([
            'hasil_panen_id'  => $hasilPanenId,
            'rumah_walet_id'  => $hp['rumah_walet_id'],
            'grade'           => $hp['grade'],
            'jenis_panen'     => $hp['jenis_panen'],
            'berat_kg'        => $hp['berat_kg'],
            'lokasi_gudang'   => 'gudang_rw',
            'tanggal_masuk'   => $hp['tanggal_panen'],
            'status_stok'     => 'tersedia',
        ]);
    }

    /**
     * Update status stok ketika penjualan dibuat (P1-2)
     */
    public function markAsSold(int $hasilPanenId, int $penjualanId, string $tanggalKeluar): void
    {
        $stok = $this->where('hasil_panen_id', $hasilPanenId)->where('status_stok', 'tersedia')->first();
        if ($stok) {
            $this->update($stok['id'], [
                'penjualan_id'    => $penjualanId,
                'tanggal_keluar'  => $tanggalKeluar,
                'status_stok'     => 'terjual',
            ]);
        }
    }

    /**
     * Summary stok per grade per lokasi
     */
    public function getSummary(): array
    {
        $db = \Config\Database::connect();
        return $db->table('stok_sarang')
            ->select('grade, jenis_panen, lokasi_gudang, status_stok, COUNT(*) AS jumlah, SUM(berat_kg) AS total_berat')
            ->where('deleted_at IS NULL')
            ->groupBy('grade, jenis_panen, lokasi_gudang, status_stok')
            ->orderBy('grade', 'ASC')
            ->get()->getResultArray();
    }
}
