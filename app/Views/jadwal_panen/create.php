<div class="card">
    <div class="card-header">
        <div><i class="fas fa-calendar-plus text-primary-custom"></i> Tambah Jadwal Panen</div>
        <a href="/jadwal-panen" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/jadwal-panen/store" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Rumah Walet <span class="required">*</span></label>
                        <select name="rumah_walet_id" class="form-control select2" required>
                            <option value="">-- Pilih Rumah Walet --</option>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= old('rumah_walet_id') == $r['id'] ? 'selected' : '' ?>>
                                    <?= esc($r['kode']) ?> - <?= esc($r['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Petugas Pelaksana</label>
                        <select name="petugas_id" class="form-control select2">
                            <option value="">-- Belum ditentukan --</option>
                            <?php foreach ($petugasList as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= old('petugas_id') == $p['id'] ? 'selected' : '' ?>>
                                    <?= esc($p['nip']) ?> - <?= esc($p['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal Rencana Panen <span class="required">*</span></label>
                        <input type="date" name="tanggal_rencana" class="form-control" value="<?= old('tanggal_rencana', date('Y-m-d')) ?>" min="<?= esc($tanggal_min ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Estimasi Hasil (kg)</label>
                        <input type="number" step="0.01" name="estimasi_hasil_kg" class="form-control" value="<?= old('estimasi_hasil_kg') ?>" placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="terjadwal" <?= old('status') === 'terjadwal' || ! old('status') ? 'selected' : '' ?>>Terjadwal</option>
                            <option value="selesai" <?= old('status') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="ditunda" <?= old('status') === 'ditunda' ? 'selected' : '' ?>>Ditunda</option>
                            <option value="batal" <?= old('status') === 'batal' ? 'selected' : '' ?>>Batal</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal Aktual Panen</label>
                        <input type="date" name="tanggal_aktual" class="form-control" value="<?= old('tanggal_aktual') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Periode (Auto)</label>
                        <input type="text" class="form-control" id="periodePreview" readonly>
                        <small class="form-text text-muted">Berdasarkan tanggal rencana</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3"><?= old('catatan') ?></textarea>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Jadwal</button>
                <a href="/jadwal-panen" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
$('input[name="tanggal_rencana"]').on('change', function () {
    var d = new Date($(this).val());
    if (! isNaN(d.getTime())) {
        var y = d.getFullYear();
        var m = ('0' + (d.getMonth() + 1)).slice(-2);
        $('#periodePreview').val(y + '-' + m);
    }
}).trigger('change');
</script>
