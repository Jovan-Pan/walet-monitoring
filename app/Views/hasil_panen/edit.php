<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Hasil Panen</h5>
    </div>
    <div class="card-body">
        <form method="post" action="/hasil-panen/update/<?= $panen['id'] ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Rumah Walet *</label>
                        <select name="rumah_walet_id" class="form-control" required>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= $panen['rumah_walet_id'] == $r['id'] ? 'selected' : '' ?>><?= esc($r['kode']) ?> - <?= esc($r['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Panen *</label>
                        <input type="date" name="tanggal_panen" class="form-control" value="<?= esc($panen['tanggal_panen']) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Petugas *</label>
                        <select name="petugas_id" class="form-control" required>
                            <?php foreach ($petugasList as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $panen['petugas_id'] == $p['id'] ? 'selected' : '' ?>><?= esc($p['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Grade *</label>
                        <select name="grade" class="form-control" required>
                            <option value="A" <?= $panen['grade'] === 'A' ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= $panen['grade'] === 'B' ? 'selected' : '' ?>>B</option>
                            <option value="C" <?= $panen['grade'] === 'C' ? 'selected' : '' ?>>C</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jenis Panen *</label>
                        <select name="jenis_panen" class="form-control" required>
                            <?php foreach ($jenis_panen_list as $k => $v): ?>
                                <option value="<?= $k ?>" <?= $panen['jenis_panen'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status Pengeringan</label>
                        <select name="status_pengeringan" class="form-control">
                            <?php foreach (['basah', 'proses', 'kering'] as $s): ?>
                                <option value="<?= $s ?>" <?= $panen['status_pengeringan'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>No. Batch</label>
                        <input type="text" name="no_batch" class="form-control" value="<?= esc($panen['no_batch']) ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat (kg) *</label>
                        <input type="number" name="berat_kg" class="form-control" step="0.001" min="0" value="<?= esc($panen['berat_kg']) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat Basah (kg)</label>
                        <input type="number" name="berat_basah_kg" class="form-control" step="0.001" min="0" value="<?= esc($panen['berat_basah_kg']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat Kering (kg)</label>
                        <input type="number" name="berat_kering_kg" class="form-control" step="0.001" min="0" value="<?= esc($panen['berat_kering_kg']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Harga/kg (Rp) *</label>
                        <input type="number" name="harga_per_kg" class="form-control" step="1000" min="0" value="<?= esc($panen['harga_per_kg']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kadar Air (%)</label>
                        <input type="number" name="kadar_air_pct" class="form-control" step="0.01" min="0" max="100" value="<?= esc($panen['kadar_air_pct']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kadar Kotoran (%)</label>
                        <input type="number" name="kadar_kotoran_pct" class="form-control" step="0.01" min="0" max="100" value="<?= esc($panen['kadar_kotoran_pct']) ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kualitas</label>
                        <input type="text" name="kualitas" class="form-control" value="<?= esc($panen['kualitas']) ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"><?= esc($panen['catatan']) ?></textarea>
            </div>

            <div class="text-right">
                <a href="/hasil-panen" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
