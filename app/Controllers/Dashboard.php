<?php

namespace App\Controllers;

use App\Models\RumahWaletModel;
use App\Models\HasilPanenModel;
use App\Models\PengeluaranModel;
use App\Models\JadwalPanenModel;
use App\Models\InspeksiModel;
use App\Models\PenjualanModel;
use App\Models\StokSarangModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $rumahModel       = new RumahWaletModel();
        $panenModel       = new HasilPanenModel();
        $pengeluaranModel = new PengeluaranModel();
        $jadwalModel      = new JadwalPanenModel();
        $inspeksiModel    = new InspeksiModel();
        $penjualanModel   = new PenjualanModel();
        $stokModel        = new StokSarangModel();

        $tahun = (int) date('Y');
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        // P1-6: Range query bukan YEAR()
        $totalPanenTahun = $panenModel->totalBeratTahun($tahun);
        $totalNilaiPanen = $panenModel->totalNilaiTahun($tahun);  // Estimasi nilai potensi jual
        $totalPengeluaranTahun = $pengeluaranModel->totalPengeluaranTahun($tahun);

        // KPI-4: Penjualan (kas riil yang masuk) - P1-2: ganti estimasi jadi kas riil
        $penjualanStats = $penjualanModel->totalPenjualanTahun($tahun);
        $kasMasukLunas    = (float) ($penjualanStats['total_lunas'] ?? 0);
        $kasMasukPending  = (float) ($penjualanStats['total_belum_bayar'] ?? 0) + (float) ($penjualanStats['total_dp'] ?? 0);

        // Estimasi keuntungan = kas masuk lunas - pengeluaran (BUKAN estimasi nilai panen)
        $estimasiKeuntunganRiil = $kasMasukLunas - $totalPengeluaranTahun;
        $estimasiKeuntunganPotensi = $totalNilaiPanen - $totalPengeluaranTahun;

        // Stok tersedia (belum terjual)
        $stokTersedia = $stokModel->where('status_stok', 'tersedia')
            ->where('deleted_at IS NULL')
            ->select('COALESCE(SUM(berat_kg),0) AS total_berat, COUNT(*) AS jumlah_item')
            ->get()->getRowArray();

        $nilaiStokTersedia = 0;
        $stokRows = $stokModel->where('status_stok', 'tersedia')->where('deleted_at IS NULL')->findAll();
        foreach ($stokRows as $s) {
            // Estimasi nilai stok pakai harga default dari master
            $nilaiStokTersedia += (float) $s['berat_kg'] * 10000000; // fallback rough estimate
        }

        // Chart trend panen per bulan (P1-6: range query)
        $trendBulanan = $panenModel->chartPanenBulanan($tahun);
        $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        $trendData = array_fill(0, 12, 0);
        foreach ($trendBulanan as $row) {
            $trendData[(int) $row['bulan'] - 1] = (float) $row['total_kg'];
        }

        // Produktivitas per RW (P1-6: range query + kapasitas tahunan)
        $db = \Config\Database::connect();
        $produktivitas = $db->table('rumah_walet rw')
            ->select('rw.id, rw.kode, rw.nama,
                      COALESCE(SUM(hp.berat_kg), 0) AS total_kg,
                      rw.kapasitas_panen_kg,
                      rw.kondisi')
            ->join('hasil_panen hp', "hp.rumah_walet_id = rw.id AND hp.tanggal_panen >= '{$awal}' AND hp.tanggal_panen <= '{$akhir}' AND hp.deleted_at IS NULL", 'left')
            ->where('rw.status', 'aktif')
            ->where('rw.deleted_at IS NULL')
            ->groupBy('rw.id')
            ->orderBy('total_kg', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Hitung kapasitas tahunan per RW (akomodasi musim - P2-2)
        foreach ($produktivitas as &$p) {
            $p['kapasitas_tahunan'] = $rumahModel->getKapasitasTahunan($p['id'], $tahun);
        }

        // Jadwal panen mendatang (status terjadwal)
        $jadwalMendatang = $jadwalModel->where('status', 'terjadwal')
            ->where('deleted_at IS NULL')
            ->where('tanggal_rencana >=', date('Y-m-d'))
            ->orderBy('tanggal_rencana', 'ASC')
            ->limit(5)
            ->findAll();

        // Inspeksi terbaru
        $inspeksiTerbaru = $inspeksiModel->select('inspeksi.*, rumah_walet.kode AS rw_kode, rumah_walet.nama AS rw_nama, petugas.nama AS petugas_nama')
            ->join('rumah_walet', 'rumah_walet.id = inspeksi.rumah_walet_id')
            ->join('petugas', 'petugas.id = inspeksi.petugas_id')
            ->where('inspeksi.deleted_at IS NULL')
            ->orderBy('inspeksi.tanggal_inspeksi', 'DESC')
            ->limit(5)
            ->findAll();

        // Pending approvals (P2-6)
        $pendingApprovals = $pengeluaranModel->getPendingApprovals();

        $data = [
            'title'                    => 'Dashboard',
            'tahun'                    => $tahun,
            'totalPanenTahun'          => $totalPanenTahun,
            'totalNilaiPanen'          => $totalNilaiPanen,
            'totalPengeluaranTahun'    => $totalPengeluaranTahun,
            'estimasiKeuntunganRiil'   => $estimasiKeuntunganRiil,
            'estimasiKeuntunganPotensi'=> $estimasiKeuntunganPotensi,
            'kasMasukLunas'            => $kasMasukLunas,
            'kasMasukPending'          => $kasMasukPending,
            'stokTersedia'             => $stokTersedia,
            'nilaiStokTersedia'        => $nilaiStokTersedia,
            'trendLabels'              => $bulanLabels,
            'trendData'                => $trendData,
            'produktivitas'            => $produktivitas,
            'jadwalMendatang'          => $jadwalMendatang,
            'inspeksiTerbaru'          => $inspeksiTerbaru,
            'pendingApprovals'         => $pendingApprovals,
        ];

        return $this->render('dashboard/index', $data);
    }

    public function chartPanen()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));
        $model = new HasilPanenModel();
        $rows  = $model->chartPanenBulanan($tahun);

        $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        $data  = array_fill(0, 12, 0);
        foreach ($rows as $r) {
            $data[(int) $r['bulan'] - 1] = (float) $r['total_kg'];
        }

        return $this->response->setJSON([
            'labels' => $bulan,
            'data'   => $data,
        ]);
    }

    public function chartProduktivitas()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));
        $awal  = "{$tahun}-01-01";
        $akhir = "{$tahun}-12-31";

        $db = \Config\Database::connect();
        $rows = $db->table('rumah_walet rw')
            ->select('rw.nama, COALESCE(SUM(hp.berat_kg),0) AS total')
            ->join('hasil_panen hp', "hp.rumah_walet_id = rw.id AND hp.tanggal_panen >= '{$awal}' AND hp.tanggal_panen <= '{$akhir}' AND hp.deleted_at IS NULL", 'left')
            ->where('rw.status', 'aktif')
            ->where('rw.deleted_at IS NULL')
            ->groupBy('rw.id')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        return $this->response->setJSON([
            'labels' => array_column($rows, 'nama'),
            'data'   => array_map('floatval', array_column($rows, 'total')),
        ]);
    }
}
