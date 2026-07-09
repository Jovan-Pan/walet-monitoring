<?php

/**
 * Common Functions v2.0
 * Updated for: new kategori, jenis_panen, fase_sarang, status_stok, approval_status
 */

if (! function_exists('rupiah')) {
    function rupiah($angka)
    {
        if ($angka === null || $angka === '') return 'Rp 0';
        return 'Rp ' . number_format((float) $angka, 0, ',', '.');
    }
}

if (! function_exists('angka')) {
    function angka($value, $desimal = 2)
    {
        if ($value === null || $value === '') return '0';
        return number_format((float) $value, $desimal, ',', '.');
    }
}

if (! function_exists('format_tanggal')) {
    function format_tanggal($tanggal, $format = 'd M Y')
    {
        if (empty($tanggal) || $tanggal === '0000-00-00') return '-';
        $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $bulanFull = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if (strtotime($tanggal) === false) return '-';
        $d = date('d', strtotime($tanggal));
        $m = (int) date('m', strtotime($tanggal));
        $y = date('Y', strtotime($tanggal));

        if ($format === 'd M Y') return $d . ' ' . $bulan[$m] . ' ' . $y;
        if ($format === 'd F Y') return $d . ' ' . $bulanFull[$m] . ' ' . $y;
        if ($format === 'd/m/Y') return date('d/m/Y', strtotime($tanggal));
        return date($format, strtotime($tanggal));
    }
}

if (! function_exists('format_tanggal_waktu')) {
    function format_tanggal_waktu($tanggal)
    {
        if (empty($tanggal)) return '-';
        return date('d/m/Y H:i', strtotime($tanggal));
    }
}

if (! function_exists('badge_status')) {
    function badge_status($status)
    {
        $badges = [
            'baik'       => '<span class="badge badge-success">Baik</span>',
            'sedang'     => '<span class="badge badge-warning">Sedang</span>',
            'buruk'      => '<span class="badge badge-danger">Buruk</span>',
            'terjadwal'  => '<span class="badge badge-info">Terjadwal</span>',
            'selesai'    => '<span class="badge badge-success">Selesai</span>',
            'ditunda'    => '<span class="badge badge-warning">Ditunda</span>',
            'batal'      => '<span class="badge badge-danger">Batal</span>',
            'aktif'      => '<span class="badge badge-success">Aktif</span>',
            'nonaktif'   => '<span class="badge badge-secondary">Nonaktif</span>',
        ];
        return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    }
}

if (! function_exists('badge_grade')) {
    function badge_grade($grade)
    {
        $badges = [
            'A' => '<span class="badge badge-a">Grade A</span>',
            'B' => '<span class="badge badge-b">Grade B</span>',
            'C' => '<span class="badge badge-c">Grade C</span>',
        ];
        return $badges[$grade] ?? '<span class="badge badge-secondary">' . $grade . '</span>';
    }
}

if (! function_exists('badge_jenis_panen')) {
    function badge_jenis_panen($jenis)
    {
        $labels = [
            'urat'        => 'Urat',
            'sarang_utuh' => 'Sarang Utuh',
            'kecil'       => 'Kecil',
        ];
        $colors = [
            'urat'        => 'primary',
            'sarang_utuh' => 'success',
            'kecil'       => 'secondary',
        ];
        $label = $labels[$jenis] ?? ucfirst($jenis);
        $color = $colors[$jenis] ?? 'secondary';
        return '<span class="badge badge-' . $color . '">' . $label . '</span>';
    }
}

if (! function_exists('badge_fase_sarang')) {
    function badge_fase_sarang($fase)
    {
        $labels = [
            'kosong'       => 'Kosong',
            'pembentukan'  => 'Pembentukan',
            'bertelur'     => 'Bertelur',
            'menetas'      => 'Menetas',
            'piyik'        => 'Piyik',
            'siap_panen'   => 'Siap Panen',
        ];
        $colors = [
            'kosong'       => 'secondary',
            'pembentukan'  => 'info',
            'bertelur'     => 'primary',
            'menetas'      => 'warning',
            'piyik'        => 'light',
            'siap_panen'   => 'success',
        ];
        $label = $labels[$fase] ?? ucfirst($fase);
        $color = $colors[$fase] ?? 'secondary';
        return '<span class="badge badge-' . $color . '">' . $label . '</span>';
    }
}

if (! function_exists('badge_role')) {
    function badge_role($role)
    {
        $badges = [
            'admin'   => '<span class="badge badge-admin">Admin</span>',
            'petugas' => '<span class="badge badge-petugas">Petugas</span>',
            'owner'   => '<span class="badge badge-owner">Owner</span>',
        ];
        return $badges[$role] ?? $role;
    }
}

if (! function_exists('badge_approval')) {
    function badge_approval($status)
    {
        $badges = [
            'draft'         => '<span class="badge badge-secondary">Draft</span>',
            'pending'       => '<span class="badge badge-warning">Pending Approval</span>',
            'approved'      => '<span class="badge badge-success">Approved</span>',
            'rejected'      => '<span class="badge badge-danger">Rejected</span>',
            'auto_approved' => '<span class="badge badge-info">Auto Approved</span>',
        ];
        return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    }
}

if (! function_exists('badge_status_bayar')) {
    function badge_status_bayar($status)
    {
        $badges = [
            'belum_bayar' => '<span class="badge badge-danger">Belum Bayar</span>',
            'dp'          => '<span class="badge badge-warning">DP</span>',
            'lunas'       => '<span class="badge badge-success">Lunas</span>',
        ];
        return $badges[$status] ?? '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    }
}

if (! function_exists('badge_status_stok')) {
    function badge_status_stok($status)
    {
        $labels = [
            'di_gudang_rw'    => 'Di Gudang RW',
            'di_gudang_pusat' => 'Di Gudang Pusat',
            'terjual'         => 'Terjual',
            'hilang'          => 'Hilang',
            'tersedia'        => 'Tersedia',
            'pindah_gudang'   => 'Pindah Gudang',
        ];
        $colors = [
            'di_gudang_rw'    => 'info',
            'di_gudang_pusat' => 'primary',
            'terjual'         => 'success',
            'hilang'          => 'danger',
            'tersedia'        => 'success',
            'pindah_gudang'   => 'warning',
        ];
        $label = $labels[$status] ?? ucfirst($status);
        $color = $colors[$status] ?? 'secondary';
        return '<span class="badge badge-' . $color . '">' . $label . '</span>';
    }
}

if (! function_exists('kategori_label')) {
    function kategori_label($kategori)
    {
        $labels = \Config\Constants::KATEGORI_PENGELUARAN;
        return $labels[$kategori] ?? ucfirst($kategori);
    }
}

if (! function_exists('predator_label')) {
    function predator_label($jenis)
    {
        $labels = \Config\Constants::PREDATOR_LIST;
        return $labels[$jenis] ?? ucfirst($jenis);
    }
}

if (! function_exists('tingkat_label')) {
    function tingkat_label($tingkat)
    {
        $labels = \Config\Constants::TINGKAT_INFESTASI;
        return $labels[$tingkat] ?? ucfirst($tingkat);
    }
}

if (! function_exists('musim_panen_label')) {
    function musim_panen_label(int $bulan): string
    {
        $musim = \Config\Constants::MUSIM_PANEN_PER_BULAN[$bulan] ?? 'offseason';
        $labels = [
            'offseason'    => 'Off Season',
            'urat'         => 'Panen Urat',
            'sarang_utuh'  => 'Panen Sarang Utuh',
            'kecil'        => 'Panen Kecil',
            'transisi'     => 'Transisi',
        ];
        return $labels[$musim] ?? ucfirst($musim);
    }
}
