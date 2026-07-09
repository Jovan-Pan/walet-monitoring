<div class="card">
    <div class="card-header">
        <div><i class="fas fa-user-edit text-primary-custom"></i> Edit Petugas: <?= esc($petugas['nama']) ?></div>
        <a href="/petugas" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/petugas/update/<?= $petugas['id'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">NIP <span class="required">*</span></label>
                        <input type="text" name="nip" class="form-control" value="<?= old('nip', $petugas['nip']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $petugas['nama']) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="L" <?= old('jenis_kelamin', $petugas['jenis_kelamin']) === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= old('jenis_kelamin', $petugas['jenis_kelamin']) === 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="<?= old('tempat_lahir', $petugas['tempat_lahir']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir', $petugas['tanggal_lahir']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk <span class="required">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="<?= old('tanggal_masuk', $petugas['tanggal_masuk']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"><?= old('alamat', $petugas['alamat']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp', $petugas['no_hp']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $petugas['email']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" <?= old('status', $petugas['status']) === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= old('status', $petugas['status']) === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <?php if (! empty($penugasan)): ?>
            <div class="form-group">
                <label class="form-label">Penugasan Aktif Saat Ini</label>
                <div>
                    <?php foreach ($penugasan as $p): ?>
                        <span class="badge badge-info mr-2 mb-1" style="padding:6px 12px;font-size:13px;">
                            <?= esc($p['kode']) ?> - <?= esc($p['nama_rumah']) ?> (sejak <?= format_tanggal($p['tanggal_mulai'], 'd/m/Y') ?>)
                        </span>
                    <?php endforeach; ?>
                </div>
                <small class="form-text text-muted">Untuk mengubah penugasan, gunakan modul Rumah Walet.</small>
            </div>
            <?php endif; ?>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="/petugas" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
