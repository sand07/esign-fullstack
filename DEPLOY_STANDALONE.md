# Panduan Deploy E-Sign Standalone (Login Lokal)

Aplikasi E-Sign sekarang sudah mendukung login standalone tanpa tergantung pada aplikasi eksternal (OAuth SIMASTER/PTTPK).

## Perubahan yang Dilakukan

1. ✅ Menambahkan field `password` pada tabel User
2. ✅ Menambahkan CredentialsProvider di NextAuth
3. ✅ Membuat API endpoint `/api/auth/register` untuk registrasi user
4. ✅ Update halaman login dengan form username/password dan registrasi
5. ✅ Menambahkan script untuk membuat admin user

## Persyaratan Minimal

Untuk menjalankan aplikasi dalam mode standalone, Anda hanya perlu:

1. **Node.js** (v14 atau lebih tinggi)
2. **PostgreSQL** database
3. **MinIO** (opsional, hanya jika menggunakan fitur upload file)

Tidak perlu lagi:
- ❌ OAuth2 Server (SIMASTER/PTTPK)
- ❌ API Gateway eksternal
- ❌ BSrE API (kecuali untuk tanda tangan elektronik resmi)

## Langkah-langkah Deploy

### 1. Clone dan Install Dependencies

```bash
git clone <repository-url>
cd esign-fullstack
yarn install
# atau
npm install
```

### 2. Setup Environment Variables

Buat file `.env` dengan konfigurasi minimal:

```bash
# Database
DATABASE_URL="postgresql://user:password@localhost:5432/esign"

# NextAuth
NEXTAUTH_URL=http://localhost:3999
NEXTAUTH_SECRET=your-random-secret-key-here
SECRET=your-random-secret-key-here

# Base Path (jika deploy di subdirectory)
BASE_PATH=/esign

# MinIO (opsional, untuk upload file)
MINIO_ACCESS_KEY=minioadmin
MINIO_SECRET_KEY=minioadmin
MINIO_ENDPOINT=localhost
MINIO_PORT=9000

# OAuth dan BSrE (opsional, bisa dikosongkan untuk mode standalone)
# Hanya isi jika ingin mengaktifkan login SSO
MASTER_ID=
MASTER_SECRET=
MASTER_WELLKNOWN=
MASTER_SCOPE=
API_GATEWAY=
ESIGN_URL=
```

### 3. Migrasi Database

```bash
# Generate Prisma Client
npx prisma generate

# Jalankan migrasi
npx prisma migrate deploy

# Atau buat migrasi baru jika belum ada
npx prisma migrate dev --name add_local_auth
```

### 4. Buat Admin User

```bash
# Cara 1: Menggunakan script
ADMIN_USERNAME=admin ADMIN_PASSWORD=admin123 ADMIN_EMAIL=admin@esign.local node scripts/create-admin.js

# Cara 2: Atau registrasi langsung melalui web setelah aplikasi jalan
```

### 5. Build dan Jalankan Aplikasi

```bash
# Development mode
yarn dev

# Production mode
yarn build
yarn start
```

Aplikasi akan berjalan di: `http://localhost:3999/esign`

## Cara Menggunakan

### Registrasi User Baru

1. Buka browser ke `http://localhost:3999/esign/signin`
2. Klik tab **"Registrasi"**
3. Isi form:
   - Username (wajib, minimal 3 karakter)
   - Email (wajib, format email valid)
   - Password (wajib, minimal 6 karakter)
   - Nama Lengkap (opsional)
   - NIK (opsional)
   - NIP (opsional)
4. Klik tombol **"Daftar"**
5. Setelah berhasil, akan otomatis login dan redirect ke dashboard

### Login User

1. Buka browser ke `http://localhost:3999/esign/signin`
2. Di tab **"Login Lokal"**:
   - Masukkan username atau email
   - Masukkan password
3. Klik tombol **"Login"**

### Login dengan SSO (Opsional)

Jika Anda sudah mengkonfigurasi OAuth di `.env`, tab **"Login SSO"** akan muncul dengan tombol:
- Login with SIMASTER
- Login with PTT-PK

## Mode Deployment

### Mode 1: Standalone Penuh (Tanpa SSO)

Kosongkan semua variable OAuth di `.env`:
```bash
MASTER_ID=
MASTER_SECRET=
MASTER_WELLKNOWN=
MASTER_SCOPE=
PTTPK_ID=
PTTPK_SECRET=
PTTPK_WELLKNOWN=
PTTPK_SCOPE=
```

Aplikasi hanya akan menampilkan login lokal.

### Mode 2: Hybrid (Lokal + SSO)

Isi variable OAuth di `.env`, maka akan ada 3 pilihan login:
1. Login Lokal (username/password)
2. Registrasi
3. Login SSO (SIMASTER/PTTPK)

## Struktur User

User yang dibuat melalui registrasi lokal memiliki:
- `group`: "LOCAL"
- `role`: "USER" (default) atau "ADMIN" (untuk admin)
- `password`: terenkripsi menggunakan bcrypt

## Keamanan

1. **Password**: Menggunakan bcrypt dengan salt round 10
2. **Session**: Menggunakan JWT dari NextAuth
3. **Login History**: Setiap login dicatat di tabel History

## Troubleshooting

### Error: bcryptjs not found
```bash
npm install bcryptjs
# atau
yarn add bcryptjs
```

### Error: Prisma Client tidak update
```bash
npx prisma generate
```

### Error: Database migration
```bash
# Reset database (HATI-HATI: akan menghapus semua data)
npx prisma migrate reset

# Atau buat migrasi baru
npx prisma migrate dev
```

### Login gagal terus
- Pastikan username/email benar
- Pastikan password minimal 6 karakter
- Cek console browser untuk error detail
- Cek logs server untuk error database

## Upgrade dari Versi Lama

Jika Anda sudah punya aplikasi yang jalan dengan OAuth:

1. Backup database terlebih dahulu
2. Pull kode terbaru
3. Install dependencies: `yarn install`
4. Jalankan migrasi: `npx prisma migrate deploy`
5. Restart aplikasi

User lama yang login via OAuth tetap bisa login seperti biasa. User baru bisa memilih login lokal atau SSO.

## Fitur yang Masih Memerlukan Eksternal API

Beberapa fitur masih memerlukan koneksi ke API eksternal:
- ❌ Tanda tangan elektronik resmi (BSrE)
- ❌ Sinkronisasi data pegawai dari SIMASTER/PTTPK

Untuk development/testing tanpa fitur tersebut, aplikasi tetap bisa berjalan normal dengan login lokal.

## Support

Jika ada masalah, silakan buat issue di:
https://github.com/taufiqurrohmansuwarto/esign-fullstack/issues
