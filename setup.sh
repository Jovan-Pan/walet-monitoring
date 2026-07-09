#!/bin/bash
# ============================================================
# SETUP SCRIPT - Sistem Monitoring Sarang Walet
# ============================================================
# Jalankan script ini setelah extract project untuk setup cepat
# ============================================================

echo "============================================================"
echo "  SETUP SISTEM MONITORING SARANG WALET (CodeIgniter 4)"
echo "============================================================"
echo ""

# Check PHP
if ! command -v php &> /dev/null; then
    echo "[ERROR] PHP tidak ditemukan. Install PHP 8.1+ terlebih dahulu."
    exit 1
fi
echo "[OK] PHP version: $(php -v | head -n 1)"

# Check Composer
if ! command -v composer &> /dev/null; then
    echo "[ERROR] Composer tidak ditemukan. Install dari https://getcomposer.org/"
    exit 1
fi
echo "[OK] Composer terdeteksi"

# Install dependencies
echo ""
echo "[INFO] Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader 2>&1
if [ $? -ne 0 ]; then
    echo "[ERROR] composer install gagal"
    exit 1
fi
echo "[OK] Dependencies installed"

# Setup environment
echo ""
echo "[INFO] Setup environment file..."
if [ ! -f .env ]; then
    cp env .env
    echo "[OK] File .env dibuat dari template"
else
    echo "[INFO] File .env sudah ada"
fi

# Create writable directories if missing
echo ""
echo "[INFO] Memastikan folder writable ada..."
mkdir -p writable/cache writable/logs writable/session writable/temp writable/uploads
mkdir -p public/uploads
chmod -R 755 writable public/uploads
echo "[OK] Folder writable siap"

echo ""
echo "============================================================"
echo "  SETUP SELESAI!"
echo "============================================================"
echo ""
echo "Langkah selanjutnya:"
echo ""
echo "1. Buat database 'db_walet' di MySQL/phpMyAdmin"
echo "2. Import file database.sql ke database tersebut"
echo "3. Sesuaikan kredensial database di file .env jika perlu"
echo "4. Jalankan development server:"
echo "   php spark serve"
echo "5. Buka browser ke http://localhost:8080"
echo "6. Login dengan akun default:"
echo "   - Admin   : admin / admin123"
echo "   - Petugas : petugas / petugas123"
echo "   - Owner   : owner / owner123"
echo ""
echo "============================================================"
