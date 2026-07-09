<style>
    h1 { color: #1e6091; text-align: center; margin-bottom: 5px; font-size: 18px; }
    h2 { color: #2c3e50; text-align: center; font-size: 13px; font-weight: normal; margin-top: 0; }
    .meta { text-align: center; margin-bottom: 15px; font-size: 11px; color: #666; }
    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    th { background: #e74c3c; color: #fff; padding: 6px; text-align: left; }
    td { padding: 5px; border-bottom: 1px solid #ddd; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total { background: #f8f9fa; font-weight: bold; }
    .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
</style>

<h1>LAPORAN PENGELUARAN OPERASIONAL</h1>
<div class="meta">Periode: <?= format_tanggal($dari, 'd/m/Y') ?> s/d <?= format_tanggal($sampai, 'd/m/Y') ?></div>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th class="text-center" width="30">No</th>
            <th width="70">Tanggal</th>
            <th width="90">Kategori</th>
            <th>Rumah Walet</th>
            <th>Keterangan</th>
            <th class="text-right" width="110">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
        <?php else: $no = 1; foreach ($data as $r): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= format_tanggal($r['tanggal'], 'd/m/Y') ?></td>
                <td><?= kategori_label($r['kategori']) ?></td>
                <td><?= ! empty($r['rw_kode']) ? esc($r['rw_kode'] . ' - ' . $r['rw_nama']) : 'Umum' ?></td>
                <td><?= esc($r['keterangan']) ?></td>
                <td class="text-right">Rp <?= number_format($r['jumlah'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="5" class="text-right">TOTAL</td>
            <td class="text-right">Rp <?= number_format($total, 0, ',', '.') ?></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer">
    Dokumen dicetak pada <?= format_tanggal(date('Y-m-d'), 'd F Y') ?> <?= date('H:i') ?> - Sistem Monitoring Sarang Walet
</div>
