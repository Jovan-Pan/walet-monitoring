<div class="card">
    <div class="card-header">
        <div><i class="fas fa-user-edit text-primary-custom"></i> Edit User: <?= esc($user['nama']) ?></div>
        <a href="/user" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/user/update/<?= $user['id'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $user['nama']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Username <span class="required">*</span></label>
                        <input type="text" name="username" class="form-control" value="<?= old('username', $user['username']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ubah">
                        <small class="form-text text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $user['email']) ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Role <span class="required">*</span></label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="petugas" <?= old('role', $user['role']) === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="owner" <?= old('role', $user['role']) === 'owner' ? 'selected' : '' ?>>Owner</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp', $user['no_hp']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" <?= old('status', $user['status']) === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= old('status', $user['status']) === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="/user" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
