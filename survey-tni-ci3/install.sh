#!/bin/bash

################################################################################
# INSTALLER SCRIPT - Survey Kepuasan Pasien TNI CI3
#
# Script ini akan:
# 1. Download CodeIgniter 3.1.13
# 2. Setup struktur folder
# 3. Copy semua file aplikasi
# 4. Install PHPExcel via Composer
# 5. Setup database
# 6. Set permissions
################################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
CI_VERSION="3.1.13"
CI_URL="https://github.com/bcit-ci/CodeIgniter/archive/${CI_VERSION}.tar.gz"
TARGET_DIR="survey-tni-app"
CURRENT_DIR=$(pwd)

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Survey TNI CI3 - Installer Script${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Check if target directory exists
if [ -d "$TARGET_DIR" ]; then
    echo -e "${YELLOW}Warning: Directory '$TARGET_DIR' already exists!${NC}"
    read -p "Do you want to continue and overwrite? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${RED}Installation cancelled.${NC}"
        exit 1
    fi
    rm -rf "$TARGET_DIR"
fi

# Step 1: Download CodeIgniter
echo -e "${GREEN}[1/7] Downloading CodeIgniter ${CI_VERSION}...${NC}"
wget -q --show-progress "$CI_URL" -O ci.tar.gz
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: Failed to download CodeIgniter${NC}"
    exit 1
fi

# Step 2: Extract CodeIgniter
echo -e "${GREEN}[2/7] Extracting CodeIgniter...${NC}"
tar -xzf ci.tar.gz
mv "CodeIgniter-${CI_VERSION}" "$TARGET_DIR"
rm ci.tar.gz

cd "$TARGET_DIR"

# Step 3: Copy application files
echo -e "${GREEN}[3/7] Copying application files...${NC}"

# Copy controllers
echo "  - Copying controllers..."
cp -r "$CURRENT_DIR/application/controllers/"* application/controllers/

# Copy models
echo "  - Copying models..."
cp -r "$CURRENT_DIR/application/models/"* application/models/

# Copy views
echo "  - Copying views..."
cp -r "$CURRENT_DIR/application/views/"* application/views/

# Copy config files
echo "  - Copying config files..."
cp "$CURRENT_DIR/application/config/database.php" application/config/
cp "$CURRENT_DIR/application/config/autoload.php" application/config/
cp "$CURRENT_DIR/application/config/routes.php" application/config/

# Step 4: Install PHPExcel via Composer
echo -e "${GREEN}[4/7] Installing PHPExcel via Composer...${NC}"
if ! command -v composer &> /dev/null; then
    echo -e "${YELLOW}Warning: Composer not found. Installing Composer...${NC}"
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer || sudo mv composer.phar /usr/local/bin/composer
fi

cat > composer.json <<EOF
{
    "require": {
        "phpoffice/phpexcel": "^1.8"
    }
}
EOF

composer install --no-dev

# Update config to enable composer autoload
sed -i "s|\$config\['composer_autoload'\] = FALSE;|\$config['composer_autoload'] = FCPATH . 'vendor/autoload.php';|g" application/config/config.php

# Step 5: Setup database
echo -e "${GREEN}[5/7] Setting up database...${NC}"
echo ""
echo -e "${YELLOW}Database Configuration:${NC}"
read -p "MySQL Host [localhost]: " DB_HOST
DB_HOST=${DB_HOST:-localhost}

read -p "MySQL Username [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "MySQL Password: " DB_PASS
echo ""

read -p "Database Name [survey_tni]: " DB_NAME
DB_NAME=${DB_NAME:-survey_tni}

# Update database config
sed -i "s/'hostname' => 'localhost'/'hostname' => '$DB_HOST'/g" application/config/database.php
sed -i "s/'username' => 'root'/'username' => '$DB_USER'/g" application/config/database.php
sed -i "s/'password' => ''/'password' => '$DB_PASS'/g" application/config/database.php
sed -i "s/'database' => 'survey_tni'/'database' => '$DB_NAME'/g" application/config/database.php

# Create database and import schema
echo -e "${YELLOW}Creating database and importing schema...${NC}"
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if [ -f "$CURRENT_DIR/database.sql" ]; then
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$CURRENT_DIR/database.sql"
    echo -e "${GREEN}Database imported successfully!${NC}"
else
    echo -e "${RED}Warning: database.sql not found. Please import manually.${NC}"
fi

# Step 6: Set permissions
echo -e "${GREEN}[6/7] Setting permissions...${NC}"
chmod -R 755 application/
chmod -R 777 application/cache/
chmod -R 777 application/logs/

# Create uploads directory
mkdir -p uploads
chmod -R 777 uploads/

# Step 7: Create .htaccess
echo -e "${GREEN}[7/7] Creating .htaccess file...${NC}"
cat > .htaccess <<'EOF'
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
EOF

# Update base_url in config
echo ""
read -p "Enter your base URL (e.g., http://localhost/survey-tni-app/): " BASE_URL

sed -i "s|\$config\['base_url'\] = ''|\$config['base_url'] = '$BASE_URL'|g" application/config/config.php

cd "$CURRENT_DIR"

# Final summary
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Installation Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${BLUE}Installation Summary:${NC}"
echo -e "  - CodeIgniter ${CI_VERSION} installed"
echo -e "  - Application files copied"
echo -e "  - PHPExcel installed via Composer"
echo -e "  - Database created: ${DB_NAME}"
echo -e "  - Permissions set"
echo ""
echo -e "${BLUE}Default Admin Login:${NC}"
echo -e "  - URL: ${BASE_URL}admin"
echo -e "  - Username: ${YELLOW}admin${NC}"
echo -e "  - Password: ${YELLOW}admin123${NC}"
echo ""
echo -e "${YELLOW}IMPORTANT: Change the admin password after first login!${NC}"
echo ""
echo -e "${BLUE}Next Steps:${NC}"
echo -e "  1. Copy '${TARGET_DIR}' to your web server document root"
echo -e "  2. Configure your virtual host to point to this directory"
echo -e "  3. Access the application via your browser"
echo -e "  4. Login to admin panel and change default password"
echo ""
echo -e "${GREEN}Application Location: ${PWD}/${TARGET_DIR}${NC}"
echo ""
echo -e "${BLUE}For manual installation or troubleshooting, refer to INSTALASI.md${NC}"
echo ""
