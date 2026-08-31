# Deployment & Docker Configuration Guide

## Overview
This Laravel application is configured with Docker for easy deployment across environments. The setup includes PHP-FPM, Nginx, MySQL, and Redis services.

## Project Structure
```
docker/
├── nginx/
│   └── Default.conf         # Nginx configuration
└── php/
    └── Dockerfile           # PHP-FPM Dockerfile (multi-stage)
```

## Environment Files
- `.env.docker` - Docker development environment variables
- `.env.production` - Production environment configuration
- `.env.testing` - Testing environment configuration
- `.env` - Local development environment (git ignored)

## Docker Services

### 1. PHP Application (FPM)
- **Image**: PHP 8.4 FPM
- **Port**: 9000 (internal)
- **Features**:
  - Multi-stage build for optimized image size
  - Composer dependencies pre-installed
  - Redis, PDO MySQL, GD, OPCache extensions
  - Production-optimized PHP configuration

### 2. Nginx Web Server
- **Image**: nginx:1.25-alpine
- **Port**: 8001 (dev) / 80 (production)
- **Config**: `docker/nginx/Default.conf`
- **Features**:
  - Reverse proxy to PHP-FPM
  - Static file caching
  - Security headers configured

### 3. MySQL Database
- **Image**: mysql:8.0
- **Port**: 3306
- **Database**: manjaalunso_db
- **Features**:
  - Health checks enabled
  - Data persisted in named volume
  - Credentials in .env.docker

### 4. Redis Cache (Production)
- **Image**: redis:7-alpine
- **Port**: 6379
- **Features**:
  - AOF persistence
  - Health checks enabled
  - Data persisted in named volume

## Docker Compose Files

### docker-compose.yml (Development/Testing)
- Uses `.env.docker` for configuration
- Volumes for live code editing
- All services on shared network

### docker-compose.production.yml
- Uses `.env.production` for configuration
- Optimized for production deployment
- Includes Redis service
- Restart policies set to `unless-stopped`
- Port mapping: 80 (HTTP), 443 (HTTPS)

## Building and Running

### Build the Docker Image
```bash
cd /path/to/project
docker-compose build --no-cache
```

### Start Services (Development)
```bash
docker-compose up -d
```

### Start Services (Production)
```bash
docker-compose -f docker-compose.production.yml up -d
```

### Stop Services
```bash
docker-compose down
```

### View Logs
```bash
docker-compose logs -f app
docker-compose logs -f nginx
```

### Access the Application
- **Development**: http://localhost:8001
- **Production**: http://your-domain.com

## Database Migrations

### Run Migrations
```bash
docker-compose exec app php artisan migrate --force
```

### Seed Database
```bash
docker-compose exec app php artisan db:seed
```

### Fresh Installation
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

## Cache & Optimization

### Clear Cache
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan config:clear
```

### Optimize for Production
```bash
docker-compose exec app php artisan optimize
```

## File Permissions
The Dockerfile automatically sets:
- Owner: www-data:www-data
- Storage: 775 permissions
- Bootstrap cache: 775 permissions

## Performance Considerations

### PHP Configuration
- Memory limit: 512M
- Max file upload: 50M
- Execution timeout: 120s
- OPCache: Enabled and optimized
- Validation timestamps: Disabled (production)

### Nginx Caching
- Static assets: 1-year cache
- Cache-Control: public, immutable
- Gzip compression enabled

## Security Features
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- PHP error logging to stderr
- Secrets not exposed in logs

## Troubleshooting

### Container Won't Start
```bash
docker-compose logs app
```

### Database Connection Failed
- Verify DB_HOST is set to 'mysql' (not localhost)
- Check MySQL is running: `docker-compose ps`
- Verify credentials in .env.docker

### Permission Errors
```bash
docker-compose exec app chown -R www-data:www-data .
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Clear Docker Cache
```bash
docker-compose down -v
docker system prune -a
docker-compose build --no-cache
```

## Production Deployment Checklist
- [ ] Update APP_KEY in .env.production
- [ ] Set APP_DEBUG=false
- [ ] Configure MAIL_* settings
- [ ] Set up AWS credentials if needed
- [ ] Configure Redis password if needed
- [ ] Update database credentials
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeds if needed: `php artisan db:seed`
- [ ] Set up SSL/HTTPS certificates
- [ ] Configure domain DNS
- [ ] Enable log rotation
- [ ] Set up backup strategy

## Docker Images Used
- `php:8.4-fpm` - Official PHP image with FPM
- `nginx:1.25-alpine` - Lightweight Nginx
- `mysql:8.0` - Official MySQL
- `redis:7-alpine` - Lightweight Redis

## Container Health Checks
- **PHP**: Checks php-fpm version availability
- **MySQL**: Mysqladmin ping check
- **Redis**: Redis CLI ping check

## Volume Management
- `mysql_data`: MySQL database persistence
- `redis_data`: Redis data persistence
- `./storage`: Application storage
- `./bootstrap/cache`: Laravel cache

## Nginx Configuration Features
- Reverse proxy setup
- Static file serving with caching
- Error page handling
- Max upload size: 50MB
- Security headers
- Access and error logging

## Next Steps
1. Build the Docker image
2. Configure environment variables
3. Start services with docker-compose
4. Run migrations
5. Access application and verify
6. Set up monitoring and logging
7. Configure backups
8. Deploy to production server

