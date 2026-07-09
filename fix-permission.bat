@echo off
REM ============================================================
REM  Walet-Monitoring: Auto-Fix Permission & Start Server
REM  Run this if you get "Cache unable to write" error
REM ============================================================

echo.
echo ============================================
echo  Walet-Monitoring Setup Fixer
echo ============================================
echo.

cd /d "C:\xampp\htdocs\walet-monitoring"
if errorlevel 1 (
    echo ERROR: Folder C:\xampp\htdocs\walet-monitoring tidak ditemukan!
    echo Pastikan Anda sudah extract zip ke C:\xampp\htdocs\
    pause
    exit /b 1
)

echo [1/5] Membuat folder writable jika belum ada...
if not exist "writable\cache" mkdir "writable\cache"
if not exist "writable\session" mkdir "writable\session"
if not exist "writable\logs" mkdir "writable\logs"
if not exist "writable\temp" mkdir "writable\temp"
if not exist "writable\uploads" mkdir "writable\uploads"
if not exist "writable\debugbar" mkdir "writable\debugbar"
echo     OK - semua folder writable sudah ada

echo.
echo [2/5] Hapus atribut Read-only dari folder writable...
attrib -R "writable\*.*" /S /D 2>nul
attrib -R "writable" /D 2>nul
echo     OK

echo.
echo [3/5] Set full permission untuk Everyone (Windows ACL)...
icacls "writable" /grant Everyone:(OI)(CI)F /T 2>nul
if errorlevel 1 (
    echo     WARNING: icacls gagal, coba jalankan sebagai Administrator
    echo     Skip - lanjut ke step berikutnya
) else (
    echo     OK
)

echo.
echo [4/5] Test tulis file ke writable\cache...
echo test > "writable\cache\__test.txt"
if exist "writable\cache\__test.txt" (
    del "writable\cache\__test.txt"
    echo     OK - writable\cache bisa ditulis
) else (
    echo     GAGAL - masih tidak bisa tulis
    echo     Coba langkah manual:
    echo       1. Right-click folder writable → Properties
    echo       2. Tab Security → klik Edit
    echo       3. Pilih Users → centang Full Control
    echo       4. Klik Apply → OK
    pause
    exit /b 1
)

echo.
echo [5/5] Cek PHP version...
php -v | findstr /R "PHP 8" > nul
if errorlevel 1 (
    echo     WARNING: PHP tidak terdeteksi di PATH
    echo     Pastikan XAMPP sudah start, atau gunakan:
    echo     C:\xampp\php\php.exe spark serve
    pause
    exit /b 1
)
php -v | findstr "PHP"

echo.
echo ============================================
echo  Semua fix berhasil! Sekarang start server...
echo ============================================
echo.
echo  Buka browser ke: http://localhost:8080
echo  Login: admin / admin123
echo.
echo  Tekan Ctrl+C untuk stop server.
echo.

php spark serve

pause
