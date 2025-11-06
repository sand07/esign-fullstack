# 🚀 Quick Start Guide - E-Sign Standalone

Panduan cepat untuk langsung menjalankan aplikasi E-Sign dengan login standalone.

---

## ⚡ Super Quick Start (5 Menit)

### 1. Masuk ke Folder

```bash
cd /home/user/esign-fullstack
```

### 2. Install Dependencies

```bash
# Dengan yarn (recommended)
yarn install

# ATAU dengan npm
npm install
```

**Jika gagal karena network, coba:**
```bash
yarn install --network-timeout 600000
# atau
npm install --legacy-peer-deps
```

### 3. Setup Database

```bash
# Start PostgreSQL (jika belum jalan)
sudo systemctl start postgresql

# Buat database
sudo -u postgres psql << EOF
CREATE DATABASE esign_dev;
CREATE USER esign WITH PASSWORD 'esign123';
GRANT ALL PRIVILEGES ON DATABASE esign_dev TO esign;
\q
