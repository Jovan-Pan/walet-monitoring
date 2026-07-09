<style>
    h1 { color: #1e6091; text-align: center; margin-bottom: 5px; font-size: 18px; }
    h2 { color: #2c3e50; text-align: center; font-size: 13px; font-weight: normal; margin-top: 0; }
    .meta { text-align: center; margin-bottom: 15px; font-size: 11px; color: #666; }
    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    th { background: #1e6091; color: #fff; padding: 6px; text-align: left; }
    td { padding: 5px; border-bottom: 1px solid #ddd; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total { background: #f8f9fa; font-weight: bold; }
    .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
</style>

<h1>LAPORAN HASIL PANEN SARANG BURUNG WALET</h1>
<div class="meta">Periode: <?= format_tanggal($dari, 'd/m/Y') ?> s/d <?= format_tanggal($sampai, 'd/m/Y') ?></div>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th class="text-center" width="30">No</th>
            <th width="70">Tanggal</th>
            <th>Rumah Walet</th>
            <th>Petugas</th>
            <th class="text-center" width="40">Grade</th>
            <th class="text-right" width="60">Berat (kg)</th>
            <th class="text-right" width="80">Harga/kg</th>
            <th class="text-right" width="100">Total Nilai</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="8" class="text-center">Tidak ada data</td></tr>
        <?php else: $no = 1; foreach ($data as $r): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= format_tanggal($r['tanggal_panen'], 'd/m/Y') ?></td>
                <td><?= esc($r['rw_kode'] . ' - ' . $r['rw_nama']) ?></td>
                <td><?= esc($r['petugas_nama']) ?></td>
                <td class="text-center"><?= esc($r['grade']) ?></td>
                <td class="text-right"><?= angka($r['berat_kg'], 3) ?></td>
                <td class="text-right">Rp <?= number_format($r['harga_per_kg'], 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($r['berat_kg'] * $r['harga_per_kg'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="5" class="text-right">TOTAL</td>
            <td class="text-right"><?= angka($total_kg, 3) ?> kg</td>
            <td></td>
            <td class="text-right">Rp <?= number_format($total_nilai, 0, ',', '.') ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    Dokumen dicetak pada <?= format_tanggal(date('Y-m-d'), 'd F Y') ?> <?= date('H:i') ?> - Sistem Monitoring Sarang Walet
</div>
