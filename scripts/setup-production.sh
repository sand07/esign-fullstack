#!/bin/bash

# Script Setup Production E-Sign Standalone
# Usage: bash scripts/setup-production.sh

set -e

echo "=========================================="
echo "  E-Sign Production Setup Script"
echo "=========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Functions
print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_info() {
    echo -e "ℹ️  $1"
}

# Check if running as root
if [ "$EUID" -eq 0 ]; then
    print_error "Jangan jalankan script ini sebagai root!"
    print_info "Jalankan sebagai user biasa: bash scripts/setup-production.sh"
    exit 1
fi

# Check Node.js
print_info "Checking Node.js..."
if ! command -v node &> /dev/null; then
    print_error "Node.js tidak ditemukan! Install Node.js terlebih dahulu."
    print_info "Lihat panduan di CARA_DEPLOY.md"
    exit 1
fi
NODE_VERSION=$(node --version)
print_success "Node.js version: $NODE_VERSION"

# Check PostgreSQL
print_info "Checking PostgreSQL..."
if ! command -v psql &> /dev/null; then
    print_error "PostgreSQL tidak ditemukan! Install PostgreSQL terlebih dahulu."
    exit 1
fi
print_success "PostgreSQL found"

# Check if .env exists
print_info "Checking environment configuration..."
if [ ! -f ".env" ]; then
    print_warning ".env file tidak ditemukan. Membuat dari .env.example..."
    cp .env.example .env
    print_info "Silakan edit file .env dan sesuaikan konfigurasi:"
    print_info "  - DATABASE_URL"
    print_info "  - NEXTAUTH_URL"
    print_info "  - NEXTAUTH_SECRET"
    print_info ""
    read -p "Apakah Anda sudah edit .env? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_error "Setup dibatalkan. Edit .env terlebih dahulu."
        exit 1
    fi
fi
print_success ".env file exists"

# Install dependencies
print_info "Installing dependencies..."
if command -v yarn &> /dev/null; then
    yarn install
    print_success "Dependencies installed with yarn"
else
    npm install
    print_success "Dependencies installed with npm"
fi

# Generate Prisma Client
print_info "Generating Prisma Client..."
npx prisma generate
print_success "Prisma Client generated"

# Run database migrations
print_info "Running database migrations..."
npx prisma migrate deploy
print_success "Database migrations completed"

# Build application
print_info "Building application for production..."
if command -v yarn &> /dev/null; then
    yarn build
else
    npm run build
fi
print_success "Application built successfully"

# Create admin user
print_info "Creating admin user..."
read -p "Admin username [admin]: " ADMIN_USER
ADMIN_USER=${ADMIN_USER:-admin}

read -sp "Admin password: " ADMIN_PASS
echo ""
if [ -z "$ADMIN_PASS" ]; then
    print_error "Password tidak boleh kosong!"
    exit 1
fi

read -p "Admin email [admin@esign.local]: " ADMIN_EMAIL
ADMIN_EMAIL=${ADMIN_EMAIL:-admin@esign.local}

ADMIN_USERNAME=$ADMIN_USER ADMIN_PASSWORD=$ADMIN_PASS ADMIN_EMAIL=$ADMIN_EMAIL node scripts/create-admin.js

if [ $? -eq 0 ]; then
    print_success "Admin user created successfully"
else
    print_warning "Admin user creation failed or already exists"
fi

# Setup PM2 (if available)
if command -v pm2 &> /dev/null; then
    print_info "PM2 detected. Setting up PM2 configuration..."

    # Create logs directory
    mkdir -p logs

    print_info "To start the application with PM2, run:"
    echo "  pm2 start ecosystem.config.js"
    echo "  pm2 save"
else
    print_warning "PM2 not found. Install PM2 for production:"
    print_info "  sudo npm install -g pm2"
fi

echo ""
echo "=========================================="
print_success "Setup completed successfully!"
echo "=========================================="
echo ""
print_info "Next steps:"
echo "1. Review your .env configuration"
echo "2. Start the application:"
if command -v pm2 &> /dev/null; then
    echo "   pm2 start ecosystem.config.js"
    echo "   pm2 save"
else
    if command -v yarn &> /dev/null; then
        echo "   yarn start"
    else
        echo "   npm start"
    fi
fi
echo "3. Setup Nginx reverse proxy (see CARA_DEPLOY.md)"
echo "4. Setup SSL certificate"
echo "5. Configure firewall"
echo ""
print_success "Happy deploying! 🚀"
