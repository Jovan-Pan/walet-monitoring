<div class="card">
    <div class="card-header">
        <div><i class="fas fa-users-cog text-primary-custom"></i> Daftar User</div>
        <a href="/user/create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari nama, username, atau email..." value="<?= esc($q ?? '') ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                    <?php if (! empty($q)): ?>
                        <a href="/user" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i> Belum ada data user
                        </td></tr>
                    <?php else: $no = 1; foreach ($users as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar mr-2" style="width:32px;height:32px;font-size:12px;"><?= strtoupper(substr($u['nama'], 0, 1)) ?></div>
                                    <strong><?= esc($u['nama']) ?></strong>
                                </div>
                            </td>
                            <td><?= esc($u['username']) ?></td>
                            <td><?= esc($u['email'] ?? '-') ?></td>
                            <td><?= badge_role($u['role']) ?></td>
                            <td><?= esc($u['no_hp'] ?? '-') ?></td>
                            <td><?= badge_status($u['status']) ?></td>
                            <td>
                                <a href="/user/edit/<?= $u['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/user/delete/<?= $u['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-center"><?= $pager->links() ?></div>
        <?php endif; ?>
    </div>
</div>
