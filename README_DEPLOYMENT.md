# ManjaAlunso Metalworks - Deployment Guide

## Quick Start - Deployment

### Prerequisites
- Docker and Docker Compose installed
- Git configured
- At least 10GB free disk space

### Option 1: Automated Deployment (Recommended)

#### Windows (PowerShell)
```powershell
cd c:\Users\DELL\Documents\Github_repo\Laravel_Projects\ManjaAlunso_Metalworks
.\deploy.ps1 dev      # For development
# OR
.\deploy.ps1 production  # For production
```

#### macOS/Linux (Bash)
```bash
cd ~/ManjaAlunso_Metalworks
chmod +x deploy.sh
./deploy.sh          # For development
# OR
./deploy.sh production  # For production
```

### Option 2: Manual Deployment

#### Step 1: Build Docker Image
```bash
docker-compose build --no-cache
```

#### Step 2: Start Services
```bash
# Development
docker-compose up -d

# Production
docker-compose -f docker-compose.production.yml up -d
```

#### Step 3: Install Dependencies
```bash
docker-compose exec app composer install --optimize-autoloader
```

#### Step 4: Configure Application
```bash
# Generate app key (if not set)
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate --force

# Seed database (optional)
docker-compose exec app php artisan db:seed
```

#### Step 5: Optimize for Production
```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## Accessing the Application

### Development
- **URL**: http://localhost:8001
- **Database**: MySQL running on localhost:3306
  - User: laravel
  - Password: laravel
- **Redis**: Available on localhost:6379

### Production
- **URL**: https://your-domain.com
- **Database**: MySQL container (db hostname)
- **Redis**: Redis container for caching

## Common Commands

### View Logs
```bash
# Application logs
docker-compose logs -f app

# Nginx logs
docker-compose logs -f nginx

# MySQL logs
docker-compose logs -f mysql
```

### Run Artisan Commands
```bash
docker-compose exec app php artisan <command>

# Examples:
docker-compose exec app php artisan tinker
docker-compose exec app php artisan make:model ModelName
docker-compose exec app php artisan queue:work
```

### Database Management
```bash
# MySQL CLI
docker-compose exec mysql mysql -uroot -p

# Backup database
docker-compose exec mysql mysqldump -uroot -p manjaalunso_db > backup.sql

# Restore database
docker-compose exec -T mysql mysql -uroot -p manjaalunso_db < backup.sql
```

### Cache Management
```bash
# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan config:clear

# Reset cache
docker-compose exec app php artisan cache:forget <key>
```

## Environment Configuration

### Development (.env.docker)
- APP_ENV=local
- APP_DEBUG=true
- CACHE_DRIVER=file
- Database: MySQL in Docker

### Production (.env.production)
- APP_ENV=production
- APP_DEBUG=false
- CACHE_DRIVER=redis
- Database: MySQL in Docker
- Update mail configuration before deployment

## SSL/HTTPS Setup (Production)

### Using Let's Encrypt with Certbot

```bash
# Install certbot on your server
sudo apt-get install certbot python3-certbot-nginx

# Get certificate
sudo certbot certonly --nginx -d your-domain.com

# Copy certificates to project
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem docker/nginx/certs/
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem docker/nginx/certs/
sudo chown nobody:nogroup docker/nginx/certs/*

# Update Nginx config: docker/nginx/Default.ssl.conf
# Uncomment SSL sections
```

## Troubleshooting

### Container Won't Start
```bash
# Check logs
docker-compose logs app

# Restart services
docker-compose restart

# Full reset (warning: deletes data)
docker-compose down -v
docker-compose up -d
```

### Permission Denied Errors
```bash
# Fix file permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Database Connection Failed
```bash
# Verify MySQL is running
docker-compose ps mysql

# Check if database exists
docker-compose exec mysql mysql -uroot -p -e "SHOW DATABASES;"

# Create database
docker-compose exec mysql mysql -uroot -p -e "CREATE DATABASE manjaalunso_db;"
```

### Composer Memory Error
```bash
# Increase memory limit temporarily
docker-compose exec app php -d memory_limit=-1 composer install
```

### Port Already in Use
```bash
# Change port mapping in docker-compose.yml
# Modify ports section:
# nginx:
#   ports:
#     - "8002:80"  # Changed from 8001 to 8002
```

## Performance Optimization

### Production Checklist
- [ ] APP_DEBUG=false in .env.production
- [ ] Run cache optimization commands
- [ ] Configure Redis for caching
- [ ] Set up proper logging
- [ ] Configure email service
- [ ] Enable HTTPS/SSL
- [ ] Set up database backups
- [ ] Configure monitoring

### Caching Strategy
```bash
docker-compose exec app php artisan optimize

# Route caching (improves performance ~25%)
docker-compose exec app php artisan route:cache

# Config caching
docker-compose exec app php artisan config:cache

# View caching
docker-compose exec app php artisan view:cache
```

## Backup & Recovery

### Backup Database
```bash
docker-compose exec mysql mysqldump -uroot -p manjaalunso_db > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Backup Uploads/Files
```bash
docker cp $(docker-compose ps -q app):/var/www/html/storage ./storage_backup_$(date +%Y%m%d)
```

### Restore Database
```bash
docker-compose exec -T mysql mysql -uroot -p manjaalunso_db < backup_file.sql
```

## Monitoring & Logs

### Real-time Monitoring
```bash
# Watch all logs
docker-compose logs -f

# Watch specific service
docker-compose logs -f app --tail=100

# Follow with timestamps
docker-compose logs -f --timestamps app
```

### Log Aggregation
Logs are sent to:
- Application: `/storage/logs/laravel.log`
- Nginx: `/var/log/nginx/access.log` and `/var/log/nginx/error.log`
- MySQL: Docker container logs

## Security Checklist

- [ ] Change default database password
- [ ] Set strong APP_KEY
- [ ] Disable APP_DEBUG in production
- [ ] Configure CORS properly
- [ ] Set up rate limiting
- [ ] Enable HTTPS/SSL
- [ ] Configure security headers
- [ ] Regular security updates
- [ ] Backup data regularly
- [ ] Monitor error logs

## File Structure

```
.
├── docker/
│   ├── nginx/
│   │   ├── Default.conf          # Development config
│   │   └── Default.ssl.conf      # Production HTTPS config
│   └── php/
│       └── Dockerfile             # Multi-stage build
├── docker-compose.yml             # Development compose
├── docker-compose.production.yml  # Production compose
├── .env.docker                    # Docker development env
├── .env.production                # Production environment
├── .env.testing                   # Testing environment
├── deploy.sh                      # Linux/macOS deployment
├── deploy.ps1                     # Windows deployment
├── DOCKER_DEPLOYMENT.md          # Detailed documentation
└── README_DEPLOYMENT.md          # This file
```

## Support & Documentation

- **Docker Docs**: https://docs.docker.com/
- **Laravel Docs**: https://laravel.com/docs
- **Docker Compose Docs**: https://docs.docker.com/compose/
- **Project Docs**: See DOCKER_DEPLOYMENT.md

## Next Steps

1. Run deployment script (Option 1) or follow manual steps (Option 2)
2. Verify application is accessible
3. Configure email settings
4. Set up backups
5. Monitor application
6. Deploy to production server

---

**Version**: 1.0
**Last Updated**: August 31, 2026
**Status**: ✅ Ready for Deployment
