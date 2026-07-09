<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table            = 'pengeluaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'tanggal', 'rumah_walet_id', 'kategori', 'keterangan', 'jumlah', 'bukti',
        'input_by', 'approval_status', 'approved_by', 'approval_date', 'approval_note',
        'vendor_id',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Get with relations (rumah_walet, input_by, approved_by)
     */
    public function getWithRelations(array $filters = [])
    {
        $builder = $this->builder();
        $builder->select('pengeluaran.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, u1.nama AS input_by_nama, u2.nama AS approved_by_nama')
            ->join('rumah_walet', 'rumah_walet.id = pengeluaran.rumah_walet_id', 'left')
            ->join('users u1', 'u1.id = pengeluaran.input_by', 'left')
            ->join('users u2', 'u2.id = pengeluaran.approved_by', 'left')
            ->where('pengeluaran.deleted_at IS NULL');

        if (! empty($filters['kategori'])) {
            $builder->where('pengeluaran.kategori', $filters['kategori']);
        }
        if (! empty($filters['approval_status'])) {
            $builder->where('pengeluaran.approval_status', $filters['approval_status']);
        }
        if (! empty($filters['rumah_walet_id'])) {
            $builder->where('pengeluaran.rumah_walet_id', $filters['rumah_walet_id']);
        }
        if (! empty($filters['dari'])) {
            $builder->where('pengeluaran.tanggal >=', $filters['dari']);
        }
        if (! empty($filters['sampai'])) {
            $builder->where('pengeluaran.tanggal <=', $filters['sampai']);
        }

        return $builder->orderBy('pengeluaran.tanggal', 'DESC')->get()->getResultArray();
    }

    /**
     * Total pengeluaran tahunan (hanya yang approved) - P1-6 range query
     */
    public function totalPengeluaranTahun(int $tahun, bool $approvedOnly = true): float
    {
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        $builder = $this->builder();
        $builder->select('COALESCE(SUM(jumlah),0) AS total')
            ->where('tanggal >=', $awal)
            ->where('tanggal <=', $akhir)
            ->where('deleted_at IS NULL');

        if ($approvedOnly) {
            $builder->whereIn('approval_status', ['approved', 'auto_approved']);
        }

        $row = $builder->get()->getRowArray();
        return (float) ($row['total'] ?? 0);
    }

    /**
     * Get pengeluaran per RW untuk periode tertentu (dengan alokasi gaji auto - P1-1)
     */
    public function getPengeluaranPerRW(int $rwId, int $tahun): array
    {
        $db = \Config\Database::connect();
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        // 1. Pengeluaran langsung ke RW ini
        $langsung = $db->table('pengeluaran')
            ->select('tanggal, kategori, keterangan, jumlah, "langsung" AS tipe_alokasi, 100 AS persentase')
            ->where('rumah_walet_id', $rwId)
            ->where('tanggal >=', $awal)
            ->where('tanggal <=', $akhir)
            ->where('deleted_at IS NULL')
            ->whereIn('approval_status', ['approved', 'auto_approved'])
            ->get()->getResultArray();

        // 2. Pengeluaran yang dialokasikan ke RW ini (via pengeluaran_alokasi - P1-1)
        $alokasi = $db->table('pengeluaran_alokasi pa')
            ->select('p.tanggal, p.kategori, p.keterangan, pa.jumlah_alokasi AS jumlah, "alokasi" AS tipe_alokasi, pa.persentase')
            ->join('pengeluaran p', 'p.id = pa.pengeluaran_id')
            ->where('pa.rumah_walet_id', $rwId)
            ->where('p.tanggal >=', $awal)
            ->where('p.tanggal <=', $akhir)
            ->where('p.deleted_at IS NULL')
            ->whereIn('p.approval_status', ['approved', 'auto_approved'])
            ->get()->getResultArray();

        return array_merge($langsung, $alokasi);
    }

    /**
     * Get total pengeluaran per RW (langsung + alokasi) - P1-1
     */
    public function getTotalPengeluaranPerRW(int $rwId, int $tahun): float
    {
        $rows = $this->getPengeluaranPerRW($rwId, $tahun);
        $total = 0;
        foreach ($rows as $r) {
            $total += (float) $r['jumlah'];
        }
        return $total;
    }

    /**
     * Auto-alokasi gaji ke RW berdasarkan proporsi kapasitas (P1-1)
     * Dipanggil setelah insert pengeluaran kategori 'gaji' dengan rumah_walet_id NULL
     */
    public function autoAlokasiGaji(int $pengeluaranId): void
    {
        $db = \Config\Database::connect();
        $pengeluaran = $this->find($pengeluaranId);
        if (! $pengeluaran) return;

        // Ambil semua RW aktif dengan kapasitas
        $rwModel = new RumahWaletModel();
        $rwList  = $rwModel->where('status', 'aktif')->findAll();

        if (empty($rwList)) return;

        // Hitung total kapasitas tahunan semua RW
        $totalKapasitas = 0;
        $rwKapasitas = [];
        foreach ($rwList as $rw) {
            $kap = $rwModel->getKapasitasTahunan($rw['id']);
            $rwKapasitas[$rw['id']] = $kap;
            $totalKapasitas += $kap;
        }

        if ($totalKapasitas == 0) {
            // Fallback: bagi rata kalau semua kapasitas 0
            $count = count($rwList);
            $perRw = $pengeluaran['jumlah'] / $count;
            foreach ($rwList as $rw) {
                $db->table('pengeluaran_alokasi')->insert([
                    'pengeluaran_id'  => $pengeluaranId,
                    'rumah_walet_id'  => $rw['id'],
                    'jumlah_alokasi'  => $perRw,
                    'persentase'      => round(100 / $count, 2),
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
            return;
        }

        // Bagi proporsional berdasarkan kapasitas
        foreach ($rwList as $rw) {
            $persen = ($rwKapasitas[$rw['id']] / $totalKapasitas) * 100;
            $jumlah = $pengeluaran['jumlah'] * ($persen / 100);

            $db->table('pengeluaran_alokasi')->insert([
                'pengeluaran_id'  => $pengeluaranId,
                'rumah_walet_id'  => $rw['id'],
                'jumlah_alokasi'  => round($jumlah, 2),
                'persentase'      => round($persen, 2),
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Get pending approvals (P2-6)
     */
    public function getPendingApprovals(): array
    {
        return $this->where('approval_status', 'pending')
            ->where('deleted_at IS NULL')
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }
}
