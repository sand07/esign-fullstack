# 🧪 Testing Deployment E-Sign

Panduan untuk testing deployment aplikasi E-Sign sebelum production.

## 📋 Quick Testing Checklist

### ✅ 1. Testing Development Mode (Lokal)

```bash
# Clone repository
git clone https://github.com/sand07/esign-fullstack.git
cd esign-fullstack

# Install dependencies
yarn install  # atau npm install

# Setup .env minimal (untuk testing tanpa database dulu)
cat > .env << 'EOF'
NEXTAUTH_URL=http://localhost:3999
NEXTAUTH_SECRET=test-secret-minimum-32-characters-for-testing-only
SECRET=test-secret-minimum-32-characters-for-testing-only
BASE_PATH=/esign
EOF

# Test build
yarn build

# Jika build sukses, berarti konfigurasi Next.js OK ✅
```

**Expected Output:**
```
✓ Compiled successfully
✓ Linting and checking validity of types
✓ Collecting page data
✓ Generating static pages (x/x)
✓ Finalizing page optimization
```

### ✅ 2. Testing Database Connection

```bash
# Setup PostgreSQL (Ubuntu/Debian)
sudo apt install postgresql postgresql-contrib -y
sudo systemctl start postgresql

# Buat database test
sudo -u postgres psql << EOF
CREATE DATABASE esign_test;
CREATE USER esign_test WITH PASSWORD 'test123';
GRANT ALL PRIVILEGES ON DATABASE esign_test TO esign_test;
\q
EOF

# Update .env dengan database URL
echo "DATABASE_URL=postgresql://esign_test:test123@localhost:5432/esign_test" >> .env

# Test Prisma connection
npx prisma db push

# Jika sukses, berarti database connection OK ✅
```

**Expected Output:**
```
✔ Generated Prisma Client to ./node_modules/@prisma/client
✔ The database is now in sync with your schema
```

### ✅ 3. Testing Migrations

```bash
# Generate Prisma Client
npx prisma generate

# Run migrations
npx prisma migrate deploy

# Verify database tables
npx prisma studio
# Browser akan terbuka di http://localhost:5555
# Check apakah semua tables ada (User, Document, dll) ✅
```

### ✅ 4. Testing Admin Creation

```bash
# Create admin user
ADMIN_USERNAME=testadmin \
ADMIN_PASSWORD=test123 \
ADMIN_EMAIL=test@test.com \
node scripts/create-admin.js
```

**Expected Output:**
```
✅ Admin berhasil dibuat!
Username: testadmin
Email: test@test.com
Password: test123
```

### ✅ 5. Testing Application Start

```bash
# Development mode
yarn dev

# Atau production mode
yarn build
yarn start
```

**Testing di Browser:**
1. Buka: http://localhost:3999/esign/signin
2. Cek apakah ada 3 tabs: Login Lokal, Registrasi, Login SSO
3. Test registrasi user baru
4. Test login dengan user yang baru dibuat
5. Test login dengan admin (testadmin/test123)

**Expected:**
- ✅ Halaman login muncul
- ✅ Form registrasi ada
- ✅ Bisa registrasi user baru
- ✅ Bisa login
- ✅ Redirect ke dashboard setelah login

### ✅ 6. Testing Docker Build (Local)

```bash
# Test Dockerfile build
docker build -t esign-test .

# Jika sukses, image akan dibuat ✅
docker images | grep esign-test
```

**Expected Output:**
```
esign-test   latest   abc123def456   Just now   XXX MB
```

### ✅ 7. Testing Docker Compose

```bash
# Copy environment template
cp .env.docker .env

# Edit .env dan sesuaikan (minimal ubah passwords)
nano .env

# Start services
docker-compose up -d

# Check services status
docker-compose ps
```

**Expected Output:**
```
NAME                COMMAND                  SERVICE    STATUS
esign-postgres      "docker-entrypoint..."   postgres   Up (healthy)
esign-minio         "/usr/bin/docker..."     minio      Up (healthy)
esign-app           "node server.js"         app        Up (healthy)
esign-nginx         "/docker-entrypoin..."   nginx      Up (healthy)
```

**Testing:**
```bash
# Check logs
docker-compose logs app

# Test connection
curl http://localhost/esign/signin

# Test database
docker-compose exec postgres psql -U esign -d esign_production -c "\dt"

# Test MinIO
curl http://localhost:9001  # MinIO console
```

### ✅ 8. Testing PM2 Setup

```bash
# Install PM2
sudo npm install -g pm2

# Start with PM2
pm2 start ecosystem.config.js

# Check status
pm2 status
pm2 logs esign-app
```

**Expected Output:**
```
┌─────┬──────────────┬─────────────┬─────────┬─────────┬──────────┬────────┐
│ id  │ name         │ namespace   │ version │ mode    │ pid      │ status │
├─────┼──────────────┼─────────────┼─────────┼─────────┼──────────┼────────┤
│ 0   │ esign-app    │ default     │ N/A     │ cluster │ xxxxx    │ online │
└─────┴──────────────┴─────────────┴─────────┴─────────┴──────────┴────────┘
```

### ✅ 9. Testing Nginx Configuration

```bash
# Test nginx config syntax
sudo nginx -t

# Jika OK:
nginx: configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
```

### ✅ 10. Testing Security

```bash
# Check exposed ports
sudo netstat -tulpn | grep -E '3999|5432|9000'

# Test firewall
sudo ufw status

# Test SSL (jika sudah setup)
curl -I https://your-domain.com/esign
```

## 🔍 Automated Testing Script

Saya sudah siapkan script untuk automated testing:

```bash
# Run automated tests
bash scripts/test-deployment.sh
```

Tunggu, saya buatkan scriptnya dulu...

