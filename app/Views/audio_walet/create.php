<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-volume-up"></i> Input Catatan Audio Walet</h5>
    </div>
    <div class="card-body">
        <form method="post" action="/audio-walet/store">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Rumah Walet *</label>
                        <select name="rumah_walet_id" class="form-control" required>
                            <option value="">- Pilih RW -</option>
                            <?php foreach ($rumahList as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= esc($r['kode']) ?> - <?= esc($r['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= esc($tanggalHariIni) ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jenis Suara *</label>
                        <select name="jenis_suara" class="form-control" required>
                            <?php foreach ($jenis_suara_list as $k => $v): ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jam Nyala *</label>
                        <input type="time" name="jam_nyala" class="form-control" value="05:00" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jam Mati *</label>
                        <input type="time" name="jam_mati" class="form-control" value="19:00" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Volume (%) *</label>
                        <input type="number" name="volume" class="form-control" value="70" min="0" max="100" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jumlah Speaker Aktif</label>
                        <input type="number" name="jumlah_speaker_aktif" class="form-control" value="0" min="0">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kondisi Speaker *</label>
                        <select name="kondisi_speaker" class="form-control" required>
                            <option value="baik">Baik</option>
                            <option value="rusak_sebagian">Rusak Sebagian</option>
                            <option value="rusak_total">Rusak Total</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kondisi Amplifier *</label>
                        <select name="kondisi_amplifier" class="form-control" required>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Misal: Coba ganti suara ke panggilan piyik untuk akhir musim panen utuh..."></textarea>
            </div>

            <div class="text-right">
                <a href="/audio-walet" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>
