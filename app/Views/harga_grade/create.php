<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Tambah Master Harga</h5>
    </div>
    <div class="card-body">
        <form method="post" action="/harga-grade/store">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grade *</label>
                        <select name="grade" class="form-control" required>
                            <option value="A">Grade A (Premium)</option>
                            <option value="B">Grade B (Baik)</option>
                            <option value="C">Grade C (Standar)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jenis Panen *</label>
                        <select name="jenis_panen" class="form-control" required>
                            <option value="urat">Urat (Maret-April)</option>
                            <option value="sarang_utuh">Sarang Utuh (Jul-Sep)</option>
                            <option value="kecil">Kecil (Nov-Des)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Periode *</label>
                        <input type="month" name="periode" class="form-control" value="<?= date('Y-m') ?>" required>
                        <small class="form-text text-muted">Format: YYYY-MM</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Minimum (Rp) *</label>
                        <input type="number" name="harga_min" class="form-control" required step="1000" min="0" placeholder="Misal: 13000000">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Default (Rp) *</label>
                        <input type="number" name="harga_default" class="form-control" required step="1000" min="0" placeholder="Misal: 15000000">
                        <small class="form-text text-muted">Pre-fill otomatis di form hasil panen</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Maksimum (Rp) *</label>
                        <input type="number" name="harga_max" class="form-control" required step="1000" min="0" placeholder="Misal: 18000000">
                    </div>
                </div>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> Harga dalam Rupiah per kilogram (Rp/kg).
            </div>

            <div class="text-right">
                <a href="/harga-grade" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
