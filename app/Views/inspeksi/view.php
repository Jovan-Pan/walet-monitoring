<?php $i = $inspeksi; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div>
                    <i class="fas fa-clipboard-check text-primary-custom"></i> 
                    Detail Inspeksi - <?= esc($i['rw_kode']) ?> <?= esc($i['rw_nama']) ?>
                </div>
                <a href="/inspeksi" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="text-muted small">Tanggal Inspeksi</label>
                        <div class="form-control-static"><i class="far fa-calendar"></i> <?= format_tanggal($i['tanggal_inspeksi'], 'd F Y') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Petugas</label>
                        <div><i class="fas fa-user"></i> <?= esc($i['petugas_nama']) ?> (<?= esc($i['petugas_nip']) ?>)</div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Status Inspeksi</label>
                        <div><?= badge_status($i['status']) ?></div>
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="/inspeksi/edit/<?= $i['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                    </div>
                </div>

                <h6 class="border-bottom pb-2"><i class="fas fa-clipboard-list text-primary-custom"></i> Kondisi</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="text-muted small">Kondisi Bangunan</div>
                                <div class="mt-2"><?= badge_status($i['kondisi_bangunan']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="text-muted small">Kondisi Sarang</div>
                                <div class="mt-2"><?= badge_status($i['kondisi_sarang']) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="text-muted small">Kebersihan</div>
                                <div class="mt-2"><?= badge_status($i['kebersihan']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="border-bottom pb-2"><i class="fas fa-thermometer-half text-info"></i> Parameter Lingkungan</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="text-muted small">Populasi Walet</div>
                                <div class="mt-2 h5 text-primary-custom"><?= number_format($i['populasi_walet']) ?> ekor</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="text-muted small">Suhu</div>
                                <div class="mt-2 h5 text-info"><?= $i['suhu'] ? angka($i['suhu'], 2) . ' °C' : '-' ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border">
                            <div class="card-body text-center">
                                <div class="text-muted small">Kelembaban</div>
                                <div class="mt-2 h5 text-success-custom"><?= $i['kelembaban'] ? angka($i['kelembaban'], 2) . ' %' : '-' ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (! empty($i['hama'])): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-bug"></i> <strong>Hama Ditemukan:</strong> <?= esc($i['hama']) ?>
                </div>
                <?php endif; ?>

                <?php if (! empty($i['catatan'])): ?>
                <h6 class="border-bottom pb-2"><i class="fas fa-sticky-note text-warning"></i> Catatan</h6>
                <div class="bg-light p-3 rounded">
                    <?= nl2br(esc($i['catatan'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
