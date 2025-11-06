# 🏥 Aplikasi Survey Kepuasan Pasien TNI

Aplikasi berbasis web untuk mengukur tingkat kepuasan pasien prajurit TNI terhadap pelayanan kesehatan di Rumah Sakit/Klinik TNI.

## ✨ Fitur Utama

### 👨‍💼 Admin Panel
- ✅ Dashboard dengan statistik real-time
- ✅ Manajemen Kategori Pertanyaan
- ✅ Manajemen Pertanyaan Survey (CRUD)
- ✅ **Import Pertanyaan dari Excel**
- ✅ **Export Laporan ke Excel**
- ✅ Laporan Detail & Statistik
- ✅ Filter Laporan (Tanggal, Poli, Kategori)
- ✅ Grafik & Visualisasi Data

### 🏥 Form Survey Public
- ✅ Form isian untuk pasien
- ✅ Responsive design (Mobile friendly)
- ✅ Validasi data otomatis
- ✅ Halaman terima kasih

## 🚀 Quick Start

```bash
# 1. Clone/Download aplikasi
cd /var/www/html
git clone [repository-url] survey-tni

# 2. Import database
mysql -u root -p < database.sql

# 3. Konfigurasi
Edit application/config/database.php
Edit application/config/config.php (base_url)

# 4. Install PHPExcel
composer require phpoffice/phpexcel

# 5. Set permission
chmod 777 uploads/excel

# 6. Akses
http://localhost/survey-tni
http://localhost/survey-tni/admin (user: admin, pass: admin123)
```

## 📊 Screenshot

(Tambahkan screenshot di sini)

## 📚 Dokumentasi Lengkap

Baca file **INSTALASI.md** untuk panduan lengkap instalasi dan penggunaan.

## 🔐 Login Default

```
URL: /admin/login
Username: admin
Password: admin123
```

**⚠️ Wajib ganti password setelah install!**

## 🛠️ Tech Stack

- CodeIgniter 3.1.x
- MySQL 5.7+
- Bootstrap 4
- jQuery
- PHPExcel (Import/Export)
- Chart.js (Grafik)

## 📁 File Penting

| File | Deskripsi |
|------|-----------|
| `database.sql` | SQL Database |
| `INSTALASI.md` | Panduan instalasi |
| `MANUAL_PENGGUNAAN.md` | Manual untuk user |
| `FORMAT_EXCEL.xlsx` | Template import Excel |

## 📞 Support

Hubungi developer untuk bantuan teknis.

---

**Developed for TNI Healthcare Services**
Version: 1.0.0
