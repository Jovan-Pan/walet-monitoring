<div class="card">
    <div class="card-header">
        <div><i class="fas fa-heartbeat text-primary-custom"></i> Monitoring Kondisi Rumah Walet</div>
    </div>
    <div class="card-body">
        <div class="kpi-grid mb-3">
            <div class="kpi-card success">
                <div>
                    <div class="kpi-label">Kondisi Baik</div>
                    <div class="kpi-value text-success-custom">
                        <?= count(array_filter($data, fn($r) => ($r['inspeksi_terakhir_status'] ?? $r['kondisi']) === 'baik')) ?>
                    </div>
                </div>
                <div class="kpi-icon success"><i class="fas fa-thumbs-up"></i></div>
            </div>
            <div class="kpi-card warning">
                <div>
                    <div class="kpi-label">Kondisi Sedang</div>
                    <div class="kpi-value text-warning-custom">
                        <?= count(array_filter($data, fn($r) => ($r['inspeksi_terakhir_status'] ?? $r['kondisi']) === 'sedang')) ?>
                    </div>
                </div>
                <div class="kpi-icon warning"><i class="fas fa-exclamation"></i></div>
            </div>
            <div class="kpi-card danger">
                <div>
                    <div class="kpi-label">Kondisi Buruk</div>
                    <div class="kpi-value text-danger-custom">
                        <?= count(array_filter($data, fn($r) => ($r['inspeksi_terakhir_status'] ?? $r['kondisi']) === 'buruk')) ?>
                    </div>
                </div>
                <div class="kpi-icon danger"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Total Rumah Aktif</div>
                    <div class="kpi-value text-primary-custom"><?= count($data) ?></div>
                </div>
                <div class="kpi-icon"><i class="fas fa-warehouse"></i></div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Inspeksi Terakhir</th>
                        <th>Kondisi Bangunan</th>
                        <th>Kondisi Sarang</th>
                        <th>Populasi</th>
                        <th>Suhu (°C)</th>
                        <th>Kelembaban (%)</th>
                        <th>Status</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="11" class="text-center text-muted py-4">
                            <i class="fas fa-warehouse fa-2x d-block mb-2"></i> Belum ada data rumah walet
                        </td></tr>
                    <?php else: $no = 1; foreach ($data as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($r['kode']) ?></strong></td>
                            <td><a href="/rumah-walet/detail/<?= $r['id'] ?>"><?= esc($r['nama']) ?></a></td>
                            <td>
                                <?php if (! empty($r['inspeksi_terakhir_tanggal'])): ?>
                                    <?= format_tanggal($r['inspeksi_terakhir_tanggal'], 'd/m/Y') ?>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada inspeksi</span>
                                <?php endif; ?>
                            </td>
                            <td><?= ! empty($r['kondisi_bangunan_terakhir']) ? badge_status($r['kondisi_bangunan_terakhir']) : badge_status($r['kondisi']) ?></td>
                            <td><?= ! empty($r['kondisi_sarang_terakhir']) ? badge_status($r['kondisi_sarang_terakhir']) : '-' ?></td>
                            <td><?= ! empty($r['populasi_terakhir']) ? number_format($r['populasi_terakhir']) : '-' ?></td>
                            <td><?= $r['suhu_terakhir'] ? angka($r['suhu_terakhir'], 1) : '-' ?></td>
                            <td><?= $r['kelembaban_terakhir'] ? angka($r['kelembaban_terakhir'], 1) : '-' ?></td>
                            <td><?= ! empty($r['inspeksi_terakhir_status']) ? badge_status($r['inspeksi_terakhir_status']) : badge_status($r['kondisi']) ?></td>
                            <td><?= esc($r['petugas_nama'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
