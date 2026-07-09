<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-layer-group"></i> Batch Input Hasil Panen - 3 Grade Sekaligus</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Batch input:</strong> 1 form untuk 3 grade (A, B, C) sekaligus — memangkas 24 step menjadi 9 step per event panen. Backend memproses semua dalam 1 database transaction.
            <br>Kosongkan berat grade yang tidak ada panenannya.
        </div>

        <form method="post" action="/hasil-panen/batch-store">
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
                        <label>Jadwal Panen Terkait</label>
                        <select name="jadwal_panen_id" class="form-control">
                            <option value="">- Tanpa Jadwal -</option>
                            <?php if (! empty($jadwalTerjadwal)): foreach ($jadwalTerjadwal as $j): ?>
                                <option value="<?= $j['id'] ?>"><?= esc($j['tanggal_rencana']) ?> (estimasi <?= angka($j['estimasi_hasil_kg'], 2) ?> kg)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jenis Panen *</label>
                        <select name="jenis_panen" class="form-control" required id="jenisPanenSelect">
                            <?php foreach ($jenis_panen_list as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted" id="musimHint"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status Pengeringan</label>
                        <select name="status_pengeringan" class="form-control">
                            <option value="basah">Basah (baru panen)</option>
                            <option value="proses">Dalam Proses Pengeringan</option>
                            <option value="kering">Kering (siap jual)</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>
            <h6><i class="fas fa-trophy"></i> Input per Grade</h6>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="80">Grade</th>
                            <th>Berat (kg)</th>
                            <th>Berat Basah (kg)</th>
                            <th>Berat Kering (kg)</th>
                            <th>Kadar Air (%)</th>
                            <th>Kadar Kotoran (%)</th>
                            <th>Harga/kg (Rp)</th>
                            <th>Subtotal</th>
                            <th>Kualitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (['A', 'B', 'C'] as $g):
                            $hd = $harga_default[$g] ?? null;
                        ?>
                        <tr>
                            <td class="text-center"><strong><?= badge_grade($g) ?></strong></td>
                            <td>
                                <input type="number" name="berat_<?= $g ?>" class="form-control form-control-sm berat-input" data-grade="<?= $g ?>" step="0.001" min="0" placeholder="0.000">
                            </td>
                            <td>
                                <input type="number" name="berat_basah_<?= $g ?>" class="form-control form-control-sm" step="0.001" min="0" placeholder="0.000">
                            </td>
                            <td>
                                <input type="number" name="berat_kering_<?= $g ?>" class="form-control form-control-sm" step="0.001" min="0" placeholder="0.000">
                            </td>
                            <td>
                                <input type="number" name="kadar_air_<?= $g ?>" class="form-control form-control-sm" step="0.01" min="0" max="100" placeholder="0.00">
                            </td>
                            <td>
                                <input type="number" name="kadar_kotoran_<?= $g ?>" class="form-control form-control-sm" step="0.01" min="0" max="100" placeholder="0.00">
                            </td>
                            <td>
                                <input type="number" name="harga_<?= $g ?>" class="form-control form-control-sm harga-input" data-grade="<?= $g ?>"
                                       step="1000" min="0" placeholder="0"
                                       value="<?= $hd ? $hd['harga_default'] : '' ?>"
                                       data-min="<?= $hd ? $hd['harga_min'] : 0 ?>"
                                       data-max="<?= $hd ? $hd['harga_max'] : 0 ?>">
                                <?php if ($hd): ?>
                                    <small class="text-muted">Range: <?= rupiah($hd['harga_min']) ?> - <?= rupiah($hd['harga_max']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-right subtotal-cell" data-grade="<?= $g ?>">Rp 0</td>
                            <td>
                                <input type="text" name="kualitas_<?= $g ?>" class="form-control form-control-sm" placeholder="Kualitas grade <?= $g ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="7" class="text-right">TOTAL:</td>
                            <td class="text-right text-success-custom" id="grandTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-group">
                <label>Catatan Umum (untuk semua grade)</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Misal: Panen rutin bulanan, kondisi sarang prima..."></textarea>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i>
                Stok otomatis tercatat di modul Stok Sarang setelah disimpan. Kalau jadwal panen dipilih, statusnya akan otomatis jadi "selesai".
            </div>

            <div class="text-right">
                <a href="/hasil-panen" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Batch Panen</button>
            </div>
        </form>
    </div>
</div>

<script>
// Musim hint
const musimInfo = {
    'urat': 'Panen urat - Maret-April, sarang muda, harga premium',
    'sarang_utuh': 'Panen sarang utuh - Juli-Sep, volume terbesar',
    'kecil': 'Panen kecil - Nov-Des, sisa/campuran, kualitas lebih rendah'
};

function updateMusimHint() {
    var v = $('#jenisPanenSelect').val();
    $('#musimHint').text(musimInfo[v] || '');
}
$('#jenisPanenSelect').on('change', updateMusimHint);
updateMusimHint();

// Update subtotal on input
$('.berat-input, .harga-input').on('input', function() {
    var g = $(this).data('grade');
    var berat = parseFloat($('.berat-input[data-grade="' + g + '"]').val()) || 0;
    var harga = parseFloat($('.harga-input[data-grade="' + g + '"]').val()) || 0;
    var subtotal = berat * harga;
    $('.subtotal-cell[data-grade="' + g + '"]').text('Rp ' + subtotal.toLocaleString('id-ID'));
    updateGrand();
});

function updateGrand() {
    var total = 0;
    $('.subtotal-cell').each(function() {
        var v = $(this).text().replace(/[^\d]/g, '');
        total += parseInt(v) || 0;
    });
    $('#grandTotal').text('Rp ' + total.toLocaleString('id-ID'));
}
</script>
