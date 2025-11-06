# 📋 Panduan Instalasi - Survey Kepuasan Pasien TNI

Aplikasi Survey Kepuasan Pasien untuk Rumah Sakit/Klinik TNI berbasis CodeIgniter 3.

## 🎯 Fitur Aplikasi

### Admin:
- ✅ **Dashboard** - Statistik dan grafik hasil survey
- ✅ **Manajemen Pertanyaan** - CRUD pertanyaan survey
- ✅ **Manajemen Kategori** - CRUD kategori pertanyaan
- ✅ **Import Excel** - Import pertanyaan dari file Excel
- ✅ **Export Excel** - Export laporan ke Excel
- ✅ **Laporan Lengkap** - Laporan detail dan statistik
- ✅ **Filter Laporan** - Filter berdasarkan tanggal, poli, dll
- ✅ **Grafik & Chart** - Visualisasi data survey

### Pasien/Public:
- ✅ **Form Survey** - Form isian survey kepuasan
- ✅ **Responsive Design** - Bisa diakses via mobile/tablet
- ✅ **User Friendly** - Mudah digunakan

---

## 📦 Requirement

- PHP >= 5.6 (Rekomendasi: 7.4)
- MySQL >= 5.7
- Apache/Nginx Web Server
- Composer (optional, untuk PHPExcel)
- CodeIgniter 3.1.x

---

## 🚀 Cara Instalasi

### 1. Download CodeIgniter 3

```bash
# Download CodeIgniter 3.1.13
cd /var/www/html
wget https://github.com/bcit-ci/CodeIgniter/archive/3.1.13.zip
unzip 3.1.13.zip
mv CodeIgniter-3.1.13 survey-tni
cd survey-tni
```

### 2. Copy File Aplikasi

```bash
# Copy semua file dari folder ini ke folder CodeIgniter
cp -r /home/user/survey-tni-ci3/application/* application/
cp -r /home/user/survey-tni-ci3/assets ./
```

### 3. Setup Database

```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE survey_tni;
USE survey_tni;

# Import database
SOURCE /home/user/survey-tni-ci3/database.sql;

# Atau via command line
mysql -u root -p survey_tni < /home/user/survey-tni-ci3/database.sql
```

### 4. Konfigurasi Database

Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',      // sesuaikan
    'password' => '',           // sesuaikan
    'database' => 'survey_tni',
);
```

### 5. Konfigurasi Base URL

Edit file `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/survey-tni/';
// Atau sesuaikan dengan domain Anda
```

### 6. Set Permission

```bash
# Set permission untuk folder uploads
chmod 777 uploads/excel
chmod 777 application/cache
chmod 777 application/logs
```

### 7. Install PHPExcel (untuk Export/Import Excel)

```bash
# Via Composer
cd /var/www/html/survey-tni
composer require phpoffice/phpexcel

# Atau download manual
# Download dari: https://github.com/PHPOffice/PHPExcel
# Extract ke folder application/third_party/PHPExcel
```

### 8. Akses Aplikasi

**Public (Form Survey):**
```
http://localhost/survey-tni/
```

**Admin:**
```
URL: http://localhost/survey-tni/admin/login
Username: admin
Password: admin123
```

---

## 📊 Struktur Database

### Tabel Utama:

1. **users** - Data admin/petugas
2. **kategori_pertanyaan** - Kategori pertanyaan survey
3. **pertanyaan** - Pertanyaan survey
4. **responden** - Data pasien yang mengisi survey
5. **survey** - Header data survey
6. **jawaban** - Detail jawaban survey
7. **import_log** - Log import data

---

## 🎨 Cara Penggunaan

### A. Setup Pertanyaan (Admin)

1. Login sebagai admin
2. Menu **Kategori** → Tambah kategori pertanyaan
3. Menu **Pertanyaan** → Tambah pertanyaan survey
4. Atau **Import dari Excel** untuk banyak pertanyaan sekaligus

### B. Import Pertanyaan dari Excel

1. Download template Excel di menu **Pertanyaan** → **Download Template**
2. Isi pertanyaan di Excel sesuai format
3. Upload file Excel via menu **Import**
4. Sistem akan validasi dan import otomatis

**Format Excel:**

| Kategori | Pertanyaan | Tipe | Wajib | Urutan |
|----------|------------|------|-------|--------|
| Pendaftaran | Bagaimana kepuasan Anda? | skala | 1 | 1 |
| Pelayanan Medis | Dokter ramah? | skala | 1 | 2 |

### C. Pasien Mengisi Survey

1. Pasien akses URL public
2. Isi data diri (Nama, Pangkat, NRP, dll)
3. Jawab semua pertanyaan
4. Submit survey
5. Tampil halaman terima kasih

### D. Lihat Laporan

1. Login admin
2. Menu **Dashboard** → Lihat statistik
3. Menu **Laporan** → Lihat detail survey
4. **Export ke Excel** untuk analisa lebih lanjut

### E. Export Laporan ke Excel

1. Menu **Laporan**
2. Set filter (tanggal, poli, kategori kepuasan)
3. Klik **Export Excel**
4. File otomatis download

---

## 📁 Struktur Folder

```
survey-tni/
├── application/
│   ├── config/
│   │   ├── database.php
│   │   ├── routes.php
│   │   └── autoload.php
│   ├── controllers/
│   │   ├── Survey.php         (Public survey)
│   │   └── admin/
│   │       ├── Auth.php        (Login/logout)
│   │       ├── Dashboard.php   (Dashboard admin)
│   │       ├── Pertanyaan.php  (CRUD pertanyaan)
│   │       ├── Kategori.php    (CRUD kategori)
│   │       └── Laporan.php     (Laporan & export)
│   ├── models/
│   │   ├── Pertanyaan_model.php
│   │   ├── Survey_model.php
│   │   ├── Kategori_model.php
│   │   └── User_model.php
│   ├── views/
│   │   ├── survey/            (View public)
│   │   ├── admin/             (View admin)
│   │   └── templates/         (Layout template)
│   └── libraries/
│       └── Excel.php          (Library Excel)
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/
│   └── excel/
└── database.sql
```

---

## 🔐 User Default

### Admin:
```
Username: admin
Password: admin123
```

**⚠️ PENTING:** Ganti password default setelah login pertama!

---

## 🎯 Tipe Pertanyaan

1. **Skala (1-5)** - Rating 1 sampai 5
   - 1 = Sangat Tidak Puas
   - 2 = Tidak Puas
   - 3 = Cukup
   - 4 = Puas
   - 5 = Sangat Puas

2. **Ya/Tidak** - Pilihan Ya atau Tidak

3. **Pilihan Ganda** - Multiple choice

4. **Text** - Input text bebas (untuk saran/kritik)

---

## 📈 Kategori Kepuasan

Berdasarkan rata-rata nilai:
- **Sangat Puas**: Rata-rata >= 4.5
- **Puas**: Rata-rata >= 3.5 dan < 4.5
- **Cukup**: Rata-rata >= 2.5 dan < 3.5
- **Kurang Puas**: Rata-rata < 2.5

---

## 🐛 Troubleshooting

### Error: Database connection failed
```bash
# Check MySQL service
sudo systemctl status mysql
sudo systemctl start mysql

# Check kredensial di application/config/database.php
```

### Error: Permission denied (uploads folder)
```bash
chmod 777 uploads/excel
chmod 777 application/cache
```

### Error: PHPExcel not found
```bash
composer require phpoffice/phpexcel
# atau install manual ke application/third_party/
```

### Error: 404 Not Found
```bash
# Enable mod_rewrite Apache
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess di root folder
```

---

## 🔧 Konfigurasi Tambahan

### Ubah Zona Waktu

Edit `application/config/config.php`:
```php
date_default_timezone_set('Asia/Jakarta');
```

### Aktifkan CSRF Protection

Edit `application/config/config.php`:
```php
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_token';
$config['csrf_cookie_name'] = 'csrf_cookie';
```

### Set Session di Database

Edit `application/config/config.php`:
```php
$config['sess_driver'] = 'database';
$config['sess_save_path'] = 'ci_sessions';
```

Buat tabel sessions:
```sql
CREATE TABLE ci_sessions (
    id varchar(128) NOT NULL,
    ip_address varchar(45) NOT NULL,
    timestamp int(10) unsigned DEFAULT 0 NOT NULL,
    data blob NOT NULL,
    KEY ci_sessions_timestamp (timestamp)
);
```

---

## 📞 Support & Dokumentasi

### Update Aplikasi:
```bash
git pull origin main
# Atau download update manual
```

### Backup Database:
```bash
mysqldump -u root -p survey_tni > backup_$(date +%Y%m%d).sql
```

### Restore Database:
```bash
mysql -u root -p survey_tni < backup_20250101.sql
```

---

## 📝 Changelog

### Version 1.0.0
- Initial release
- CRUD Pertanyaan & Kategori
- Form Survey Public
- Import/Export Excel
- Dashboard & Laporan
- Statistik & Grafik

---

**Developed for TNI Healthcare Services**
