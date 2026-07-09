<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-eye"></i> Detail Hasil Panen</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="30%">Tanggal Panen</th><td><?= format_tanggal($panen['tanggal_panen'], 'd F Y') ?></td></tr>
                    <tr><th>Rumah Walet</th><td><small><?= esc($panen['rw_kode']) ?></small> <?= esc($panen['rw_nama']) ?></td></tr>
                    <tr><th>Petugas</th><td><?= esc($panen['petugas_nama']) ?></td></tr>
                    <tr>
                        <th>Grade</th>
                        <td><?= badge_grade($panen['grade']) ?> <?= badge_jenis_panen($panen['jenis_panen']) ?></td>
                    </tr>
                    <tr><th>Berat (kg)</th><td><strong><?= angka($panen['berat_kg'], 3) ?> kg</strong></td></tr>
                    <?php if (! empty($panen['berat_basah_kg'])): ?>
                        <tr><th>Berat Basah (kg)</th><td><?= angka($panen['berat_basah_kg'], 3) ?> kg</td></tr>
                    <?php endif; ?>
                    <?php if (! empty($panen['berat_kering_kg'])): ?>
                        <tr><th>Berat Kering (kg)</th><td><?= angka($panen['berat_kering_kg'], 3) ?> kg</td></tr>
                    <?php endif; ?>
                    <tr><th>Harga/kg</th><td><?= rupiah($panen['harga_per_kg']) ?></td></tr>
                    <tr><th>Total Nilai</th><td><strong class="text-success"><?= rupiah($panen['total_nilai']) ?></strong></td></tr>
                    <tr>
                        <th>Status Pengeringan</th>
                        <td><span class="badge badge-info"><?= ucfirst($panen['status_pengeringan']) ?></span></td>
                    </tr>
                    <tr>
                        <th>Status Stok</th>
                        <td><?= badge_status_stok($panen['status_stok']) ?></td>
                    </tr>
                    <?php if (! empty($panen['no_batch'])): ?>
                        <tr><th>No. Batch</th><td><code><?= esc($panen['no_batch']) ?></code></td></tr>
                    <?php endif; ?>
                    <?php if (! empty($panen['kadar_air_pct'])): ?>
                        <tr><th>Kadar Air</th><td><?= angka($panen['kadar_air_pct'], 2) ?>%</td></tr>
                    <?php endif; ?>
                    <?php if (! empty($panen['kadar_kotoran_pct'])): ?>
                        <tr><th>Kadar Kotoran</th><td><?= angka($panen['kadar_kotoran_pct'], 2) ?>%</td></tr>
                    <?php endif; ?>
                    <?php if (! empty($panen['kualitas'])): ?>
                        <tr><th>Kualitas</th><td><?= esc($panen['kualitas']) ?></td></tr>
                    <?php endif; ?>
                    <?php if (! empty($panen['catatan'])): ?>
                        <tr><th>Catatan</th><td><?= esc($panen['catatan']) ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-cog"></i> Aksi</div>
            <div class="card-body">
                <a href="/hasil-panen/edit/<?= $panen['id'] ?>" class="btn btn-warning btn-block mb-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="/hasil-panen" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
