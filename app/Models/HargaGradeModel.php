<?php

namespace App\Models;

use CodeIgniter\Model;

class HargaGradeModel extends Model
{
    protected $table            = 'harga_grade';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'grade', 'jenis_panen', 'periode', 'harga_min', 'harga_max', 'harga_default',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    /**
     * Get harga for grade+jenis_panen+periode
     * Falls back to most recent prior periode if not found
     */
    public function getHarga(string $grade, string $jenisPanen, string $periode): ?array
    {
        $row = $this->where('grade', $grade)
            ->where('jenis_panen', $jenisPanen)
            ->where('periode', $periode)
            ->first();

        // Fallback: cari periode sebelumnya jika tidak ada
        if (! $row) {
            $row = $this->where('grade', $grade)
                ->where('jenis_panen', $jenisPanen)
                ->where('periode <=', $periode)
                ->orderBy('periode', 'DESC')
                ->first();
        }

        return $row;
    }
}
