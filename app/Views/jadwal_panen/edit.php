<div class="card">
    <div class="card-header">
        <div><i class="fas fa-calendar-edit text-primary-custom"></i> Edit Jadwal Panen</div>
        <a href="/jadwal-panen" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/jadwal-panen/update/<?= $jadwal['id'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Rumah Walet <span class="required">*</span></label>
                        <select name="rumah_walet_id" class="form-control select2" required>
                            <option value="">-- Pilih Rumah Walet --</option>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= old('rumah_walet_id', $jadwal['rumah_walet_id']) == $r['id'] ? 'selected' : '' ?>>
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
                                <option value="<?= $p['id'] ?>" <?= old('petugas_id', $jadwal['petugas_id']) == $p['id'] ? 'selected' : '' ?>>
                                    <?= esc($p['nip']) ?> - <?= esc($p['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal Rencana <span class="required">*</span></label>
                        <input type="date" name="tanggal_rencana" class="form-control" value="<?= old('tanggal_rencana', $jadwal['tanggal_rencana']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Estimasi Hasil (kg)</label>
                        <input type="number" step="0.01" name="estimasi_hasil_kg" class="form-control" value="<?= old('estimasi_hasil_kg', $jadwal['estimasi_hasil_kg']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="terjadwal" <?= old('status', $jadwal['status']) === 'terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                            <option value="selesai" <?= old('status', $jadwal['status']) === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="ditunda" <?= old('status', $jadwal['status']) === 'ditunda' ? 'selected' : '' ?>>Ditunda</option>
                            <option value="batal" <?= old('status', $jadwal['status']) === 'batal' ? 'selected' : '' ?>>Batal</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal Aktual Panen</label>
                        <input type="date" name="tanggal_aktual" class="form-control" value="<?= old('tanggal_aktual', $jadwal['tanggal_aktual']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Periode</label>
                        <input type="text" class="form-control" value="<?= esc($jadwal['periode']) ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3"><?= old('catatan', $jadwal['catatan']) ?></textarea>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="/jadwal-panen" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
