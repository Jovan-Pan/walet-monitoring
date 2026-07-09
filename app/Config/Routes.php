<?php

/**
 * --------------------------------------------------------------------
 * Route Definitions v2.0 - dengan Role Enforcement (P0-2)
 * --------------------------------------------------------------------
 * Setiap grup route dilindungi role yang sesuai:
 *   - admin    : akses penuh (master data, operasional, pengeluaran, laporan)
 *   - petugas  : input inspeksi & hasil panen + lihat jadwal
 *   - owner    : dashboard + monitoring + laporan (read-only)
 * --------------------------------------------------------------------
 */

$routes = service('routes');

// Default route - redirect to login
$routes->get('/', 'AuthController::index');

// Auth routes (public)
$routes->get('login', 'AuthController::index');
$routes->post('login/auth', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('auth/change-password', 'AuthController::changePasswordForm');
$routes->post('auth/change-password', 'AuthController::changePassword');

// Protected routes - need login
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // ============ Dashboard (all roles) ============
    $routes->get('dashboard', 'Dashboard::index');

    // ============ MASTER DATA - admin only ============
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        // User management
        $routes->get('user', 'User::index');
        $routes->get('user/create', 'User::create');
        $routes->post('user/store', 'User::store');
        $routes->get('user/edit/(:num)', 'User::edit/$1');
        $routes->post('user/update/(:num)', 'User::update/$1');
        $routes->get('user/delete/(:num)', 'User::delete/$1');

        // Rumah Walet
        $routes->get('rumah-walet', 'RumahWalet::index');
        $routes->get('rumah-walet/create', 'RumahWalet::create');
        $routes->post('rumah-walet/store', 'RumahWalet::store');
        $routes->get('rumah-walet/edit/(:num)', 'RumahWalet::edit/$1');
        $routes->post('rumah-walet/update/(:num)', 'RumahWalet::update/$1');
        $routes->get('rumah-walet/delete/(:num)', 'RumahWalet::delete/$1');

        // Petugas
        $routes->get('petugas', 'Petugas::index');
        $routes->get('petugas/create', 'Petugas::create');
        $routes->post('petugas/store', 'Petugas::store');
        $routes->get('petugas/edit/(:num)', 'Petugas::edit/$1');
        $routes->post('petugas/update/(:num)', 'Petugas::update/$1');
        $routes->get('petugas/delete/(:num)', 'Petugas::delete/$1');

        // Pengeluaran + Approval
        $routes->get('pengeluaran', 'Pengeluaran::index');
        $routes->get('pengeluaran/create', 'Pengeluaran::create');
        $routes->post('pengeluaran/store', 'Pengeluaran::store');
        $routes->get('pengeluaran/edit/(:num)', 'Pengeluaran::edit/$1');
        $routes->post('pengeluaran/update/(:num)', 'Pengeluaran::update/$1');
        $routes->get('pengeluaran/delete/(:num)', 'Pengeluaran::delete/$1');
        $routes->get('pengeluaran/approve/(:num)/(:segment)', 'Pengeluaran::approve/$1/$2');
        $routes->post('pengeluaran/approve/(:num)', 'Pengeluaran::approveAction/$1');

        // Harga Grade (master)
        $routes->get('harga-grade', 'HargaGrade::index');
        $routes->get('harga-grade/create', 'HargaGrade::create');
        $routes->post('harga-grade/store', 'HargaGrade::store');
        $routes->get('harga-grade/edit/(:num)', 'HargaGrade::edit/$1');
        $routes->post('harga-grade/update/(:num)', 'HargaGrade::update/$1');
        $routes->get('harga-grade/delete/(:num)', 'HargaGrade::delete/$1');
    });

    // Rumah Walet detail - owner juga bisa lihat (read-only)
    $routes->get('rumah-walet/detail/(:num)', 'RumahWalet::detail/$1');

    // ============ OPERASIONAL - admin & petugas ============
    $routes->group('', ['filter' => 'role:admin,petugas'], function ($routes) {
        // Inspeksi
        $routes->get('inspeksi', 'Inspeksi::index');
        $routes->get('inspeksi/create', 'Inspeksi::create');
        $routes->post('inspeksi/store', 'Inspeksi::store');
        $routes->get('inspeksi/edit/(:num)', 'Inspeksi::edit/$1');
        $routes->post('inspeksi/update/(:num)', 'Inspeksi::update/$1');
        $routes->get('inspeksi/delete/(:num)', 'Inspeksi::delete/$1');
        $routes->get('inspeksi/view/(:num)', 'Inspeksi::view/$1');

        // Jadwal Panen
        $routes->get('jadwal-panen', 'JadwalPanen::index');
        $routes->get('jadwal-panen/create', 'JadwalPanen::create');
        $routes->post('jadwal-panen/store', 'JadwalPanen::store');
        $routes->get('jadwal-panen/edit/(:num)', 'JadwalPanen::edit/$1');
        $routes->post('jadwal-panen/update/(:num)', 'JadwalPanen::update/$1');
        $routes->get('jadwal-panen/delete/(:num)', 'JadwalPanen::delete/$1');
        $routes->get('jadwal-panen/status/(:num)/(:segment)', 'JadwalPanen::updateStatus/$1/$2');

        // Hasil Panen (+ batch input)
        $routes->get('hasil-panen', 'HasilPanen::index');
        $routes->get('hasil-panen/create', 'HasilPanen::create');
        $routes->get('hasil-panen/batch', 'HasilPanen::batchCreate');
        $routes->post('hasil-panen/store', 'HasilPanen::store');
        $routes->post('hasil-panen/batch-store', 'HasilPanen::batchStore');
        $routes->get('hasil-panen/edit/(:num)', 'HasilPanen::edit/$1');
        $routes->post('hasil-panen/update/(:num)', 'HasilPanen::update/$1');
        $routes->get('hasil-panen/delete/(:num)', 'HasilPanen::delete/$1');
        $routes->get('hasil-panen/view/(:num)', 'HasilPanen::view/$1');

        // Audio Walet (P2-1)
        $routes->get('audio-walet', 'AudioWalet::index');
        $routes->get('audio-walet/create', 'AudioWalet::create');
        $routes->post('audio-walet/store', 'AudioWalet::store');
        $routes->get('audio-walet/edit/(:num)', 'AudioWalet::edit/$1');
        $routes->post('audio-walet/update/(:num)', 'AudioWalet::update/$1');
        $routes->get('audio-walet/delete/(:num)', 'AudioWalet::delete/$1');
        $routes->get('audio-walet/view/(:num)', 'AudioWalet::view/$1');
    });

    // ============ PENJUALAN - admin & owner ============
    $routes->group('', ['filter' => 'role:admin,owner'], function ($routes) {
        $routes->get('penjualan', 'Penjualan::index');
        $routes->get('penjualan/create', 'Penjualan::create');
        $routes->post('penjualan/store', 'Penjualan::store');
        $routes->get('penjualan/view/(:num)', 'Penjualan::view/$1');
        $routes->get('penjualan/edit/(:num)', 'Penjualan::edit/$1');
        $routes->post('penjualan/update/(:num)', 'Penjualan::update/$1');
        $routes->get('penjualan/delete/(:num)', 'Penjualan::delete/$1');
        $routes->get('penjualan/invoice/(:num)', 'Penjualan::invoice/$1');
        $routes->get('penjualan/invoice-pdf/(:num)', 'Penjualan::invoicePDF/$1');
        $routes->post('penjualan/mark-paid/(:num)', 'Penjualan::markPaid/$1');

        // Stok Sarang
        $routes->get('stok', 'Stok::index');
        $routes->get('stok/move/(:num)', 'Stok::moveForm/$1');
        $routes->post('stok/move/(:num)', 'Stok::move/$1');
        $routes->get('stok/opname', 'Stok::opname');
    });

    // ============ MONITORING & LAPORAN - admin & owner ============
    $routes->group('', ['filter' => 'role:admin,owner'], function ($routes) {
        $routes->get('monitoring', 'Monitoring::index');
        $routes->get('monitoring/produktivitas', 'Monitoring::produktivitas');
        $routes->get('monitoring/kondisi-rumah', 'Monitoring::kondisiRumah');
        $routes->get('monitoring/total-panen', 'Monitoring::totalPanen');

        $routes->get('laporan', 'Laporan::index');
        $routes->get('laporan/panen', 'Laporan::panen');
        $routes->get('laporan/pengeluaran', 'Laporan::pengeluaran');
        $routes->get('laporan/produktivitas', 'Laporan::produktivitas');
        $routes->get('laporan/penjualan', 'Laporan::penjualan');
        $routes->post('laporan/panen/pdf', 'Laporan::panenPDF');
        $routes->post('laporan/panen/excel', 'Laporan::panenExcel');
        $routes->post('laporan/pengeluaran/pdf', 'Laporan::pengeluaranPDF');
        $routes->post('laporan/pengeluaran/excel', 'Laporan::pengeluaranExcel');
        $routes->post('laporan/produktivitas/pdf', 'Laporan::produktivitasPDF');
        $routes->post('laporan/produktivitas/excel', 'Laporan::produktivitasExcel');
        $routes->post('laporan/penjualan/pdf', 'Laporan::penjualanPDF');
        $routes->post('laporan/penjualan/excel', 'Laporan::penjualanExcel');
    });
});

// API endpoint for chart data
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('chart-panen', 'Dashboard::chartPanen');
    $routes->get('chart-produktivitas', 'Dashboard::chartProduktivitas');
    $routes->get('harga-by-grade/(:segment)/(:segment)', 'HasilPanen::getHargaByGrade/$1/$2');
});
