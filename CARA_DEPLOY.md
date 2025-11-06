# 📖 Panduan Lengkap Deploy E-Sign Standalone

Panduan ini akan membantu Anda deploy aplikasi E-Sign dengan mode standalone (login lokal tanpa OAuth eksternal).

## 📋 Daftar Isi

1. [Persyaratan](#persyaratan)
2. [Deploy Development (Lokal)](#deploy-development-lokal)
3. [Deploy Production (Server)](#deploy-production-server)
4. [Deploy dengan Docker](#deploy-dengan-docker)
5. [Troubleshooting](#troubleshooting)

---

## 1️⃣ Persyaratan

### Software yang Dibutuhkan

| Software | Versi Minimal | Catatan |
|----------|---------------|---------|
| Node.js | 14.x | Rekomendasi: 18.x atau 20.x LTS |
| PostgreSQL | 12.x | Database utama |
| Yarn atau NPM | - | Package manager |
| Git | - | Untuk clone repository |

### Server Requirement (Production)

- **RAM**: Minimal 2GB (Rekomendasi: 4GB)
- **CPU**: 2 cores
- **Storage**: 20GB
- **OS**: Ubuntu 20.04/22.04, CentOS 7/8, atau Debian 10/11

---

## 2️⃣ Deploy Development (Lokal)

Deploy di komputer lokal untuk development/testing.

### Langkah 1: Clone Repository

```bash
git clone https://github.com/sand07/esign-fullstack.git
cd esign-fullstack
```

### Langkah 2: Install Dependencies

```bash
# Menggunakan yarn (rekomendasi)
yarn install

# ATAU menggunakan npm
npm install
```

⏱️ Estimasi: 5-10 menit

### Langkah 3: Setup Database PostgreSQL

**A. Install PostgreSQL (jika belum ada)**

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install postgresql postgresql-contrib

# CentOS/RHEL
sudo yum install postgresql-server postgresql-contrib
sudo postgresql-setup initdb
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

**B. Buat Database dan User**

```bash
# Masuk ke PostgreSQL
sudo -u postgres psql

# Buat database dan user (jalankan di psql)
CREATE DATABASE esign;
CREATE USER esignuser WITH PASSWORD 'password_anda';
GRANT ALL PRIVILEGES ON DATABASE esign TO esignuser;
\q
```

### Langkah 4: Konfigurasi Environment

```bash
# Copy file .env.example
cp .env.example .env

# Edit file .env
nano .env  # atau gunakan editor lain (vim, code, dll)
```

**Isi minimal untuk development:**

```bash
# Database
DATABASE_URL="postgresql://esignuser:password_anda@localhost:5432/esign"

# NextAuth (generate secret random)
NEXTAUTH_URL=http://localhost:3999
NEXTAUTH_SECRET=secret-random-minimal-32-karakter-abcdefghijklmnopqrstuvwxyz
SECRET=secret-random-minimal-32-karakter-abcdefghijklmnopqrstuvwxyz

# Base Path
BASE_PATH=/esign

# OAuth (kosongkan untuk mode standalone)
MASTER_ID=
MASTER_SECRET=
MASTER_WELLKNOWN=
MASTER_SCOPE=
API_GATEWAY=

# MinIO (opsional)
MINIO_ACCESS_KEY=
MINIO_SECRET_KEY=
MINIO_ENDPOINT=
MINIO_PORT=
```

💡 **Tips Generate Secret:**
```bash
# Cara 1: Menggunakan openssl
openssl rand -base64 32

# Cara 2: Menggunakan node
node -e "console.log(require('crypto').randomBytes(32).toString('base64'))"
```

### Langkah 5: Migrasi Database

```bash
# Generate Prisma Client
npx prisma generate

# Jalankan migrasi
npx prisma migrate dev --name initial_setup

# Atau jika sudah ada migrasi
npx prisma migrate deploy
```

✅ Database tables sudah dibuat!

### Langkah 6: Buat Admin User

```bash
# Set environment variables untuk admin
export ADMIN_USERNAME=admin
export ADMIN_PASSWORD=admin123
export ADMIN_EMAIL=admin@esign.local

# Jalankan script create admin
node scripts/create-admin.js
```

Output yang benar:
```
✅ Admin berhasil dibuat!
Username: admin
Email: admin@esign.local
Password: admin123

⚠️  PENTING: Segera ganti password setelah login pertama!
```

### Langkah 7: Jalankan Aplikasi

```bash
# Development mode (hot reload)
yarn dev

# Atau dengan npm
npm run dev
```

Aplikasi berjalan di: **http://localhost:3999/esign**

### Langkah 8: Testing Login

1. Buka browser: `http://localhost:3999/esign/signin`
2. Login dengan:
   - Username: `admin`
   - Password: `admin123`
3. Jika berhasil, akan redirect ke dashboard

🎉 **Selesai!** Aplikasi sudah jalan di development mode.

---

## 3️⃣ Deploy Production (Server)

Deploy ke server production (VPS, Cloud, On-Premise).

### A. Persiapan Server

#### 1. Update System

```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y

# CentOS/RHEL
sudo yum update -y
```

#### 2. Install Node.js

```bash
# Ubuntu/Debian - Install Node.js 20.x LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# CentOS/RHEL - Install Node.js 20.x LTS
curl -fsSL https://rpm.nodesource.com/setup_20.x | sudo bash -
sudo yum install -y nodejs

# Verifikasi
node --version  # harus v20.x.x
npm --version
```

#### 3. Install Yarn (Opsional)

```bash
sudo npm install -g yarn
```

#### 4. Install PostgreSQL

```bash
# Ubuntu/Debian
sudo apt install postgresql postgresql-contrib -y

# CentOS/RHEL
sudo yum install postgresql-server postgresql-contrib -y
sudo postgresql-setup initdb
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

#### 5. Install PM2 (Process Manager)

```bash
sudo npm install -g pm2
```

#### 6. Install Nginx (Web Server)

```bash
# Ubuntu/Debian
sudo apt install nginx -y

# CentOS/RHEL
sudo yum install nginx -y

# Start Nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

### B. Setup Aplikasi

#### 1. Buat User untuk Aplikasi

```bash
# Buat user esign (untuk keamanan, jangan jalankan sebagai root)
sudo useradd -m -s /bin/bash esign
sudo passwd esign

# Switch ke user esign
sudo su - esign
```

#### 2. Clone Repository

```bash
cd /home/esign
git clone https://github.com/sand07/esign-fullstack.git
cd esign-fullstack
```

#### 3. Install Dependencies

```bash
yarn install --production=false

# Atau dengan npm
npm install
```

#### 4. Setup Database

```bash
# Kembali ke user root
exit

# Buat database dan user
sudo -u postgres psql
```

Di PostgreSQL console:

```sql
CREATE DATABASE esign_production;
CREATE USER esign_app WITH PASSWORD 'password_sangat_kuat_123!@#';
GRANT ALL PRIVILEGES ON DATABASE esign_production TO esign_app;
\q
```

#### 5. Konfigurasi Environment

```bash
# Kembali ke user esign
sudo su - esign
cd /home/esign/esign-fullstack

# Copy .env.example
cp .env.example .env

# Edit .env untuk production
nano .env
```

**Konfigurasi Production (.env):**

```bash
# Database Production
DATABASE_URL="postgresql://esign_app:password_sangat_kuat_123!@#@localhost:5432/esign_production"

# NextAuth - PRODUCTION DOMAIN
NEXTAUTH_URL=https://your-domain.com/esign
NEXT_PUBLIC_NEXTAUTH_URL=https://your-domain.com/esign

# Secret - HARUS DIGANTI dengan random string yang kuat!
NEXTAUTH_SECRET=GANTI_DENGAN_SECRET_RANDOM_MINIMAL_32_KARAKTER_1234567890
SECRET=GANTI_DENGAN_SECRET_RANDOM_MINIMAL_32_KARAKTER_1234567890

# Base Path
BASE_PATH=/esign

# Node Environment
NODE_ENV=production

# OAuth (kosongkan untuk standalone mode)
MASTER_ID=
MASTER_SECRET=
MASTER_WELLKNOWN=
MASTER_SCOPE=
API_GATEWAY=
```

⚠️ **PENTING:** Ganti semua password dan secret dengan nilai yang kuat!

#### 6. Migrasi Database

```bash
# Pastid masih di /home/esign/esign-fullstack
npx prisma generate
npx prisma migrate deploy
```

#### 7. Build Aplikasi

```bash
# Build untuk production
yarn build

# Atau dengan npm
npm run build
```

⏱️ Estimasi: 5-15 menit tergantung server

#### 8. Buat Admin User

```bash
ADMIN_USERNAME=admin ADMIN_PASSWORD=AdminKuat123! ADMIN_EMAIL=admin@yourdomain.com node scripts/create-admin.js
```

### C. Setup PM2 (Process Manager)

#### 1. Buat File Ecosystem PM2

```bash
nano ecosystem.config.js
```

Isi dengan:

```javascript
module.exports = {
  apps: [{
    name: 'esign-app',
    script: 'yarn',
    args: 'start',
    cwd: '/home/esign/esign-fullstack',
    instances: 2,  // atau sesuai jumlah CPU
    exec_mode: 'cluster',
    env: {
      NODE_ENV: 'production',
      PORT: 3999
    },
    error_file: '/home/esign/logs/esign-error.log',
    out_file: '/home/esign/logs/esign-out.log',
    log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
    merge_logs: true,
    autorestart: true,
    max_memory_restart: '1G',
    watch: false
  }]
}
```

#### 2. Buat Folder Logs

```bash
mkdir -p /home/esign/logs
```

#### 3. Start Aplikasi dengan PM2

```bash
# Start aplikasi
pm2 start ecosystem.config.js

# Simpan konfigurasi PM2
pm2 save

# Setup PM2 startup (jalankan sebagai root)
exit  # keluar dari user esign
sudo env PATH=$PATH:/usr/bin pm2 startup systemd -u esign --hp /home/esign
```

#### 4. Check Status

```bash
sudo su - esign
pm2 status
pm2 logs esign-app
```

Output yang benar:
```
┌─────┬──────────────┬─────────────┬─────────┬─────────┬──────────┬────────┐
│ id  │ name         │ namespace   │ version │ mode    │ pid      │ status │
├─────┼──────────────┼─────────────┼─────────┼─────────┼──────────┼────────┤
│ 0   │ esign-app    │ default     │ 0.1.0   │ cluster │ 12345    │ online │
└─────┴──────────────┴─────────────┴─────────┴─────────┴──────────┴────────┘
```

### D. Setup Nginx Reverse Proxy

#### 1. Buat Konfigurasi Nginx

```bash
# Kembali ke root
exit
sudo nano /etc/nginx/sites-available/esign
```

Isi dengan:

```nginx
server {
    listen 80;
    server_name your-domain.com;  # Ganti dengan domain Anda

    # Redirect HTTP ke HTTPS (setelah SSL dipasang)
    # return 301 https://$server_name$request_uri;

    location /esign {
        proxy_pass http://localhost:3999/esign;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;

        # Timeout settings
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;

        # Buffer settings
        proxy_buffering on;
        proxy_buffer_size 4k;
        proxy_buffers 8 4k;
        proxy_busy_buffers_size 8k;
    }

    # Untuk Next.js static files
    location /_next/static {
        proxy_pass http://localhost:3999/_next/static;
        add_header Cache-Control "public, max-age=31536000, immutable";
    }

    # Client body size (untuk upload file)
    client_max_body_size 50M;
}
```

#### 2. Aktifkan Konfigurasi

```bash
# Buat symbolic link
sudo ln -s /etc/nginx/sites-available/esign /etc/nginx/sites-enabled/

# Test konfigurasi
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

#### 3. Setup SSL dengan Let's Encrypt (Opsional tapi Recommended)

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx -y

# Generate SSL certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal sudah otomatis diaktifkan
# Test renewal:
sudo certbot renew --dry-run
```

Nginx akan otomatis update konfigurasi untuk HTTPS.

### E. Setup Firewall

```bash
# UFW (Ubuntu/Debian)
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
sudo ufw status

# Firewalld (CentOS/RHEL)
sudo firewall-cmd --permanent --add-service=ssh
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

### F. Testing Production

1. Buka browser: `http://your-domain.com/esign/signin`
2. Login dengan admin credentials
3. Test fitur upload, sign, dll

### G. Monitoring & Maintenance

#### Melihat Logs

```bash
# PM2 logs
pm2 logs esign-app

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log

# System logs
sudo journalctl -u nginx -f
```

#### Restart Aplikasi

```bash
sudo su - esign
pm2 restart esign-app

# Atau restart semua
pm2 restart all
```

#### Update Aplikasi

```bash
sudo su - esign
cd /home/esign/esign-fullstack

# Pull update dari git
git pull origin main  # atau branch yang sesuai

# Install dependencies baru (jika ada)
yarn install

# Migrasi database (jika ada)
npx prisma migrate deploy

# Rebuild
yarn build

# Restart
pm2 restart esign-app
```

---

## 4️⃣ Deploy dengan Docker

### Langkah 1: Buat Dockerfile

Saya akan buatkan file Docker terpisah.

### Langkah 2: Buat docker-compose.yml

Saya akan buatkan file docker-compose terpisah.

### Langkah 3: Deploy

```bash
# Build dan jalankan
docker-compose up -d

# Lihat logs
docker-compose logs -f

# Stop
docker-compose down
```

---

## 5️⃣ Troubleshooting

### Problem: Port 3999 sudah digunakan

**Solusi:**
```bash
# Cari process yang pakai port 3999
sudo lsof -i :3999

# Kill process
sudo kill -9 <PID>

# Atau ganti port di .env dan ecosystem.config.js
```

### Problem: Database connection failed

**Solusi:**
1. Check PostgreSQL berjalan: `sudo systemctl status postgresql`
2. Check DATABASE_URL di `.env`
3. Check user dan password database
4. Check PostgreSQL accept local connections:
   ```bash
   sudo nano /etc/postgresql/*/main/pg_hba.conf
   # Pastikan ada: local all all trust atau md5
   sudo systemctl restart postgresql
   ```

### Problem: yarn install gagal di CentOS 7

**Solusi:**
```bash
# Install dependencies untuk canvas
sudo yum install gcc-c++ cairo-devel pango-devel libjpeg-turbo-devel giflib-devel

# Jalankan setup.sh
bash setup.sh
```

### Problem: Prisma migrate error

**Solusi:**
```bash
# Reset database (HATI-HATI: akan hapus data!)
npx prisma migrate reset

# Atau generate ulang
npx prisma generate
npx prisma db push
```

### Problem: NextAuth error "Invalid secret"

**Solusi:**
```bash
# Generate secret baru
openssl rand -base64 32

# Update di .env
NEXTAUTH_SECRET=hasil_generate_di_atas
```

### Problem: PM2 tidak start otomatis saat reboot

**Solusi:**
```bash
pm2 startup
pm2 save
# Ikuti instruksi yang muncul
```

### Problem: 502 Bad Gateway Nginx

**Solusi:**
1. Check aplikasi running: `pm2 status`
2. Check port benar: `netstat -tulpn | grep 3999`
3. Check Nginx config: `sudo nginx -t`
4. Restart semua:
   ```bash
   pm2 restart all
   sudo systemctl restart nginx
   ```

---

## 📞 Support

Jika mengalami masalah:
1. Check logs: `pm2 logs esign-app`
2. Check issue di GitHub
3. Buat issue baru dengan detail error

---

## 🔐 Security Checklist

- [ ] Ganti semua default password
- [ ] Generate secret random yang kuat
- [ ] Setup firewall
- [ ] Install SSL certificate
- [ ] Disable root login SSH
- [ ] Setup fail2ban
- [ ] Regular backup database
- [ ] Update sistem secara berkala

---

## ✅ Quick Reference

```bash
# Start aplikasi
pm2 start ecosystem.config.js

# Stop aplikasi
pm2 stop esign-app

# Restart aplikasi
pm2 restart esign-app

# Logs
pm2 logs esign-app

# Status
pm2 status

# Monitoring
pm2 monit

# Restart Nginx
sudo systemctl restart nginx

# Database backup
pg_dump -U esign_app esign_production > backup.sql

# Database restore
psql -U esign_app esign_production < backup.sql
```

---

**Selamat! Aplikasi E-Sign Anda sudah deploy! 🎉**
