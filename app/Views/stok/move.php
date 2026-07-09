<div class="card">
    <div class="card-header bg-warning text-white">
        <h5 class="mb-0"><i class="fas fa-exchange-alt"></i> Pindah Stok Antar Gudang</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Pindahkan stok sarang walet antara Gudang RW dan Gudang Pusat. Stok di Gudang Pusat biasanya sudah siap untuk dijual/dikirim ke pembeli.
        </div>

        <table class="table table-bordered">
            <tr>
                <th width="30%">RW Asal:</th>
                <td><small><?= esc($stok['rw_kode'] ?? '') ?></small> <?= esc($stok['rw_nama'] ?? 'RW') ?></td>
            </tr>
            <tr>
                <th>Grade:</th>
                <td><?= badge_grade($stok['grade']) ?> <?= badge_jenis_panen($stok['jenis_panen']) ?></td>
            </tr>
            <tr>
                <th>Berat:</th>
                <td><strong><?= angka($stok['berat_kg'], 3) ?> kg</strong></td>
            </tr>
            <tr>
                <th>Tanggal Masuk:</th>
                <td><?= format_tanggal($stok['tanggal_masuk'], 'd F Y') ?></td>
            </tr>
            <tr>
                <th>Lokasi Saat Ini:</th>
                <td>
                    <?php if ($stok['lokasi_gudang'] === 'gudang_rw'): ?>
                        <span class="badge badge-info">Gudang RW</span>
                    <?php else: ?>
                        <span class="badge badge-primary">Gudang Pusat</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <form method="post" action="/stok/move/<?= $stok['id'] ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Pindahkan Ke *</label>
                <select name="lokasi_gudang" class="form-control" required>
                    <?php if ($stok['lokasi_gudang'] === 'gudang_rw'): ?>
                        <option value="gudang_pusat">Gudang Pusat</option>
                    <?php else: ?>
                        <option value="gudang_rw">Gudang RW</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Misal: Pindah ke gudang pusat untuk persiapan penjualan..."></textarea>
            </div>

            <div class="text-right">
                <a href="/stok" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-warning"><i class="fas fa-exchange-alt"></i> Pindahkan</button>
            </div>
        </form>
    </div>
</div>
