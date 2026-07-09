<div class="card">
    <div class="card-header">
        <div><i class="fas fa-calendar-alt text-primary-custom"></i> Daftar Jadwal Panen</div>
        <a href="/jadwal-panen/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Jadwal</a>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="status" class="form-control auto-submit">
                        <option value="">Semua Status</option>
                        <option value="terjadwal" <?= ($status ?? '') === 'terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
                        <option value="selesai" <?= ($status ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        <option value="ditunda" <?= ($status ?? '') === 'ditunda' ? 'selected' : '' ?>>Ditunda</option>
                        <option value="batal" <?= ($status ?? '') === 'batal' ? 'selected' : '' ?>>Batal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="month" name="periode" class="form-control" value="<?= esc($periode ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Tanggal Rencana</th>
                        <th>Periode</th>
                        <th>Rumah Walet</th>
                        <th>Petugas</th>
                        <th>Estimasi (kg)</th>
                        <th>Status</th>
                        <th width="280">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jadwal)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-calendar fa-2x d-block mb-2"></i> Belum ada jadwal panen
                        </td></tr>
                    <?php else: $no = 1; foreach ($jadwal as $j): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= format_tanggal($j['tanggal_rencana'], 'd/m/Y') ?></td>
                            <td><span class="badge badge-secondary"><?= esc($j['periode']) ?></span></td>
                            <td><strong><?= esc($j['rw_kode']) ?></strong><br><small><?= esc($j['rw_nama']) ?></small></td>
                            <td><?= esc($j['petugas_nama'] ?? '-') ?></td>
                            <td><?= angka($j['estimasi_hasil_kg'], 2) ?> kg</td>
                            <td><?= badge_status($j['status']) ?></td>
                            <td>
                                <?php if ($j['status'] === 'terjadwal'): ?>
                                    <a href="/jadwal-panen/status/<?= $j['id'] ?>/selesai" class="btn btn-success btn-sm" title="Tandai Selesai" onclick="return confirm('Tandai jadwal panen ini sebagai SELESAI?')"><i class="fas fa-check"></i></a>
                                    <a href="/jadwal-panen/status/<?= $j['id'] ?>/ditunda" class="btn btn-warning btn-sm" title="Tunda"><i class="fas fa-pause"></i></a>
                                    <a href="/jadwal-panen/status/<?= $j['id'] ?>/batal" class="btn btn-danger btn-sm" title="Batalkan" onclick="return confirm('Batalkan jadwal panen ini?')"><i class="fas fa-times"></i></a>
                                <?php endif; ?>
                                <a href="/jadwal-panen/edit/<?= $j['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/jadwal-panen/delete/<?= $j['id'] ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
