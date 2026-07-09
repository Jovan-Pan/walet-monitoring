<div class="card mb-3">
    <div class="card-header"><i class="fas fa-filter text-primary-custom"></i> Filter Laporan</div>
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Dari:</label>
            <input type="date" name="dari" class="form-control mr-3" value="<?= esc($dari) ?>">
            <label class="mr-2">Sampai:</label>
            <input type="date" name="sampai" class="form-control mr-3" value="<?= esc($sampai) ?>">
            <label class="mr-2">Kategori:</label>
            <select name="kategori" class="form-control mr-3">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategori_list as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $kategori === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <div><i class="fas fa-money-bill-wave text-primary-custom"></i> Laporan Pengeluaran Operasional</div>
        <div class="btn-group">
            <form method="post" action="/laporan/pengeluaran/pdf" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="dari" value="<?= esc($dari) ?>">
                <input type="hidden" name="sampai" value="<?= esc($sampai) ?>">
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</button>
            </form>
            <form method="post" action="/laporan/pengeluaran/excel" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="dari" value="<?= esc($dari) ?>">
                <input type="hidden" name="sampai" value="<?= esc($sampai) ?>">
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="kpi-grid mb-3">
            <div class="kpi-card danger">
                <div>
                    <div class="kpi-label">Total Pengeluaran</div>
                    <div class="kpi-value text-danger-custom"><?= rupiah($total) ?></div>
                </div>
                <div class="kpi-icon danger"><i class="fas fa-money-bill-wave"></i></div>
            </div>
            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Jumlah Transaksi</div>
                    <div class="kpi-value text-primary-custom"><?= count($data) ?></div>
                </div>
                <div class="kpi-icon"><i class="fas fa-list-ol"></i></div>
            </div>
            <?php
            $perKategori = [];
            foreach ($data as $r) { $perKategori[$r['kategori']] = ($perKategori[$r['kategori']] ?? 0) + $r['jumlah']; }
            arsort($perKategori);
            $topKategori = ! empty($perKategori) ? array_key_first($perKategori) : null;
            ?>
            <div class="kpi-card warning">
                <div>
                    <div class="kpi-label">Kategori Terbesar</div>
                    <div class="kpi-value text-warning-custom" style="font-size:18px;"><?= $topKategori ? kategori_label($topKategori) : '-' ?></div>
                </div>
                <div class="kpi-icon warning"><i class="fas fa-chart-pie"></i></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Rumah Walet</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data untuk filter ini</td></tr>
                    <?php else: $no = 1; foreach ($data as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= format_tanggal($r['tanggal'], 'd/m/Y') ?></td>
                            <td><span class="badge badge-info"><?= kategori_label($r['kategori']) ?></span></td>
                            <td><?= ! empty($r['rw_kode']) ? esc($r['rw_kode'] . ' - ' . $r['rw_nama']) : '<span class="text-muted">Umum</span>' ?></td>
                            <td><?= esc($r['keterangan']) ?></td>
                            <td class="text-right text-danger-custom"><strong><?= rupiah($r['jumlah']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="5" class="text-right">TOTAL</td>
                        <td class="text-right text-danger-custom"><?= rupiah($total) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (! empty($perKategori)): ?>
        <hr>
        <h6>Rekap per Kategori</h6>
        <table class="table table-sm">
            <thead><tr><th>Kategori</th><th class="text-right">Jumlah</th><th class="text-right">Persentase</th></tr></thead>
            <tbody>
                <?php foreach ($perKategori as $k => $v): ?>
                    <tr>
                        <td><?= kategori_label($k) ?></td>
                        <td class="text-right"><?= rupiah($v) ?></td>
                        <td class="text-right"><?= $total > 0 ? round(($v / $total) * 100, 1) : 0 ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
