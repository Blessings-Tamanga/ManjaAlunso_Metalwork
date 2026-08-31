#!/bin/bash

# Laravel Docker Deployment Script
# This script automates the deployment process

set -e

ENV_FILE="${1:-.env.docker}"
COMPOSE_FILE="docker-compose.yml"

if [ "$ENV_FILE" = "production" ]; then
    ENV_FILE=".env.production"
    COMPOSE_FILE="docker-compose.production.yml"
fi

echo "========================================="
echo "Laravel Docker Deployment Script"
echo "Environment: $ENV_FILE"
echo "Compose File: $COMPOSE_FILE"
echo "========================================="

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Step 1: Check if Docker is running
echo -e "${YELLOW}[1/7] Checking Docker daemon...${NC}"
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}Docker is not running. Please start Docker first.${NC}"
    exit 1
fi
echo -e "${GREEN}✓ Docker is running${NC}"

# Step 2: Build the image
echo -e "${YELLOW}[2/7] Building Docker image...${NC}"
docker-compose -f "$COMPOSE_FILE" build --no-cache
echo -e "${GREEN}✓ Image built successfully${NC}"

# Step 3: Start services
echo -e "${YELLOW}[3/7] Starting Docker services...${NC}"
docker-compose -f "$COMPOSE_FILE" up -d
sleep 10  # Give services time to start
echo -e "${GREEN}✓ Services started${NC}"

# Step 4: Install/Update Composer dependencies
echo -e "${YELLOW}[4/7] Installing Composer dependencies...${NC}"
docker-compose -f "$COMPOSE_FILE" exec -T app composer install --optimize-autoloader
echo -e "${GREEN}✓ Dependencies installed${NC}"

# Step 5: Run migrations
echo -e "${YELLOW}[5/7] Running database migrations...${NC}"
docker-compose -f "$COMPOSE_FILE" exec -T app php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"

# Step 6: Cache optimization
echo -e "${YELLOW}[6/7] Optimizing application...${NC}"
docker-compose -f "$COMPOSE_FILE" exec -T app php artisan optimize
docker-compose -f "$COMPOSE_FILE" exec -T app php artisan config:cache
docker-compose -f "$COMPOSE_FILE" exec -T app php artisan route:cache
echo -e "${GREEN}✓ Application optimized${NC}"

# Step 7: Display service status
echo -e "${YELLOW}[7/7] Verifying services...${NC}"
docker-compose -f "$COMPOSE_FILE" ps
echo -e "${GREEN}✓ All services are running${NC}"

echo ""
echo "========================================="
echo -e "${GREEN}Deployment Complete!${NC}"
echo "========================================="
echo ""

if [ "$ENV_FILE" = ".env.production" ]; then
    echo "Application is running in PRODUCTION"
    echo "Access at: https://your-domain.com"
else
    echo "Application is running in DEVELOPMENT"
    echo "Access at: http://localhost:8001"
fi

echo ""
echo "Useful commands:"
echo "  View logs:        docker-compose -f $COMPOSE_FILE logs -f app"
echo "  Run artisan:      docker-compose -f $COMPOSE_FILE exec app php artisan <command>"
echo "  Database shell:   docker-compose -f $COMPOSE_FILE exec mysql mysql -uroot -p"
echo "  Stop services:    docker-compose -f $COMPOSE_FILE down"
echo ""
