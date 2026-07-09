<div class="card">
    <div class="card-header">
        <div><i class="fas fa-clipboard-check text-primary-custom"></i> Edit Inspeksi</div>
        <a href="/inspeksi" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/inspeksi/update/<?= $inspeksi['id'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Rumah Walet <span class="required">*</span></label>
                        <select name="rumah_walet_id" class="form-control select2" required>
                            <option value="">-- Pilih Rumah Walet --</option>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= old('rumah_walet_id', $inspeksi['rumah_walet_id']) == $r['id'] ? 'selected' : '' ?>>
                                    <?= esc($r['kode']) ?> - <?= esc($r['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Petugas <span class="required">*</span></label>
                        <select name="petugas_id" class="form-control select2" required>
                            <option value="">-- Pilih Petugas --</option>
                            <?php foreach ($petugasList as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= old('petugas_id', $inspeksi['petugas_id']) == $p['id'] ? 'selected' : '' ?>>
                                    <?= esc($p['nip']) ?> - <?= esc($p['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal Inspeksi <span class="required">*</span></label>
                        <input type="date" name="tanggal_inspeksi" class="form-control" value="<?= old('tanggal_inspeksi', $inspeksi['tanggal_inspeksi']) ?>" required>
                    </div>
                </div>
            </div>

            <h6 class="mt-3 mb-2"><i class="fas fa-clipboard-list text-primary-custom"></i> Kondisi Inspeksi</h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Kondisi Bangunan <span class="required">*</span></label>
                        <select name="kondisi_bangunan" class="form-control" required>
                            <option value="baik" <?= old('kondisi_bangunan', $inspeksi['kondisi_bangunan']) === 'baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="sedang" <?= old('kondisi_bangunan', $inspeksi['kondisi_bangunan']) === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                            <option value="buruk" <?= old('kondisi_bangunan', $inspeksi['kondisi_bangunan']) === 'buruk' ? 'selected' : '' ?>>Buruk</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Kondisi Sarang <span class="required">*</span></label>
                        <select name="kondisi_sarang" class="form-control" required>
                            <option value="baik" <?= old('kondisi_sarang', $inspeksi['kondisi_sarang']) === 'baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="sedang" <?= old('kondisi_sarang', $inspeksi['kondisi_sarang']) === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                            <option value="buruk" <?= old('kondisi_sarang', $inspeksi['kondisi_sarang']) === 'buruk' ? 'selected' : '' ?>>Buruk</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Kebersihan <span class="required">*</span></label>
                        <select name="kebersihan" class="form-control" required>
                            <option value="baik" <?= old('kebersihan', $inspeksi['kebersihan']) === 'baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="sedang" <?= old('kebersihan', $inspeksi['kebersihan']) === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                            <option value="buruk" <?= old('kebersihan', $inspeksi['kebersihan']) === 'buruk' ? 'selected' : '' ?>>Buruk</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Populasi Walet (ekor)</label>
                        <input type="number" name="populasi_walet" class="form-control" value="<?= old('populasi_walet', $inspeksi['populasi_walet']) ?>" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Suhu (°C)</label>
                        <input type="number" step="0.01" name="suhu" class="form-control" value="<?= old('suhu', $inspeksi['suhu']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Kelembaban (%)</label>
                        <input type="number" step="0.01" name="kelembaban" class="form-control" value="<?= old('kelembaban', $inspeksi['kelembaban']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Hama Ditemukan</label>
                        <input type="text" name="hama" class="form-control" value="<?= old('hama', $inspeksi['hama']) ?>" placeholder="mis: semut, tikus, kecoak">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3"><?= old('catatan', $inspeksi['catatan']) ?></textarea>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="/inspeksi" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
