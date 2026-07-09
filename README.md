# 🕊️ Sistem Informasi Monitoring Produksi Sarang Burung Walet

Sistem informasi berbasis web untuk monitoring produksi, inspeksi, dan pengeluaran operasional sarang burung walet. Dibangun menggunakan **CodeIgniter 4** dengan tampilan **Admin Modern** (sidebar + topbar, palette biru/hijau).


## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.1+ dengan CodeIgniter 4 |
| Database | MySQL / MariaDB 10+ |
| Frontend | Bootstrap 4.6 + Font Awesome 6 + Chart.js 4 |
| PDF Export | TCPDF 6.6 |
| Excel Export | PhpSpreadsheet 1.29 |
| Select2 | Untuk dropdown search |

---

## 📦 Cara Instalasi

### Opsi 1: XAMPP (Paling Mudah)

#### Langkah 1 — Persiapan Environment
1. Install [XAMPP](https://www.apachefriends.org/) (versi 8.1 atau lebih baru)
2. Pastikan PHP & MySQL berjalan (start Apache & MySQL dari XAMPP Control Panel)
3. Download & install [Composer](https://getcomposer.org/)

#### Langkah 2 — Salin Project
1. Ekstrak file `walet-monitoring.zip` ke folder `C:\xampp\htdocs\` (Windows) atau `/opt/lampp/htdocs/` (Linux)
2. Buka terminal/command prompt, masuk ke folder project:
   ```bash
   cd C:\xampp\htdocs\walet-monitoring
   ```

#### Langkah 3 — Install Dependencies
```bash
composer install
```
*Jika muncul error, pastikan PHP & Composer sudah terdaftar di PATH environment variable.*

#### Langkah 4 — Setup Database
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik tab **Import**
3. Pilih file `database.sql` dari folder project
4. Klik **Go** / **Kirim**

*Atau jalankan via command line:*
```bash
mysql -u root < database.sql
```

#### Langkah 5 — Konfigurasi Environment
File `.env` sudah pre-configured untuk XAMPP default (user: `root`, password kosong, database: `db_walet`). Jika konfigurasi MySQL Anda berbeda, edit file `.env`:
```ini
database.default.hostname = localhost
database.default.database = db_walet
database.default.username = root
database.default.password = 
database.default.port = 3306
```

#### Langkah 6 — Jalankan Aplikasi

**Opsi A — Via Spark (Built-in server, port 8080):**
```bash
php spark serve
```
Akses: `http://localhost:8080`

**Opsi B — Via Apache XAMPP (port 80):**
Akses langsung: `http://localhost/walet-monitoring/public`

---

### Opsi 2: Laragon

1. Ekstrak project ke `C:\laragon\www\`
2. Jalankan Laragon (Start All)
3. Buka terminal Laragon, masuk ke folder project, jalankan:
   ```bash
   composer install
   ```
4. Buat database `db_walet` via phpMyAdmin (`http://localhost/phpmyadmin`), import `database.sql`
5. Akses: `http://walet-monitoring.test` (Laragon auto-virtual host) atau `http://localhost/walet-monitoring/public`

---

## 🔑 Akun Login Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Petugas | `petugas` | `petugas123` |
| Owner | `owner` | `owner123` |

> ⚠️ **PENTING:** Segera ubah password default setelah login pertama! Buka menu **Manajemen User** → Edit User → masukkan password baru.

---

## 🚀 Penggunaan Cepat

### 1. Login sebagai Admin
- Buka `http://localhost:8080/login`
- Login dengan `admin` / `admin123`

### 2. Tambah Rumah Walet Baru
- Menu **Master Data → Rumah Walet → Tambah Rumah Walet**
- Kode otomatis digenerate (RW-001, RW-002, ...)

### 3. Input Inspeksi
- Menu **Operasional → Inspeksi → Tambah Inspeksi**
- Pilih rumah walet & petugas, isi kondisi
- Status inspeksi & kondisi rumah otomatis ter-update

### 4. Buat Jadwal Panen
- Menu **Operasional → Jadwal Panen → Tambah Jadwal**
- Pilih rumah walet, tanggal, dan estimasi hasil

### 5. Input Hasil Panen
- Menu **Operasional → Hasil Panen → Input Hasil Panen**
- Bisa dikaitkan dengan jadwal panen (status otomatis selesai)
- Pilih grade (A/B/C), berat, dan harga per kg

### 6. Catat Pengeluaran
- Menu **Operasional → Pengeluaran → Tambah Pengeluaran**
- Pilih kategori, isi keterangan & jumlah

### 7. Lihat Dashboard & Laporan
- Menu **Dashboard** untuk ringkasan & grafik
- Menu **Laporan** untuk export PDF/Excel

---

## 🛠️ Command CLI CodeIgniter 4

Beberapa perintah berguna via spark (jalankan dari root project):

```bash
# Jalankan development server
php spark serve

# Run migration (alternatif import SQL)
php spark migrate

# Rollback migration
php spark migrate:rollback

# Seed data awal
php spark db:seed InitialSeeder

# List semua routes
php spark routes

# Clear cache
php spark cache:clear
```

---

**Selamat menggunakan! 🕊️**
