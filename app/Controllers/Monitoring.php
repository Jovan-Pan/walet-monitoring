<?php

namespace App\Controllers;

use App\Models\RumahWaletModel;
use App\Models\HasilPanenModel;
use App\Models\PengeluaranModel;
use App\Models\InspeksiModel;

class Monitoring extends BaseController
{
    public function index()
    {
        return $this->produktivitas();
    }

    /**
     * Monitoring Produktivitas Rumah Walet
     */
    public function produktivitas()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));
        $db = \Config\Database::connect();

        $rows = $db->table('rumah_walet')
            ->select('rumah_walet.id, rumah_walet.kode, rumah_walet.nama, rumah_walet.lokasi, 
                      rumah_walet.kapasitas_panen_kg, rumah_walet.kondisi, rumah_walet.status,
                      COALESCE(SUM(hasil_panen.berat_kg), 0) AS total_panen,
                      COALESCE(SUM(hasil_panen.berat_kg * hasil_panen.harga_per_kg), 0) AS total_nilai,
                      COUNT(DISTINCT hasil_panen.id) AS jumlah_panen')
            ->join('hasil_panen', 'hasil_panen.rumah_walet_id = rumah_walet.id AND YEAR(hasil_panen.tanggal_panen) = ' . $tahun, 'left')
            ->where('rumah_walet.status', 'aktif')
            ->groupBy('rumah_walet.id')
            ->orderBy('total_panen', 'DESC')
            ->get()->getResultArray();

        // Hitung persentase terhadap kapasitas
        foreach ($rows as &$r) {
            $kapasitasTahunan = (float) ($r['kapasitas_panen_kg'] ?? 0) * 12;
            $r['persentase_kapasitas'] = $kapasitasTahunan > 0 
                ? min(100, ($r['total_panen'] / $kapasitasTahunan) * 100)
                : 0;
        }

        return $this->render('monitoring/produktivitas', [
            'title' => 'Monitoring Produktivitas',
            'data'  => $rows,
            'tahun' => $tahun,
        ]);
    }

    /**
     * Monitoring Kondisi Tiap Rumah Walet
     */
    public function kondisiRumah()
    {
        $db = \Config\Database::connect();

        $rows = $db->table('rumah_walet')
            ->select('rumah_walet.*, 
                      inspeksi_terakhir.tanggal_inspeksi AS inspeksi_terakhir_tanggal,
                      inspeksi_terakhir.status AS inspeksi_terakhir_status,
                      inspeksi_terakhir.kondisi_bangunan AS kondisi_bangunan_terakhir,
                      inspeksi_terakhir.kondisi_sarang AS kondisi_sarang_terakhir,
                      inspeksi_terakhir.populasi_walet AS populasi_terakhir,
                      inspeksi_terakhir.suhu AS suhu_terakhir,
                      inspeksi_terakhir.kelembaban AS kelembaban_terakhir,
                      petugas.nama AS petugas_nama')
            ->join('(
                SELECT rumah_walet_id, MAX(tanggal_inspeksi) AS max_tanggal
                FROM inspeksi GROUP BY rumah_walet_id
            ) latest', 'latest.rumah_walet_id = rumah_walet.id', 'left')
            ->join('inspeksi AS inspeksi_terakhir', 'inspeksi_terakhir.rumah_walet_id = latest.rumah_walet_id AND inspeksi_terakhir.tanggal_inspeksi = latest.max_tanggal', 'left')
            ->join('petugas', 'petugas.id = inspeksi_terakhir.petugas_id', 'left')
            ->where('rumah_walet.status', 'aktif')
            ->orderBy('rumah_walet.kode', 'ASC')
            ->get()->getResultArray();

        return $this->render('monitoring/kondisi_rumah', [
            'title' => 'Monitoring Kondisi Rumah Walet',
            'data'  => $rows,
        ]);
    }

    /**
     * Monitoring Total Hasil Panen per Periode
     * Support year range filter (tahun_dari - tahun_sampai).
     */
    public function totalPanen()
    {
        $currentYear = (int) date('Y');
        $tahunDari   = (int) ($this->request->getGet('tahun_dari')   ?? ($currentYear - 2));
        $tahunSampai = (int) ($this->request->getGet('tahun_sampai') ?? $currentYear);

        // Swap if user reversed the range
        if ($tahunDari > $tahunSampai) {
            [$tahunDari, $tahunSampai] = [$tahunSampai, $tahunDari];
        }

        // Clamp to a sane range
        $minYear = 2000;
        $maxYear = $currentYear + 1;
        $tahunDari   = max($minYear, min($maxYear, $tahunDari));
        $tahunSampai = max($minYear, min($maxYear, $tahunSampai));

        $db = \Config\Database::connect();

        // Total per TAHUN (for chart + table)
        $perTahun = $db->table('hasil_panen')
            ->select('YEAR(tanggal_panen) AS tahun,
                      SUM(berat_kg) AS total_kg,
                      SUM(berat_kg * harga_per_kg) AS total_nilai,
                      COUNT(*) AS jumlah_panen')
            ->where('YEAR(tanggal_panen) >=', $tahunDari)
            ->where('YEAR(tanggal_panen) <=', $tahunSampai)
            ->groupBy('tahun')
            ->orderBy('tahun', 'ASC')
            ->get()->getResultArray();

        // Build complete year range (so empty years show as 0 in chart)
        $tahunList    = range($tahunDari, $tahunSampai);
        $dataPerTahun = array_fill_keys($tahunList, null);
        foreach ($perTahun as $row) {
            $dataPerTahun[(int) $row['tahun']] = $row;
        }

        // Total per grade (for the range)
        $perGrade = $db->table('hasil_panen')
            ->select('grade, SUM(berat_kg) AS total_kg, SUM(berat_kg * harga_per_kg) AS total_nilai, COUNT(*) AS jumlah')
            ->where('YEAR(tanggal_panen) >=', $tahunDari)
            ->where('YEAR(tanggal_panen) <=', $tahunSampai)
            ->groupBy('grade')
            ->get()->getResultArray();

        // Total per rumah walet (for the range)
        $perRumah = $db->table('rumah_walet')
            ->select('rumah_walet.kode, rumah_walet.nama,
                      COALESCE(SUM(hasil_panen.berat_kg),0) AS total_kg,
                      COALESCE(SUM(hasil_panen.berat_kg * hasil_panen.harga_per_kg),0) AS total_nilai')
            ->join('hasil_panen',
                'hasil_panen.rumah_walet_id = rumah_walet.id ' .
                'AND YEAR(hasil_panen.tanggal_panen) >= ' . (int) $tahunDari .
                ' AND YEAR(hasil_panen.tanggal_panen) <= ' . (int) $tahunSampai,
                'left')
            ->where('rumah_walet.status', 'aktif')
            ->groupBy('rumah_walet.id')
            ->orderBy('total_kg', 'DESC')
            ->get()->getResultArray();

        return $this->render('monitoring/total_panen', [
            'title'        => 'Total Hasil Panen per Periode',
            'tahunDari'    => $tahunDari,
            'tahunSampai'  => $tahunSampai,
            'dataPerTahun' => $dataPerTahun,
            'perTahun'     => $perTahun,
            'perGrade'     => $perGrade,
            'perRumah'     => $perRumah,
        ]);
    }
}
