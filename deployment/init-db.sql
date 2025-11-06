-- Initial Database Setup Script for PostgreSQL
-- This script runs automatically when PostgreSQL container starts for the first time

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE esign_production TO esign;

-- You can add additional initialization SQL here
-- For example: create additional schemas, roles, etc.
