<?php

/*
 | --------------------------------------------------------------------
 | App Namespace (required by CodeIgniter 4.4.x bootstrap)
 | --------------------------------------------------------------------
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Application Constants (also exposed via Config\Constants class for app use)
 | --------------------------------------------------------------------------
 */

// Roles
defined('ROLE_ADMIN')   || define('ROLE_ADMIN', 'admin');
defined('ROLE_PETUGAS') || define('ROLE_PETUGAS', 'petugas');
defined('ROLE_OWNER')   || define('ROLE_OWNER', 'owner');

// Status Inspeksi
defined('INSPEKSI_STATUS_BAIK')   || define('INSPEKSI_STATUS_BAIK', 'baik');
defined('INSPEKSI_STATUS_SEDANG') || define('INSPEKSI_STATUS_SEDANG', 'sedang');
defined('INSPEKSI_STATUS_BURUK')  || define('INSPEKSI_STATUS_BURUK', 'buruk');

// Status Jadwal Panen
defined('JADWAL_STATUS_TERJADWAL') || define('JADWAL_STATUS_TERJADWAL', 'terjadwal');
defined('JADWAL_STATUS_SELESAI')   || define('JADWAL_STATUS_SELESAI', 'selesai');
defined('JADWAL_STATUS_DITUNDA')   || define('JADWAL_STATUS_DITUNDA', 'ditunda');
defined('JADWAL_STATUS_BATAL')     || define('JADWAL_STATUS_BATAL', 'batal');

// Grade Sarang Walet
defined('GRADE_A') || define('GRADE_A', 'A');
defined('GRADE_B') || define('GRADE_B', 'B');
defined('GRADE_C') || define('GRADE_C', 'C');

// Kategori Pengeluaran
defined('KATEGORI_PENGELUARAN') || define('KATEGORI_PENGELUARAN', serialize([
    'maintenance' => 'Maintenance',
    'gaji'        => 'Gaji Petugas',
    'listrik'     => 'Listrik & Air',
    'peralatan'   => 'Peralatan',
    'pakan'       => 'Pakan & Stimulan',
    'lainnya'     => 'Lain-lain',
]));

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6);
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);
