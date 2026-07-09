<?php
// Invoice PDF template - rendered via TCPDF
$waletInfo = [
    'nama'    => 'Sistem Monitoring Walet',
    'alamat'  => 'Pelaihari, Tanah Laut, Kalimantan Selatan',
    'kontak'  => 'Walet Pro - Sistem Monitoring',
];
?>
<style>
    .invoice-header { text-align: right; padding-bottom: 15px; border-bottom: 2px solid #1e6091; margin-bottom: 20px; }
    .invoice-title { font-size: 24px; font-weight: bold; color: #1e6091; }
    .invoice-meta { font-size: 11px; color: #666; }
    .section-title { font-size: 12px; font-weight: bold; color: #1e6091; text-transform: uppercase; margin-bottom: 5px; }
    .party-box { padding: 10px; background: #f8f9fa; border-left: 3px solid #1e6091; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th { background: #1e6091; color: white; padding: 8px; font-size: 11px; text-align: left; }
    td { padding: 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .total-row { background: #f8f9fa; font-weight: bold; }
    .total-row td { font-size: 13px; padding: 12px 8px; }
    .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 10px; color: #666; text-align: center; }
    .signature-area { margin-top: 40px; text-align: center; width: 200px; float: right; }
    .signature-line { margin-top: 60px; border-top: 1px solid #333; padding-top: 5px; }
</style>

<div class="invoice-header">
    <div class="invoice-title">INVOICE</div>
    <div class="invoice-meta">
        No: <strong><?= esc($penjualan['no_invoice']) ?></strong><br>
        Tanggal: <?= format_tanggal($penjualan['tanggal'], 'd F Y') ?>
    </div>
</div>

<table cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:50%; vertical-align:top;">
            <div class="section-title">Dari:</div>
            <div class="party-box">
                <strong><?= esc($waletInfo['nama']) ?></strong><br>
                <?= esc($waletInfo['alamat']) ?><br>
                <em><?= esc($waletInfo['kontak']) ?></em>
            </div>
        </td>
        <td style="width:50%; vertical-align:top;">
            <div class="section-title">Ditagihkan Kepada:</div>
            <div class="party-box">
                <strong><?= esc($penjualan['pembeli_nama']) ?></strong><br>
                <?php if (! empty($penjualan['pembeli_kontak'])): ?>
                    <?= esc($penjualan['pembeli_kontak']) ?><br>
                <?php endif; ?>
                <?php if (! empty($penjualan['pembeli_alamat'])): ?>
                    <?= nl2br(esc($penjualan['pembeli_alamat'])) ?>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>

<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th style="width:5%">#</th>
            <th style="width:25%">Rumah Walet</th>
            <th style="width:10%">Grade</th>
            <th style="width:15%">Jenis</th>
            <th class="text-right" style="width:15%">Berat (kg)</th>
            <th class="text-right" style="width:15%">Harga/kg</th>
            <th class="text-right" style="width:15%">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($penjualan['details'] as $d): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($d['rw_kode'] ?? '') ?> <?= esc($d['rw_nama'] ?? '-') ?></td>
                <td class="text-center"><?= esc($d['grade']) ?></td>
                <td><?= esc($d['jenis_panen']) ?></td>
                <td class="text-right"><?= number_format($d['berat_kg'], 3, ',', '.') ?></td>
                <td class="text-right"><?= 'Rp ' . number_format($d['harga_per_kg'], 0, ',', '.') ?></td>
                <td class="text-right"><?= 'Rp ' . number_format($d['subtotal'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="4" class="text-right">Total Berat: <?= number_format($penjualan['total_berat_kg'], 3, ',', '.') ?> kg</td>
            <td colspan="2" class="text-right">TOTAL:</td>
            <td class="text-right"><?= 'Rp ' . number_format($penjualan['total_nilai'], 0, ',', '.') ?></td>
        </tr>
    </tfoot>
</table>

<table cellpadding="0" cellspacing="0" style="margin-top:20px;">
    <tr>
        <td style="width:60%;">
            <div class="section-title">Status Pembayaran</div>
            <div class="party-box">
                Status: <strong><?= ucfirst(str_replace('_', ' ', $penjualan['status_bayar'])) ?></strong><br>
                <?php if (! empty($penjualan['tanggal_bayar'])): ?>
                    Tanggal Bayar: <?= format_tanggal($penjualan['tanggal_bayar'], 'd F Y') ?>
                    <?php if (! empty($penjualan['metode_bayar'])): ?> (via <?= ucfirst($penjualan['metode_bayar']) ?>)<?php endif; ?>
                <?php else: ?>
                    <em>Menunggu pembayaran</em>
                <?php endif; ?>
            </div>
        </td>
        <td style="width:40%;">
            <div class="signature-area">
                <div>Hormat kami,</div>
                <div class="signature-line">Admin Walet Pro</div>
            </div>
        </td>
    </tr>
</table>

<?php if (! empty($penjualan['catatan'])): ?>
    <div style="margin-top:20px; padding:10px; background:#fff3cd; border-left:3px solid #ffc107; font-size:11px;">
        <strong>Catatan:</strong> <?= esc($penjualan['catatan']) ?>
    </div>
<?php endif; ?>

<div class="footer">
    Invoice ini dihasilkan secara otomatis oleh Sistem Monitoring Walet pada <?= date('d/m/Y H:i') ?>.<br>
    Dokumen ini sah tanpa tanda tangan basah.
</div>
