<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Tahun:</label>
            <select name="tahun" class="form-control auto-submit mr-2">
                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                    <option value="<?= $y ?>" <?= (int) $tahun === $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-sync"></i> Refresh</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div><i class="fas fa-chart-line text-primary-custom"></i> Monitoring Produktivitas Rumah Walet Tahun <?= esc($tahun) ?></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Kode</th>
                        <th>Nama Rumah Walet</th>
                        <th>Kapasitas/Bln</th>
                        <th>Total Panen (kg)</th>
                        <th>% Kapasitas Tahun</th>
                        <th>Total Nilai</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i> Tidak ada data
                        </td></tr>
                    <?php else: $no = 1; foreach ($data as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($r['kode']) ?></strong></td>
                            <td>
                                <a href="/rumah-walet/detail/<?= $r['id'] ?>"><?= esc($r['nama']) ?></a>
                            </td>
                            <td><?= $r['kapasitas_panen_kg'] ? angka($r['kapasitas_panen_kg'], 2) . ' kg' : '-' ?></td>
                            <td><strong class="text-primary-custom"><?= angka($r['total_panen'], 2) ?> kg</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 mr-2" style="height:8px;min-width:80px;">
                                        <div class="progress-bar <?= $r['persentase_kapasitas'] >= 80 ? 'success' : ($r['persentase_kapasitas'] >= 50 ? '' : 'warning') ?>" 
                                             style="width: <?= min(100, $r['persentase_kapasitas']) ?>%"></div>
                                    </div>
                                    <small><?= round($r['persentase_kapasitas'], 1) ?>%</small>
                                </div>
                            </td>
                            <td><strong class="text-success-custom"><?= rupiah($r['total_nilai']) ?></strong></td>
                            <td><?= badge_status($r['kondisi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="4" class="text-right">TOTAL</td>
                        <td class="text-primary-custom"><?= angka(array_sum(array_column($data, 'total_panen')), 2) ?> kg</td>
                        <td></td>
                        <td class="text-success-custom"><?= rupiah(array_sum(array_column($data, 'total_nilai'))) ?></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
