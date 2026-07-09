<div class="card">
    <div class="card-header">
        <div><i class="fas fa-clipboard-check text-primary-custom"></i> Daftar Inspeksi Rumah Walet</div>
        <a href="/inspeksi/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Inspeksi</a>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" placeholder="Cari rumah walet atau petugas..." value="<?= esc($q ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control auto-submit">
                        <option value="">Semua Status</option>
                        <option value="baik" <?= ($status ?? '') === 'baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="sedang" <?= ($status ?? '') === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                        <option value="buruk" <?= ($status ?? '') === 'buruk' ? 'selected' : '' ?>>Buruk</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                        <th>Rumah Walet</th>
                        <th>Petugas</th>
                        <th>Kondisi Bangunan</th>
                        <th>Kondisi Sarang</th>
                        <th>Populasi</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inspeksi)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-clipboard fa-2x d-block mb-2"></i> Belum ada data inspeksi
                        </td></tr>
                    <?php else: $no = 1; foreach ($inspeksi as $i): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= format_tanggal($i['tanggal_inspeksi'], 'd/m/Y') ?></td>
                            <td><strong><?= esc($i['rw_kode']) ?></strong><br><small class="text-muted"><?= esc($i['rw_nama']) ?></small></td>
                            <td><?= esc($i['petugas_nama']) ?></td>
                            <td><?= badge_status($i['kondisi_bangunan']) ?></td>
                            <td><?= badge_status($i['kondisi_sarang']) ?></td>
                            <td><?= number_format($i['populasi_walet']) ?> ekor</td>
                            <td><?= badge_status($i['status']) ?></td>
                            <td>
                                <a href="/inspeksi/view/<?= $i['id'] ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="/inspeksi/edit/<?= $i['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/inspeksi/delete/<?= $i['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
