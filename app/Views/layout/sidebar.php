<?php
$role = session()->get('role');
$currentPath = current_url(true)->getPath();
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-feather-alt text-warning"></i>
        <div>
            <div class="brand">Walet Pro</div>
            <small class="text-muted">Monitoring System v2</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a href="/dashboard" class="nav-item <?= url_is('dashboard') ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
        </a>

        <?php if (in_array($role, ['admin'])): ?>
        <!-- Master Data -->
        <div class="nav-section">MASTER DATA</div>
        <a href="/user" class="nav-item <?= url_is('user*') ? 'active' : '' ?>">
            <i class="fas fa-users-cog"></i> <span>Manajemen User</span>
        </a>
        <a href="/rumah-walet" class="nav-item <?= url_is('rumah-walet*') ? 'active' : '' ?>">
            <i class="fas fa-warehouse"></i> <span>Rumah Walet</span>
        </a>
        <a href="/petugas" class="nav-item <?= url_is('petugas*') ? 'active' : '' ?>">
            <i class="fas fa-user-hard-hat"></i> <span>Petugas</span>
        </a>
        <a href="/harga-grade" class="nav-item <?= url_is('harga-grade*') ? 'active' : '' ?>">
            <i class="fas fa-tags"></i> <span>Master Harga</span>
        </a>
        <?php endif; ?>

        <!-- Operasional -->
        <div class="nav-section">OPERASIONAL</div>
        <?php if (in_array($role, ['admin', 'petugas'])): ?>
        <a href="/inspeksi" class="nav-item <?= url_is('inspeksi*') ? 'active' : '' ?>">
            <i class="fas fa-clipboard-check"></i> <span>Inspeksi</span>
        </a>
        <a href="/audio-walet" class="nav-item <?= url_is('audio-walet*') ? 'active' : '' ?>">
            <i class="fas fa-volume-up"></i> <span>Audio Walet</span>
        </a>
        <a href="/jadwal-panen" class="nav-item <?= url_is('jadwal-panen*') ? 'active' : '' ?>">
            <i class="fas fa-calendar-alt"></i> <span>Jadwal Panen</span>
        </a>
        <a href="/hasil-panen" class="nav-item <?= url_is('hasil-panen*') ? 'active' : '' ?>">
            <i class="fas fa-balance-scale"></i> <span>Hasil Panen</span>
        </a>
        <?php endif; ?>
        <?php if (in_array($role, ['admin'])): ?>
        <a href="/pengeluaran" class="nav-item <?= url_is('pengeluaran*') ? 'active' : '' ?>">
            <i class="fas fa-money-bill-wave"></i>
            <span>Pengeluaran</span>
            <?php if (! empty($pending_count) && $pending_count > 0): ?>
                <span class="badge badge-warning badge-pill ml-auto"><?= $pending_count ?></span>
            <?php endif; ?>
        </a>
        <?php endif; ?>

        <?php if (in_array($role, ['admin', 'owner'])): ?>
        <!-- Penjualan & Stok -->
        <div class="nav-section">PENJUALAN & STOK</div>
        <a href="/penjualan" class="nav-item <?= url_is('penjualan*') ? 'active' : '' ?>">
            <i class="fas fa-file-invoice"></i> <span>Invoice / Penjualan</span>
        </a>
        <a href="/stok" class="nav-item <?= url_is('stok*') ? 'active' : '' ?>">
            <i class="fas fa-boxes"></i> <span>Stok Sarang</span>
        </a>
        <?php endif; ?>

        <!-- Monitoring -->
        <?php if (in_array($role, ['admin', 'owner'])): ?>
        <div class="nav-section">MONITORING</div>
        <a href="/monitoring/produktivitas" class="nav-item <?= url_is('monitoring/produktivitas*') ? 'active' : '' ?>">
            <i class="fas fa-chart-line"></i> <span>Produktivitas</span>
        </a>
        <a href="/monitoring/kondisi-rumah" class="nav-item <?= url_is('monitoring/kondisi-rumah*') ? 'active' : '' ?>">
            <i class="fas fa-heartbeat"></i> <span>Kondisi Rumah Walet</span>
        </a>
        <a href="/monitoring/total-panen" class="nav-item <?= url_is('monitoring/total-panen*') ? 'active' : '' ?>">
            <i class="fas fa-coins"></i> <span>Total Hasil Panen</span>
        </a>

        <!-- Laporan -->
        <div class="nav-section">LAPORAN</div>
        <a href="/laporan/panen" class="nav-item <?= url_is('laporan/panen*') ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i> <span>Laporan Panen</span>
        </a>
        <a href="/laporan/pengeluaran" class="nav-item <?= url_is('laporan/pengeluaran*') ? 'active' : '' ?>">
            <i class="fas fa-file-invoice-dollar"></i> <span>Laporan Pengeluaran</span>
        </a>
        <a href="/laporan/produktivitas" class="nav-item <?= url_is('laporan/produktivitas*') ? 'active' : '' ?>">
            <i class="fas fa-file-signature"></i> <span>Laporan Produktivitas</span>
        </a>
        <a href="/laporan/penjualan" class="nav-item <?= url_is('laporan/penjualan*') ? 'active' : '' ?>">
            <i class="fas fa-file-export"></i> <span>Laporan Penjualan</span>
        </a>
        <?php endif; ?>
    </nav>
</div>

<div class="main-content" id="main-content">
    <header class="topbar">
        <button class="btn-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <div class="topbar-title">
            <h1><?= esc($title ?? 'Dashboard') ?></h1>
        </div>

        <div class="topbar-right">
            <div class="dropdown">
                <a href="#" class="user-menu" data-toggle="dropdown">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= esc(session()->get('nama')) ?></div>
                        <div class="user-role">
                            <span class="badge-role badge-<?= esc(session()->get('role')) ?>"><?= ucfirst(session()->get('role')) ?></span>
                        </div>
                    </div>
                    <i class="fas fa-chevron-down ml-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/auth/change-password" class="dropdown-item">
                        <i class="fas fa-key"></i> Ganti Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="page-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('warning') ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        <?php endif; ?>
