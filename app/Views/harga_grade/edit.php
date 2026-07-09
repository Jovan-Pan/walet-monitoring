<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Master Harga</h5>
    </div>
    <div class="card-body">
        <form method="post" action="/harga-grade/update/<?= $harga['id'] ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Grade / Jenis / Periode</label>
                <input type="text" class="form-control" value="<?= $harga['grade'] ?> / <?= $harga['jenis_panen'] ?> / <?= $harga['periode'] ?>" readonly>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Minimum (Rp) *</label>
                        <input type="number" name="harga_min" class="form-control" value="<?= esc($harga['harga_min']) ?>" required step="1000" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Default (Rp) *</label>
                        <input type="number" name="harga_default" class="form-control" value="<?= esc($harga['harga_default']) ?>" required step="1000" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Harga Maksimum (Rp) *</label>
                        <input type="number" name="harga_max" class="form-control" value="<?= esc($harga['harga_max']) ?>" required step="1000" min="0">
                    </div>
                </div>
            </div>

            <div class="text-right">
                <a href="/harga-grade" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
