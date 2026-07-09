<div class="row">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="kpi-card">
            <div>
                <div class="kpi-label">Total Panen <?= $tahun ?></div>
                <div class="kpi-value text-primary-custom"><?= angka($totalPanenTahun, 2) ?> <small>kg</small></div>
                <div class="kpi-subtext">Estimasi nilai: <?= rupiah($totalNilaiPanen) ?></div>
            </div>
            <div class="kpi-icon"><i class="fas fa-balance-scale"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="kpi-card success">
            <div>
                <div class="kpi-label">Kas Masuk (Lunas)</div>
                <div class="kpi-value text-success-custom"><?= rupiah($kasMasukLunas) ?></div>
                <div class="kpi-subtext">Outstanding: <?= rupiah($kasMasukPending) ?></div>
            </div>
            <div class="kpi-icon success"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="kpi-card danger">
            <div>
                <div class="kpi-label">Total Pengeluaran</div>
                <div class="kpi-value text-danger"><?= rupiah($totalPengeluaranTahun) ?></div>
                <div class="kpi-subtext">Tahun <?= $tahun ?></div>
            </div>
            <div class="kpi-icon danger"><i class="fas fa-receipt"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="kpi-card info">
            <div>
                <div class="kpi-label">Keuntungan Riil (Kas)</div>
                <div class="kpi-value text-info">
                    <?php if ($estimasiKeuntunganRiil >= 0): ?>
                        <?= rupiah($estimasiKeuntunganRiil) ?>
                    <?php else: ?>
                        <span class="text-danger"><?= rupiah($estimasiKeuntunganRiil) ?></span>
                    <?php endif; ?>
                </div>
                <div class="kpi-subtext">Kas masuk - pengeluaran</div>
            </div>
            <div class="kpi-icon info"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
</div>

<?php if ($estimasiKeuntunganPotensi !== $estimasiKeuntunganRiil): ?>
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Perhatian:</strong> Estimasi nilai panen (<?= rupiah($totalNilaiPanen) ?>) adalah <em>potensi jual</em>, bukan kas masuk.
    Selisih potensi vs kas riil: <strong><?= rupiah($estimasiKeuntunganPotensi - $estimasiKeuntunganRiil) ?></strong> (sarang yang belum terjual / belum lunas).
    Stok tersedia: <strong><?= angka($stokTersedia['total_berat'] ?? 0, 2) ?> kg</strong> di gudang.
</div>
<?php endif; ?>

<?php if (! empty($pendingApprovals)): ?>
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <strong><?= count($pendingApprovals) ?> pengeluaran menunggu approval Anda.</strong>
    <a href="/pengeluaran?approval_status=pending" class="alert-link">Review sekarang →</a>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar text-primary-custom"></i> Tren Panen per Bulan (<?= $tahun ?>)
            </div>
            <div class="card-body">
                <div class="chart-wrapper" style="height:320px;">
                    <canvas id="chartTrend"></canvas>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-trophy text-warning"></i> Top 5 Rumah Walet (Produksi <?= $tahun ?>)
            </div>
            <div class="card-body">
                <div class="chart-wrapper" style="height:260px;">
                    <canvas id="chartProduktivitas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-calendar-alt text-info"></i> Jadwal Panen Mendatang</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($jadwalMendatang)): ?>
                        <li class="list-group-item text-center text-muted py-3">
                            <i class="fas fa-calendar-times"></i><br>Tidak ada jadwal mendatang
                        </li>
                    <?php else: foreach ($jadwalMendatang as $j): ?>
                        <li class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= esc($j['kode'] ?? 'RW') ?> - <?= esc($j['nama'] ?? '') ?></h6>
                                <small><?= format_tanggal($j['tanggal_rencana']) ?></small>
                            </div>
                            <small class="text-muted">
                                Estimasi: <?= angka($j['estimasi_hasil_kg'], 2) ?> kg
                                <?php if (! empty($j['jenis_panen_rencana'])): ?>
                                    | <?= badge_jenis_panen($j['jenis_panen_rencana']) ?>
                                <?php endif; ?>
                            </small>
                        </li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-clipboard-check text-success"></i> Inspeksi Terbaru</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($inspeksiTerbaru)): ?>
                        <li class="list-group-item text-center text-muted py-3">
                            <i class="fas fa-clipboard"></i><br>Belum ada inspeksi
                        </li>
                    <?php else: foreach ($inspeksiTerbaru as $i): ?>
                        <li class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= esc($i['rw_kode']) ?> <?= esc($i['rw_nama']) ?></h6>
                                <small><?= format_tanggal($i['tanggal_inspeksi']) ?></small>
                            </div>
                            <small>
                                <?= badge_status($i['status']) ?>
                                <?php if (! empty($i['fase_sarang'])): ?>
                                    | <?= badge_fase_sarang($i['fase_sarang']) ?>
                                <?php endif; ?>
                                | <i class="fas fa-user"></i> <?= esc($i['petugas_nama']) ?>
                            </small>
                        </li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-boxes text-primary"></i> Stok Sarang Tersedia</div>
            <div class="card-body">
                <div class="text-center">
                    <h2 class="text-primary-custom mb-0"><?= angka($stokTersedia['total_berat'] ?? 0, 2) ?> <small>kg</small></h2>
                    <small class="text-muted"><?= $stokTersedia['jumlah_item'] ?? 0 ?> item di gudang</small>
                </div>
                <a href="/stok" class="btn btn-outline-primary btn-block btn-sm mt-2">
                    <i class="fas fa-arrow-right"></i> Detail Stok
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Wrap in DOMContentLoaded supaya Chart.js sudah ter-load (di footer) sebelum dieksekusi
document.addEventListener('DOMContentLoaded', function () {
    // Tren panen per bulan
    new Chart(document.getElementById('chartTrend'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($trendLabels) ?>,
            datasets: [{
                label: 'Panen (kg)',
                data: <?= json_encode($trendData) ?>,
                backgroundColor: 'rgba(30, 96, 145, 0.85)',
                borderColor: '#1e6091',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => v + ' kg' } } }
        }
    });

    // Top 5 RW produktivitas
    fetch('/api/chart-produktivitas?tahun=<?= $tahun ?>')
        .then(r => r.json())
        .then(data => {
            new Chart(document.getElementById('chartProduktivitas'), {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: ['#1e6091', '#28a745', '#ffc107', '#17a2b8', '#dc3545'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: { callbacks: { label: c => c.label + ': ' + c.raw.toFixed(2) + ' kg' } }
                    }
                }
            });
        });
});
</script>
