<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - <?= esc($appName ?? 'Sistem Monitoring Walet') ?></title>
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/all.min.css">
    <link rel="stylesheet" href="/assets/css/vendor/select2.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php if (! empty($toast)): $t = json_decode($toast, true); ?>
<div class="toast-container position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    <div class="toast toast-<?= esc($t['tipe']) ?>" role="alert" data-delay="4000">
        <div class="toast-body">
            <i class="fas fa-<?= $t['tipe'] === 'success' ? 'check-circle' : ($t['tipe'] === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
            <?= esc($t['pesan']) ?>
        </div>
    </div>
</div>
<?php endif; ?>
