<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'no_invoice', 'tanggal', 'pembeli_nama', 'pembeli_kontak', 'pembeli_alamat',
        'total_berat_kg', 'total_nilai', 'status_bayar', 'tanggal_bayar',
        'metode_bayar', 'catatan', 'input_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Generate no invoice otomatis: INV-YYYY-NNN
     */
    public function generateNoInvoice(): string
    {
        $tahun = date('Y');
        $last = $this->like('no_invoice', "INV-{$tahun}-", 'after')
                     ->orderBy('id', 'DESC')
                     ->first();
        $num = $last ? ((int) substr($last['no_invoice'], 9)) + 1 : 1;
        return "INV-{$tahun}-" . str_pad((string) $num, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get with detail items
     */
    public function getWithDetails(int $id): ?array
    {
        $penjualan = $this->find($id);
        if (! $penjualan) return null;

        $db = \Config\Database::connect();

        $details = $db->table('penjualan_detail pd')
            ->select('pd.*, rw.kode AS rw_kode, rw.nama AS rw_nama')
            ->join('rumah_walet rw', 'rw.id = pd.rumah_walet_id', 'left')
            ->where('pd.penjualan_id', $id)
            ->get()->getResultArray();

        $penjualan['details'] = $details;

        return $penjualan;
    }

    /**
     * Get all with filter
     */
    public function getAllFiltered(array $filters = [])
    {
        $builder = $this->builder();
        $builder->where('deleted_at IS NULL');

        if (! empty($filters['dari'])) {
            $builder->where('tanggal >=', $filters['dari']);
        }
        if (! empty($filters['sampai'])) {
            $builder->where('tanggal <=', $filters['sampai']);
        }
        if (! empty($filters['status_bayar'])) {
            $builder->where('status_bayar', $filters['status_bayar']);
        }
        if (! empty($filters['pembeli'])) {
            $builder->like('pembeli_nama', $filters['pembeli']);
        }

        return $builder->orderBy('tanggal', 'DESC')->get()->getResultArray();
    }

    /**
     * Total penjualan (kas masuk) untuk tahun tertentu - hanya yang lunas/DP
     */
    public function totalPenjualanTahun(int $tahun, bool $lunasOnly = false): array
    {
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        $builder = $this->builder();
        $builder->select('
            COALESCE(SUM(total_nilai),0) AS total_nilai,
            COALESCE(SUM(total_berat_kg),0) AS total_berat,
            COUNT(*) AS jumlah_invoice,
            SUM(CASE WHEN status_bayar = "lunas" THEN total_nilai ELSE 0 END) AS total_lunas,
            SUM(CASE WHEN status_bayar = "belum_bayar" THEN total_nilai ELSE 0 END) AS total_belum_bayar,
            SUM(CASE WHEN status_bayar = "dp" THEN total_nilai ELSE 0 END) AS total_dp
        ')
        ->where('tanggal >=', $awal)
        ->where('tanggal <=', $akhir)
        ->where('deleted_at IS NULL');

        return $builder->get()->getRowArray();
    }
}
