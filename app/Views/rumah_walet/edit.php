<div class="card">
    <div class="card-header">
        <div><i class="fas fa-warehouse text-primary-custom"></i> Edit Rumah Walet: <?= esc($rumah['kode']) ?></div>
        <a href="/rumah-walet" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/rumah-walet/update/<?= $rumah['id'] ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Kode Rumah</label>
                        <input type="text" class="form-control" value="<?= esc($rumah['kode']) ?>" readonly>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="form-label">Nama Rumah Walet <span class="required">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $rumah['nama']) ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi / Alamat</label>
                <textarea name="lokasi" class="form-control" rows="2"><?= old('lokasi', $rumah['lokasi']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Latitude</label>
                        <input type="text" name="latitude" class="form-control" value="<?= old('latitude', $rumah['latitude']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Longitude</label>
                        <input type="text" name="longitude" class="form-control" value="<?= old('longitude', $rumah['longitude']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Luas (m²)</label>
                        <input type="number" step="0.01" name="luas" class="form-control" value="<?= old('luas', $rumah['luas']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Jumlah Lantai</label>
                        <input type="number" name="jumlah_lantai" class="form-control" value="<?= old('jumlah_lantai', $rumah['jumlah_lantai']) ?>" min="1">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tahun Dibangun</label>
                        <input type="number" name="tahun_dibangun" class="form-control" value="<?= old('tahun_dibangun', $rumah['tahun_dibangun']) ?>" min="1990" max="<?= date('Y') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Berdiri</label>
                        <input type="date" name="tanggal_berdiri" class="form-control" value="<?= old('tanggal_berdiri', $rumah['tanggal_berdiri']) ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Kapasitas Panen/Bln (kg)</label>
                        <input type="number" step="0.01" name="kapasitas_panen_kg" class="form-control" value="<?= old('kapasitas_panen_kg', $rumah['kapasitas_panen_kg']) ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Kondisi <span class="required">*</span></label>
                        <select name="kondisi" class="form-control" required>
                            <option value="baik" <?= old('kondisi', $rumah['kondisi']) === 'baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="sedang" <?= old('kondisi', $rumah['kondisi']) === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                            <option value="buruk" <?= old('kondisi', $rumah['kondisi']) === 'buruk' ? 'selected' : '' ?>>Buruk</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" <?= old('status', $rumah['status']) === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= old('status', $rumah['status']) === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"><?= old('keterangan', $rumah['keterangan']) ?></textarea>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="/rumah-walet" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
