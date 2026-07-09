<?php
$r = $rumah;
?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="kpi-icon mx-auto mb-3" style="width:80px;height:80px;font-size:36px;background:linear-gradient(135deg,var(--primary),var(--primary-light));">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h4 class="mb-1"><?= esc($r['kode']) ?></h4>
                <h5 class="text-muted mb-2"><?= esc($r['nama']) ?></h5>
                <div class="mb-2"><?= badge_status($r['kondisi']) ?> <?= badge_status($r['status']) ?></div>
                <p class="text-muted small"><i class="fas fa-map-marker-alt"></i> <?= esc($r['lokasi'] ?? '-') ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle text-primary-custom"></i> Informasi Umum</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">Luas</td><td class="text-right"><?= $r['luas'] ? angka($r['luas'], 2) . ' m²' : '-' ?></td></tr>
                    <tr><td class="text-muted">Jumlah Lantai</td><td class="text-right"><?= $r['jumlah_lantai'] ?> lantai</td></tr>
                    <tr><td class="text-muted">Tahun Dibangun</td><td class="text-right"><?= $r['tahun_dibangun'] ?? '-' ?></td></tr>
                    <tr><td class="text-muted">Tanggal Berdiri</td><td class="text-right"><?= format_tanggal($r['tanggal_berdiri']) ?></td></tr>
                    <tr><td class="text-muted">Kapasitas/Bln</td><td class="text-right"><?= $r['kapasitas_panen_kg'] ? angka($r['kapasitas_panen_kg'], 2) . ' kg' : '-' ?></td></tr>
                    <tr><td class="text-muted">Latitude</td><td class="text-right"><?= $r['latitude'] ?? '-' ?></td></tr>
                    <tr><td class="text-muted">Longitude</td><td class="text-right"><?= $r['longitude'] ?? '-' ?></td></tr>
                </table>
                <?php if (! empty($r['keterangan'])): ?>
                    <hr>
                    <div class="small">
                        <strong>Keterangan:</strong><br>
                        <?= esc($r['keterangan']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-chart-pie text-success-custom"></i> Statistik <?= date('Y') ?></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Panen</span>
                    <strong class="text-primary-custom"><?= angka($r['stat_panen_tahun'], 2) ?> kg</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Pengeluaran</span>
                    <strong class="text-danger-custom"><?= rupiah($r['stat_pengeluaran_tahun']) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Jumlah Inspeksi</span>
                    <strong><?= $r['stat_jumlah_inspeksi'] ?> kali</strong>
                </div>
                <hr>
                <div class="small">
                    <strong>Inspeksi Terakhir:</strong><br>
                    <?php if (! empty($r['stat_inspeksi_terakhir'])): ?>
                        <?= format_tanggal($r['stat_inspeksi_terakhir']['tanggal_inspeksi']) ?> - 
                        <?= badge_status($r['stat_inspeksi_terakhir']['status']) ?>
                    <?php else: ?>
                        <span class="text-muted">Belum ada inspeksi</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="text-center mb-3">
            <a href="/rumah-walet/edit/<?= $r['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
            <a href="/rumah-walet" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-balance-scale text-warning"></i> Riwayat Panen Terbaru</div>
            <div class="card-body">
                <?php if (empty($riwayatPanen)): ?>
                    <div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada riwayat panen</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Tanggal</th><th>Petugas</th><th>Grade</th><th>Berat (kg)</th><th class="text-right">Nilai</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatPanen as $p): ?>
                                    <tr>
                                        <td><?= format_tanggal($p['tanggal_panen'], 'd/m/Y') ?></td>
                                        <td><?= esc($p['petugas_nama']) ?></td>
                                        <td><?= badge_grade($p['grade']) ?></td>
                                        <td><?= angka($p['berat_kg'], 3) ?></td>
                                        <td class="text-right"><?= rupiah($p['berat_kg'] * $p['harga_per_kg']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-check text-info"></i> Riwayat Inspeksi Terbaru</div>
            <div class="card-body">
                <?php if (empty($riwayatInspeksi)): ?>
                    <div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada riwayat inspeksi</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Tanggal</th><th>Petugas</th><th>Bangunan</th><th>Sarang</th><th>Populasi</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatInspeksi as $i): ?>
                                    <tr>
                                        <td><?= format_tanggal($i['tanggal_inspeksi'], 'd/m/Y') ?></td>
                                        <td><?= esc($i['petugas_nama']) ?></td>
                                        <td><?= badge_status($i['kondisi_bangunan']) ?></td>
                                        <td><?= badge_status($i['kondisi_sarang']) ?></td>
                                        <td><?= number_format($i['populasi_walet']) ?> ekor</td>
                                        <td><?= badge_status($i['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-money-bill-wave text-danger-custom"></i> Riwayat Pengeluaran Terbaru</div>
            <div class="card-body">
                <?php if (empty($riwayatPengeluaran)): ?>
                    <div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada riwayat pengeluaran</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th class="text-right">Jumlah</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatPengeluaran as $peng): ?>
                                    <tr>
                                        <td><?= format_tanggal($peng['tanggal'], 'd/m/Y') ?></td>
                                        <td><?= kategori_label($peng['kategori']) ?></td>
                                        <td><?= esc($peng['keterangan']) ?></td>
                                        <td class="text-right"><?= rupiah($peng['jumlah']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
