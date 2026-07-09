<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Approval Pengeluaran</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Pengeluaran ini melebihi threshold kategori dan butuh approval Anda sebelum bisa masuk laporan keuangan.
                </div>

                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Tanggal</th>
                        <td><?= format_tanggal($pengeluaran['tanggal'], 'd F Y') ?></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td><?= kategori_label($pengeluaran['kategori']) ?></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?= esc($pengeluaran['keterangan']) ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td><strong class="text-danger h5"><?= rupiah($pengeluaran['jumlah']) ?></strong></td>
                    </tr>
                    <?php if (! empty($pengeluaran['bukti'])): ?>
                    <tr>
                        <th>Bukti</th>
                        <td>
                            <a href="/uploads/<?= esc($pengeluaran['bukti']) ?>" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-paperclip"></i> Lihat Bukti
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Status Saat Ini</th>
                        <td><?= badge_approval($pengeluaran['approval_status']) ?></td>
                    </tr>
                </table>

                <form method="post" action="/pengeluaran/approve/<?= $pengeluaran['id'] ?>">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Catatan Approval</label>
                        <textarea name="approval_note" class="form-control" rows="2" placeholder="Misal: Approved - urgent, atau Rejected - di luar budget"></textarea>
                    </div>

                    <div class="text-right">
                        <a href="/pengeluaran" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                        <button type="submit" name="action" value="reject" class="btn btn-danger"
                                onclick="return confirm('Yakin REJECT pengeluaran ini?')">
                            <i class="fas fa-times-circle"></i> REJECT
                        </button>
                        <button type="submit" name="action" value="approve" class="btn btn-success"
                                onclick="return confirm('Yakin APPROVE pengeluaran ini?')">
                            <i class="fas fa-check-circle"></i> APPROVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
