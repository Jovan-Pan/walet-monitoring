<?php

namespace App\Models;

use CodeIgniter\Model;

class RumahWaletModel extends Model
{
    protected $table            = 'rumah_walet';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;  // P2-7

    protected $allowedFields = [
        'kode', 'nama', 'lokasi', 'latitude', 'longitude', 'luas', 'jumlah_lantai',
        'tahun_dibangun', 'jenis_bangunan', 'kapasitas_panen_kg',
        'kapasitas_bulan_01_kg', 'kapasitas_bulan_02_kg', 'kapasitas_bulan_03_kg',
        'kapasitas_bulan_04_kg', 'kapasitas_bulan_05_kg', 'kapasitas_bulan_06_kg',
        'kapasitas_bulan_07_kg', 'kapasitas_bulan_08_kg', 'kapasitas_bulan_09_kg',
        'kapasitas_bulan_10_kg', 'kapasitas_bulan_11_kg', 'kapasitas_bulan_12_kg',
        'jumlah_speaker', 'jenis_player', 'jam_operasi_audio', 'humidifier_count',
        'cctv_url', 'tanggal_berdiri', 'tanggal_renovasi_terakhir', 'sertifikat_bpom',
        'foto_depan', 'foto_dalam', 'pajak_properti_tahunan', 'status_kepemilikan',
        'kondisi', 'keterangan', 'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Generate kode otomatis untuk RW baru
     */
    public function generateKode(): string
    {
        $last = $this->orderBy('id', 'DESC')->first();
        $num  = $last ? ((int) substr($last['kode'], 3)) + 1 : 1;
        return 'RW-' . str_pad((string) $num, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get kapasitas tahunan (akomodasi musim - P2-2)
     * Jika semua bulan 0, fallback ke kapasitas_panen_kg * 12
     */
    public function getKapasitasTahunan(int $rwId, ?int $tahun = null): float
    {
        $rw = $this->find($rwId);
        if (! $rw) return 0;

        $totalPerBulan = 0;
        for ($m = 1; $m <= 12; $m++) {
            $totalPerBulan += (float) ($rw["kapasitas_bulan_{$m}_kg"] ?? 0);
        }

        // Fallback ke flat * 12 jika belum diisi per-bulan
        if ($totalPerBulan == 0) {
            return (float) ($rw['kapasitas_panen_kg'] ?? 0) * 12;
        }

        return $totalPerBulan;
    }

    /**
     * Get kapasitas bulan tertentu (P2-2)
     */
    public function getKapasitasBulan(int $rwId, int $bulan): float
    {
        $rw = $this->find($rwId);
        if (! $rw) return 0;

        $field = "kapasitas_bulan_" . str_pad((string) $bulan, 2, '0', STR_PAD_LEFT) . "_kg";
        $val = (float) ($rw[$field] ?? 0);

        // Fallback ke rata-rata flat
        if ($val == 0) {
            return (float) ($rw['kapasitas_panen_kg'] ?? 0);
        }

        return $val;
    }

    /**
     * Get detail dengan statistik agregat
     */
    public function getDetailWithStats(int $id): ?array
    {
        $rw = $this->find($id);
        if (! $rw) return null;

        $db = \Config\Database::connect();

        // Statistik panen
        $panenStats = $db->table('hasil_panen')
            ->select('COUNT(*) AS jumlah_panen, COALESCE(SUM(berat_kg),0) AS total_kg, COALESCE(SUM(total_nilai),0) AS total_nilai')
            ->where('rumah_walet_id', $id)
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();

        // Statistik inspeksi
        $inspeksiStats = $db->table('inspeksi')
            ->select('COUNT(*) AS jumlah_inspeksi, MAX(tanggal_inspeksi) AS last_inspeksi')
            ->where('rumah_walet_id', $id)
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();

        $rw['statistik'] = [
            'panen'    => $panenStats,
            'inspeksi' => $inspeksiStats,
        ];

        return $rw;
    }

    /**
     * Get aktif RWs only
     */
    public function getAktif()
    {
        return $this->where('status', 'aktif')->orderBy('kode', 'ASC')->findAll();
    }
}
