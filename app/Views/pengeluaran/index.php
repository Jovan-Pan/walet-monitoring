<div class="card">
    <div class="card-header">
        <div><i class="fas fa-money-bill-wave text-primary-custom"></i> Pengeluaran Operasional</div>
        <a href="/pengeluaran/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pengeluaran</a>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="kategori" class="form-control auto-submit">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($kategori_list as $k => $v): ?>
                            <option value="<?= $k ?>" <?= ($kategori ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="dari" class="form-control" value="<?= esc($dari ?? '') ?>" placeholder="Dari">
                </div>
                <div class="col-md-3">
                    <input type="date" name="sampai" class="form-control" value="<?= esc($sampai ?? '') ?>" placeholder="Sampai">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Rumah Walet</th>
                        <th>Keterangan</th>
                        <th class="text-right">Jumlah</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pengeluaran)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x d-block mb-2"></i> Belum ada data pengeluaran
                        </td></tr>
                    <?php else: $no = 1; $totalAll = 0; foreach ($pengeluaran as $p): $totalAll += $p['jumlah']; ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= format_tanggal($p['tanggal'], 'd/m/Y') ?></td>
                            <td><span class="badge badge-info"><?= kategori_label($p['kategori']) ?></span></td>
                            <td><?= ! empty($p['rw_kode']) ? esc($p['rw_kode'] . ' - ' . $p['rw_nama']) : '<span class="text-muted">Umum</span>' ?></td>
                            <td><?= esc($p['keterangan']) ?></td>
                            <td class="text-right"><strong class="text-danger-custom"><?= rupiah($p['jumlah']) ?></strong></td>
                            <td>
                                <a href="/pengeluaran/edit/<?= $p['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/pengeluaran/delete/<?= $p['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="bg-light font-weight-bold">
                        <td colspan="5" class="text-right">TOTAL</td>
                        <td class="text-right text-danger-custom"><?= rupiah($totalAll) ?></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
