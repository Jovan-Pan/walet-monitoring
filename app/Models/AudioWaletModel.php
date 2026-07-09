<?php

namespace App\Models;

use CodeIgniter\Model;

class AudioWaletModel extends Model
{
    protected $table            = 'audio_walet';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'rumah_walet_id', 'tanggal', 'jenis_suara', 'jam_nyala', 'jam_mati',
        'volume', 'kondisi_speaker', 'jumlah_speaker_aktif', 'kondisi_amplifier',
        'catatan', 'input_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getWithRelations(array $filters = [])
    {
        $builder = $this->builder();
        $builder->select('audio_walet.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, u.nama AS input_by_nama')
            ->join('rumah_walet', 'rumah_walet.id = audio_walet.rumah_walet_id', 'left')
            ->join('users u', 'u.id = audio_walet.input_by', 'left')
            ->where('audio_walet.deleted_at IS NULL');

        if (! empty($filters['rumah_walet_id'])) {
            $builder->where('audio_walet.rumah_walet_id', $filters['rumah_walet_id']);
        }
        if (! empty($filters['dari'])) {
            $builder->where('audio_walet.tanggal >=', $filters['dari']);
        }
        if (! empty($filters['sampai'])) {
            $builder->where('audio_walet.tanggal <=', $filters['sampai']);
        }

        return $builder->orderBy('audio_walet.tanggal', 'DESC')->get()->getResultArray();
    }
}
