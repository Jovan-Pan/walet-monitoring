<div class="row">
    <?php
    $cards = [
        ['url' => '/laporan/panen', 'icon' => 'fa-balance-scale', 'title' => 'Laporan Hasil Panen', 'desc' => 'Laporan rekapitulasi panen sarang walet per periode, grade, dan rumah walet.', 'color' => 'primary'],
        ['url' => '/laporan/pengeluaran', 'icon' => 'fa-money-bill-wave', 'title' => 'Laporan Pengeluaran', 'desc' => 'Laporan biaya operasional per kategori dan periode waktu tertentu.', 'color' => 'danger'],
        ['url' => '/laporan/produktivitas', 'icon' => 'fa-chart-line', 'title' => 'Laporan Produktivitas', 'desc' => 'Laporan produktivitas rumah walet dengan perbandingan panen, biaya, dan keuntungan.', 'color' => 'success'],
    ];
    foreach ($cards as $c):
    ?>
    <div class="col-md-4">
        <a href="<?= $c['url'] ?>" class="text-decoration-none text-reset">
            <div class="card h-100 hover-shadow">
                <div class="card-body text-center">
                    <div class="kpi-icon mx-auto mb-3 <?= $c['color'] ?>" style="width:80px;height:80px;font-size:36px;">
                        <i class="fas <?= $c['icon'] ?>"></i>
                    </div>
                    <h5 class="card-title"><?= $c['title'] ?></h5>
                    <p class="text-muted small"><?= $c['desc'] ?></p>
                    <span class="btn btn-<?= $c['color'] ?> btn-sm">Lihat Laporan <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
