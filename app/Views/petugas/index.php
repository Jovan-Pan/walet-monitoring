<div class="card">
    <div class="card-header">
        <div><i class="fas fa-user-hard-hat text-primary-custom"></i> Daftar Petugas</div>
        <a href="/petugas/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Petugas</a>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari NIP, nama, atau no HP..." value="<?= esc($q ?? '') ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                    <?php if (! empty($q)): ?>
                        <a href="/petugas" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>No. HP</th>
                        <th>Tanggal Masuk</th>
                        <th>Akun User</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($petugas)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-user-slash fa-2x d-block mb-2"></i> Belum ada data petugas
                        </td></tr>
                    <?php else: $no = 1; foreach ($petugas as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($p['nip']) ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar mr-2" style="width:32px;height:32px;font-size:12px;background:<?= $p['jenis_kelamin'] === 'L' ? 'var(--info)' : 'var(--accent)' ?>;">
                                        <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                                    </div>
                                    <?= esc($p['nama']) ?>
                                </div>
                            </td>
                            <td><?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= esc($p['no_hp'] ?? '-') ?></td>
                            <td><?= format_tanggal($p['tanggal_masuk'], 'd/m/Y') ?></td>
                            <td><?= ! empty($p['username']) ? '<span class="text-success"><i class="fas fa-link"></i> ' . esc($p['username']) . '</span>' : '<span class="text-muted">Tidak ada</span>' ?></td>
                            <td><?= badge_status($p['status']) ?></td>
                            <td>
                                <a href="/petugas/edit/<?= $p['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/petugas/delete/<?= $p['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
