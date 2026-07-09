<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Stock Opname - Listing Fisik vs Sistem</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Cetak halaman ini sebagai checklist untuk stock opname fisik di gudang. Bandingkan jumlah fisik dengan yang tercatat di sistem.
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>RW</th>
                        <th>Grade</th>
                        <th>Jenis</th>
                        <th class="text-right">Tercatat (kg)</th>
                        <th class="text-right">Fisik (kg)</th>
                        <th class="text-right">Selisih (kg)</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($stokTersedia as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><small><?= esc($s['rw_kode'] ?? '') ?></small> <?= esc($s['rw_nama'] ?? '') ?></td>
                            <td><?= badge_grade($s['grade']) ?></td>
                            <td><?= badge_jenis_panen($s['jenis_panen']) ?></td>
                            <td class="text-right"><strong><?= angka($s['berat_kg'], 3) ?></strong></td>
                            <td class="text-right"><input type="text" class="form-control form-control-sm text-right" placeholder="0,000" style="width:100px;"></td>
                            <td class="text-right"><input type="text" class="form-control form-control-sm text-right" placeholder="0,000" style="width:100px;"></td>
                            <td><input type="text" class="form-control form-control-sm" placeholder="Catatan"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-right mt-3">
            <button onclick="window.print()" class="btn btn-info"><i class="fas fa-print"></i> Cetak Checklist</button>
            <a href="/stok" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
</div>

<style media="print">
    .sidebar, .topbar, .page-content > .card:first-child { display: none !important; }
    body { background: white !important; }
    input[type="text"] { border: 1px solid #999 !important; background: white !important; }
</style>
