<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">Dari:</label>
            <input type="date" name="dari" class="form-control mr-2" value="<?= esc($filters['dari'] ?? '') ?>">
            <label class="mr-2">Sampai:</label>
            <input type="date" name="sampai" class="form-control mr-2" value="<?= esc($filters['sampai'] ?? '') ?>">
            <label class="mr-2">Status:</label>
            <select name="status_bayar" class="form-control mr-2">
                <option value="">Semua</option>
                <?php foreach ($status_list as $k => $v): ?>
                    <option value="<?= $k ?>" <?= ($filters['status_bayar'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <label class="mr-2">Pembeli:</label>
            <input type="text" name="pembeli" class="form-control mr-2" value="<?= esc($filters['pembeli'] ?? '') ?>" placeholder="Nama pembeli">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
            <a href="/penjualan/create" class="btn btn-success btn-sm ml-auto"><i class="fas fa-plus"></i> Buat Invoice</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-file-invoice text-primary-custom"></i> Daftar Invoice Penjualan
    </div>
    <div class="card-body">
        <?php if (empty($penjualanList)): ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>Belum ada invoice</h5>
                <p>Invoce penjualan otomatis tercatat di sini setelah Anda membuat transaksi penjualan.</p>
                <a href="/penjualan/create" class="btn btn-success"><i class="fas fa-plus"></i> Buat Invoice Pertama</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Pembeli</th>
                            <th class="text-right">Berat (kg)</th>
                            <th class="text-right">Total Nilai</th>
                            <th class="text-center">Status Bayar</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penjualanList as $p): ?>
                            <tr>
                                <td><strong><?= esc($p['no_invoice']) ?></strong></td>
                                <td><?= format_tanggal($p['tanggal']) ?></td>
                                <td>
                                    <?= esc($p['pembeli_nama']) ?><br>
                                    <small class="text-muted"><?= esc($p['pembeli_kontak'] ?? '') ?></small>
                                </td>
                                <td class="text-right"><?= angka($p['total_berat_kg'], 2) ?></td>
                                <td class="text-right text-success-custom"><strong><?= rupiah($p['total_nilai']) ?></strong></td>
                                <td class="text-center"><?= badge_status_bayar($p['status_bayar']) ?></td>
                                <td class="text-center"><?= $p['jumlah_item'] ?? 0 ?></td>
                                <td class="text-center">
                                    <a href="/penjualan/view/<?= $p['id'] ?>" class="btn btn-info btn-sm" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/penjualan/invoice-pdf/<?= $p['id'] ?>" class="btn btn-danger btn-sm" title="Cetak PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
