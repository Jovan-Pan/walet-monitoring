<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Buat Invoice Penjualan Baru</h5>
    </div>
    <div class="card-body">
        <form method="post" action="/penjualan/store" id="penjualanForm">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>No. Invoice</label>
                        <input type="text" class="form-control" value="<?= esc($no_invoice) ?>" readonly>
                        <small class="form-text text-muted">Auto-generated</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggalHariIni) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status Pembayaran *</label>
                        <select name="status_bayar" class="form-control" required>
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="dp">DP</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Pembeli *</label>
                        <input type="text" name="pembeli_nama" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kontak Pembeli</label>
                        <input type="text" name="pembeli_kontak" class="form-control" placeholder="No HP / email">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat Pembeli</label>
                <textarea name="pembeli_alamat" class="form-control" rows="2"></textarea>
            </div>

            <div class="form-group" id="metodeBayarGroup" style="display:none;">
                <label>Metode Pembayaran</label>
                <select name="metode_bayar" class="form-control">
                    <option value="transfer">Transfer Bank</option>
                    <option value="tunai">Tunai</option>
                    <option value="cek">Cek / Giro</option>
                </select>
            </div>

            <hr>
            <h6><i class="fas fa-boxes"></i> Pilih Stok Sarang yang Dijual</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="stokTable">
                    <thead class="thead-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="selectAll"></th>
                            <th>RW</th>
                            <th>Grade</th>
                            <th>Jenis Panen</th>
                            <th>Berat Tersedia (kg)</th>
                            <th>Berat Dijual (kg)</th>
                            <th>Harga/kg (Rp)</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stokGrouped)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">
                                    <i class="fas fa-inbox fa-2x"></i><br>
                                    Stok sarang walet kosong. Input hasil panen dulu sebelum bisa menjual.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $idx = 0; foreach ($stokGrouped as $key => $group):
                                $first = $group['items'][0]; ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="items[<?= $idx ?>][selected]" class="item-check" data-idx="<?= $idx ?>" data-berat-max="<?= esc($group['total_berat']) ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][hasil_panen_id]" value="<?= esc($first['hasil_panen_id']) ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][grade]" value="<?= esc($first['grade']) ?>">
                                        <input type="hidden" name="items[<?= $idx ?>][jenis_panen]" value="<?= esc($first['jenis_panen']) ?>">
                                    </td>
                                    <td>
                                        <small><?= esc($first['rw_kode'] ?? '') ?></small>
                                        <?= esc($first['rw_nama'] ?? 'RW') ?>
                                    </td>
                                    <td><?= badge_grade($first['grade']) ?></td>
                                    <td><?= badge_jenis_panen($first['jenis_panen']) ?></td>
                                    <td class="text-right"><?= angka($group['total_berat'], 2) ?></td>
                                    <td>
                                        <input type="number" name="items[<?= $idx ?>][berat]" class="form-control form-control-sm berat-input"
                                               data-idx="<?= $idx ?>" step="0.001" min="0" max="<?= esc($group['total_berat']) ?>" placeholder="0.000" disabled>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?= $idx ?>][harga]" class="form-control form-control-sm harga-input"
                                               data-idx="<?= $idx ?>" step="1000" min="0" placeholder="0" disabled>
                                    </td>
                                    <td class="text-right subtotal-display" data-idx="<?= $idx ?>">Rp 0</td>
                                </tr>
                            <?php $idx++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="7" class="text-right">Total:</td>
                            <td class="text-right text-success-custom" id="grandTotal">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan invoice (opsional)"></textarea>
            </div>

            <div class="text-right">
                <a href="/penjualan" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Invoice</button>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide metode bayar based on status
$('[name=status_bayar]').on('change', function() {
    $('#metodeBayarGroup').toggle($(this).val() !== 'belum_bayar');
});

// Select all
$('#selectAll').on('change', function() {
    $('.item-check').prop('checked', this.checked).trigger('change');
});

// Enable/disable inputs based on checkbox
$(document).on('change', '.item-check', function() {
    var idx = $(this).data('idx');
    var checked = this.checked;
    $('.berat-input[data-idx="' + idx + '"]').prop('disabled', !checked);
    $('.harga-input[data-idx="' + idx + '"]').prop('disabled', !checked);
    if (!checked) {
        $('.berat-input[data-idx="' + idx + '"]').val('');
        $('.harga-input[data-idx="' + idx + '"]').val('');
    }
    updateSubtotal(idx);
});

// Update subtotal on input
$(document).on('input', '.berat-input, .harga-input', function() {
    updateSubtotal($(this).data('idx'));
});

function updateSubtotal(idx) {
    var berat = parseFloat($('.berat-input[data-idx="' + idx + '"]').val()) || 0;
    var harga = parseFloat($('.harga-input[data-idx="' + idx + '"]').val()) || 0;
    var subtotal = berat * harga;
    $('.subtotal-display[data-idx="' + idx + '"]').text('Rp ' + subtotal.toLocaleString('id-ID'));
    updateGrandTotal();
}

function updateGrandTotal() {
    var total = 0;
    $('.subtotal-display').each(function() {
        var val = $(this).text().replace(/[^\d]/g, '');
        total += parseInt(val) || 0;
    });
    $('#grandTotal').text('Rp ' + total.toLocaleString('id-ID'));
}

// Form submit - remove disabled inputs (so they don't get sent)
$('#penjualanForm').on('submit', function() {
    $('.item-check:not(:checked)').each(function() {
        var idx = $(this).data('idx');
        $('.berat-input[data-idx="' + idx + '"], .harga-input[data-idx="' + idx + '"]').prop('disabled', true);
    });
    // Re-enable checked items' inputs (they were enabled)
    $('.item-check:checked').each(function() {
        var idx = $(this).data('idx');
        $('.berat-input[data-idx="' + idx + '"], .harga-input[data-idx="' + idx + '"]').prop('disabled', false);
    });
});
</script>
