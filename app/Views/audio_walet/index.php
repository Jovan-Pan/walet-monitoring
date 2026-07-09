<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="form-inline">
            <label class="mr-2">RW:</label>
            <select name="rumah_walet_id" class="form-control mr-2">
                <option value="">Semua</option>
                <?php foreach ($rumahList as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= ($filters['rumah_walet_id'] ?? '') == $r['id'] ? 'selected' : '' ?>><?= esc($r['kode']) ?> - <?= esc($r['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <label class="mr-2">Dari:</label>
            <input type="date" name="dari" class="form-control mr-2" value="<?= esc($filters['dari'] ?? '') ?>">
            <label class="mr-2">Sampai:</label>
            <input type="date" name="sampai" class="form-control mr-2" value="<?= esc($filters['sampai'] ?? '') ?>">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
            <a href="/audio-walet/create" class="btn btn-success btn-sm ml-auto"><i class="fas fa-plus"></i> Input Audio</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-volume-up text-primary-custom"></i> Catatan Audio Walet
        <small class="text-muted ml-2">(Faktor #1 penarik populasi walet)</small>
    </div>
    <div class="card-body">
        <?php if (empty($audioList)): ?>
            <div class="text-center text-muted py-4">
                <i class="fas fa-volume-mute fa-3x mb-3"></i>
                <h5>Belum ada catatan audio</h5>
                <p>Catatan audio penting untuk track korelasi antara jenis suara, jam nyala, kondisi speaker dengan populasi walet.</p>
                <a href="/audio-walet/create" class="btn btn-success"><i class="fas fa-plus"></i> Input Audio Pertama</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>RW</th>
                            <th>Jenis Suara</th>
                            <th>Jam Nyala</th>
                            <th>Volume</th>
                            <th>Speaker</th>
                            <th>Amplifier</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($audioList as $a): ?>
                            <tr>
                                <td><?= format_tanggal($a['tanggal']) ?></td>
                                <td><small><?= esc($a['rw_kode']) ?></small> <?= esc($a['rw_nama']) ?></td>
                                <td><?= esc($jenis_suara_list[$a['jenis_suara']] ?? $a['jenis_suara']) ?></td>
                                <td><?= esc($a['jam_nyala']) ?> - <?= esc($a['jam_mati']) ?></td>
                                <td><?= esc($a['volume']) ?>%</td>
                                <td>
                                    <?php
                                    $warna = ['baik' => 'success', 'rusak_sebagian' => 'warning', 'rusak_total' => 'danger'][$a['kondisi_speaker']] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $warna ?>"><?= ucfirst(str_replace('_', ' ', $a['kondisi_speaker'])) ?></span>
                                    <small><?= $a['jumlah_speaker_aktif'] ?> unit</small>
                                </td>
                                <td>
                                    <?php $warna = $a['kondisi_amplifier'] === 'baik' ? 'success' : 'danger'; ?>
                                    <span class="badge badge-<?= $warna ?>"><?= ucfirst($a['kondisi_amplifier']) ?></span>
                                </td>
                                <td><small><?= esc($a['catatan']) ?></small></td>
                                <td>
                                    <a href="/audio-walet/edit/<?= $a['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <a href="/audio-walet/delete/<?= $a['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus catatan audio ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
