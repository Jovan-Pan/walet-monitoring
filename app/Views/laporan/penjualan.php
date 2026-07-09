<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Dari:</label>
            <input type="date" name="dari" class="form-control mr-2" value="<?= esc($dari) ?>">
            <label class="mr-2">Sampai:</label>
            <input type="date" name="sampai" class="form-control mr-2" value="<?= esc($sampai) ?>">
            <label class="mr-2">Status:</label>
            <select name="status_bayar" class="form-control mr-2">
                <option value="">Semua</option>
                <?php foreach ($status_list as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $status_bayar === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
        </form>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="small">Total Invoice: <?= $summary['total_invoice'] ?? 0 ?></div>
                <div class="h5 mb-0">Rp <?= number_format($summary['total_nilai'] ?? 0, 0, ',', '.') ?></div>
                <small>Total Nilai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="small">Kas Lunas</div>
                <div class="h5 mb-0">Rp <?= number_format($summary['total_lunas'] ?? 0, 0, ',', '.') ?></div>
                <small>Sudah masuk rekening</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="small">Outstanding (Belum + DP)</div>
                <div class="h5 mb-0">Rp <?= number_format(($summary['total_belum_bayar'] ?? 0) + ($summary['total_dp'] ?? 0), 0, ',', '.') ?></div>
                <small>Belum masuk kas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="small">Total Berat Terjual</div>
                <div class="h5 mb-0"><?= number_format($summary['total_berat'] ?? 0, 3, ',', '.') ?> kg</div>
                <small>Seluruh periode</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-export text-primary-custom"></i> Detail Invoice</h5>
        <div>
            <form method="post" action="/laporan/penjualan/pdf" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="dari" value="<?= esc($dari) ?>">
                <input type="hidden" name="sampai" value="<?= esc($sampai) ?>">
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> PDF</button>
            </form>
            <form method="post" action="/laporan/penjualan/excel" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="dari" value="<?= esc($dari) ?>">
                <input type="hidden" name="sampai" value="<?= esc($sampai) ?>">
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Excel</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($penjualan)): ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>Tidak ada invoice pada periode ini</h5>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No Invoice</th>
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th class="text-right">Item</th>
                            <th class="text-right">Berat (kg)</th>
                            <th class="text-right">Total Nilai</th>
                            <th class="text-center">Status</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalNilai = 0; $totalBerat = 0; foreach ($penjualan as $p): ?>
                            <tr>
                                <td><strong><?= esc($p['no_invoice']) ?></strong></td>
                                <td><?= format_tanggal($p['tanggal'], 'd/m/Y') ?></td>
                                <td>
                                    <?= esc($p['pembeli_nama']) ?><br>
                                    <small class="text-muted"><?= esc($p['pembeli_kontak'] ?? '') ?></small>
                                </td>
                                <td class="text-right"><?= esc($p['jumlah_item'] ?? 0) ?></td>
                                <td class="text-right"><?= angka($p['total_berat_kg'], 3) ?></td>
                                <td class="text-right text-success-custom"><strong><?= rupiah($p['total_nilai']) ?></strong></td>
                                <td class="text-center"><?= badge_status_bayar($p['status_bayar']) ?></td>
                                <td><?= ! empty($p['tanggal_bayar']) ? format_tanggal($p['tanggal_bayar'], 'd/m/Y') : '-' ?></td>
                            </tr>
                        <?php $totalNilai += $p['total_nilai']; $totalBerat += $p['total_berat_kg']; endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="4" class="text-right">TOTAL</td>
                            <td class="text-right"><?= angka($totalBerat, 3) ?> kg</td>
                            <td class="text-right text-success-custom"><?= rupiah($totalNilai) ?></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
