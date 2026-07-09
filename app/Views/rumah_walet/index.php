<div class="card">
    <div class="card-header">
        <div><i class="fas fa-warehouse text-primary-custom"></i> Daftar Rumah Walet</div>
        <a href="/rumah-walet/create" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Rumah Walet
        </a>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari kode, nama, atau lokasi..." value="<?= esc($q ?? '') ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                    <?php if (! empty($q)): ?>
                        <a href="/rumah-walet" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Lokasi</th>
                        <th>Luas (m²)</th>
                        <th>Kapasitas/Bln</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rumah)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-warehouse fa-2x d-block mb-2"></i> Belum ada data rumah walet
                        </td></tr>
                    <?php else: $no = 1; foreach ($rumah as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= esc($r['kode']) ?></strong></td>
                            <td><?= esc($r['nama']) ?></td>
                            <td><small><?= esc($r['lokasi'] ?? '-') ?></small></td>
                            <td><?= $r['luas'] ? angka($r['luas'], 2) : '-' ?></td>
                            <td><?= $r['kapasitas_panen_kg'] ? angka($r['kapasitas_panen_kg'], 2) . ' kg' : '-' ?></td>
                            <td><?= badge_status($r['kondisi']) ?></td>
                            <td><?= badge_status($r['status']) ?></td>
                            <td>
                                <a href="/rumah-walet/detail/<?= $r['id'] ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="/rumah-walet/edit/<?= $r['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/rumah-walet/delete/<?= $r['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-center"><?= $pager->links() ?></div>
        <?php endif; ?>
    </div>
</div>
