<div class="card">
    <div class="card-header">
        <div><i class="fas fa-money-bill-wave text-primary-custom"></i> Edit Pengeluaran</div>
        <a href="/pengeluaran" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        <form action="/pengeluaran/update/<?= $pengeluaran['id'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="<?= old('tanggal', $pengeluaran['tanggal']) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori_list as $k => $v): ?>
                                <option value="<?= $k ?>" <?= old('kategori', $pengeluaran['kategori']) === $k ? 'selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Rumah Walet (Opsional)</label>
                        <select name="rumah_walet_id" class="form-control select2">
                            <option value="">-- Umum / Tidak terkait --</option>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= old('rumah_walet_id', $pengeluaran['rumah_walet_id']) == $r['id'] ? 'selected' : '' ?>>
                                    <?= esc($r['kode']) ?> - <?= esc($r['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan <span class="required">*</span></label>
                <input type="text" name="keterangan" class="form-control" value="<?= old('keterangan', $pengeluaran['keterangan']) ?>" required>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Jumlah (Rp) <span class="required">*</span></label>
                        <input type="text" name="jumlah" class="form-control format-rupiah" value="<?= old('jumlah', $pengeluaran['jumlah']) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Bukti / Nota</label>
                        <input type="file" name="bukti" class="form-control" accept="image/*,application/pdf">
                        <?php if (! empty($pengeluaran['bukti'])): ?>
                            <small class="form-text text-muted">
                                Bukti saat ini: <a href="/uploads/<?= esc($pengeluaran['bukti']) ?>" target="_blank">Lihat</a>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                <a href="/pengeluaran" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
