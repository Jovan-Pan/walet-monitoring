<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-tags text-primary-custom"></i> Master Harga per Grade</h5>
        <a href="/harga-grade/create" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah Harga</a>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Master harga dipakai untuk validasi range harga saat input hasil panen. Operator hanya bisa input harga di antara <strong>harga_min</strong> dan <strong>harga_max</strong>. <strong>harga_default</strong> otomatis pre-fill di form.
        </div>

        <?php if (empty($grouped)): ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-tags fa-3x mb-3"></i>
                <h5>Belum ada master harga</h5>
                <a href="/harga-grade/create" class="btn btn-success"><i class="fas fa-plus"></i> Buat Master Harga Pertama</a>
            </div>
        <?php else: ?>
            <?php foreach ($grouped as $periode => $items): ?>
                <div class="mb-3">
                    <h6 class="text-muted">Periode: <?= esc($periode) ?></h6>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Grade</th>
                                <th>Jenis Panen</th>
                                <th class="text-right">Harga Min</th>
                                <th class="text-right">Harga Default</th>
                                <th class="text-right">Harga Max</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $h): ?>
                                <tr>
                                    <td><?= badge_grade($h['grade']) ?></td>
                                    <td><?= badge_jenis_panen($h['jenis_panen']) ?></td>
                                    <td class="text-right"><?= rupiah($h['harga_min']) ?></td>
                                    <td class="text-right text-success-custom"><strong><?= rupiah($h['harga_default']) ?></strong></td>
                                    <td class="text-right"><?= rupiah($h['harga_max']) ?></td>
                                    <td class="text-center">
                                        <a href="/harga-grade/edit/<?= $h['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="/harga-grade/delete/<?= $h['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus master harga ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
