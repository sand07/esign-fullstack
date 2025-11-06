# Survey Kepuasan Pasien TNI - Deployment Guide

## Quick Summary

Aplikasi survey kepuasan pasien untuk TNI yang lengkap dengan:
- Admin panel untuk mengelola pertanyaan dan kategori
- Form survey publik dengan sistem rating
- Import/export Excel
- Dashboard statistik dengan chart
- Laporan komprehensif

---

## Installation Options

### Option 1: Automated Installation (Recommended)

```bash
# 1. Clone atau download repository
cd survey-tni-ci3

# 2. Jalankan installer
chmod +x install.sh
./install.sh

# 3. Ikuti instruksi pada screen
# Script akan:
# - Download CodeIgniter 3.1.13
# - Setup struktur folder
# - Install PHPExcel via Composer
# - Import database
# - Set permissions
# - Configure base URL
```

### Option 2: Manual Installation

Lihat `INSTALASI.md` untuk panduan lengkap manual installation.

**Ringkasan langkah manual:**

1. **Download CodeIgniter 3.1.13**
   ```bash
   wget https://github.com/bcit-ci/CodeIgniter/archive/3.1.13.tar.gz
   tar -xzf 3.1.13.tar.gz
   ```

2. **Copy application files**
   ```bash
   cp -r survey-tni-ci3/application/* CodeIgniter-3.1.13/application/
   ```

3. **Install PHPExcel**
   ```bash
   cd CodeIgniter-3.1.13
   composer require phpoffice/phpexcel
   ```

4. **Import database**
   ```bash
   mysql -u root -p < survey-tni-ci3/database.sql
   ```

5. **Configure database**
   Edit `application/config/database.php`:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => 'your_password',
   'database' => 'survey_tni',
   ```

6. **Set permissions**
   ```bash
   chmod -R 755 application/
   chmod -R 777 application/cache/
   chmod -R 777 application/logs/
   ```

7. **Configure base URL**
   Edit `application/config/config.php`:
   ```php
   $config['base_url'] = 'http://localhost/survey-tni/';
   ```

---

## Default Login

**Admin Panel:** `http://your-domain/admin`

- **Username:** `admin`
- **Password:** `admin123`

**IMPORTANT:** Segera ubah password setelah login pertama kali!

---

## Features Overview

### Admin Panel

1. **Dashboard**
   - Total survey
   - Rata-rata kepuasan
   - Chart tren kepuasan
   - Top 5 pertanyaan terbaik/terburuk

2. **Manajemen Kategori**
   - CRUD kategori pertanyaan
   - Contoh: Pelayanan Medis, Fasilitas, Administrasi, dll.

3. **Manajemen Pertanyaan**
   - CRUD pertanyaan survey
   - 4 tipe jawaban: Skala (1-5), Pilihan Ganda, Text, Ya/Tidak
   - **Import Excel** - Import bulk pertanyaan
   - **Export Excel** - Download semua pertanyaan
   - Download template Excel

4. **Laporan**
   - Daftar semua survey dengan filter
   - Detail jawaban per responden
   - **Statistik lengkap** dengan chart
   - **Export ke Excel** dengan semua data

### Public Survey

1. **Landing Page** (`/survey`)
   - Informasi survey
   - Kategori pertanyaan
   - Button mulai survey

2. **Form Survey** (`/survey/form`)
   - Data responden (Nama, NRP, Pangkat, Unit, dll.)
   - Pertanyaan terkelompok per kategori
   - Rating buttons untuk skala 1-5
   - Komentar & saran
   - Validasi form otomatis

3. **Thank You Page**
   - Konfirmasi survey terkirim
   - Tampilkan hasil kepuasan responden

---

## Excel Import/Export

### Import Pertanyaan

1. Download template Excel dari admin panel
2. Isi sesuai format:
   - kategori_id (opsional)
   - pertanyaan (wajib)
   - tipe_jawaban (skala/pilihan_ganda/text/ya_tidak)
   - pilihan_jawaban (untuk pilihan_ganda)
   - is_wajib (1/0)
   - urutan (angka)
   - is_active (1/0)

3. Upload via menu Pertanyaan > Import Excel

### Export Laporan

Laporan dapat diekspor ke Excel dengan semua filter:
- Filter tanggal
- Filter kategori kepuasan
- Semua jawaban per pertanyaan
- Statistik lengkap

---

## Technical Specifications

### Server Requirements

- **PHP:** 7.4 or higher (8.0+ recommended)
- **MySQL:** 5.7+ or MariaDB 10.2+
- **Apache/Nginx** with mod_rewrite enabled
- **Composer** for PHPExcel installation
- **Memory:** Minimum 128MB PHP memory_limit

### Database Schema

7 Tables:
- `users` - Admin users
- `kategori_pertanyaan` - Question categories
- `pertanyaan` - Survey questions
- `responden` - Respondents data
- `survey` - Survey sessions
- `jawaban` - Individual answers
- `import_log` - Excel import logs

2 Views:
- `v_laporan_survey` - Survey reports view
- `v_statistik_pertanyaan` - Question statistics view

### Tech Stack

- **Framework:** CodeIgniter 3.1.x
- **Database:** MySQL/MariaDB
- **Frontend:** Bootstrap 4, jQuery 3.6
- **Charts:** Chart.js 3.9
- **Tables:** DataTables 1.11
- **Excel:** PHPExcel 1.8
- **Icons:** Font Awesome 5.15

---

## File Structure

```
survey-tni-ci3/
├── README.md                          # Overview
├── INSTALASI.md                       # Detailed installation (2000+ lines)
├── SUMMARY.md                         # Feature summary
├── DEPLOYMENT_GUIDE.md                # This file
├── FORMAT_EXCEL_README.md             # Excel format guide
├── database.sql                       # Complete database schema
├── install.sh                         # Automated installer script
│
└── application/
    ├── controllers/                   # 6 Controllers
    │   ├── Survey.php                 # Public survey form
    │   └── admin/
    │       ├── Auth.php               # Login/logout
    │       ├── Dashboard.php          # Statistics & charts
    │       ├── Pertanyaan.php         # Questions CRUD + Excel
    │       ├── Kategori.php           # Categories CRUD
    │       └── Laporan.php            # Reports + Excel export
    │
    ├── models/                        # 5 Models
    │   ├── User_model.php
    │   ├── Kategori_model.php
    │   ├── Pertanyaan_model.php
    │   ├── Survey_model.php
    │   └── Laporan_model.php
    │
    ├── views/                         # 17 Views
    │   ├── templates/                 # 4 layout templates
    │   ├── admin/                     # 9 admin panel views
    │   └── survey/                    # 3 public survey views
    │
    └── config/
        ├── database.php               # Database config
        ├── autoload.php               # Autoload config
        └── routes.php                 # URL routes
```

---

## Testing Checklist

### After Installation:

- [ ] Access public survey: `http://your-domain/survey`
- [ ] Fill and submit a test survey
- [ ] Login to admin: `http://your-domain/admin`
  - Username: admin
  - Password: admin123
- [ ] View dashboard statistics
- [ ] Check survey in laporan
- [ ] Add new category
- [ ] Add new question
- [ ] Download Excel template
- [ ] Import questions from Excel
- [ ] Export questions to Excel
- [ ] Export laporan to Excel
- [ ] View statistik with charts
- [ ] Change admin password
- [ ] Test all question types (skala, pilihan_ganda, text, ya_tidak)

---

## Troubleshooting

### 1. Database Connection Error

**Error:** "Unable to connect to database"

**Solution:**
- Check `application/config/database.php`
- Verify MySQL service is running
- Test connection: `mysql -u username -p`

### 2. 404 Not Found on URLs

**Error:** Admin/survey pages show 404

**Solution:**
- Enable mod_rewrite in Apache
- Check `.htaccess` file exists
- Verify `$config['base_url']` is correct

### 3. Excel Import Fails

**Error:** "Invalid file format"

**Solution:**
- Ensure file is .xlsx or .xls
- Download fresh template from admin
- Check all required columns are filled

### 4. Permission Denied Errors

**Error:** "Permission denied writing to cache/logs"

**Solution:**
```bash
chmod -R 777 application/cache/
chmod -R 777 application/logs/
```

### 5. PHPExcel Not Found

**Error:** "Class 'PHPExcel' not found"

**Solution:**
```bash
composer require phpoffice/phpexcel
# Update config.php:
$config['composer_autoload'] = FCPATH . 'vendor/autoload.php';
```

### 6. Charts Not Showing

**Error:** Charts on dashboard/statistik blank

**Solution:**
- Check browser console for JavaScript errors
- Ensure Chart.js loaded (check network tab)
- Verify AJAX endpoints returning data

---

## Production Deployment

### Security Checklist

- [ ] Change default admin password
- [ ] Update `$config['encryption_key']` in config.php
- [ ] Set `$config['log_threshold'] = 1` (production mode)
- [ ] Enable HTTPS
- [ ] Set proper file permissions (755 for dirs, 644 for files)
- [ ] Disable directory listing
- [ ] Configure CSRF protection
- [ ] Setup regular database backups
- [ ] Configure proper error logging
- [ ] Use environment-specific database credentials

### Performance Optimization

- [ ] Enable PHP OPcache
- [ ] Setup MySQL query caching
- [ ] Enable gzip compression
- [ ] Minify CSS/JS assets
- [ ] Setup CDN for static assets
- [ ] Configure proper caching headers
- [ ] Optimize database indexes
- [ ] Regular cleanup of old logs

### Backup Strategy

**Database Backup:**
```bash
# Daily backup
mysqldump -u username -p survey_tni > backup_$(date +%Y%m%d).sql
```

**Application Backup:**
```bash
# Weekly backup
tar -czf survey_tni_app_$(date +%Y%m%d).tar.gz /path/to/application
```

---

## Support & Maintenance

### Regular Maintenance

1. **Weekly:**
   - Check error logs
   - Review survey submissions
   - Monitor disk space

2. **Monthly:**
   - Database backup
   - Update statistics
   - Review and archive old surveys

3. **Quarterly:**
   - Security updates
   - Performance review
   - User feedback analysis

### Monitoring

Monitor these metrics:
- Survey completion rate
- Average satisfaction score
- Response time
- Database size
- Error rate

---

## Customization Guide

### Adding New Question Type

1. Modify `pertanyaan.tipe_jawaban` enum in database
2. Update `admin/Pertanyaan.php` controller
3. Add view handling in `survey/form.php`
4. Update validation in `Survey.php` controller

### Changing Theme Colors

Edit `application/views/templates/admin_header.php`:
```css
.navbar { background: #2d5016; }  /* TNI green */
```

Edit `application/views/templates/public_header.php`:
```css
body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
```

### Adding Email Notifications

1. Configure email in `config/email.php`
2. Load email library in controller
3. Send notification after survey submission

---

## FAQ

**Q: Berapa maksimal pertanyaan yang bisa dibuat?**
A: Tidak ada limit, tapi disarankan 20-30 pertanyaan untuk user experience yang baik.

**Q: Apakah bisa import pertanyaan dari aplikasi lain?**
A: Ya, siapkan Excel dengan format yang sesuai (lihat FORMAT_EXCEL_README.md).

**Q: Bagaimana cara backup data survey?**
A: Export laporan ke Excel atau backup database MySQL secara langsung.

**Q: Apakah bisa multi-language?**
A: Saat ini hanya Bahasa Indonesia. Bisa dikustomisasi dengan CI language files.

**Q: Berapa lama data survey disimpan?**
A: Tidak ada auto-delete. Setup archive policy sesuai kebutuhan organisasi.

---

## Contact & Support

Untuk pertanyaan atau issue:
1. Cek dokumentasi lengkap di `INSTALASI.md`
2. Review troubleshooting section di atas
3. Hubungi developer atau IT support TNI

---

## License

Aplikasi ini dibuat khusus untuk TNI (Tentara Nasional Indonesia).
Penggunaan terbatas untuk keperluan internal TNI.

---

**Last Updated:** 2025-11-06
**Version:** 1.0.0
