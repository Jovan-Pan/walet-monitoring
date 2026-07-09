<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilPanenModel extends Model
{
    protected $table            = 'hasil_panen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'jadwal_panen_id', 'rumah_walet_id', 'petugas_id', 'tanggal_panen', 'periode',
        'grade', 'jenis_panen', 'berat_kg', 'berat_basah_kg', 'berat_kering_kg',
        'kadar_air_pct', 'kadar_kotoran_pct', 'no_batch', 'harga_per_kg',
        'status_pengeringan', 'status_stok', 'pembeli_id', 'sertifikat_mutu',
        'kualitas', 'catatan',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Get hasil panen with relations
     */
    public function getWithRelations(int $id): ?array
    {
        return $this->select('hasil_panen.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama')
            ->join('rumah_walet', 'rumah_walet.id = hasil_panen.rumah_walet_id', 'left')
            ->join('petugas', 'petugas.id = hasil_panen.petugas_id', 'left')
            ->find($id);
    }

    /**
     * Get all with relations + filter
     */
    public function getAllWithRelations(array $filters = [])
    {
        $builder = $this->builder();
        $builder->select('hasil_panen.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama, jadwal_panen.tanggal_rencana AS jadwal_tanggal')
            ->join('rumah_walet', 'rumah_walet.id = hasil_panen.rumah_walet_id', 'left')
            ->join('petugas', 'petugas.id = hasil_panen.petugas_id', 'left')
            ->join('jadwal_panen', 'jadwal_panen.id = hasil_panen.jadwal_panen_id', 'left')
            ->where('hasil_panen.deleted_at IS NULL');

        if (! empty($filters['rumah_walet_id'])) {
            $builder->where('hasil_panen.rumah_walet_id', $filters['rumah_walet_id']);
        }
        if (! empty($filters['grade'])) {
            $builder->where('hasil_panen.grade', $filters['grade']);
        }
        if (! empty($filters['jenis_panen'])) {
            $builder->where('hasil_panen.jenis_panen', $filters['jenis_panen']);
        }
        if (! empty($filters['dari'])) {
            $builder->where('hasil_panen.tanggal_panen >=', $filters['dari']);
        }
        if (! empty($filters['sampai'])) {
            $builder->where('hasil_panen.tanggal_panen <=', $filters['sampai']);
        }

        return $builder->orderBy('hasil_panen.tanggal_panen', 'DESC')->get()->getResultArray();
    }

    /**
     * Total nilai panen tahunan (P1-6: range query, bukan YEAR())
     */
    public function totalNilaiTahun(int $tahun): float
    {
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        $row = $this->select('COALESCE(SUM(total_nilai),0) AS total')
            ->where('tanggal_panen >=', $awal)
            ->where('tanggal_panen <=', $akhir)
            ->first();
        return (float) ($row['total'] ?? 0);
    }

    /**
     * Total berat panen tahunan (P1-6)
     */
    public function totalBeratTahun(int $tahun): float
    {
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        $row = $this->select('COALESCE(SUM(berat_kg),0) AS total')
            ->where('tanggal_panen >=', $awal)
            ->where('tanggal_panen <=', $akhir)
            ->first();
        return (float) ($row['total'] ?? 0);
    }

    /**
     * Chart data panen per bulan (P1-6)
     */
    public function chartPanenBulanan(int $tahun): array
    {
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        return $this->select("MONTH(tanggal_panen) AS bulan, SUM(berat_kg) AS total_kg, SUM(total_nilai) AS total_nilai, COUNT(*) AS jumlah")
            ->where('tanggal_panen >=', $awal)
            ->where('tanggal_panen <=', $akhir)
            ->groupBy('MONTH(tanggal_panen)')
            ->orderBy('bulan', 'ASC')
            ->findAll();
    }

    /**
     * Update status jadwal panen terkait (P1-8: dalam transaction)
     */
    public function updateJadwalStatus(int $jadwalId, string $status = 'selesai'): void
    {
        if (! $jadwalId) return;
        $db = \Config\Database::connect();
        $db->table('jadwal_panen')->where('id', $jadwalId)->update(['status' => $status]);
    }
}
