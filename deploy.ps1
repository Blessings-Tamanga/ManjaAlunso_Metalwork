#!/usr/bin/env powershell

# Laravel Docker Deployment Script for Windows
# Usage: .\deploy.ps1 [dev|production]

param(
    [Parameter(Position=0)]
    [ValidateSet("dev", "production")]
    [string]$Environment = "dev"
)

$ErrorActionPreference = "Stop"

# Configuration
$envFile = if ($Environment -eq "production") { ".env.production" } else { ".env.docker" }
$composeFile = if ($Environment -eq "production") { "docker-compose.production.yml" } else { "docker-compose.yml" }

# Colors (ANSI)
$Green = "`e[32m"
$Yellow = "`e[33m"
$Red = "`e[31m"
$Reset = "`e[0m"

Write-Host "$('='*50)" -ForegroundColor Cyan
Write-Host "Laravel Docker Deployment Script" -ForegroundColor Cyan
Write-Host "Environment: $Environment"
Write-Host "Config File: $envFile"
Write-Host "$('='*50)" -ForegroundColor Cyan
Write-Host ""

try {
    # Step 1: Check Docker
    Write-Host "[1/7] Checking Docker daemon..." -ForegroundColor Yellow
    $dockerCheck = docker ps *> $null
    Write-Host "$Green✓ Docker is running$Reset" -ForegroundColor Green

    # Step 2: Build Image
    Write-Host "[2/7] Building Docker image..." -ForegroundColor Yellow
    docker-compose -f $composeFile build --no-cache
    Write-Host "$Green✓ Image built successfully$Reset" -ForegroundColor Green

    # Step 3: Start Services
    Write-Host "[3/7] Starting Docker services..." -ForegroundColor Yellow
    docker-compose -f $composeFile up -d
    Start-Sleep -Seconds 10
    Write-Host "$Green✓ Services started$Reset" -ForegroundColor Green

    # Step 4: Composer Install
    Write-Host "[4/7] Installing Composer dependencies..." -ForegroundColor Yellow
    docker-compose -f $composeFile exec -T app composer install --optimize-autoloader
    Write-Host "$Green✓ Dependencies installed$Reset" -ForegroundColor Green

    # Step 5: Run Migrations
    Write-Host "[5/7] Running database migrations..." -ForegroundColor Yellow
    docker-compose -f $composeFile exec -T app php artisan migrate --force
    Write-Host "$Green✓ Migrations completed$Reset" -ForegroundColor Green

    # Step 6: Optimization
    Write-Host "[6/7] Optimizing application..." -ForegroundColor Yellow
    docker-compose -f $composeFile exec -T app php artisan optimize
    docker-compose -f $composeFile exec -T app php artisan config:cache
    docker-compose -f $composeFile exec -T app php artisan route:cache
    Write-Host "$Green✓ Application optimized$Reset" -ForegroundColor Green

    # Step 7: Verify Status
    Write-Host "[7/7] Verifying services..." -ForegroundColor Yellow
    docker-compose -f $composeFile ps
    Write-Host "$Green✓ All services are running$Reset" -ForegroundColor Green

    Write-Host ""
    Write-Host "$('='*50)" -ForegroundColor Green
    Write-Host "Deployment Complete!" -ForegroundColor Green
    Write-Host "$('='*50)" -ForegroundColor Green
    Write-Host ""

    if ($Environment -eq "production") {
        Write-Host "Application running in PRODUCTION mode"
        Write-Host "Access at: https://your-domain.com"
    } else {
        Write-Host "Application running in DEVELOPMENT mode"
        Write-Host "Access at: http://localhost:8001"
    }

    Write-Host ""
    Write-Host "Useful commands:" -ForegroundColor Cyan
    Write-Host "  View logs:        docker-compose -f $composeFile logs -f app"
    Write-Host "  Run artisan:      docker-compose -f $composeFile exec app php artisan <command>"
    Write-Host "  Database shell:   docker-compose -f $composeFile exec mysql mysql -uroot -p"
    Write-Host "  Stop services:    docker-compose -f $composeFile down"
    Write-Host ""

} catch {
    Write-Host ""
    Write-Host "Error during deployment:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    Write-Host "Troubleshooting:" -ForegroundColor Yellow
    Write-Host "1. Make sure Docker is running"
    Write-Host "2. Check disk space (at least 10GB free)"
    Write-Host "3. Verify docker-compose is installed"
    Write-Host "4. Run: docker-compose -f $composeFile logs to see detailed errors"
    exit 1
}
