<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="text-uppercase small">Stok Tersedia</div>
                <div class="h3 mb-0 mt-2"><?= angka(($rekapTersedia['A'] ?? 0) + ($rekapTersedia['B'] ?? 0) + ($rekapTersedia['C'] ?? 0), 2) ?> kg</div>
                <small>A: <?= angka($rekapTersedia['A'] ?? 0, 2) ?> | B: <?= angka($rekapTersedia['B'] ?? 0, 2) ?> | C: <?= angka($rekapTersedia['C'] ?? 0, 2) ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="text-uppercase small">Sudah Terjual</div>
                <div class="h3 mb-0 mt-2"><?= angka(($rekapTerjual['A'] ?? 0) + ($rekapTerjual['B'] ?? 0) + ($rekapTerjual['C'] ?? 0), 2) ?> kg</div>
                <small>A: <?= angka($rekapTerjual['A'] ?? 0, 2) ?> | B: <?= angka($rekapTerjual['B'] ?? 0, 2) ?> | C: <?= angka($rekapTerjual['C'] ?? 0, 2) ?></small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="text-uppercase small">Total Item Aktif</div>
                <div class="h3 mb-0 mt-2"><?= count($stokList) ?></div>
                <small>Item di gudang</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="text-uppercase small">Aksi</div>
                <a href="/stok/opname" class="btn btn-light btn-sm mt-2"><i class="fas fa-clipboard-list"></i> Stock Opname</a>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Status:</label>
            <select name="status_stok" class="form-control mr-2">
                <option value="tersedia" <?= ($filters['status_stok'] ?? '') === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="terjual" <?= ($filters['status_stok'] ?? '') === 'terjual' ? 'selected' : '' ?>>Terjual</option>
                <option value="pindah_gudang" <?= ($filters['status_stok'] ?? '') === 'pindah_gudang' ? 'selected' : '' ?>>Pindah Gudang</option>
                <option value="">Semua Status</option>
            </select>
            <label class="mr-2">Gudang:</label>
            <select name="lokasi_gudang" class="form-control mr-2">
                <option value="">Semua</option>
                <option value="gudang_rw" <?= ($filters['lokasi_gudang'] ?? '') === 'gudang_rw' ? 'selected' : '' ?>>Gudang RW</option>
                <option value="gudang_pusat" <?= ($filters['lokasi_gudang'] ?? '') === 'gudang_pusat' ? 'selected' : '' ?>>Gudang Pusat</option>
            </select>
            <label class="mr-2">Grade:</label>
            <select name="grade" class="form-control mr-2">
                <option value="">Semua</option>
                <option value="A" <?= ($filters['grade'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
                <option value="B" <?= ($filters['grade'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
                <option value="C" <?= ($filters['grade'] ?? '') === 'C' ? 'selected' : '' ?>>C</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-boxes text-primary-custom"></i> Daftar Stok Sarang Walet
    </div>
    <div class="card-body">
        <?php if (empty($stokList)): ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-box-open fa-3x mb-3"></i>
                <h5>Tidak ada stok</h5>
                <p>Stok otomatis terisi setiap kali hasil panen dicatat. Coba ubah filter.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>RW</th>
                            <th>Grade</th>
                            <th>Jenis</th>
                            <th class="text-right">Berat (kg)</th>
                            <th>Gudang</th>
                            <th>Status</th>
                            <th>Tanggal Keluar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stokList as $s): ?>
                            <tr>
                                <td><?= format_tanggal($s['tanggal_masuk']) ?></td>
                                <td><small><?= esc($s['rw_kode']) ?></small> <?= esc($s['rw_nama']) ?></td>
                                <td><?= badge_grade($s['grade']) ?></td>
                                <td><?= badge_jenis_panen($s['jenis_panen']) ?></td>
                                <td class="text-right"><strong><?= angka($s['berat_kg'], 3) ?></strong></td>
                                <td>
                                    <?php if ($s['lokasi_gudang'] === 'gudang_rw'): ?>
                                        <span class="badge badge-info">Gudang RW</span>
                                    <?php else: ?>
                                        <span class="badge badge-primary">Gudang Pusat</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= badge_status_stok($s['status_stok']) ?></td>
                                <td><?= ! empty($s['tanggal_keluar']) ? format_tanggal($s['tanggal_keluar']) : '-' ?></td>
                                <td>
                                    <?php if ($s['status_stok'] === 'tersedia'): ?>
                                        <a href="/stok/move/<?= $s['id'] ?>" class="btn btn-warning btn-sm" title="Pindah Gudang">
                                            <i class="fas fa-exchange-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
