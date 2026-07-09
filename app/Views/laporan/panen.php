<div class="card mb-3">
    <div class="card-header"><i class="fas fa-filter text-primary-custom"></i> Filter Laporan</div>
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Dari:</label>
            <input type="date" name="dari" class="form-control mr-3" value="<?= esc($dari) ?>">
            <label class="mr-2">Sampai:</label>
            <input type="date" name="sampai" class="form-control mr-3" value="<?= esc($sampai) ?>">
            <label class="mr-2">Grade:</label>
            <select name="grade" class="form-control mr-3">
                <option value="">Semua Grade</option>
                <option value="A" <?= $grade === 'A' ? 'selected' : '' ?>>Grade A</option>
                <option value="B" <?= $grade === 'B' ? 'selected' : '' ?>>Grade B</option>
                <option value="C" <?= $grade === 'C' ? 'selected' : '' ?>>Grade C</option>
            </select>
            <label class="mr-2">Rumah:</label>
            <select name="rumah_walet_id" class="form-control mr-3">
                <option value="">Semua</option>
                <?php foreach ($rumahList as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= (string)$rumah_id === (string)$r['id'] ? 'selected' : '' ?>>
                        <?= esc($r['kode']) ?> - <?= esc($r['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <div><i class="fas fa-balance-scale text-primary-custom"></i> Laporan Hasil Panen</div>
        <div class="btn-group">
            <form method="post" action="/laporan/panen/pdf" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="dari" value="<?= esc($dari) ?>">
                <input type="hidden" name="sampai" value="<?= esc($sampai) ?>">
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</button>
            </form>
            <form method="post" action="/laporan/panen/excel" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="dari" value="<?= esc($dari) ?>">
                <input type="hidden" name="sampai" value="<?= esc($sampai) ?>">
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="kpi-grid mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Total Berat</div>
                    <div class="kpi-value text-primary-custom"><?= angka($total_kg, 2) ?> kg</div>
                </div>
                <div class="kpi-icon"><i class="fas fa-weight"></i></div>
            </div>
            <div class="kpi-card success">
                <div>
                    <div class="kpi-label">Total Nilai</div>
                    <div class="kpi-value text-success-custom"><?= rupiah($total_nilai) ?></div>
                </div>
                <div class="kpi-icon success"><i class="fas fa-coins"></i></div>
            </div>
            <div class="kpi-card info">
                <div>
                    <div class="kpi-label">Jumlah Transaksi</div>
                    <div class="kpi-value text-info"><?= count($data) ?></div>
                </div>
                <div class="kpi-icon info"><i class="fas fa-list-ol"></i></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Rumah Walet</th>
                        <th>Petugas</th>
                        <th>Grade</th>
                        <th>Berat (kg)</th>
                        <th>Harga/kg</th>
                        <th>Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data untuk filter ini</td></tr>
                    <?php else: $no = 1; foreach ($data as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= format_tanggal($r['tanggal_panen'], 'd/m/Y') ?></td>
                            <td><small><?= esc($r['rw_kode']) ?></small> <?= esc($r['rw_nama']) ?></td>
                            <td><?= esc($r['petugas_nama']) ?></td>
                            <td><?= badge_grade($r['grade']) ?></td>
                            <td class="text-right"><?= angka($r['berat_kg'], 3) ?></td>
                            <td class="text-right"><?= rupiah($r['harga_per_kg']) ?></td>
                            <td class="text-right text-success-custom"><strong><?= rupiah($r['berat_kg'] * $r['harga_per_kg']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="5" class="text-right">TOTAL</td>
                        <td class="text-right"><?= angka($total_kg, 3) ?> kg</td>
                        <td></td>
                        <td class="text-right text-success-custom"><?= rupiah($total_nilai) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
