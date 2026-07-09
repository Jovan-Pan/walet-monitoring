<style>
    h1 { color: #1e6091; text-align: center; margin-bottom: 5px; font-size: 18px; }
    .meta { text-align: center; margin-bottom: 15px; font-size: 11px; color: #666; }
    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    th { background: #2d8659; color: #fff; padding: 6px; text-align: left; }
    td { padding: 5px; border-bottom: 1px solid #ddd; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total { background: #f8f9fa; font-weight: bold; }
    .positif { color: #27ae60; }
    .negatif { color: #e74c3c; }
    .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
</style>

<h1>LAPORAN PRODUKTIVITAS RUMAH WALET</h1>
<div class="meta">Tahun: <?= esc($tahun) ?></div>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th class="text-center" width="30">No</th>
            <th width="50">Kode</th>
            <th>Nama Rumah Walet</th>
            <th class="text-right" width="60">Kap/Bln</th>
            <th class="text-right" width="70">Panen (kg)</th>
            <th class="text-right" width="50">% Kap</th>
            <th class="text-right" width="100">Nilai (Rp)</th>
            <th class="text-right" width="100">Pengeluaran</th>
            <th class="text-right" width="100">Keuntungan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="9" class="text-center">Tidak ada data</td></tr>
        <?php else: $no = 1; $gPanen = $gNilai = $gPeng = $gUntung = 0; foreach ($data as $r): 
            $gPanen += $r['total_panen']; $gNilai += $r['total_nilai'];
            $gPeng += $r['total_pengeluaran']; $gUntung += $r['estimasi_keuntungan'];
        ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($r['kode']) ?></td>
                <td><?= esc($r['nama']) ?></td>
                <td class="text-right"><?= $r['kapasitas_panen_kg'] ? angka($r['kapasitas_panen_kg'], 2) : '-' ?></td>
                <td class="text-right"><?= angka($r['total_panen'], 2) ?></td>
                <td class="text-right"><?= round($r['persentase_kapasitas'], 1) ?>%</td>
                <td class="text-right">Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($r['total_pengeluaran'], 0, ',', '.') ?></td>
                <td class="text-right <?= $r['estimasi_keuntungan'] >= 0 ? 'positif' : 'negatif' ?>">
                    Rp <?= number_format($r['estimasi_keuntungan'], 0, ',', '.') ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="4" class="text-right">TOTAL</td>
            <td class="text-right"><?= angka($gPanen, 2) ?></td>
            <td></td>
            <td class="text-right">Rp <?= number_format($gNilai, 0, ',', '.') ?></td>
            <td class="text-right">Rp <?= number_format($gPeng, 0, ',', '.') ?></td>
            <td class="text-right <?= $gUntung >= 0 ? 'positif' : 'negatif' ?>">Rp <?= number_format($gUntung, 0, ',', '.') ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    Dokumen dicetak pada <?= format_tanggal(date('Y-m-d'), 'd F Y') ?> <?= date('H:i') ?> - Sistem Monitoring Sarang Walet
</div>
