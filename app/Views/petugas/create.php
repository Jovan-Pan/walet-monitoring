<div class="card">
    <div class="card-header">
        <div><i class="fas fa-user-plus text-primary-custom"></i> Tambah Petugas</div>
        <a href="/petugas" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/petugas/store" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">NIP <span class="required">*</span></label>
                        <input type="text" name="nip" class="form-control" value="<?= old('nip') ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="<?= old('tempat_lahir') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk <span class="required">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="<?= old('tanggal_masuk', date('Y-m-d')) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"><?= old('alamat') ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" <?= old('status') === 'aktif' || ! old('status') ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= old('status') === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Penugasan Rumah Walet</label>
                <select name="rumah_walet_id" class="form-control select2">
                    <option value="">-- Tidak ditugaskan --</option>
                    <?php foreach ($rumahList as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= old('rumah_walet_id') == $r['id'] ? 'selected' : '' ?>>
                            <?= esc($r['kode']) ?> - <?= esc($r['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Petugas akan otomatis ditugaskan ke rumah walet yang dipilih mulai hari ini.</small>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="/petugas" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
