<?php

namespace App\Models;

use CodeIgniter\Model;

class InspeksiModel extends Model
{
    protected $table            = 'inspeksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'rumah_walet_id', 'petugas_id', 'tanggal_inspeksi',
        'kondisi_bangunan', 'kondisi_sarang', 'kebersihan',
        'populasi_walet', 'suhu', 'kelembaban',
        'fase_sarang', 'cahaya_lux', 'ketinggian_sarang_cm',
        'suhu_per_lantai', 'kelembaban_per_lantai', 'humidifier_status',
        'audio_player_status', 'foto_inspeksi', 'signature_petugas',
        'catatan', 'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Hitung status inspeksi otomatis dari kondisi terburuk
     */
    public function hitungStatus(array $data): string
    {
        $conditions = [
            $data['kondisi_bangunan'] ?? 'baik',
            $data['kondisi_sarang']   ?? 'baik',
            $data['kebersihan']       ?? 'baik',
        ];

        if (in_array('buruk', $conditions, true))   return 'buruk';
        if (in_array('sedang', $conditions, true))  return 'sedang';
        return 'baik';
    }

    /**
     * Update kondisi rumah walet setelah inspeksi (P1-8: dipanggil dalam transaction)
     */
    public function updateKondisiRumah(int $rwId, string $status): void
    {
        $db = \Config\Database::connect();
        $db->table('rumah_walet')->where('id', $rwId)->update(['kondisi' => $status]);
    }

    /**
     * Get with relations (rumah_walet, petugas)
     */
    public function getWithRelations(array $filters = [])
    {
        $builder = $this->builder();
        $builder->select('inspeksi.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama')
            ->join('rumah_walet', 'rumah_walet.id = inspeksi.rumah_walet_id', 'left')
            ->join('petugas', 'petugas.id = inspeksi.petugas_id', 'left')
            ->where('inspeksi.deleted_at IS NULL');

        if (! empty($filters['rumah_walet_id'])) {
            $builder->where('inspeksi.rumah_walet_id', $filters['rumah_walet_id']);
        }
        if (! empty($filters['dari'])) {
            $builder->where('inspeksi.tanggal_inspeksi >=', $filters['dari']);
        }
        if (! empty($filters['sampai'])) {
            $builder->where('inspeksi.tanggal_inspeksi <=', $filters['sampai']);
        }

        return $builder->orderBy('inspeksi.tanggal_inspeksi', 'DESC')->get()->getResultArray();
    }

    /**
     * Get predator records for an inspeksi (P2-3)
     */
    public function getPredators(int $inspeksiId): array
    {
        $db = \Config\Database::connect();
        return $db->table('predator_inspeksi')
            ->where('inspeksi_id', $inspeksiId)
            ->get()->getResultArray();
    }
}
