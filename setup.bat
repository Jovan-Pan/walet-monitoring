@echo off
REM ============================================================
REM SETUP SCRIPT - Sistem Monitoring Sarang Walet
REM ============================================================
REM Jalankan script ini di Command Prompt setelah extract project
REM ============================================================

echo ============================================================
echo   SETUP SISTEM MONITORING SARANG WALET (CodeIgniter 4)
echo ============================================================
echo.

REM Check PHP
where php >nul 2>nul
if errorlevel 1 (
    echo [ERROR] PHP tidak ditemukan di PATH. Install PHP 8.1+ terlebih dahulu.
    pause
    exit /b 1
)
echo [OK] PHP terdeteksi
php -v

REM Check Composer
where composer >nul 2>nul
if errorlevel 1 (
    echo [ERROR] Composer tidak ditemukan. Install dari https://getcomposer.org/
    pause
    exit /b 1
)
echo [OK] Composer terdeteksi

REM Install dependencies
echo.
echo [INFO] Installing Composer dependencies...
call composer install --no-dev --optimize-autoloader
if errorlevel 1 (
    echo [ERROR] composer install gagal
    pause
    exit /b 1
)
echo [OK] Dependencies installed

REM Setup environment
echo.
echo [INFO] Setup environment file...
if not exist .env (
    copy env .env >nul
    echo [OK] File .env dibuat dari template
) else (
    echo [INFO] File .env sudah ada
)

REM Create writable directories
echo.
echo [INFO] Memastikan folder writable ada...
if not exist writable\cache mkdir writable\cache
if not exist writable\logs mkdir writable\logs
if not exist writable\session mkdir writable\session
if not exist writable\temp mkdir writable\temp
if not exist writable\uploads mkdir writable\uploads
if not exist public\uploads mkdir public\uploads
echo [OK] Folder writable siap

echo.
echo ============================================================
echo   SETUP SELESAI!
echo ============================================================
echo.
echo Langkah selanjutnya:
echo.
echo 1. Buat database 'db_walet' di MySQL/phpMyAdmin
echo 2. Import file database.sql ke database tersebut
echo 3. Sesuaikan kredensial database di file .env jika perlu
echo 4. Jalankan development server: php spark serve
echo 5. Buka browser ke http://localhost:8080
echo 6. Login dengan akun default:
echo    - Admin   : admin / admin123
echo    - Petugas : petugas / petugas123
echo    - Owner   : owner / owner123
echo.
echo ============================================================
pause
