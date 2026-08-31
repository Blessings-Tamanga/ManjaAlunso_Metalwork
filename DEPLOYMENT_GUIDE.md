# ManjaAlunso Metalworks - Complete Deployment Guide

**Last Updated:** August 31, 2026  
**Status:** ✅ Ready for Deployment  
**Laravel Version:** 11  
**PHP Version:** 8.4-FPM  
**Docker:** Fully Configured

---

## 📋 Table of Contents

1. [Quick Start](#quick-start)
2. [System Requirements](#system-requirements)
3. [Pre-Deployment Checklist](#pre-deployment-checklist)
4. [Development Deployment](#development-deployment)
5. [Production Deployment](#production-deployment)
6. [Docker Overview](#docker-overview)
7. [Environment Configuration](#environment-configuration)
8. [Troubleshooting](#troubleshooting)
9. [Security](#security)
10. [Performance Optimization](#performance-optimization)
11. [Monitoring & Maintenance](#monitoring--maintenance)
12. [Backup & Recovery](#backup--recovery)

---

## 🚀 Quick Start

### For Windows (PowerShell)

```powershell
cd "c:\Users\DELL\Documents\Github_repo\Laravel_Projects\ManjaAlunso_Metalworks"

# Run the automated deployment script
.\deploy.ps1
```

### For Linux/macOS

```bash
cd /path/to/ManjaAlunso_Metalworks
chmod +x deploy.sh
./deploy.sh
```

### Manual Docker Deployment

```bash
# Build the Docker image
docker-compose build --no-cache

# Start all services
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Access the application
# Development: http://localhost:8001
# Database: localhost:3306
```

---

## 📦 System Requirements

### Hardware Requirements
- **CPU:** 2+ cores recommended
- **RAM:** 4GB minimum, 8GB recommended
- **Disk:** 20GB free space (10GB for Docker images, 10GB for data)
- **Network:** Stable internet for Docker image pulls

### Software Requirements

#### Windows
- Windows 10 Pro/Enterprise or Windows 11
- Docker Desktop for Windows (version 4.0+)
- PowerShell 5.1 or higher
- Git for Windows (optional, for version control)

#### Linux
- Ubuntu 20.04 LTS or higher (or compatible distribution)
- Docker Engine (version 20.10+)
- Docker Compose (version 2.0+)
- Bash shell

#### macOS
- macOS 11.0 or higher
- Docker Desktop for Mac (version 4.0+)
- Bash or Zsh shell

#### Docker Components
- **PHP 8.4-FPM** - Application runtime
- **Nginx 1.25** - Web server
- **MySQL 8.0** - Database
- **Redis 7** - Cache (production only)

---

## ✅ Pre-Deployment Checklist

Before deploying, verify the following:

```
[ ] Docker Desktop is installed and running
[ ] Docker Compose is installed (check: docker-compose --version)
[ ] Git repository is cloned
[ ] Required ports are available:
    [ ] Port 8001 (Nginx - HTTP)
    [ ] Port 443 (HTTPS - if using SSL)
    [ ] Port 3306 (MySQL)
    [ ] Port 6379 (Redis - production only)
[ ] Environment files exist:
    [ ] .env.docker
    [ ] .env.production
    [ ] .env.testing
[ ] Firewall allows Docker communication
[ ] At least 10GB free disk space available
[ ] Internet connection is stable
[ ] Database backup (if upgrading existing installation)
```

**Verify checklist:**
```bash
# Check Docker status
docker --version
docker-compose --version

# Check port availability (Linux/macOS)
netstat -tulpn | grep -E '8001|3306|6379'

# Check disk space
df -h

# Check internet connectivity
ping 8.8.8.8
```

---

## 🐳 Development Deployment

### Starting the Development Environment

```bash
# Navigate to project directory
cd ManjaAlunso_Metalworks

# Build images (first time only)
docker-compose build

# Start services in background
docker-compose up -d

# Watch services start
docker-compose logs -f

# Wait for services to be ready (typically 30-60 seconds)
```

### Initial Setup Steps

```bash
# 1. Run migrations
docker-compose exec app php artisan migrate

# 2. Seed the database (optional)
docker-compose exec app php artisan db:seed

# 3. Generate application key (if needed)
docker-compose exec app php artisan key:generate

# 4. Create storage symlink
docker-compose exec app php artisan storage:link

# 5. Set proper permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Access the Application

| Service | URL | Port |
|---------|-----|------|
| **Application** | http://localhost:8001 | 8001 |
| **Database** | localhost | 3306 |
| **MySQL User** | root | (see .env.docker) |

### View Logs

```bash
# All services
docker-compose logs

# Specific service
docker-compose logs app      # PHP application
docker-compose logs nginx    # Web server
docker-compose logs mysql    # Database

# Follow logs in real-time
docker-compose logs -f app
```

### Stop Development Services

```bash
# Stop all services (keep data)
docker-compose stop

# Stop and remove containers (keep data/volumes)
docker-compose down

# Remove everything including volumes
docker-compose down -v  # WARNING: Deletes database data!
```

---

## 🏭 Production Deployment

### Pre-Production Steps

1. **Test Configuration**
   ```bash
   docker-compose -f docker-compose.production.yml config
   ```

2. **Build Production Image**
   ```bash
   docker-compose -f docker-compose.production.yml build --no-cache
   ```

3. **Set Production Environment**
   ```bash
   # Create/update .env.production with production values
   APP_ENV=production
   APP_DEBUG=false
   CACHE_DRIVER=redis
   SESSION_DRIVER=redis
   QUEUE_CONNECTION=redis
   ```

### Starting Production Services

```bash
# Start production environment
docker-compose -f docker-compose.production.yml up -d

# Run migrations
docker-compose -f docker-compose.production.yml exec app php artisan migrate --force

# Cache configuration
docker-compose -f docker-compose.production.yml exec app php artisan config:cache
docker-compose -f docker-compose.production.yml exec app php artisan route:cache
docker-compose -f docker-compose.production.yml exec app php artisan view:cache
```

### Production Services Included

| Service | Version | Port | Purpose |
|---------|---------|------|---------|
| PHP-FPM | 8.4 | 9000 | Application runtime |
| Nginx | 1.25 | 80, 443 | Web server & reverse proxy |
| MySQL | 8.0 | 3306 | Database |
| Redis | 7-alpine | 6379 | Cache & sessions |

### SSL/TLS Configuration

Production Nginx configuration includes SSL support. To enable:

1. **Get SSL Certificate** (Let's Encrypt example)
   ```bash
   sudo certbot certonly --standalone -d yourdomain.com -d www.yourdomain.com
   ```

2. **Update Nginx Config**
   - Edit `docker/nginx/Default.ssl.conf`
   - Uncomment SSL section
   - Update certificate paths

3. **Restart Nginx**
   ```bash
   docker-compose -f docker-compose.production.yml restart nginx
   ```

### Production Monitoring

```bash
# Check service health
docker-compose -f docker-compose.production.yml ps

# View application logs
docker-compose -f docker-compose.production.yml logs -f app

# Check Redis connection
docker-compose -f docker-compose.production.yml exec redis redis-cli PING

# Database backup
docker-compose -f docker-compose.production.yml exec mysql mysqldump -u root -p$MYSQL_ROOT_PASSWORD --all-databases > backup.sql
```

---

## 🐳 Docker Overview

### Architecture

```
┌─────────────────────────────────────────┐
│           Your Application              │
└──────────────┬──────────────────────────┘
               │
       ┌───────┴────────┐
       │                │
   ┌───▼───┐       ┌───▼────┐
   │ Nginx │       │  App   │
   │ 1.25  │       │PHP 8.4 │
   └───┬───┘       └────────┘
       │               │
       └───────┬───────┘
               │
       ┌───────┴──────────┐
       │                  │
   ┌───▼───┐         ┌───▼────┐
   │ MySQL │         │ Redis  │
   │ 8.0   │         │7-alpine│
   └───────┘         └────────┘
```

### Docker Compose Files

| File | Environment | Services | Use Case |
|------|-------------|----------|----------|
| `docker-compose.yml` | Development | PHP, Nginx, MySQL | Local development |
| `docker-compose.production.yml` | Production | PHP, Nginx, MySQL, Redis | Production deployment |
| `docker-compose.override.yml` | Development | Override settings | Local customization |

### Build Configuration

#### Multi-Stage Dockerfile

The `docker/php/Dockerfile` uses multi-stage builds to optimize image size:

```dockerfile
# Stage 1: Builder
# - Installs build dependencies
# - Compiles PHP extensions
# - Size: ~1.2GB (temporary)

# Stage 2: Runtime  
# - Copies only necessary artifacts
# - Minimal runtime dependencies
# - Final size: ~550MB
```

This approach reduces final image size by ~60% compared to single-stage builds.

---

## 🔧 Environment Configuration

### Environment Files

#### `.env.docker` (Development)
Used for local development with Docker:
```env
APP_ENV=local
APP_DEBUG=true
DB_HOST=mysql
DB_PORT=3306
CACHE_DRIVER=file
```

#### `.env.production` (Production)
Used for production deployment:
```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=mysql
DB_PORT=3306
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

#### `.env.testing` (Testing)
Used for running tests:
```env
APP_ENV=testing
APP_DEBUG=true
CACHE_DRIVER=array
SESSION_DRIVER=array
```

### Key Configuration Variables

| Variable | Dev | Prod | Purpose |
|----------|-----|------|---------|
| `APP_ENV` | local | production | Application environment |
| `APP_DEBUG` | true | false | Debug mode |
| `CACHE_DRIVER` | file | redis | Cache backend |
| `SESSION_DRIVER` | file | redis | Session storage |
| `DB_HOST` | mysql | mysql | Database host |
| `QUEUE_CONNECTION` | sync | redis | Queue driver |

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### 1. Port Already in Use

**Error:** `bind: address already in use`

**Solution:**
```bash
# Find process using port
# Windows
netstat -ano | findstr :8001

# Linux/macOS
lsof -i :8001

# Kill process
# Windows
taskkill /PID <PID> /F

# Linux/macOS
kill -9 <PID>

# Or change port in docker-compose.yml
```

#### 2. Docker Daemon Not Running

**Error:** `Cannot connect to Docker daemon`

**Solution:**
```bash
# Windows: Start Docker Desktop application
# Linux: sudo systemctl start docker
# macOS: Open Docker.app from Applications
```

#### 3. Insufficient Disk Space

**Error:** `no space left on device`

**Solution:**
```bash
# Check disk usage
df -h

# Clean Docker
docker system prune -a

# Remove old images
docker rmi $(docker images -f dangling=true -q)
```

#### 4. Database Connection Failed

**Error:** `SQLSTATE[HY000]: General error: 2006 MySQL has gone away`

**Solution:**
```bash
# Check MySQL is running
docker-compose ps mysql

# View MySQL logs
docker-compose logs mysql

# Restart MySQL
docker-compose restart mysql

# Verify connection
docker-compose exec app php -r "echo 'MySQL version: ' . shell_exec('mysql -h mysql -u root -proot -e \"SELECT VERSION()\"');"
```

#### 5. Permission Denied on Files

**Error:** `Permission denied` when accessing storage

**Solution:**
```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### See Also

- [DOCKER_BUILD_RECOVERY.md](DOCKER_BUILD_RECOVERY.md) - Build failure troubleshooting
- [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md) - Advanced Docker configuration

---

## 🔐 Security

### Security Checklist

```
[ ] Set APP_DEBUG=false in production
[ ] Generate unique APP_KEY
[ ] Use strong database passwords
[ ] Enable HTTPS/SSL certificates
[ ] Restrict database access to internal network
[ ] Use environment variables for secrets
[ ] Keep Docker images updated
[ ] Monitor application logs
[ ] Implement rate limiting
[ ] Regular security backups
```

### Production Security Best Practices

1. **Secrets Management**
   ```bash
   # Never commit .env files
   # Use .env.example as template
   # Store secrets in environment variables or secret management system
   ```

2. **Network Security**
   ```bash
   # Firewall rules
   - Allow only necessary ports (80, 443)
   - Restrict database access to Docker network
   - Disable direct MySQL access from internet
   ```

3. **SSL/TLS**
   - Use Let's Encrypt for free certificates
   - Enable HSTS headers
   - Use strong cipher suites
   - Update certificates before expiration

4. **Regular Updates**
   ```bash
   # Update PHP image
   docker pull php:8.4-fpm
   docker-compose build --no-cache

   # Update system packages
   docker-compose exec app apt-get update && apt-get upgrade -y
   ```

---

## ⚡ Performance Optimization

### Caching Strategy

```bash
# Enable query caching
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Verify cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

### Database Optimization

```bash
# Run optimization
docker-compose exec app php artisan optimize
docker-compose exec app php artisan optimize:clear

# Check slow queries
docker-compose exec mysql mysql -u root -proot -e "SET GLOBAL slow_query_log = 'ON'; SET GLOBAL long_query_time = 2;"
```

### Redis Performance

```bash
# Monitor Redis
docker-compose exec redis redis-cli

# Inside Redis CLI:
MONITOR          # Watch all commands
INFO memory      # Memory usage
DBSIZE          # Key count
FLUSHALL        # Clear all data
```

---

## 📊 Monitoring & Maintenance

### Health Checks

```bash
# Application health
curl http://localhost:8001/health

# Database
docker-compose exec mysql mysql -h localhost -u root -proot -e "SELECT 1;"

# Redis
docker-compose exec redis redis-cli PING

# Nginx
docker-compose exec nginx nginx -t
```

### Log Management

```bash
# View logs with timestamp
docker-compose logs --timestamps

# Follow logs
docker-compose logs -f

# Specific service logs
docker-compose logs app --tail=100

# Export logs
docker-compose logs app > app.log
```

### Scheduled Maintenance

```bash
# Daily
- Check logs for errors
- Monitor disk usage
- Verify database backups

# Weekly
- Update packages
- Review slow query logs
- Test backup restoration

# Monthly
- Update Docker images
- Review security logs
- Performance analysis
```

---

## 💾 Backup & Recovery

### Database Backup

```bash
# Full backup
docker-compose exec mysql mysqldump -u root -proot --all-databases > backup-$(date +%Y%m%d_%H%M%S).sql

# Specific database
docker-compose exec mysql mysqldump -u root -proot laravel_app > laravel_backup.sql

# Backup to compressed file
docker-compose exec mysql mysqldump -u root -proot --all-databases | gzip > backup.sql.gz
```

### Database Restore

```bash
# From SQL file
docker-compose exec -T mysql mysql -u root -proot < backup.sql

# From compressed file
gunzip < backup.sql.gz | docker-compose exec -T mysql mysql -u root -proot
```

### Volume Backup

```bash
# Backup MySQL volume
docker run --rm \
  --volumes-from $(docker-compose ps -q mysql) \
  -v $(pwd):/backup \
  busybox tar czf /backup/mysql-volume.tar.gz /var/lib/mysql

# Backup application volume
docker run --rm \
  --volumes-from $(docker-compose ps -q app) \
  -v $(pwd):/backup \
  busybox tar czf /backup/app-volume.tar.gz /var/www/html
```

### Recovery Procedure

```bash
# 1. Stop services
docker-compose down

# 2. Remove old volumes
docker volume rm $(docker volume ls -q)

# 3. Restore from backup
docker-compose up -d
docker-compose exec -T mysql mysql -u root -proot < backup.sql

# 4. Verify
docker-compose exec mysql mysql -u root -proot -e "SELECT DATABASE();"
```

---

## 📞 Support & Documentation

### Additional Resources

- **Framework**: [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- **Docker**: [Docker Documentation](https://docs.docker.com/)
- **Nginx**: [Nginx Documentation](https://nginx.org/en/docs/)
- **PHP**: [PHP 8.4 Documentation](https://www.php.net/docs.php)
- **MySQL**: [MySQL 8.0 Documentation](https://dev.mysql.com/doc/)

### Related Documentation

- [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md) - Detailed Docker configuration
- [README_DEPLOYMENT.md](README_DEPLOYMENT.md) - Quick start reference
- [DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md) - Deployment status
- [DOCKER_BUILD_RECOVERY.md](DOCKER_BUILD_RECOVERY.md) - Build troubleshooting

---

## 🔄 Deployment Scripts

### Windows (PowerShell)

```powershell
# Run automated deployment
.\deploy.ps1

# Features:
# ✓ Docker verification
# ✓ Image building
# ✓ Service startup
# ✓ Dependencies installation
# ✓ Database migrations
# ✓ Optimization
# ✓ Health verification
```

### Linux/macOS (Bash)

```bash
# Run automated deployment
./deploy.sh

# Same features as Windows PowerShell script
```

---

## ✨ Success Indicators

Your deployment is successful when:

```
✓ All Docker services are running
  docker-compose ps shows:
  - app (running)
  - nginx (running)
  - mysql (running)
  - redis (running, production only)

✓ Application is accessible
  http://localhost:8001 loads without errors

✓ Database migrations completed
  No "migration failed" errors in logs

✓ Services report healthy
  Health check endpoints return 200 OK

✓ No error logs
  docker-compose logs shows no ERROR entries
```

---

**Last Updated:** August 31, 2026  
**Maintainer:** ManjaAlunso Metalworks Development Team  
**Status:** ✅ Production Ready
