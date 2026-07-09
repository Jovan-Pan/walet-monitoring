<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">RW:</label>
            <select name="rumah_walet_id" class="form-control mr-2">
                <option value="">Semua</option>
                <?php foreach ($rumahList as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= ($filters['rumah_walet_id'] ?? '') == $r['id'] ? 'selected' : '' ?>><?= esc($r['kode']) ?> - <?= esc($r['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <label class="mr-2">Grade:</label>
            <select name="grade" class="form-control mr-2">
                <option value="">Semua</option>
                <option value="A" <?= ($filters['grade'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
                <option value="B" <?= ($filters['grade'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
                <option value="C" <?= ($filters['grade'] ?? '') === 'C' ? 'selected' : '' ?>>C</option>
            </select>
            <label class="mr-2">Jenis:</label>
            <select name="jenis_panen" class="form-control mr-2">
                <option value="">Semua</option>
                <?php foreach ($jenis_panen_list as $k => $v): ?>
                    <option value="<?= $k ?>" <?= ($filters['jenis_panen'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <label class="mr-2">Dari:</label>
            <input type="date" name="dari" class="form-control mr-2" value="<?= esc($filters['dari'] ?? '') ?>">
            <label class="mr-2">Sampai:</label>
            <input type="date" name="sampai" class="form-control mr-2" value="<?= esc($filters['sampai'] ?? '') ?>">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
            <a href="/hasil-panen/batch" class="btn btn-success btn-sm ml-2"><i class="fas fa-layer-group"></i> Batch Input (3 Grade)</a>
            <a href="/hasil-panen/create" class="btn btn-primary btn-sm ml-2"><i class="fas fa-plus"></i> Input Single</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-balance-scale text-primary-custom"></i> Daftar Hasil Panen
        <small class="text-muted ml-2">(Total <?= $total ?> data, halaman <?= $currentPage ?> dari <?= $totalPages ?>)</small>
    </div>
    <div class="card-body">
        <?php if (empty($hasilPanen)): ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>Belum ada data hasil panen</h5>
                <p>Gunakan Batch Input untuk input 3 grade sekaligus, atau Input Single untuk 1 grade.</p>
                <a href="/hasil-panen/batch" class="btn btn-success"><i class="fas fa-layer-group"></i> Batch Input</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>RW</th>
                            <th>Grade</th>
                            <th>Jenis</th>
                            <th class="text-right">Berat (kg)</th>
                            <th class="text-right">Harga/kg</th>
                            <th class="text-right">Total Nilai</th>
                            <th>Status Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasilPanen as $p): ?>
                            <tr>
                                <td><?= format_tanggal($p['tanggal_panen'], 'd/m/Y') ?></td>
                                <td><small><?= esc($p['rw_kode']) ?></small> <?= esc($p['rw_nama']) ?></td>
                                <td><?= badge_grade($p['grade']) ?></td>
                                <td><?= badge_jenis_panen($p['jenis_panen']) ?></td>
                                <td class="text-right"><strong><?= angka($p['berat_kg'], 3) ?></strong></td>
                                <td class="text-right"><?= rupiah($p['harga_per_kg']) ?></td>
                                <td class="text-right text-success-custom"><?= rupiah($p['total_nilai']) ?></td>
                                <td><?= badge_status_stok($p['status_stok']) ?></td>
                                <td class="text-center">
                                    <a href="/hasil-panen/view/<?= $p['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="/hasil-panen/edit/<?= $p['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="/hasil-panen/delete/<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus hasil panen ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php
                    $queryParams = $filters;
                    for ($i = 1; $i <= $totalPages; $i++):
                        $queryParams['page'] = $i;
                        $queryString = http_build_query(array_filter($queryParams));
                    ?>
                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= $queryString ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
