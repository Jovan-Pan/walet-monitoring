<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-plus"></i> Input Hasil Panen (Single Grade)</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Untuk input 3 grade sekaligus (A+B+C) dalam 1 form, gunakan
            <a href="/hasil-panen/batch" class="alert-link"><strong>Batch Input</strong></a> yang lebih cepat.
        </div>

        <form method="post" action="/hasil-panen/store">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Rumah Walet *</label>
                        <select name="rumah_walet_id" class="form-control" required>
                            <option value="">- Pilih RW -</option>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= esc($r['kode']) ?> - <?= esc($r['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Panen *</label>
                        <input type="date" name="tanggal_panen" class="form-control" value="<?= esc($tanggalHariIni) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Petugas *</label>
                        <select name="petugas_id" class="form-control" required>
                            <option value="">- Pilih Petugas -</option>
                            <?php foreach ($petugasList as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (! empty($currentPetugas) && $currentPetugas['id'] == $p['id']) ? 'selected' : '' ?>><?= esc($p['nama']) ?> (<?= esc($p['nip']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Grade *</label>
                        <select name="grade" class="form-control" required id="gradeSelect">
                            <option value="A">Grade A (Premium)</option>
                            <option value="B">Grade B (Baik)</option>
                            <option value="C">Grade C (Standar)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jenis Panen *</label>
                        <select name="jenis_panen" class="form-control" required id="jenisPanenSelect">
                            <?php foreach ($jenis_panen_list as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status Pengeringan</label>
                        <select name="status_pengeringan" class="form-control">
                            <option value="basah">Basah</option>
                            <option value="proses">Proses Pengeringan</option>
                            <option value="kering">Kering</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>No. Batch</label>
                        <input type="text" name="no_batch" class="form-control" placeholder="Auto-generate jika kosong">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat (kg) *</label>
                        <input type="number" name="berat_kg" class="form-control" step="0.001" min="0" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat Basah (kg)</label>
                        <input type="number" name="berat_basah_kg" class="form-control" step="0.001" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Berat Kering (kg)</label>
                        <input type="number" name="berat_kering_kg" class="form-control" step="0.001" min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Harga/kg (Rp) *</label>
                        <input type="number" name="harga_per_kg" id="hargaInput" class="form-control" step="1000" min="0" required>
                        <small id="hargaHint" class="form-text text-muted"></small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kadar Air (%)</label>
                        <input type="number" name="kadar_air_pct" class="form-control" step="0.01" min="0" max="100">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kadar Kotoran (%)</label>
                        <input type="number" name="kadar_kotoran_pct" class="form-control" step="0.01" min="0" max="100">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kualitas</label>
                        <input type="text" name="kualitas" class="form-control" placeholder="Misal: Sarang utuh, kualitas premium">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="2"></textarea>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> Stok otomatis tercatat di modul Stok Sarang setelah disimpan.
            </div>

            <div class="text-right">
                <a href="/hasil-panen" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fetch harga when grade/jenis_panen changes
function fetchHarga() {
    var grade = $('#gradeSelect').val();
    var jenis = $('#jenisPanenSelect').val();
    var periode = new Date().toISOString().slice(0, 7);

    $.get('/api/harga-by-grade/' + grade + '/' + jenis + '?periode=' + periode, function(res) {
        if (res.status === 'ok' && res.data) {
            $('#hargaInput').val(res.data.harga_default);
            $('#hargaHint').html('Range: <strong>Rp ' + res.data.harga_min.toLocaleString('id-ID') + '</strong> - <strong>Rp ' + res.data.harga_max.toLocaleString('id-ID') + '</strong>');
        } else {
            $('#hargaHint').text('Master harga belum diset untuk kombinasi ini. Hubungi admin.');
        }
    }).fail(function() {
        $('#hargaHint').text('');
    });
}

$('#gradeSelect, #jenisPanenSelect').on('change', fetchHarga);
fetchHarga();
</script>
