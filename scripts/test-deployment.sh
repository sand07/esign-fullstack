#!/bin/bash

# Automated Deployment Testing Script
# Usage: bash scripts/test-deployment.sh

set +e  # Don't exit on error, we want to see all test results

echo "=========================================="
echo "  E-Sign Deployment Testing"
echo "=========================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Counters
PASSED=0
FAILED=0

# Functions
print_test() {
    echo -e "${BLUE}🧪 Testing: $1${NC}"
}

print_pass() {
    echo -e "${GREEN}✅ PASS: $1${NC}"
    ((PASSED++))
}

print_fail() {
    echo -e "${RED}❌ FAIL: $1${NC}"
    ((FAILED++))
}

print_skip() {
    echo -e "${YELLOW}⏭️  SKIP: $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

echo ""

# Test 1: Node.js
print_test "Node.js installation"
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    print_pass "Node.js found: $NODE_VERSION"
else
    print_fail "Node.js not found"
fi

# Test 2: npm/yarn
print_test "Package manager"
if command -v yarn &> /dev/null; then
    YARN_VERSION=$(yarn --version)
    print_pass "Yarn found: $YARN_VERSION"
elif command -v npm &> /dev/null; then
    NPM_VERSION=$(npm --version)
    print_pass "NPM found: $NPM_VERSION"
else
    print_fail "No package manager found (npm/yarn)"
fi

# Test 3: PostgreSQL
print_test "PostgreSQL"
if command -v psql &> /dev/null; then
    PG_VERSION=$(psql --version)
    print_pass "PostgreSQL found: $PG_VERSION"
else
    print_fail "PostgreSQL not found"
fi

# Test 4: package.json
print_test "package.json"
if [ -f "package.json" ]; then
    print_pass "package.json exists"
else
    print_fail "package.json not found"
fi

# Test 5: Prisma schema
print_test "Prisma schema"
if [ -f "prisma/schema.prisma" ]; then
    print_pass "Prisma schema exists"
else
    print_fail "Prisma schema not found"
fi

# Test 6: .env file
print_test "Environment configuration"
if [ -f ".env" ]; then
    print_pass ".env file exists"

    # Check required variables
    if grep -q "DATABASE_URL" .env && grep -q "NEXTAUTH_SECRET" .env; then
        print_pass "Required env variables present"
    else
        print_fail "Missing required env variables"
    fi
else
    print_fail ".env file not found"
    print_info "Run: cp .env.example .env"
fi

# Test 7: node_modules
print_test "Dependencies installation"
if [ -d "node_modules" ]; then
    print_pass "node_modules exists"
else
    print_fail "node_modules not found"
    print_info "Run: yarn install or npm install"
fi

# Test 8: Prisma Client
print_test "Prisma Client generation"
if [ -d "node_modules/.prisma/client" ]; then
    print_pass "Prisma Client generated"
else
    print_fail "Prisma Client not generated"
    print_info "Run: npx prisma generate"
fi

# Test 9: Build output
print_test "Next.js build"
if [ -d ".next" ]; then
    print_pass "Build directory exists"
else
    print_fail "Build not found"
    print_info "Run: yarn build or npm run build"
fi

# Test 10: Scripts
print_test "Deployment scripts"
SCRIPT_COUNT=0
if [ -f "scripts/create-admin.js" ]; then
    ((SCRIPT_COUNT++))
fi
if [ -f "scripts/setup-production.sh" ]; then
    ((SCRIPT_COUNT++))
fi
if [ $SCRIPT_COUNT -eq 2 ]; then
    print_pass "All deployment scripts present ($SCRIPT_COUNT/2)"
else
    print_fail "Missing deployment scripts ($SCRIPT_COUNT/2)"
fi

# Test 11: Deployment configs
print_test "Deployment configurations"
CONFIG_COUNT=0
if [ -f "ecosystem.config.js" ]; then
    ((CONFIG_COUNT++))
fi
if [ -f "Dockerfile" ]; then
    ((CONFIG_COUNT++))
fi
if [ -f "docker-compose.yml" ]; then
    ((CONFIG_COUNT++))
fi
if [ $CONFIG_COUNT -eq 3 ]; then
    print_pass "All deployment configs present ($CONFIG_COUNT/3)"
else
    print_fail "Missing deployment configs ($CONFIG_COUNT/3)"
fi

# Test 12: Docker (optional)
print_test "Docker (optional)"
if command -v docker &> /dev/null; then
    DOCKER_VERSION=$(docker --version)
    print_pass "Docker found: $DOCKER_VERSION"

    # Test docker-compose
    if command -v docker-compose &> /dev/null; then
        COMPOSE_VERSION=$(docker-compose --version)
        print_pass "Docker Compose found: $COMPOSE_VERSION"
    else
        print_skip "Docker Compose not found (optional)"
    fi
else
    print_skip "Docker not found (optional)"
fi

# Test 13: PM2 (optional)
print_test "PM2 (optional)"
if command -v pm2 &> /dev/null; then
    PM2_VERSION=$(pm2 --version)
    print_pass "PM2 found: $PM2_VERSION"
else
    print_skip "PM2 not found (optional)"
fi

# Test 14: Nginx (optional)
print_test "Nginx (optional)"
if command -v nginx &> /dev/null; then
    NGINX_VERSION=$(nginx -v 2>&1)
    print_pass "Nginx found: $NGINX_VERSION"
else
    print_skip "Nginx not found (optional)"
fi

# Test 15: Port availability
print_test "Port 3999 availability"
if command -v netstat &> /dev/null; then
    if netstat -tuln | grep -q ":3999"; then
        print_fail "Port 3999 is already in use"
    else
        print_pass "Port 3999 is available"
    fi
else
    print_skip "netstat not available"
fi

# Test 16: Database connection (if .env exists)
if [ -f ".env" ] && grep -q "DATABASE_URL" .env; then
    print_test "Database connection"
    if command -v npx &> /dev/null; then
        if npx prisma db execute --stdin < /dev/null 2>&1 | grep -q "Connected"; then
            print_pass "Database connection successful"
        else
            print_fail "Database connection failed"
            print_info "Check DATABASE_URL in .env"
        fi
    else
        print_skip "npx not available"
    fi
fi

# Summary
echo ""
echo "=========================================="
echo "  Test Summary"
echo "=========================================="
TOTAL=$((PASSED + FAILED))
echo -e "${GREEN}✅ Passed: $PASSED${NC}"
echo -e "${RED}❌ Failed: $FAILED${NC}"
echo -e "Total: $TOTAL"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed! Ready for deployment.${NC}"
    exit 0
else
    echo -e "${YELLOW}⚠️  Some tests failed. Please fix the issues above.${NC}"
    exit 1
fi
