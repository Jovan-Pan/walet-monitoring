<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Tahun:</label>
            <select name="tahun" class="form-control mr-3">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                    <option value="<?= $y ?>" <?= (int) $tahun === $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <div><i class="fas fa-chart-line text-primary-custom"></i> Laporan Produktivitas Tahun <?= esc($tahun) ?></div>
        <div class="btn-group">
            <form method="post" action="/laporan/produktivitas/pdf" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="tahun" value="<?= esc($tahun) ?>">
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Export PDF</button>
            </form>
            <form method="post" action="/laporan/produktivitas/excel" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="tahun" value="<?= esc($tahun) ?>">
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Export Excel</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Rumah Walet</th>
                        <th>Kapasitas/Bln (kg)</th>
                        <th>Total Panen (kg)</th>
                        <th>% Kapasitas</th>
                        <th>Total Nilai (Rp)</th>
                        <th>Total Pengeluaran (Rp)</th>
                        <th>Estimasi Keuntungan</th>
                        <th>Jumlah Panen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada data</td></tr>
                    <?php else: $no = 1; $gtPanen = $gtNilai = $gtPeng = $gtUntung = 0; foreach ($data as $r): 
                        $gtPanen += $r['total_panen']; $gtNilai += $r['total_nilai']; 
                        $gtPeng += $r['total_pengeluaran']; $gtUntung += $r['estimasi_keuntungan'];
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($r['kode']) ?></strong></td>
                            <td><?= esc($r['nama']) ?></td>
                            <td class="text-right"><?= $r['kapasitas_panen_kg'] ? angka($r['kapasitas_panen_kg'], 2) : '-' ?></td>
                            <td class="text-right text-primary-custom"><strong><?= angka($r['total_panen'], 2) ?></strong></td>
                            <td>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar <?= $r['persentase_kapasitas'] >= 80 ? 'bg-success' : ($r['persentase_kapasitas'] >= 50 ? '' : 'bg-warning') ?>" 
                                         style="width: <?= min(100, $r['persentase_kapasitas']) ?>%"></div>
                                </div>
                                <small><?= round($r['persentase_kapasitas'], 1) ?>%</small>
                            </td>
                            <td class="text-right text-success-custom"><?= rupiah($r['total_nilai']) ?></td>
                            <td class="text-right text-danger-custom"><?= rupiah($r['total_pengeluaran']) ?></td>
                            <td class="text-right <?= $r['estimasi_keuntungan'] >= 0 ? 'text-success-custom' : 'text-danger-custom' ?>">
                                <strong><?= rupiah($r['estimasi_keuntungan']) ?></strong>
                            </td>
                            <td class="text-center"><?= $r['jumlah_panen'] ?>x</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="4" class="text-right">TOTAL</td>
                        <td class="text-right text-primary-custom"><?= angka($gtPanen, 2) ?></td>
                        <td></td>
                        <td class="text-right text-success-custom"><?= rupiah($gtNilai) ?></td>
                        <td class="text-right text-danger-custom"><?= rupiah($gtPeng) ?></td>
                        <td class="text-right <?= $gtUntung >= 0 ? 'text-success-custom' : 'text-danger-custom' ?>"><?= rupiah($gtUntung) ?></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
