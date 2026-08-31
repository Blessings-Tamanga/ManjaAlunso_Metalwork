# Application Debugging Guide
## ManjaAlunso Metalworks Laravel Application

**Created:** August 31, 2026  
**Version:** 1.0

---

## 📋 Table of Contents

1. [Getting Started](#getting-started)
2. [Common Issues & Solutions](#common-issues--solutions)
3. [Logging & Monitoring](#logging--monitoring)
4. [Database Debugging](#database-debugging)
5. [Frontend Debugging](#frontend-debugging)
6. [Performance Debugging](#performance-debugging)
7. [Security Issues](#security-issues)
8. [Advanced Debugging](#advanced-debugging)

---

## 🚀 Getting Started

### Access the Application Container

```powershell
# Enter application shell
docker-compose exec app bash

# Inside container:
cd /var/www/html
pwd  # Verify location
ls -la  # List files
```

### Quick Diagnostic Commands

```powershell
# Check Laravel version
docker-compose exec app php artisan --version

# Check environment
docker-compose exec app php artisan env

# Check application is configured
docker-compose exec app php artisan config:show | head -20

# Test database connection
docker-compose exec app php artisan tinker
# In tinker: \DB::connection()->getPdo();  # Returns PDO object if connected
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Database Connection Refused

**Symptoms:**
- "SQLSTATE[HY000]: General error: 2006 MySQL has gone away"
- "connection refused"
- Pages requiring database return 500 error

**Debug Steps:**

```powershell
# 1. Check MySQL is running
docker-compose ps mysql
# Expected: mysql    Up (healthy)

# 2. Check MySQL logs
docker-compose logs mysql

# 3. Try direct connection
docker-compose exec mysql mysql -h localhost -u root -proot -e "SELECT 1;"

# 4. Check network connectivity
docker-compose exec app ping mysql
# Expected: successful ping

# 5. Check database exists
docker-compose exec mysql mysql -h localhost -u root -proot -e "SHOW DATABASES;"
```

**Solutions:**

```powershell
# Restart MySQL
docker-compose restart mysql
Start-Sleep -Seconds 10

# Run migrations again
docker-compose exec app php artisan migrate --force

# Rebuild containers
docker-compose down -v
docker-compose up -d
docker-compose exec app php artisan migrate --force
```

---

### Issue 2: 500 Internal Server Error

**Symptoms:**
- Pages return "500 Internal Server Error"
- No clear error message in browser

**Debug Steps:**

```powershell
# 1. Check application logs
docker-compose logs app | tail -50

# 2. Check Nginx logs
docker-compose logs nginx | tail -50

# 3. Access application container
docker-compose exec app bash

# 4. Inside container, check Laravel log
tail -50 /var/www/html/storage/logs/laravel.log

# 5. Check file permissions
ls -la /var/www/html/storage/logs/
ls -la /var/www/html/bootstrap/cache/
```

**Solutions:**

```powershell
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Regenerate key if needed
docker-compose exec app php artisan key:generate

# Restart services
docker-compose restart
```

---

### Issue 3: CSRF Token Mismatch

**Symptoms:**
- Form submission fails with "419 Page Expired" or "CSRF token mismatch"
- Session errors

**Debug Steps:**

```powershell
# Check session driver
docker-compose exec app php artisan config:show session.driver
# Expected: file, database, redis, or array

# Check session storage
docker-compose exec app ls -la /var/www/html/storage/framework/sessions/

# Check cookies in browser (F12 > Application > Cookies)
# Look for: XSRF-TOKEN, laravel_session

# Verify session configuration
docker-compose exec app php artisan tinker
# In tinker:
config('session.driver')
config('session.lifetime')
```

**Solutions:**

```powershell
# Clear sessions
docker-compose exec app php artisan session:table
docker-compose exec app php artisan migrate

# Ensure middleware is loaded
docker-compose exec app php artisan config:show middleware

# Rebuild cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear

# Test form submission:
# 1. Navigate to form
# 2. View page source (Ctrl+U)
# 3. Find: <input type="hidden" name="_token" value="...">
# 4. Verify token is present
```

---

### Issue 4: Assets Not Loading (CSS/JavaScript)

**Symptoms:**
- Page loads but looks broken
- No styling
- JavaScript not working
- Console shows 404 errors for assets

**Debug Steps:**

```powershell
# 1. Check Vite build status
docker-compose logs app | grep -i "vite\|build"

# 2. Check public/build directory
docker-compose exec app ls -la /var/www/html/public/build/
# Should contain: manifest.json

# 3. Check asset paths in HTML
# In browser: View page source (Ctrl+U)
# Look for: <script src="/build/...">
# Verify paths start with /build/

# 4. Check Vite configuration
docker-compose exec app cat /var/www/html/vite.config.js

# 5. Check manifest file
docker-compose exec app cat /var/www/html/public/build/manifest.json
```

**Solutions:**

```powershell
# Rebuild frontend assets
docker-compose exec app npm run build
# OR
docker-compose exec app yarn build

# Watch for changes (development)
docker-compose exec app npm run dev

# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Restart Nginx
docker-compose restart nginx

# Hard refresh in browser: Ctrl+Shift+R (or Cmd+Shift+R on Mac)
```

---

### Issue 5: Email Not Sending

**Symptoms:**
- Contact form doesn't send email
- No error message
- Mail log shows errors

**Debug Steps:**

```powershell
# Check mail configuration
docker-compose exec app php artisan config:show mail

# Test mail driver
docker-compose exec app php artisan tinker
# In tinker:
Mail::to('test@example.com')->send(new \App\Mail\ContactMail());

# Check Mailtrap or email service logs
# In .env.docker or .env.production:
# Check: MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD

# View mail log
docker-compose exec app tail -50 /var/www/html/storage/logs/laravel.log
```

**Solutions:**

```powershell
# Update .env with correct mail settings
# For development/testing, use:
MAIL_DRIVER=log  # Emails go to laravel.log

# For production with SMTP:
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=from@example.com
MAIL_FROM_NAME=YourApp

# Restart application
docker-compose restart app

# Test again
docker-compose exec app php artisan tinker
# In tinker:
Mail::to('test@example.com')->send(new \App\Mail\ContactMail());
```

---

### Issue 6: Database Migration Fails

**Symptoms:**
- `php artisan migrate` fails
- "Table already exists" error
- "Unknown column" error

**Debug Steps:**

```powershell
# Check migration status
docker-compose exec app php artisan migrate:status

# Check database tables
docker-compose exec mysql mysql -u root -proot laravel_app -e "SHOW TABLES;"

# Check failed migration errors
docker-compose logs app | grep -i "migrate\|error"

# Check specific table structure
docker-compose exec mysql mysql -u root -proot laravel_app -e "DESCRIBE services;"
```

**Solutions:**

```powershell
# Rollback failed migrations
docker-compose exec app php artisan migrate:rollback

# Check for migration conflicts
docker-compose exec app ls -la /var/www/html/database/migrations/

# Manually fix migration file if needed
docker-compose exec app php artisan make:migration fix_table_name

# Re-run migrations
docker-compose exec app php artisan migrate

# For fresh database (WARNING: deletes all data)
docker-compose exec app php artisan migrate:fresh
docker-compose exec app php artisan db:seed
```

---

### Issue 7: CRUD Operations Not Saving

**Symptoms:**
- Form submits but data doesn't save
- No error message
- Data appears in form but doesn't persist

**Debug Steps:**

```powershell
# Enable query logging
docker-compose exec app php artisan tinker
# In tinker:
\DB::enableQueryLog();

# Then perform action in browser
# Back to tinker:
dd(\DB::getQueryLog());

# Check validation errors
# Add to controller:
return redirect()->back()->withErrors($validated);

# Check database insert logs
docker-compose exec app tail -100 /var/www/html/storage/logs/laravel.log | grep -i "insert\|update"
```

**Solutions:**

```powershell
# Check model fillable properties
docker-compose exec app php artisan tinker
# In tinker:
\App\Models\Service::$fillable
# Should include: title, slug, description, etc.

# Update model if needed
# Edit: app/Models/Service.php
# Add: protected $fillable = ['title', 'slug', 'description', 'icon', 'sort_order', 'is_active'];

# Check validation in controller
# In ServiceController::store():
$validated = $request->validate([...]);

# Verify form is submitting data
# In browser F12 > Network tab:
# Submit form and check POST request payload
```

---

### Issue 8: Authentication Issues

**Symptoms:**
- Can't login
- "Invalid credentials" even with correct password
- Session not persisting

**Debug Steps:**

```powershell
# Check user exists in database
docker-compose exec mysql mysql -u root -proot laravel_app -e "SELECT id, email, password FROM users LIMIT 5;"

# Test password verification
docker-compose exec app php artisan tinker
# In tinker:
$user = \App\Models\User::find(1);
\Hash::check('password', $user->password); # Returns true/false

# Check authentication middleware
docker-compose exec app cat /var/www/html/app/Http/Middleware/Authenticate.php

# Test login flow
# In tinker:
auth()->attempt(['email' => 'admin@example.com', 'password' => 'password']);
```

**Solutions:**

```powershell
# Reset password for user
docker-compose exec app php artisan tinker
# In tinker:
$user = \App\Models\User::find(1);
$user->password = \Hash::make('newpassword');
$user->save();

# Create test user if none exist
docker-compose exec app php artisan tinker
# In tinker:
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => \Hash::make('password'),
]);

# Clear auth cache
docker-compose exec app php artisan cache:clear

# Verify AUTH_GUARD in config
docker-compose exec app php artisan config:show auth.guards
```

---

### Issue 9: Permission Denied Errors

**Symptoms:**
- "Permission denied" when accessing files/folders
- "Cannot write to storage" error
- Cache files not writable

**Debug Steps:**

```powershell
# Check file ownership and permissions
docker-compose exec app ls -la /var/www/html/storage/logs/
docker-compose exec app ls -la /var/www/html/bootstrap/cache/

# Check who owns files
docker-compose exec app stat /var/www/html/storage/logs/laravel.log

# Check current user running PHP
docker-compose exec app whoami

# Check web server user
docker-compose exec app id www-data
```

**Solutions:**

```powershell
# Fix ownership
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

# Fix permissions
docker-compose exec app chmod -R 755 /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/bootstrap/cache

# Make directories writable
docker-compose exec app chmod -R g+w /var/www/html/storage
docker-compose exec app chmod -R g+w /var/www/html/bootstrap/cache

# Restart PHP-FPM
docker-compose restart app
```

---

### Issue 10: Port Already in Use

**Symptoms:**
- "Address already in use" error
- Cannot start Docker services
- Connection refused

**Debug Steps (Windows PowerShell):**

```powershell
# Find process using port 8001
netstat -ano | findstr :8001

# Find process using port 3306
netstat -ano | findstr :3306

# Find process using port 6379
netstat -ano | findstr :6379
```

**Solutions:**

```powershell
# Kill process using port (Windows)
# Get the PID from netstat output above
taskkill /PID <PID> /F

# Or change ports in docker-compose.yml
# Edit ports section:
# nginx:
#   ports:
#     - "8002:80"  # Changed from 8001 to 8002

# Then restart
docker-compose down
docker-compose up -d
```

---

## 📊 Logging & Monitoring

### View Application Logs

```powershell
# Real-time logs
docker-compose logs -f app

# Last 50 lines
docker-compose logs app --tail=50

# Search logs
docker-compose logs app | findstr "error"

# View logs from specific time
docker-compose logs app --since 10m  # Last 10 minutes

# Export logs to file
docker-compose logs app > app-logs.txt
```

### Check Service Logs

```powershell
# Nginx logs
docker-compose logs -f nginx

# MySQL logs
docker-compose logs -f mysql

# All services
docker-compose logs -f
```

### View Laravel Log File

```powershell
# Access container
docker-compose exec app bash

# View laravel log
tail -100 /var/www/html/storage/logs/laravel.log

# Follow log in real-time
tail -f /var/www/html/storage/logs/laravel.log

# Search for errors
grep -i "error" /var/www/html/storage/logs/laravel.log

# Count errors by type
grep -i "error" /var/www/html/storage/logs/laravel.log | wc -l
```

### Enable Debug Mode

```powershell
# In .env.docker
APP_DEBUG=true

# In .env.production (NEVER use in production!)
# Keep: APP_DEBUG=false

# Restart application
docker-compose restart app

# Now you'll see detailed error messages
```

---

## 🗄️ Database Debugging

### Connect to Database

```powershell
# Interactive MySQL shell
docker-compose exec mysql mysql -u root -proot laravel_app

# Or use:
docker-compose exec mysql mysql -h mysql -u root -p
# Password: root
```

### Common Database Commands

```sql
-- Show all tables
SHOW TABLES;

-- Show table structure
DESCRIBE services;
DESCRIBE users;

-- Count records
SELECT COUNT(*) FROM services;
SELECT COUNT(*) FROM projects;

-- View records
SELECT * FROM services;
SELECT * FROM users;

-- Check recent activity
SELECT * FROM services ORDER BY created_at DESC LIMIT 5;

-- Find duplicate entries
SELECT slug, COUNT(*) as count FROM services GROUP BY slug HAVING count > 1;

-- Check data types
SHOW CREATE TABLE services;
```

### Database Optimization

```powershell
# Access tinker
docker-compose exec app php artisan tinker

# In tinker:
# Enable query logging
\DB::enableQueryLog();

# Perform an action
\App\Models\Service::get();

# View queries
dd(\DB::getQueryLog());

# Check slow queries
\DB::select("SELECT * FROM mysql.slow_log");

# Analyze table performance
\DB::statement("ANALYZE TABLE services");

# Optimize table
\DB::statement("OPTIMIZE TABLE services");
```

---

## 🎨 Frontend Debugging

### Browser DevTools

**Open DevTools:** F12 or Right-click → Inspect

**Tabs to check:**

1. **Console Tab**
   - JavaScript errors
   - Warning messages
   - API response logs

2. **Network Tab**
   - HTTP requests and responses
   - Failed requests (404, 500)
   - Request timing
   - Asset sizes

3. **Application Tab**
   - Cookies (check XSRF-TOKEN, laravel_session)
   - Local Storage
   - Session Storage

4. **Elements Tab**
   - HTML structure
   - CSS styling
   - Computed styles
   - Box model

### Common Frontend Issues

```javascript
// In browser console (F12 > Console):

// Check if jQuery loaded
typeof jQuery  // Should return "function"

// Check if Vue loaded (if using Vue)
typeof Vue  // Should return "function"

// Check CSRF token
document.querySelector('meta[name="csrf-token"]')?.content

// Test API endpoint
fetch('/api/services').then(r => r.json()).then(console.log)
```

### Clear Cache

```powershell
# Clear browser cache (DevTools)
# F12 > Application > Clear site data

# Hard refresh page
# Ctrl+Shift+R (or Cmd+Shift+R on Mac)

# Clear Nginx cache (if caching enabled)
docker-compose exec app php artisan cache:clear
docker-compose exec nginx nginx -s reload
```

---

## ⚡ Performance Debugging

### Measure Page Load Time

```powershell
# Use curl with timing
curl -w "@curl-format.txt" -o NUL -s http://localhost:8001

# Or use built-in timing
curl -w "Time: %{time_total}s\n" -o NUL -s http://localhost:8001
```

### Check Database Query Performance

```powershell
docker-compose exec app php artisan tinker

# In tinker:
\DB::enableQueryLog();

# Simulate page load
\App\Models\Service::with('projects')->get();

# View queries
dd(\DB::getQueryLog());

# Check slow queries
$queries = \DB::getQueryLog();
foreach ($queries as $query) {
    echo $query['time'] . "ms: " . $query['query'] . "\n";
}
```

### Monitor Resource Usage

```powershell
# Check container resource usage
docker stats

# View individual service stats
docker stats app mysql nginx

# Check memory usage
docker-compose exec app free -h

# Check disk usage
docker-compose exec app du -sh /var/www/html
```

---

## 🔒 Security Issues

### Check for Vulnerabilities

```powershell
# Check Laravel security advisories
docker-compose exec app composer audit

# Check dependencies
docker-compose exec app composer outdated

# Update composer packages
docker-compose exec app composer update
```

### Test CSRF Protection

```powershell
# 1. View page source with form
Ctrl+U on form page

# 2. Look for:
<input type="hidden" name="_token" value="ABC123...">

# 3. Should be different on each request
```

### Test Input Sanitization

```powershell
docker-compose exec app php artisan tinker

# In tinker:
// Test that malicious input is escaped
$service = \App\Models\Service::create([
    'title' => '<script>alert("XSS")</script>',
    'slug' => 'test',
    'description' => 'test'
]);

// Check saved value
dd($service->title); # Should show escaped HTML
```

---

## 🔧 Advanced Debugging

### Enable Query Logging

```powershell
# Edit config/database.php:
# Set 'logging' => true,

# Or set at runtime in tinker:
\DB::enableQueryLog();

# View all executed queries:
dd(\DB::getQueryLog());
```

### Use Laravel Debugbar

```powershell
# Install debugbar
docker-compose exec app composer require barryvdh/laravel-debugbar --dev

# Publish config
docker-compose exec app php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"

# Access at bottom of pages (with APP_DEBUG=true)
```

### Test Artisan Commands

```powershell
# Access artisan
docker-compose exec app php artisan

# List all commands
docker-compose exec app php artisan list

# Get help on command
docker-compose exec app php artisan help migrate

# Run command
docker-compose exec app php artisan cache:clear
```

### Test Email in Development

```powershell
# Set mail driver to log
# In .env.docker:
MAIL_DRIVER=log

# Send test email
docker-compose exec app php artisan tinker
# In tinker:
Mail::to('test@example.com')->send(new \App\Mail\ContactMail());

# Check log file
docker-compose exec app tail -20 /var/www/html/storage/logs/laravel.log

# You should see email in log output
```

---

## 📋 Quick Reference Commands

```powershell
# Container Access
docker-compose exec app bash               # Access PHP container
docker-compose exec mysql mysql            # Access MySQL

# Logs
docker-compose logs -f app                # Follow app logs
docker-compose logs app --tail=50         # Last 50 lines

# Cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Database
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan migrate:fresh

# Files
docker-compose exec app chown -R www-data:www-data storage
docker-compose exec app chmod -R 755 storage

# Build/Restart
docker-compose build --no-cache
docker-compose restart
docker-compose restart app

# Tinker (Interactive Shell)
docker-compose exec app php artisan tinker

# Check Health
docker-compose ps
docker stats
```

---

## 📞 Getting Help

When reporting issues, include:

1. **Error message** (exact text)
2. **Steps to reproduce** (how to trigger the issue)
3. **Environment** (OS, Docker version, PHP version)
4. **Logs** (relevant log excerpts)
5. **Screenshots** (if visual issue)

---

**Last Updated:** August 31, 2026  
**Document Version:** 1.0
