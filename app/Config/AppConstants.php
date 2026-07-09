<?php

namespace Config;

/**
 * Class Constants
 *
 * Application-level constants for walet monitoring.
 * Updated v2.0: kategori pengeluaran recategorized (P2-5)
 */
class Constants
{
    // Roles
    const ROLE_ADMIN   = 'admin';
    const ROLE_PETUGAS = 'petugas';
    const ROLE_OWNER   = 'owner';

    // Status Inspeksi
    const INSPEKSI_STATUS_BAIK    = 'baik';
    const INSPEKSI_STATUS_SEDANG  = 'sedang';
    const INSPEKSI_STATUS_BURUK   = 'buruk';

    // Status Jadwal Panen
    const JADWAL_STATUS_TERJADWAL = 'terjadwal';
    const JADWAL_STATUS_SELESAI   = 'selesai';
    const JADWAL_STATUS_DITUNDA   = 'ditunda';
    const JADWAL_STATUS_BATAL     = 'batal';

    // Grade Sarang Walet
    const GRADE_A = 'A';
    const GRADE_B = 'B';
    const GRADE_C = 'C';

    // Jenis Panen (P2-2) - akomodasi musim
    const JENIS_PANEN_URAT        = 'urat';          // Maret-April
    const JENIS_PANEN_SARANG_UTUH = 'sarang_utuh';   // Juli-September
    const JENIS_PANEN_KECIL       = 'kecil';         // November-Desember

    // Fase Sarang (P2-4) - biologis
    const FASE_KOSONG        = 'kosong';
    const FASE_PEMBENTUKAN   = 'pembentukan';
    const FASE_BERTELUR      = 'bertelur';
    const FASE_MENETAS       = 'menetas';
    const FASE_PIYIK         = 'piyik';
    const FASE_SIAP_PANEN    = 'siap_panen';

    // Kategori Pengeluaran (P2-5: recategorized)
    // - pakan DIHAPUS (walet liar, tidak dipelihara pakan)
    // - tambah: stimulan_aroma, audio, sertifikasi, transportasi, pajak_retribusi, renovasi_besar
    const KATEGORI_PENGELUARAN = [
        'maintenance'      => 'Maintenance',
        'gaji'             => 'Gaji Petugas',
        'listrik'          => 'Listrik & Air',
        'peralatan'        => 'Peralatan',
        'stimulan_aroma'   => 'Stimulan Aroma',
        'audio'            => 'Maintenance Audio',
        'sertifikasi'      => 'Sertifikasi & Lab',
        'transportasi'     => 'Transportasi',
        'pajak_retribusi'  => 'Pajak & Retribusi',
        'renovasi_besar'   => 'Renovasi Besar (Capex)',
        'lainnya'          => 'Lain-lain',
    ];

    // Approval threshold per kategori (P2-6)
    // Pengeluaran >= threshold butuh approval owner
    const APPROVAL_THRESHOLD = [
        'maintenance'      => 5000000,
        'gaji'             => 0,  // auto-approved
        'listrik'          => 0,  // auto-approved
        'peralatan'        => 3000000,
        'stimulan_aroma'   => 2000000,
        'audio'            => 3000000,
        'sertifikasi'      => 5000000,
        'transportasi'     => 2000000,
        'pajak_retribusi'  => 0,  // auto-approved (mandatory)
        'renovasi_besar'   => 10000000,
        'lainnya'          => 5000000,
    ];

    // Status pengeluaran approval
    const APPROVAL_DRAFT         = 'draft';
    const APPROVAL_PENDING       = 'pending';
    const APPROVAL_APPROVED      = 'approved';
    const APPROVAL_REJECTED      = 'rejected';
    const APPROVAL_AUTO_APPROVED = 'auto_approved';

    // Predator types (P2-3)
    const PREDATOR_LIST = [
        'cicak'     => 'Cicak',
        'tikus'     => 'Tikus',
        'semut'     => 'Semut',
        'kecoak'    => 'Kecoak',
        'laba_laba' => 'Laba-laba',
        'kelelawar' => 'Kelelawar',
        'lainnya'   => 'Lainnya',
    ];

    // Tingkat infestasi predator
    const TINGKAT_INFESTASI = [
        'ringan' => 'Ringan',
        'sedang' => 'Sedang',
        'berat'  => 'Berat',
    ];

    // Status stok sarang walet
    const STATUS_STOK_DI_GUDANG_RW    = 'di_gudang_rw';
    const STATUS_STOK_DI_GUDANG_PUSAT = 'di_gudang_pusat';
    const STATUS_STOK_TERJUAL         = 'terjual';
    const STATUS_STOK_HILANG          = 'hilang';

    // Musim panen per bulan (1-12) - kapasitas tahunan tidak flat
    const MUSIM_PANEN_PER_BULAN = [
        1  => 'offseason',
        2  => 'offseason',
        3  => 'urat',          // Panen urat mulai
        4  => 'urat',          // Panen urat peak
        5  => 'transisi',
        6  => 'transisi',
        7  => 'sarang_utuh',   // Panen utuh mulai
        8  => 'sarang_utuh',   // Panen utuh peak
        9  => 'sarang_utuh',   // Panen utuh
        10 => 'transisi',
        11 => 'kecil',         // Panen kecil mulai
        12 => 'kecil',         // Panen kecil
    ];

    // Login rate limit (P0-5)
    const LOGIN_MAX_ATTEMPTS = 5;
    const LOGIN_LOCK_MINUTES = 15;
}
