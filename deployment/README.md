# 📁 Deployment Files

Folder ini berisi file-file konfigurasi untuk deployment aplikasi E-Sign.

## 📋 Isi Folder

### 1. **nginx.conf**
Konfigurasi Nginx untuk reverse proxy.

**Penggunaan:**
```bash
sudo cp deployment/nginx.conf /etc/nginx/sites-available/esign
sudo ln -s /etc/nginx/sites-available/esign /etc/nginx/sites-enabled/
sudo nano /etc/nginx/sites-available/esign  # Edit domain dan path
sudo nginx -t
sudo systemctl reload nginx
```

### 2. **nginx-docker.conf**
Konfigurasi Nginx khusus untuk Docker environment.

**Penggunaan:**
- Sudah otomatis di-mount di docker-compose.yml
- Tidak perlu manual setup

### 3. **esign.service**
Systemd service file untuk menjalankan aplikasi tanpa PM2.

**Penggunaan:**
```bash
sudo cp deployment/esign.service /etc/systemd/system/
sudo nano /etc/systemd/system/esign.service  # Edit path dan user
sudo systemctl daemon-reload
sudo systemctl enable esign
sudo systemctl start esign
sudo systemctl status esign
```

### 4. **init-db.sql**
SQL script untuk inisialisasi database PostgreSQL.

**Penggunaan:**
- Di Docker: Otomatis dijalankan saat container pertama kali dibuat
- Manual: `psql -U postgres < deployment/init-db.sql`

## 🚀 Panduan Deploy

Lihat file lengkap di root folder:
- **CARA_DEPLOY.md** - Panduan lengkap deployment
- **DEPLOY_STANDALONE.md** - Panduan khusus mode standalone

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buat issue di GitHub.
