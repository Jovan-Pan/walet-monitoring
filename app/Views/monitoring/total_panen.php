<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline align-items-center">
            <label class="mr-2"><i class="fas fa-calendar-alt text-primary-custom"></i> Periode:</label>
            <select name="tahun_dari" class="form-control mr-1">
                <?php for ($y = date('Y') + 1; $y >= 2015; $y--): ?>
                    <option value="<?= $y ?>" <?= (int) $tahunDari === $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <span class="mx-2 font-weight-bold">s/d</span>
            <select name="tahun_sampai" class="form-control mr-2">
                <?php for ($y = date('Y') + 1; $y >= 2015; $y--): ?>
                    <option value="<?= $y ?>" <?= (int) $tahunSampai === $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-sync"></i> Tampilkan</button>
            <?php $totalTahunRange = (int) $tahunSampai - (int) $tahunDari + 1; ?>
            <span class="ml-auto text-muted small">
                <i class="fas fa-info-circle"></i> Menampilkan <?= $totalTahunRange ?> tahun (<?= esc($tahunDari) ?> - <?= esc($tahunSampai) ?>)
            </span>
        </form>
    </div>
</div>

<?php
// Compute totals across the year range
$totalPanenRange = 0; $totalNilaiRange = 0; $totalJumlahRange = 0;
foreach ($dataPerTahun as $tahun => $row) {
    if ($row) {
        $totalPanenRange  += (float) $row['total_kg'];
        $totalNilaiRange  += (float) $row['total_nilai'];
        $totalJumlahRange += (int)   $row['jumlah_panen'];
    }
}
$rataPanenPerTahun = $totalTahunRange > 0 ? $totalPanenRange / $totalTahunRange : 0;
?>

<div class="kpi-grid">
    <div class="kpi-card">
        <div>
            <div class="kpi-label">Total Panen <?= esc($tahunDari) ?>-<?= esc($tahunSampai) ?></div>
            <div class="kpi-value text-primary-custom"><?= angka($totalPanenRange, 2) ?> <small>kg</small></div>
        </div>
        <div class="kpi-icon"><i class="fas fa-balance-scale"></i></div>
    </div>
    <div class="kpi-card success">
        <div>
            <div class="kpi-label">Total Nilai Panen</div>
            <div class="kpi-value text-success-custom"><?= rupiah($totalNilaiRange) ?></div>
        </div>
        <div class="kpi-icon success"><i class="fas fa-coins"></i></div>
    </div>
    <div class="kpi-card info">
        <div>
            <div class="kpi-label">Rata-rata / Tahun</div>
            <div class="kpi-value text-info"><?= angka($rataPanenPerTahun, 2) ?> <small>kg</small></div>
        </div>
        <div class="kpi-icon info"><i class="fas fa-chart-line"></i></div>
    </div>
    <div class="kpi-card warning">
        <div>
            <div class="kpi-label">Jumlah Panen</div>
            <div class="kpi-value text-warning-custom">
                <?= $totalJumlahRange ?> <small>x</small>
            </div>
        </div>
        <div class="kpi-icon warning"><i class="fas fa-calendar-check"></i></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-chart-bar text-primary-custom"></i> Total Panen per Tahun</div>
    <div class="card-body">
        <div class="chart-wrapper" style="height:320px;">
            <canvas id="chartTotalPanen"></canvas>
        </div>
        <?php if ($totalPanenRange == 0): ?>
            <div class="text-center text-muted mt-3">
                <i class="fas fa-info-circle"></i>
                Belum ada data panen pada rentang tahun ini.
                Silakan input data di menu <a href="/hasil-panen/create">Operasional → Hasil Panen</a>.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-table text-primary-custom"></i> Detail per Tahun</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th class="text-center">Jumlah Panen</th>
                        <th class="text-right">Total Berat (kg)</th>
                        <th class="text-right">Total Nilai (Rp)</th>
                        <th class="text-right">Rata-rata / Panen (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataPerTahun as $tahun => $row): ?>
                        <tr>
                            <td><strong><?= esc($tahun) ?></strong></td>
                            <td class="text-center"><?= $row ? $row['jumlah_panen'] . 'x' : '-' ?></td>
                            <td class="text-right"><?= $row ? angka($row['total_kg'], 2) : '-' ?></td>
                            <td class="text-right"><?= $row ? rupiah($row['total_nilai']) : '-' ?></td>
                            <td class="text-right">
                                <?php if ($row && $row['jumlah_panen'] > 0): ?>
                                    <?= angka($row['total_kg'] / $row['jumlah_panen'], 2) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="2">TOTAL</td>
                        <td class="text-right text-primary-custom"><?= angka($totalPanenRange, 2) ?> kg</td>
                        <td class="text-right text-success-custom"><?= rupiah($totalNilaiRange) ?></td>
                        <td class="text-right text-muted">
                            <?= $totalJumlahRange > 0 ? angka($totalPanenRange / $totalJumlahRange, 2) : '-' ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-trophy text-warning"></i> Total per Grade</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Grade</th><th class="text-right">Berat (kg)</th><th class="text-right">Nilai (Rp)</th><th class="text-center">Jumlah</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($perGrade)): ?>
                            <tr><td colspan="4" class="text-center text-muted">Tidak ada data</td></tr>
                        <?php else: foreach ($perGrade as $g): ?>
                            <tr>
                                <td><?= badge_grade($g['grade']) ?></td>
                                <td class="text-right"><?= angka($g['total_kg'], 2) ?></td>
                                <td class="text-right text-success-custom"><?= rupiah($g['total_nilai']) ?></td>
                                <td class="text-center"><?= $g['jumlah'] ?>x</td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-warehouse text-info"></i> Total per Rumah Walet</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Rumah Walet</th><th class="text-right">Berat (kg)</th><th class="text-right">Nilai</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($perRumah) || (count($perRumah) === 1 && $perRumah[0]['total_kg'] == 0)): ?>
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>
                        <?php else: foreach ($perRumah as $r): ?>
                            <?php if ($r['total_kg'] > 0): ?>
                            <tr>
                                <td><small><?= esc($r['kode']) ?></small> <?= esc($r['nama']) ?></td>
                                <td class="text-right"><?= angka($r['total_kg'], 2) ?></td>
                                <td class="text-right text-success-custom"><?= rupiah($r['total_nilai']) ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Wrap in DOMContentLoaded supaya Chart.js sudah ter-load (di footer) sebelum dieksekusi
document.addEventListener('DOMContentLoaded', function () {
    // Chart: total panen per tahun
    const ctx = document.getElementById('chartTotalPanen').getContext('2d');

    const labels = <?php
        $ylabels = [];
        foreach ($dataPerTahun as $y => $row) { $ylabels[] = (string) $y; }
        echo json_encode($ylabels);
    ?>;
    const dataPanen = [
    <?php foreach ($dataPerTahun as $row): ?><?= $row ? round((float) $row['total_kg'], 2) : 0 ?>,<?php endforeach; ?>
    ];
    const dataNilai = [
    <?php foreach ($dataPerTahun as $row): ?><?= $row ? round((float) $row['total_nilai'], 0) : 0 ?>,<?php endforeach; ?>
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Panen (kg)',
                    data: dataPanen,
                    backgroundColor: 'rgba(30, 96, 145, 0.85)',
                    borderColor: '#1e6091',
                    borderWidth: 1,
                    borderRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Total Nilai (Rp)',
                    data: dataNilai,
                    type: 'line',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.15)',
                    tension: 0.3,
                    fill: false,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            let v = ctx.raw;
                            if (ctx.dataset.label.includes('Nilai')) {
                                return ctx.dataset.label + ': Rp ' + v.toLocaleString('id-ID');
                            }
                            return ctx.dataset.label + ': ' + v.toLocaleString('id-ID') + ' kg';
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Berat (kg)' },
                    ticks: { callback: v => v + ' kg' }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Nilai (Rp)' },
                    ticks: {
                        callback: function(v) {
                            if (v >= 1e9) return (v/1e9).toFixed(1) + ' M';
                            if (v >= 1e6) return (v/1e6).toFixed(1) + ' jt';
                            if (v >= 1e3) return (v/1e3).toFixed(0) + ' rb';
                            return v;
                        }
                    }
                }
            }
        }
    });
});
</script>
