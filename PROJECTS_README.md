# Repository Contents

This repository contains TWO separate applications:

## 1. E-Sign Application (Next.js)

**Location:** Root directory (main application)

**Description:** Electronic signature application for TNI with standalone authentication

**Key Features:**
- Next.js 13 with App Router
- Standalone authentication (username/password) + OAuth support
- PostgreSQL database with Prisma ORM
- User management and e-signature functionality

**Documentation:**
- `README.md` - Main documentation
- `DEPLOY_STANDALONE.md` - Standalone deployment guide
- `DEPLOY_CLOUD.md` - Cloud deployment options (Vercel, Railway, Render, Fly.io)
- `CARA_DEPLOY.md` - Complete deployment guide (Indonesian)
- `TESTING.md` - Testing guide

**Quick Start:**
```bash
# Install dependencies
npm install

# Setup database
npx prisma migrate dev

# Create admin user
node scripts/create-admin.js

# Run development server
npm run dev
```

**Tech Stack:**
- Next.js 13.5.4
- React 18
- NextAuth.js
- Prisma ORM
- PostgreSQL
- Tailwind CSS

---

## 2. Survey Kepuasan Pasien TNI (CodeIgniter 3)

**Location:** `survey-tni-ci3/` directory

**Description:** Survey application for TNI patient satisfaction with Excel import/export

**Key Features:**
- Complete CRUD for questions and categories
- Public survey form with rating system
- Excel import/export for questions and reports
- Comprehensive admin dashboard with statistics
- Chart.js visualizations
- DataTables for data management

**Documentation:**
- `survey-tni-ci3/README.md` - Application overview
- `survey-tni-ci3/INSTALASI.md` - Detailed installation guide
- `survey-tni-ci3/SUMMARY.md` - Feature summary
- `survey-tni-ci3/FORMAT_EXCEL_README.md` - Excel format guide

**Quick Start (Automated):**
```bash
cd survey-tni-ci3
chmod +x install.sh
./install.sh
```

**Quick Start (Manual):**
```bash
# 1. Import database
mysql -u root -p < survey-tni-ci3/database.sql

# 2. Configure database
# Edit application/config/database.php

# 3. Install PHPExcel
cd survey-tni-ci3
composer require phpoffice/phpexcel

# 4. Set permissions
chmod -R 777 application/cache
chmod -R 777 application/logs

# 5. Access application
http://localhost/survey-tni-ci3
```

**Default Admin Login:**
- Username: `admin`
- Password: `admin123`

**Tech Stack:**
- CodeIgniter 3.1.x
- PHP 7.4+
- MySQL/MariaDB
- PHPExcel (Excel operations)
- Bootstrap 4
- jQuery + DataTables
- Chart.js

---

## File Structure

```
esign-fullstack/
├── README.md                          # E-Sign documentation
├── package.json                       # E-Sign dependencies
├── pages/                             # E-Sign Next.js pages
├── prisma/                            # E-Sign database schema
├── public/                            # E-Sign static files
├── scripts/                           # E-Sign utility scripts
├── DEPLOY_*.md                        # E-Sign deployment guides
├── CARA_DEPLOY.md                     # E-Sign deployment guide (ID)
├── docker-compose.yml                 # E-Sign Docker config
├── Dockerfile                         # E-Sign Docker config
│
└── survey-tni-ci3/                    # Survey TNI Application
    ├── README.md                      # Survey app documentation
    ├── INSTALASI.md                   # Detailed installation
    ├── SUMMARY.md                     # Feature summary
    ├── database.sql                   # Database schema
    ├── install.sh                     # Automated installer
    ├── FORMAT_EXCEL_README.md         # Excel import guide
    │
    └── application/                   # CodeIgniter application
        ├── controllers/               # 6 controllers
        │   ├── Survey.php             # Public survey
        │   └── admin/                 # Admin controllers
        │       ├── Auth.php           # Login/logout
        │       ├── Dashboard.php      # Statistics
        │       ├── Pertanyaan.php     # Questions CRUD + Excel
        │       ├── Kategori.php       # Categories CRUD
        │       └── Laporan.php        # Reports + Excel export
        │
        ├── models/                    # 5 models
        │   ├── User_model.php
        │   ├── Kategori_model.php
        │   ├── Pertanyaan_model.php
        │   ├── Survey_model.php
        │   └── Laporan_model.php
        │
        ├── views/                     # 17 view files
        │   ├── templates/             # Layout templates
        │   ├── admin/                 # Admin panel views
        │   │   ├── login.php
        │   │   ├── dashboard.php
        │   │   ├── pertanyaan/        # Questions management
        │   │   ├── kategori/          # Categories management
        │   │   └── laporan/           # Reports & statistics
        │   │
        │   └── survey/                # Public survey views
        │       ├── index.php          # Landing page
        │       ├── form.php           # Survey form
        │       └── terima_kasih.php   # Thank you page
        │
        └── config/                    # Configuration files
            ├── database.php
            ├── autoload.php
            └── routes.php
```

---

## Which Application Should I Use?

### Use E-Sign Application if you need:
- Electronic signature functionality
- Document signing workflow
- User authentication (standalone or OAuth)
- Modern Next.js/React application
- PostgreSQL database
- Cloud deployment (Vercel, etc.)

### Use Survey TNI Application if you need:
- Patient satisfaction surveys
- Rating/feedback system
- Excel import/export
- Traditional PHP application
- MySQL database
- Question/category management
- Statistical reports and charts

---

## Development Guidelines

### E-Sign Application
- Follow Next.js 13 best practices
- Use Prisma for database operations
- Implement proper authentication checks
- Test both standalone and OAuth login

### Survey TNI Application
- Follow CodeIgniter 3 MVC pattern
- Use transactions for multi-table operations
- Validate all user inputs
- Test Excel import/export thoroughly
- Use PHPExcel for all Excel operations

---

## Deployment

### E-Sign Application
See deployment guides:
- `DEPLOY_STANDALONE.md` - For VPS/dedicated servers
- `DEPLOY_CLOUD.md` - For cloud platforms
- `CARA_DEPLOY.md` - Complete guide in Indonesian

### Survey TNI Application
**Option 1: Automated Installation**
```bash
cd survey-tni-ci3
./install.sh
```

**Option 2: Manual Installation**
Follow `survey-tni-ci3/INSTALASI.md` for detailed steps

---

## Support & Contact

For issues or questions:
1. Check the respective README files
2. Review installation guides
3. Check troubleshooting sections

---

## License

Both applications are provided as-is for TNI internal use.

---

## Change Log

### 2025-11-06
- Added standalone authentication to E-Sign app
- Created comprehensive deployment guides
- Built Survey Kepuasan Pasien TNI application
- Added Excel import/export functionality
- Created automated installer script
