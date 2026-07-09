<?php
/**
 * Diagnostic Tool v2 untuk Walet-Monitoring
 * Lokasi: public/diagnose.php
 *
 * Cara akses: http://localhost:8080/diagnose.php
 *
 * Hapus file ini setelah troubleshooting selesai (jangan deploy ke production).
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=UTF-8');

// diagnose.php ada di dalam folder public/, jadi project root = parent dir
$publicDir    = dirname(__FILE__);          // .../walet-monitoring/public
$projectRoot  = dirname($publicDir);         // .../walet-monitoring
$assetBase    = $publicDir . '/assets';      // .../walet-monitoring/public/assets

function check_file($label, $path, $expectedMinSize = 1000) {
    $exists = file_exists($path);
    $size   = $exists ? filesize($path) : 0;
    $ok     = $exists && $size >= $expectedMinSize;
    $color  = $ok ? 'green' : 'red';
    $mark   = $ok ? '✓' : '✗';
    $displayPath = str_replace('\\', '/', str_replace(dirname(dirname(__FILE__)), '', $path));
    echo "<tr><td style='color:$color;font-size:18px;font-weight:bold'>$mark</td>";
    echo "<td>" . htmlspecialchars($label) . "</td>";
    echo "<td><code>" . htmlspecialchars($displayPath) . "</code></td>";
    echo "<td>" . ($exists ? number_format($size) . ' bytes' : '<strong>NOT FOUND</strong>') . "</td></tr>";
}

function check_dir($label, $path) {
    $exists = is_dir($path);
    $writable = $exists && is_writable($path);
    $color = $writable ? 'green' : 'red';
    $mark = $writable ? '✓' : '✗';
    $displayPath = str_replace('\\', '/', str_replace(dirname(dirname(__FILE__)), '', $path));
    echo "<tr><td style='color:$color;font-size:18px;font-weight:bold'>$mark</td>";
    echo "<td>" . htmlspecialchars($label) . "</td>";
    echo "<td><code>" . htmlspecialchars($displayPath) . "</code></td>";
    echo "<td>" . ($writable ? '<strong style="color:green">Writable</strong>' : ($exists ? '<strong style="color:orange">READ-ONLY</strong>' : '<strong>NOT FOUND</strong>')) . "</td></tr>";
}

function check_url($label, $url) {
    // curl mungkin tidak ada di Windows, pakai file_get_contents dengan context
    $ctx = stream_context_create(['http' => ['timeout' => 5, 'method' => 'HEAD']]);
    $start = microtime(true);
    $headers = @get_headers($url, 0, $ctx);
    $elapsed = round((microtime(true) - $start) * 1000);

    if ($headers === false) {
        echo "<tr><td style='color:red;font-size:18px;font-weight:bold'>✗</td>";
        echo "<td>" . htmlspecialchars($label) . "</td>";
        echo "<td><code>" . htmlspecialchars($url) . "</code></td>";
        echo "<td><strong style='color:red'>Cannot connect</strong></td></tr>";
        return;
    }

    // Cari HTTP status code
    $status = 'Unknown';
    foreach ($headers as $h) {
        if (preg_match('/^HTTP\/[\d.]+\s+(\d+)/', $h, $m)) {
            $status = $m[1];
            break;
        }
    }

    // Get content size jika 200
    $size = 0;
    if ($status === '200') {
        $content = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 5]]));
        if ($content !== false) $size = strlen($content);
    }

    $ok = $status === '200' && $size > 500;
    $color = $ok ? 'green' : 'red';
    $mark = $ok ? '✓' : '✗';
    $note = "HTTP $status, " . number_format($size) . ' bytes, ' . $elapsed . 'ms';

    echo "<tr><td style='color:$color;font-size:18px;font-weight:bold'>$mark</td>";
    echo "<td>" . htmlspecialchars($label) . "</td>";
    echo "<td><code>" . htmlspecialchars($url) . "</code></td>";
    echo "<td>$note</td></tr>";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic v2 - Walet Monitoring</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; max-width: 1100px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #1e6091; border-bottom: 3px solid #1e6091; padding-bottom: 10px; }
        h2 { color: #1e6091; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; background: white; margin: 10px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; font-size: 13px; }
        th { background: #1e6091; color: white; }
        td:first-child { width: 30px; text-align: center; }
        .box { background: white; padding: 20px; border-radius: 8px; margin: 15px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196f3; margin: 15px 0; }
        .warn { background: #fff3e0; padding: 15px; border-left: 4px solid #ff9800; margin: 15px 0; }
        .danger { background: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 15px 0; }
        .success { background: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50; margin: 15px 0; }
        code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; font-family: Consolas, monospace; font-size: 12px; }
        .path { font-family: Consolas, monospace; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <h1>🔧 Diagnostic Tool v2 - Walet Monitoring</h1>

    <div class="info">
        <strong>PHP Version:</strong> <?= PHP_VERSION ?><br>
        <strong>Server:</strong> <?= php_sapi_name() ?><br>
        <strong>Time:</strong> <?= date('Y-m-d H:i:s') ?><br>
        <strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?><br>
        <strong>Project Root:</strong> <span class="path"><?= $projectRoot ?></span><br>
        <strong>Public Dir:</strong> <span class="path"><?= $publicDir ?></span><br>
        <strong>Asset Base:</strong> <span class="path"><?= $assetBase ?></span>
    </div>

    <?php if (! empty($_GET['debug'])): ?>
    <div class="box">
        <strong>Debug info:</strong><br>
        <span class="path">__FILE__ = <?= __FILE__ ?></span><br>
        <span class="path">dirname(__FILE__) = <?= dirname(__FILE__) ?></span><br>
        <span class="path">dirname(dirname(__FILE__)) = <?= dirname(dirname(__FILE__)) ?></span>
    </div>
    <?php endif; ?>

    <h2>1. Cek Asset File di Server (Fisik)</h2>
    <p>Pastikan file-file library JS/CSS ada di folder <code>public/assets/</code></p>
    <table>
        <tr><th></th><th>Asset</th><th>Path (relatif ke project root)</th><th>Status</th></tr>
        <?php
        check_file('Chart.js (untuk chart)', "$assetBase/js/vendor/chart.umd.min.js", 100000);
        check_file('jQuery', "$assetBase/js/vendor/jquery.min.js", 50000);
        check_file('Bootstrap JS', "$assetBase/js/vendor/bootstrap.bundle.min.js", 50000);
        check_file('Select2 JS', "$assetBase/js/vendor/select2.min.js", 50000);
        check_file('app.js', "$assetBase/js/app.js", 100);
        check_file('Bootstrap CSS', "$assetBase/css/vendor/bootstrap.min.css", 100000);
        check_file('Font Awesome CSS', "$assetBase/css/vendor/all.min.css", 50000);
        check_file('Select2 CSS', "$assetBase/css/vendor/select2.min.css", 5000);
        check_file('style.css', "$assetBase/css/style.css", 100);
        check_file('FA Solid woff2', "$assetBase/webfonts/fa-solid-900.woff2", 50000);
        check_file('FA Regular woff2', "$assetBase/webfonts/fa-regular-400.woff2", 10000);
        check_file('FA Brands woff2', "$assetBase/webfonts/fa-brands-400.woff2", 50000);
        ?>
    </table>

    <h2>2. Cek Folder writable (Permission)</h2>
    <table>
        <tr><th></th><th>Folder</th><th>Path</th><th>Status</th></tr>
        <?php
        check_dir('writable', "$projectRoot/writable");
        check_dir('writable/cache', "$projectRoot/writable/cache");
        check_dir('writable/session', "$projectRoot/writable/session");
        check_dir('writable/logs', "$projectRoot/writable/logs");
        check_dir('writable/temp', "$projectRoot/writable/temp");
        check_dir('writable/uploads', "$projectRoot/writable/uploads");
        check_dir('writable/debugbar', "$projectRoot/writable/debugbar");
        ?>
    </table>

    <h2>3. Cek HTTP Akses ke Asset (via Server)</h2>
    <p>Pastikan server PHP bisa melayani request ke file asset via browser</p>
    <table>
        <tr><th></th><th>Asset</th><th>URL</th><th>HTTP Status</th></tr>
        <?php
        $port = $_SERVER['SERVER_PORT'] ?: '8080';
        $host = $_SERVER['HTTP_HOST'] ?: "localhost:$port";
        $base = "http://$host";
        check_url('Chart.js', "$base/assets/js/vendor/chart.umd.min.js");
        check_url('jQuery', "$base/assets/js/vendor/jquery.min.js");
        check_url('Bootstrap JS', "$base/assets/js/vendor/bootstrap.bundle.min.js");
        check_url('Font Awesome CSS', "$base/assets/css/vendor/all.min.css");
        check_url('fa-solid.woff2', "$base/assets/webfonts/fa-solid-900.woff2");
        check_url('app.js', "$base/assets/js/app.js");
        check_url('style.css', "$base/assets/css/style.css");
        ?>
    </table>

    <h2>4. Cek View Files (HTML templates)</h2>
    <p>Pastikan file-file view ada di <code>app/Views/</code></p>
    <table>
        <tr><th></th><th>File</th><th>Path</th><th>Status</th></tr>
        <?php
        $files = [
            'header.php'  => "$projectRoot/app/Views/layout/header.php",
            'footer.php'  => "$projectRoot/app/Views/layout/footer.php",
            'login.php'   => "$projectRoot/app/Views/auth/login.php",
            'dashboard'   => "$projectRoot/app/Views/dashboard/index.php",
            'error_404'   => "$projectRoot/app/Views/errors/html/error_404.php",
            'Laporan.php' => "$projectRoot/app/Controllers/Laporan.php",
            'Routes.php'  => "$projectRoot/app/Config/Routes.php",
            '.env'        => "$projectRoot/.env",
            'spark'       => "$projectRoot/spark",
        ];
        foreach ($files as $name => $path) {
            $exists = file_exists($path);
            $size   = $exists ? filesize($path) : 0;
            $ok     = $exists && $size > 500;
            $color  = $ok ? 'green' : 'red';
            $mark   = $ok ? '✓' : '✗';
            $displayPath = str_replace('\\', '/', str_replace($projectRoot, '', $path));
            echo "<tr><td style='color:$color;font-size:18px;font-weight:bold'>$mark</td>";
            echo "<td>$name</td>";
            echo "<td><code>$displayPath</code></td>";
            echo "<td>" . ($exists ? number_format($size) . ' bytes' : '<strong>NOT FOUND</strong>') . "</td></tr>";
        }
        ?>
    </table>

    <h2>5. Cek CDN References di View (harus sudah dihilangkan)</h2>
    <p>Jika masih ada link CDN, file view Anda masih versi lama. Harus di-overwrite dengan yang baru.</p>
    <table>
        <tr><th>File</th><th>Status CDN</th><th>Status Local</th></tr>
        <?php
        $checkViews = [
            'header.php'  => "$projectRoot/app/Views/layout/header.php",
            'footer.php'  => "$projectRoot/app/Views/layout/footer.php",
            'login.php'   => "$projectRoot/app/Views/auth/login.php",
            'error_404'   => "$projectRoot/app/Views/errors/html/error_404.php",
        ];
        foreach ($checkViews as $name => $path) {
            if (! file_exists($path)) {
                echo "<tr><td>$name</td><td style='color:gray'>-</td><td style='color:red'>FILE NOT FOUND</td></tr>";
                continue;
            }
            $content = file_get_contents($path);
            $cdnCount   = preg_match_all('/https:\/\/(cdn|cdnjs|code\.jquery)/', $content);
            $localCount = preg_match_all('/\/assets\/(js|css)\/vendor\//', $content);
            $cdnLabel   = $cdnCount > 0 ? "<strong style='color:red'>MASIH ADA $cdnCount CDN</strong>" : "<strong style='color:green'>✓ Bersih (0 CDN)</strong>";
            $localLabel = $localCount > 0 ? "<strong style='color:green'>✓ $localCount local refs</strong>" : "<strong style='color:orange'>0 local refs (perlu update)</strong>";
            echo "<tr><td>$name</td><td>$cdnLabel</td><td>$localLabel</td></tr>";
        }
        ?>
    </table>

    <h2>6. PHP Extensions yang Dibutuhkan</h2>
    <table>
        <tr><th></th><th>Extension</th><th>Status</th></tr>
        <?php
        $extensions = ['intl', 'gd', 'mbstring', 'mysqli', 'pdo_mysql', 'zip', 'curl', 'xml', 'json', 'tokenizer'];
        foreach ($extensions as $ext) {
            $loaded = extension_loaded($ext);
            $color = $loaded ? 'green' : 'red';
            $mark = $loaded ? '✓' : '✗';
            echo "<tr><td style='color:$color;font-size:18px;font-weight:bold'>$mark</td>";
            echo "<td>$ext</td>";
            echo "<td>" . ($loaded ? 'Loaded' : 'NOT LOADED') . "</td></tr>";
        }
        // Opcache is optional
        $loaded = extension_loaded('opcache');
        $color = $loaded ? 'green' : 'orange';
        $mark = $loaded ? '✓' : '⚠';
        echo "<tr><td style='color:$color;font-size:18px;font-weight:bold'>$mark</td>";
        echo "<td>opcache (optional)</td>";
        echo "<td>" . ($loaded ? 'Loaded' : 'NOT LOADED (optional, OK to skip)') . "</td></tr>";
        ?>
    </table>

    <h2>📋 Diagnosis & Solusi</h2>

    <?php
    // Auto-detect problems and suggest solutions
    $problems = [];

    // Check 1: Asset files
    $assetFiles = [
        "$assetBase/js/vendor/chart.umd.min.js",
        "$assetBase/js/vendor/jquery.min.js",
        "$assetBase/css/vendor/all.min.css",
        "$assetBase/webfonts/fa-solid-900.woff2",
    ];
    $missingAssets = 0;
    foreach ($assetFiles as $f) {
        if (! file_exists($f) || filesize($f) < 1000) $missingAssets++;
    }
    if ($missingAssets > 0) {
        $problems[] = [
            'level' => 'danger',
            'title' => "Asset File Hilang ($missingAssets dari 4 kritis)",
            'desc'  => "Anda pakai zip versi lama yang folder <code>public/assets/vendor/</code> dan <code>public/assets/webfonts/</code> belum terisi.",
            'fix'   => "Download <strong>walet-fix-pack.zip</strong> (486KB) dari folder download, extract dengan overwrite ke <code>C:\\xampp\\htdocs\\walet-monitoring\\</code>. Pastikan saat extract, pilih <strong>\"Yes to All\"</strong> saat prompt overwrite.",
        ];
    }

    // Check 2: Writable permission
    $writableDirs = ["$projectRoot/writable/cache", "$projectRoot/writable/session", "$projectRoot/writable/logs"];
    $badWritable = 0;
    foreach ($writableDirs as $d) {
        if (! is_writable($d)) $badWritable++;
    }
    if ($badWritable > 0) {
        $problems[] = [
            'level' => 'warn',
            'title' => "Permission Folder writable Bermasalah ($badWritable dari 3)",
            'desc'  => "PHP tidak bisa menulis cache/session/log ke folder <code>writable/</code>.",
            'fix'   => "Jalankan <code>fix-permission.bat</code> (double-click) di folder project. Atau buka Command Prompt as Administrator lalu jalankan: <code>icacls \"writable\" /grant Everyone:(OI)(CI)F /T</code>",
        ];
    }

    // Check 5: CDN references
    $cdnFiles = 0;
    foreach ($checkViews as $path) {
        if (file_exists($path)) {
            $content = file_get_contents($path);
            if (preg_match('/https:\/\/(cdn|cdnjs|code\.jquery)/', $content)) {
                $cdnFiles++;
            }
        }
    }
    if ($cdnFiles > 0) {
        $problems[] = [
            'level' => 'warn',
            'title' => "View Files Masih Pakai CDN ($cdnFiles file)",
            'desc'  => "File <code>header.php</code>, <code>footer.php</code>, dll. masih referensi CDN online. Harus di-overwrite dengan versi yang pakai local asset.",
            'fix'   => "Extract ulang <strong>walet-fix-pack.zip</strong> dengan opsi <strong>overwrite</strong>. Pastikan folder <code>app/Views/layout/</code> juga ter-overwrite (header.php, footer.php).",
        ];
    }

    if (empty($problems)) {
        echo '<div class="success">
            <strong>🎉 SEMUA BAGUS!</strong><br>
            Semua check hijau. Jika chart/icon masih belum muncul di browser:
            <ol>
                <li>Restart <code>php spark serve</code> (Ctrl+C, lalu jalankan lagi)</li>
                <li><strong>Hard refresh browser</strong>: <code>Ctrl+Shift+R</code> atau buka <strong>Incognito/Private window</strong></li>
                <li>Buka <a href="/dashboard">/dashboard</a> — chart & icon harusnya muncul</li>
            </ol>
        </div>';
    } else {
        foreach ($problems as $p) {
            echo "<div class='{$p['level']}'>
                <strong>⚠ {$p['title']}</strong><br><br>
                <em>Penyebab:</em> {$p['desc']}<br><br>
                <em>Solusi:</em> {$p['fix']}
            </div>";
        }
    }
    ?>

    <h2>🔗 Quick Test Links</h2>
    <p>
        <a href="/login" style="background:#1e6091;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block">→ Halaman Login</a>
        &nbsp;
        <a href="/dashboard" style="background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block">→ Dashboard (perlu login dulu)</a>
        &nbsp;
        <a href="/diagnose.php?debug=1" style="background:#6c757d;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block">→ Debug Mode</a>
    </p>

    <hr style="margin-top:40px">
    <p style="color:#999;font-size:11px">
        Diagnostic Tool v2.0 - Hapus file <code>diagnose.php</code> ini setelah troubleshooting selesai.
    </p>
</body>
</html>
