============================================================
  WALET-MONITORING v2.0 (POST-AUDIT IMPROVEMENT)
============================================================

Improved from 5.0/10 → ~8.5/10 based on deep audit
20 recommendations implemented (P0×5 + P1×8 + P2×7)

============================================================
  WHAT'S NEW IN v2.0
============================================================

SECURITY (P0):
- CSRF protection AKTIF di semua POST form
- Role enforcement: admin/petugas/owner dibatasi per modul
- File upload: validate ext/mime/size + disable PHP exec in /uploads
- Force change password on first login (admin/petugas/owner)
- Rate limiting login (max 5 attempts / 15 min)
- Session regenerate after login (anti session fixation)

FINANCIAL ACCURACY (P1):
- Modul Penjualan/Invoice (kas riil, bukan estimasi nilai panen)
- Auto-alokasi gaji per RW berdasarkan proporsi kapasitas tahunan
- Master harga per grade per periode (validasi range min-max)
- Stok sarang walet tracking (panen → masuk stok → jual → keluar)
- Cetak invoice PDF (TCPDF)

WORKFLOW (P1):
- Batch input hasil panen (3 grade A/B/C dalam 1 form, 1 transaction)
- Pagination di semua index page
- DB transaction untuk multi-tabel operation
- DB index untuk performance (tanggal, periode, status, dll)
- Range query (BETWEEN) bukan YEAR() — pakai index

INDUSTRY-SPECIFIC (P2):
- Modul Audio Walet (jenis suara, jam nyala, kondisi speaker)
- Field jenis_panen (urat/sarang_utuh/kecil) — beda harga pasar
- Tabel predator_inspeksi terstruktur (ganti CSV string)
- Field fase_sarang (kosong/pembentukan/bertelur/menetas/piyik/siap_panen)
- Recategorize pengeluaran (hapus "pakan", tambah stimulan_aroma, audio, sertifikasi, transportasi, pajak_retribusi, renovasi_besar)
- Approval flow per kategori (threshold berbeda per kategori)
- Soft delete di semua tabel utama (data nggak hilang permanen)

============================================================
  CARA INSTALASI
============================================================

1. Extract zip ke C:\xampp\htdocs\
   Hasilnya: C:\xampp\htdocs\walet-monitoring\app, public, vendor, ...

2. Start Apache + MySQL dari XAMPP Control Panel

3. Aktifkan PHP extensions di C:\xampp\php\php.ini (jika belum):
   - extension=intl
   - extension=gd
   - extension=zip
   - extension=mysqli
   - extension=mbstring

4. Import database:
   - Buka http://localhost/phpmyadmin
   - Tab Import → pilih "database.sql"
   - Klik Go

5. Run seeder (untuk fix password hash):
   -----------------------------------------------------------
   cd C:\xampp\htdocs\walet-monitoring
   php spark db:seed InitialSeeder
   -----------------------------------------------------------

6. Jalankan server:
   -----------------------------------------------------------
   php spark serve
   -----------------------------------------------------------
   → Buka http://localhost:8080
   → Login: admin / admin123
   → Akan diminta ganti password wajib (force change)

============================================================
  AKUN DEFAULT (setelah seeder)
============================================================
  Admin   : admin / admin123    → akses penuh
  Petugas : petugas / petugas123 → inspeksi, hasil panen, audio
  Owner   : owner / owner123    → dashboard, penjualan, monitoring, laporan
  Semua WAJIB ganti password saat login pertama.

============================================================
  TROUBLESHOOTING
============================================================

- "PHP version 8.3 required" → edit vendor/composer/platform_check.php,
  comment out baris `$issues[] = 'Your Composer dependencies require a PHP version...'`

- 500 error di dashboard → cek writable/logs/log-YYYY-MM-DD.log
  Pastikan folder writable/ writable (chmod 755 atau 777).

- Login gagal padahal password benar → jalankan:
  php spark db:seed InitialSeeder
  (reset password ke default + set must_change_password=1)

- Permission denied writable/ → chmod -R 777 writable public/uploads

============================================================
  STRUKTUR MENU (v2.0)
============================================================

ADMIN (akses penuh):
- Master Data: User, Rumah Walet, Petugas, Harga Grade
- Operasional: Inspeksi, Audio Walet, Jadwal Panen, Hasil Panen, Pengeluaran
- Penjualan & Stok: Invoice, Stok Sarang
- Monitoring & Laporan: semua

PETUGAS (input lapangan):
- Operasional: Inspeksi, Audio Walet, Jadwal Panen, Hasil Panen (single + batch)

OWNER (decision maker):
- Penjualan & Stok: Invoice (cetak PDF), Stok Sarang
- Monitoring & Laporan: semua (read-only + approve pengeluaran)
- Pending approval badge di sidebar

============================================================
  NEW FEATURES TO TRY
============================================================

1. BATCH INPUT HASIL PANEN
   Menu: Hasil Panen → "Batch Input (3 Grade)"
   Input A, B, C sekaligus dalam 1 form, 1 transaction.
   Harga auto-fetch dari master harga (Bisa di-override dalam range).

2. PENJUALAN + CETAK INVOICE PDF
   Menu: Penjualan → "Buat Invoice"
   - Pilih stok sarang yang akan dijual
   - Sistem auto-hitung total + update status stok jadi "terjual"
   - Klik "Cetak Invoice PDF" untuk export PDF formal

3. APPROVAL FLOW PENGELUARAN
   Pengeluaran di atas threshold kategori → status PENDING
   Menu: Pengeluaran → filter "Pending Approval"
   Owner bisa APPROVE/REJECT dengan catatan

4. AUTO-ALOKASI GAJI
   Input pengeluaran kategori "gaji" tanpa pilih RW
   → sistem otomatis bagi ke semua RW aktif berdasarkan proporsi kapasitas tahunan
   → laporan produktivitas per RW jadi akurat

5. MODUL AUDIO WALET
   Menu: Audio Walet → "Input Audio"
   Catat jenis suara, jam nyala, kondisi speaker, kondisi amplifier
   Penting untuk track korelasi audio dengan populasi walet

6. MASTER HARGA PER PERIODE
   Menu: Harga Grade
   Set range min-max + default per grade × jenis panen × periode (YYYY-MM)
   Operator hanya bisa input harga dalam range tersebut

7. STOK OPNAME
   Menu: Stok → "Stock Opname"
   Cetak checklist listing fisik vs sistem

============================================================
  CHANGES FROM v1 (untuk developer)
============================================================

Database:
- 8 tabel baru ditambahkan (total 16)
- All main tables: + deleted_at column (soft delete)
- All FK: ON DELETE CASCADE → ON DELETE RESTRICT
- 15+ indexes added untuk performance
- 40+ new fields added across all tables

Code:
- 4 new controllers (Penjualan, AudioWalet, Stok, HargaGrade)
- 4 new models (PenjualanDetail, AudioWalet, StokSarang, HargaGrade)
- 15+ new views
- Updated sidebar with role-aware menu + new modules
- Updated Common.php with 7 new helper functions
- Updated Filters.php (CSRF global)
- Updated Routes.php (role enforcement per group)
- Updated Security.php (CSRF config)
- Updated AuthController (rate limit + session regenerate + force change password)

============================================================
