<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-file-invoice"></i> <?= esc($penjualan['no_invoice']) ?></h5>
                <div>
                    <?= badge_status_bayar($penjualan['status_bayar']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Ditagihkan Kepada:</h6>
                        <strong><?= esc($penjualan['pembeli_nama']) ?></strong><br>
                        <?php if (! empty($penjualan['pembeli_kontak'])): ?>
                            <small><i class="fas fa-phone"></i> <?= esc($penjualan['pembeli_kontak']) ?></small><br>
                        <?php endif; ?>
                        <?php if (! empty($penjualan['pembeli_alamat'])): ?>
                            <small><i class="fas fa-map-marker-alt"></i> <?= nl2br(esc($penjualan['pembeli_alamat'])) ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <h6 class="text-muted">Tanggal Invoice:</h6>
                        <strong><?= format_tanggal($penjualan['tanggal'], 'd F Y') ?></strong>
                        <?php if (! empty($penjualan['tanggal_bayar'])): ?>
                            <br><small class="text-success"><i class="fas fa-check"></i> Dibayar: <?= format_tanggal($penjualan['tanggal_bayar'], 'd F Y') ?>
                            <?php if (! empty($penjualan['metode_bayar'])): ?> via <?= ucfirst($penjualan['metode_bayar']) ?><?php endif; ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>RW</th>
                            <th>Grade</th>
                            <th>Jenis</th>
                            <th class="text-right">Berat (kg)</th>
                            <th class="text-right">Harga/kg</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($penjualan['details'] as $d): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><small><?= esc($d['rw_kode'] ?? '') ?></small> <?= esc($d['rw_nama'] ?? '-') ?></td>
                                <td><?= badge_grade($d['grade']) ?></td>
                                <td><?= badge_jenis_panen($d['jenis_panen']) ?></td>
                                <td class="text-right"><?= angka($d['berat_kg'], 3) ?></td>
                                <td class="text-right"><?= rupiah($d['harga_per_kg']) ?></td>
                                <td class="text-right text-success-custom"><?= rupiah($d['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="4" class="text-right">Total Berat:</td>
                            <td class="text-right"><?= angka($penjualan['total_berat_kg'], 3) ?> kg</td>
                            <td class="text-right">Total Nilai:</td>
                            <td class="text-right text-success-custom"><?= rupiah($penjualan['total_nilai']) ?></td>
                        </tr>
                    </tfoot>
                </table>

                <?php if (! empty($penjualan['catatan'])): ?>
                    <div class="alert alert-light">
                        <strong>Catatan:</strong> <?= esc($penjualan['catatan']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($penjualan['status_bayar'] !== 'lunas'): ?>
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <i class="fas fa-money-check-alt"></i> Tandai Sudah Bayar / Lunas
            </div>
            <div class="card-body">
                <form method="post" action="/penjualan/mark-paid/<?= $penjualan['id'] ?>" class="form-inline">
                    <?= csrf_field() ?>
                    <label class="mr-2">Tanggal Bayar:</label>
                    <input type="date" name="tanggal_bayar" class="form-control mr-2" value="<?= date('Y-m-d') ?>" required>
                    <label class="mr-2">Metode:</label>
                    <select name="metode_bayar" class="form-control mr-2">
                        <option value="transfer">Transfer</option>
                        <option value="tunai">Tunai</option>
                        <option value="cek">Cek/Giro</option>
                    </select>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Tandai Lunas</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-cog"></i> Aksi</div>
            <div class="card-body">
                <a href="/penjualan/invoice-pdf/<?= $penjualan['id'] ?>" class="btn btn-danger btn-block mb-2" target="_blank">
                    <i class="fas fa-file-pdf"></i> Cetak Invoice PDF
                </a>
                <a href="/penjualan" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
                <?php if (session()->get('role') === 'admin'): ?>
                <hr>
                <a href="/penjualan/delete/<?= $penjualan['id'] ?>"
                   class="btn btn-outline-danger btn-block"
                   onclick="return confirm('Hapus invoice ini? Stok akan dikembalikan menjadi tersedia.')">
                    <i class="fas fa-trash"></i> Hapus Invoice
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-info-circle"></i> Info</div>
            <div class="card-body">
                <small class="text-muted">
                    Invoice dibuat oleh: <strong><?= esc($penjualan['input_by'] ?? 'Sistem') ?></strong><br>
                    Status stok sarang walet otomatis berubah jadi <strong>terjual</strong> saat invoice dibuat.
                </small>
            </div>
        </div>
    </div>
</div>
