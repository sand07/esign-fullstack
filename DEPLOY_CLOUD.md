# ☁️ Deploy E-Sign ke Cloud (GRATIS via GitHub)

Panduan deploy aplikasi E-Sign ke platform cloud yang terintegrasi dengan GitHub.

## 🎯 Platform Cloud yang Didukung

| Platform | Database | Harga Free Tier | Best For |
|----------|----------|-----------------|----------|
| **Vercel** | External (Neon, Supabase) | Unlimited | Next.js apps (recommended) |
| **Railway** | PostgreSQL included | $5 credit/month | Full-stack with DB |
| **Render** | PostgreSQL included | 90 hari free | Production apps |
| **Fly.io** | PostgreSQL included | Free tier | Docker apps |

---

## 🚀 Opsi 1: Vercel (PALING MUDAH)

Vercel dibuat oleh pembuat Next.js, jadi **paling optimal** untuk aplikasi Next.js.

### Langkah 1: Persiapan

**A. Push ke GitHub** (jika belum):
```bash
cd /home/user/esign-fullstack

# Pastikan repository sudah ada di GitHub
git remote -v

# Push semua perubahan
git push origin main
# atau: git push origin master
```

**B. Setup Database External** (pilih salah satu):

#### Opsi A: Neon (PostgreSQL Serverless - GRATIS)

1. Daftar di https://neon.tech (login dengan GitHub)
2. Create New Project → Beri nama "esign-db"
3. Copy **Connection String**:
   ```
   postgresql://username:password@ep-xxx.region.aws.neon.tech/esign?sslmode=require
   ```

#### Opsi B: Supabase (PostgreSQL + Storage - GRATIS)

1. Daftar di https://supabase.com (login dengan GitHub)
2. New Project → Beri nama "esign"
3. Settings → Database → Copy **Connection String** (Pooling mode):
   ```
   postgresql://postgres:password@db.xxx.supabase.co:5432/postgres
   ```

### Langkah 2: Deploy ke Vercel

**Via Web Dashboard:**

1. Buka https://vercel.com
2. Login dengan GitHub
3. **Import Git Repository**
4. Pilih repository: `esign-fullstack`
5. Configure Project:
   ```
   Framework Preset: Next.js
   Root Directory: ./
   Build Command: yarn build
   Output Directory: .next
   Install Command: yarn install
   ```

6. **Environment Variables** - Klik "Add" dan isi:

   ```bash
   # Database (dari Neon/Supabase)
   DATABASE_URL=postgresql://...

   # NextAuth (PENTING: ganti dengan domain Vercel Anda)
   NEXTAUTH_URL=https://your-app-name.vercel.app
   NEXT_PUBLIC_NEXTAUTH_URL=https://your-app-name.vercel.app

   # Secret (generate random)
   NEXTAUTH_SECRET=your-random-secret-32-chars-minimum
   SECRET=your-random-secret-32-chars-minimum

   # Base Path
   BASE_PATH=/esign

   # Node Environment
   NODE_ENV=production
   ```

   **Generate secret:**
   ```bash
   openssl rand -base64 32
   ```

7. **Deploy** - Klik tombol Deploy!

### Langkah 3: Post-Deploy Setup

**A. Run Database Migration:**

Setelah deploy sukses, buka terminal lokal:

```bash
# Set DATABASE_URL dari Neon/Supabase
export DATABASE_URL="postgresql://..."

# Run migration
npx prisma migrate deploy
```

**B. Create Admin User:**

```bash
# Via Vercel CLI
npx vercel env pull .env.production
ADMIN_USERNAME=admin ADMIN_PASSWORD=admin123 node scripts/create-admin.js

# Atau via Prisma Studio
npx prisma studio
# Buat user manual dengan password terenkripsi
```

**C. Akses Aplikasi:**
```
https://your-app-name.vercel.app/esign/signin
```

### Langkah 4: Custom Domain (Opsional)

1. Buka Vercel Dashboard → Settings → Domains
2. Add Domain → Masukkan domain Anda: `esign.yourdomain.com`
3. Update DNS di domain provider Anda
4. Update `NEXTAUTH_URL` di Environment Variables

---

## 🚂 Opsi 2: Railway (DATABASE INCLUDED)

Railway menyediakan PostgreSQL database dalam satu platform.

### Langkah 1: Deploy

1. Buka https://railway.app
2. Login dengan GitHub
3. **New Project** → **Deploy from GitHub repo**
4. Pilih repository: `esign-fullstack`
5. Railway akan otomatis detect Next.js

### Langkah 2: Add PostgreSQL

1. Di project → **New** → **Database** → **PostgreSQL**
2. Railway akan otomatis create database dan set `DATABASE_URL`

### Langkah 3: Environment Variables

1. Klik service "esign-fullstack"
2. **Variables** tab
3. Add variables:

   ```bash
   # Database URL (sudah otomatis dari PostgreSQL service)
   # DATABASE_URL=${{Postgres.DATABASE_URL}}

   # NextAuth
   NEXTAUTH_URL=${{RAILWAY_PUBLIC_DOMAIN}}
   NEXT_PUBLIC_NEXTAUTH_URL=${{RAILWAY_PUBLIC_DOMAIN}}
   NEXTAUTH_SECRET=your-random-secret-32-chars
   SECRET=your-random-secret-32-chars

   # Base Path
   BASE_PATH=/esign

   # Node
   NODE_ENV=production
   ```

### Langkah 4: Run Migration

1. Di Railway Dashboard → Service → **Settings**
2. **Deploy Hooks** → Add custom script

   Atau manual via CLI:
   ```bash
   # Install Railway CLI
   npm install -g @railway/cli

   # Login
   railway login

   # Link project
   railway link

   # Run migration
   railway run npx prisma migrate deploy

   # Create admin
   railway run node -e "
   const bcrypt = require('bcryptjs');
   const { PrismaClient } = require('@prisma/client');
   const prisma = new PrismaClient();
   // ... create admin code
   "
   ```

### Langkah 5: Generate Public Domain

1. Settings → **Generate Domain**
2. Akses: `https://your-app-name.railway.app/esign/signin`

**Free Tier Railway:**
- $5 credit/month (500 jam runtime)
- Cukup untuk development/testing

---

## 🎨 Opsi 3: Render (90 HARI FREE)

### Langkah 1: Create PostgreSQL Database

1. Buka https://render.com
2. Login dengan GitHub
3. **New** → **PostgreSQL**
4. Name: `esign-db`
5. Plan: Free
6. Create Database
7. Copy **Internal Database URL**

### Langkah 2: Create Web Service

1. **New** → **Web Service**
2. Connect repository: `esign-fullstack`
3. Configure:
   ```
   Name: esign-app
   Environment: Node
   Region: Singapore (atau terdekat)
   Branch: main
   Build Command: yarn install && npx prisma generate && yarn build
   Start Command: npx prisma migrate deploy && yarn start
   ```

4. Plan: **Free**

### Langkah 3: Environment Variables

Add di Environment section:

```bash
DATABASE_URL=postgresql://...  (dari database yang dibuat)
NEXTAUTH_URL=https://esign-app.onrender.com
NEXT_PUBLIC_NEXTAUTH_URL=https://esign-app.onrender.com
NEXTAUTH_SECRET=your-random-secret
SECRET=your-random-secret
BASE_PATH=/esign
NODE_ENV=production
```

### Langkah 4: Deploy

1. Click **Create Web Service**
2. Render akan otomatis build dan deploy
3. Tunggu ~5-10 menit
4. Akses: `https://esign-app.onrender.com/esign/signin`

**Catatan Render Free:**
- Service sleep setelah 15 menit inactive
- Database free 90 hari (setelah itu $7/bulan)

---

## 🐳 Opsi 4: Fly.io (DOCKER)

Untuk yang suka Docker dan ingin kontrol penuh.

### Langkah 1: Install Fly CLI

```bash
curl -L https://fly.io/install.sh | sh
```

### Langkah 2: Login dan Setup

```bash
# Login
flyctl auth login

# Launch app
cd /home/user/esign-fullstack
flyctl launch

# Jawab pertanyaan:
# - App name: esign-app
# - Region: Singapore
# - PostgreSQL: Yes (akan create database)
# - Deploy: No (belum)
```

### Langkah 3: Configure

File `fly.toml` akan dibuat otomatis. Edit jika perlu:

```toml
app = "esign-app"
primary_region = "sin"

[build]
  dockerfile = "Dockerfile"

[env]
  BASE_PATH = "/esign"
  NODE_ENV = "production"
  PORT = "3999"

[http_service]
  internal_port = 3999
  force_https = true
  auto_stop_machines = true
  auto_start_machines = true
  min_machines_running = 0

[[services]]
  protocol = "tcp"
  internal_port = 3999

  [[services.ports]]
    port = 80
    handlers = ["http"]

  [[services.ports]]
    port = 443
    handlers = ["tls", "http"]
```

### Langkah 4: Set Secrets

```bash
# Database URL (dari PostgreSQL yang dibuat)
flyctl secrets set DATABASE_URL="postgresql://..."

# NextAuth secrets
flyctl secrets set NEXTAUTH_SECRET="$(openssl rand -base64 32)"
flyctl secrets set SECRET="$(openssl rand -base64 32)"
flyctl secrets set NEXTAUTH_URL="https://esign-app.fly.dev"
```

### Langkah 5: Deploy

```bash
# Deploy
flyctl deploy

# Run migration
flyctl ssh console
npx prisma migrate deploy
exit

# Akses
# https://esign-app.fly.dev/esign/signin
```

---

## 📊 Perbandingan Platform

| Feature | Vercel | Railway | Render | Fly.io |
|---------|--------|---------|--------|--------|
| **Ease of Use** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐⭐ |
| **Next.js Optimization** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐ |
| **Database Included** | ❌ | ✅ | ✅ | ✅ |
| **Free Tier** | Unlimited | $5/month | 90 days | Limited |
| **Auto Sleep** | ❌ | ❌ | ✅ | ✅ |
| **Custom Domain** | ✅ Free | ✅ Paid | ✅ Free | ✅ Free |
| **Build Time** | Fast | Medium | Slow | Medium |
| **Best For** | Production | Dev/Testing | Hobby | Advanced |

---

## 🎯 Rekomendasi

### Untuk Production:
**Vercel + Neon/Supabase**
- Paling cepat dan stabil
- Next.js optimized
- Unlimited free tier
- Database external (lebih aman untuk production)

### Untuk Development/Testing:
**Railway**
- Setup paling mudah (database included)
- $5 credit cukup untuk testing
- Good developer experience

### Untuk Hobby/Personal:
**Render**
- 90 hari free trial
- Database included
- Mudah setup

### Untuk Advanced Users:
**Fly.io**
- Full Docker control
- Global distribution
- Auto-scaling

---

## 🔧 File yang Perlu Ditambahkan

### Untuk Vercel

Buat file `vercel.json`:

```json
{
  "buildCommand": "yarn build",
  "devCommand": "yarn dev",
  "installCommand": "yarn install",
  "framework": "nextjs",
  "regions": ["sin1"],
  "env": {
    "NODE_ENV": "production"
  }
}
```

### Untuk Railway

Buat file `railway.json`:

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "yarn install && npx prisma generate && yarn build"
  },
  "deploy": {
    "startCommand": "npx prisma migrate deploy && yarn start",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

### Untuk Render

Buat file `render.yaml`:

```yaml
services:
  - type: web
    name: esign-app
    env: node
    region: singapore
    plan: free
    buildCommand: yarn install && npx prisma generate && yarn build
    startCommand: npx prisma migrate deploy && yarn start
    envVars:
      - key: NODE_ENV
        value: production
      - key: BASE_PATH
        value: /esign
      - key: DATABASE_URL
        fromDatabase:
          name: esign-db
          property: connectionString
      - key: NEXTAUTH_URL
        sync: false
      - key: NEXTAUTH_SECRET
        generateValue: true
      - key: SECRET
        generateValue: true

databases:
  - name: esign-db
    databaseName: esign
    user: esign
    plan: free
```

---

## 🚀 Quick Deploy Commands

### Vercel

```bash
# Install Vercel CLI
npm i -g vercel

# Login
vercel login

# Deploy
vercel

# Production
vercel --prod
```

### Railway

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# Deploy
railway up
```

### Render

```bash
# Deploy via Dashboard (lebih mudah)
# Atau gunakan Render CLI (beta)
```

### Fly.io

```bash
# Install Fly CLI
curl -L https://fly.io/install.sh | sh

# Login
flyctl auth login

# Launch and deploy
flyctl launch
```

---

## 🧪 Testing Cloud Deployment

Setelah deploy, test:

1. **Health Check:**
   ```bash
   curl https://your-app.vercel.app/api/health
   ```

2. **Login Page:**
   ```
   https://your-app.vercel.app/esign/signin
   ```

3. **Registrasi:**
   - Buka halaman signin
   - Tab "Registrasi"
   - Daftar user baru
   - Test login

4. **Database:**
   ```bash
   # Connect ke database cloud
   psql $DATABASE_URL -c "SELECT * FROM \"User\" LIMIT 5;"
   ```

---

## 🔒 Security Checklist

Sebelum production:

- [ ] Generate strong `NEXTAUTH_SECRET` (32+ chars random)
- [ ] Update `NEXTAUTH_URL` dengan domain production
- [ ] Enable database SSL (sudah default di Neon/Supabase)
- [ ] Setup database backup
- [ ] Enable rate limiting
- [ ] Monitor error logs
- [ ] Setup uptime monitoring (UptimeRobot, BetterStack)

---

## 💰 Biaya Estimasi

### Vercel + Neon (GRATIS)
```
Vercel: $0/month (unlimited)
Neon Database: $0/month (3GB storage, 0.5GB RAM)
Total: $0/month
```

### Railway (Development)
```
$5 credit/month (~500 hours runtime)
Total: $0/month (dengan credit)
```

### Render (90 hari)
```
Free 90 hari
Setelah itu: $7/month (database)
```

### Production (Rekomendasi)
```
Vercel Pro: $20/month (untuk custom domain + better limits)
Neon Scale: $19/month (lebih banyak storage)
Total: ~$40/month
```

---

## 📚 Resource Links

- **Vercel Docs**: https://vercel.com/docs
- **Railway Docs**: https://docs.railway.app
- **Render Docs**: https://render.com/docs
- **Fly.io Docs**: https://fly.io/docs
- **Neon**: https://neon.tech
- **Supabase**: https://supabase.com

---

## 🎉 Kesimpulan

**Untuk deploy cepat dan gratis:**
1. Push code ke GitHub
2. Deploy ke Vercel (5 menit)
3. Setup database di Neon (3 menit)
4. Run migration
5. Create admin user
6. ✅ Done!

**Total waktu: ~15 menit** dan aplikasi Anda sudah online! 🚀
