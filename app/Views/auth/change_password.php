<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-key"></i> Ganti Password</h4>
            </div>
            <div class="card-body">
                <?php if (! empty($forced)): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Keamanan Pertama:</strong> Anda menggunakan password default. Demi keamanan akun & data bisnis, Anda <strong>wajib</strong> mengganti password sebelum bisa mengakses sistem.
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="/auth/change-password">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password Lama</label>
                        <input type="password" name="current" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-key"></i> Password Baru</label>
                        <input type="password" name="new" class="form-control" required minlength="8">
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Minimal 8 karakter, harus mengandung: huruf besar, huruf kecil, dan angka.
                        </small>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-check-double"></i> Konfirmasi Password Baru</label>
                        <input type="password" name="confirm" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
