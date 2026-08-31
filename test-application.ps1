# Automated Test Suite - PowerShell
# ManjaAlunso Metalworks Application Testing

```powershell
# File: test-application.ps1
# Usage: .\test-application.ps1

# ============================================================================
# Color Output Functions
# ============================================================================

function Write-Success {
    param([string]$Message)
    Write-Host "✓ $Message" -ForegroundColor Green
}

function Write-Error {
    param([string]$Message)
    Write-Host "✗ $Message" -ForegroundColor Red
}

function Write-Info {
    param([string]$Message)
    Write-Host "ℹ $Message" -ForegroundColor Cyan
}

function Write-Warning {
    param([string]$Message)
    Write-Host "⚠ $Message" -ForegroundColor Yellow
}

# ============================================================================
# Test Functions
# ============================================================================

function Test-DockerServices {
    Write-Info "Testing Docker Services..."
    
    $services = @("app", "nginx", "mysql")
    $allRunning = $true
    
    foreach ($service in $services) {
        $status = docker-compose ps $service
        if ($status -match "Up") {
            Write-Success "$service is running"
        } else {
            Write-Error "$service is not running"
            $allRunning = $false
        }
    }
    
    return $allRunning
}

function Test-DatabaseConnection {
    Write-Info "Testing Database Connection..."
    
    try {
        $result = docker-compose exec -T mysql mysql -u root -proot -e "SELECT 1;" 2>&1
        if ($? -and $result -match "1") {
            Write-Success "Database connection successful"
            return $true
        }
    } catch {
        Write-Error "Database connection failed: $_"
        return $false
    }
}

function Test-ApplicationHealth {
    Write-Info "Testing Application Health..."
    
    try {
        $response = curl.exe -s -w "%{http_code}" http://localhost:8001 -o $null
        if ($response -eq "200") {
            Write-Success "Application responds with HTTP 200"
            return $true
        } else {
            Write-Error "Application returned HTTP $response"
            return $false
        }
    } catch {
        Write-Error "Cannot reach application: $_"
        return $false
    }
}

function Test-MigrationStatus {
    Write-Info "Checking Migration Status..."
    
    try {
        docker-compose exec app php artisan migrate:status | out-null
        if ($?) {
            Write-Success "Migrations executed successfully"
            return $true
        }
    } catch {
        Write-Warning "Migration status check failed: $_"
        return $false
    }
}

function Test-ServiceCRUD {
    Write-Info "Testing Service CRUD Operations..."
    
    $testsPassed = 0
    
    # Create
    try {
        $result = docker-compose exec -T app php artisan tinker --execute `
            "@{@echo false} `
            \$service = \App\Models\Service::create([ `
            'title' => 'Test Service', `
            'slug' => 'test-service-' . time(), `
            'description' => 'Test Description' `
            ]); `
            echo json_encode(\$service);"
        
        if ($? -and $result -match "Test Service") {
            Write-Success "Service creation test passed"
            $testsPassed++
        }
    } catch {
        Write-Error "Service creation failed: $_"
    }
    
    # Read
    try {
        $count = docker-compose exec -T app php artisan tinker --execute `
            "\App\Models\Service::count();" 2>&1
        
        if ($count -gt 0) {
            Write-Success "Service read test passed (found $count services)"
            $testsPassed++
        }
    } catch {
        Write-Error "Service read failed: $_"
    }
    
    # Update - assumes a service exists
    try {
        docker-compose exec -T app php artisan tinker --execute `
            "\$service = \App\Models\Service::first(); `
            \$service->update(['title' => 'Updated']); `
            echo 'Updated';" 2>&1 | out-null
        
        if ($?) {
            Write-Success "Service update test passed"
            $testsPassed++
        }
    } catch {
        Write-Error "Service update failed: $_"
    }
    
    # Delete
    try {
        docker-compose exec -T app php artisan tinker --execute `
            "\$service = \App\Models\Service::orderBy('created_at', 'desc')->first(); `
            if (\$service) \$service->delete();" 2>&1 | out-null
        
        if ($?) {
            Write-Success "Service delete test passed"
            $testsPassed++
        }
    } catch {
        Write-Error "Service delete failed: $_"
    }
    
    return $testsPassed
}

function Test-DatabaseSeed {
    Write-Info "Testing Database Seeding..."
    
    try {
        docker-compose exec app php artisan db:seed 2>&1 | out-null
        
        if ($?) {
            Write-Success "Database seeding successful"
            return $true
        }
    } catch {
        Write-Error "Database seeding failed: $_"
        return $false
    }
}

function Test-CacheClearing {
    Write-Info "Testing Cache Operations..."
    
    $caches = @("config:clear", "cache:clear", "view:clear")
    $passed = 0
    
    foreach ($cache in $caches) {
        try {
            docker-compose exec app php artisan $cache 2>&1 | out-null
            if ($?) {
                Write-Success "Cache $cache passed"
                $passed++
            }
        } catch {
            Write-Warning "Cache $cache failed: $_"
        }
    }
    
    return $passed -eq $caches.Count
}

function Test-LogFiles {
    Write-Info "Checking Log Files..."
    
    try {
        $logs = docker-compose exec -T app ls -lah storage/logs/ 2>&1
        
        if ($logs -match "laravel.log") {
            Write-Success "Log file exists"
            
            # Check for errors
            $errors = docker-compose exec -T app grep -i "error" storage/logs/laravel.log 2>&1
            $errorCount = @($errors | measure-object -line).Lines
            
            if ($errorCount -gt 0) {
                Write-Warning "Found $errorCount error entries in logs"
            } else {
                Write-Success "No errors in log file"
            }
            return $true
        }
    } catch {
        Write-Warning "Could not check logs: $_"
        return $false
    }
}

function Test-NginxConfig {
    Write-Info "Testing Nginx Configuration..."
    
    try {
        $test = docker-compose exec nginx nginx -t 2>&1
        if ($test -match "successful|ok") {
            Write-Success "Nginx configuration is valid"
            return $true
        }
    } catch {
        Write-Error "Nginx configuration test failed: $_"
        return $false
    }
}

function Start-Tests {
    Write-Host "`n╔════════════════════════════════════════════╗" -ForegroundColor Cyan
    Write-Host "║  ManjaAlunso Metalworks - Test Suite      ║" -ForegroundColor Cyan
    Write-Host "╚════════════════════════════════════════════╝`n" -ForegroundColor Cyan
    
    $results = @{
        DockerServices = 0
        DatabaseConnection = 0
        ApplicationHealth = 0
        MigrationStatus = 0
        ServiceCRUD = 0
        DatabaseSeed = 0
        CacheClearing = 0
        LogFiles = 0
        NginxConfig = 0
    }
    
    # Run tests
    if (Test-DockerServices) { $results.DockerServices = 1 }
    Start-Sleep -Milliseconds 500
    
    if (Test-DatabaseConnection) { $results.DatabaseConnection = 1 }
    Start-Sleep -Milliseconds 500
    
    if (Test-ApplicationHealth) { $results.ApplicationHealth = 1 }
    Start-Sleep -Milliseconds 500
    
    if (Test-MigrationStatus) { $results.MigrationStatus = 1 }
    Start-Sleep -Milliseconds 500
    
    if (Test-NginxConfig) { $results.NginxConfig = 1 }
    Start-Sleep -Milliseconds 500
    
    $results.ServiceCRUD = Test-ServiceCRUD
    Start-Sleep -Milliseconds 500
    
    if (Test-DatabaseSeed) { $results.DatabaseSeed = 1 }
    Start-Sleep -Milliseconds 500
    
    if (Test-CacheClearing) { $results.CacheClearing = 1 }
    Start-Sleep -Milliseconds 500
    
    if (Test-LogFiles) { $results.LogFiles = 1 }
    
    # Display summary
    Write-Host "`n╔════════════════════════════════════════════╗" -ForegroundColor Cyan
    Write-Host "║  Test Results Summary                      ║" -ForegroundColor Cyan
    Write-Host "╚════════════════════════════════════════════╝`n" -ForegroundColor Cyan
    
    foreach ($test in $results.Keys) {
        if ($results[$test] -eq 1) {
            Write-Host "  ✓ $test" -ForegroundColor Green
        } else {
            Write-Host "  ✗ $test" -ForegroundColor Red
        }
    }
    
    $totalPassed = ($results.Values | measure-object -Sum).Sum
    $totalTests = $results.Count
    
    Write-Host "`n  Total: $totalPassed / $totalTests tests passed`n" -ForegroundColor Cyan
    
    if ($totalPassed -eq $totalTests) {
        Write-Host "  ✓ All tests PASSED!" -ForegroundColor Green
        return 0
    } else {
        Write-Host "  ✗ Some tests FAILED. Review output above." -ForegroundColor Red
        return 1
    }
}

# ============================================================================
# Main Execution
# ============================================================================

$exitCode = Start-Tests
exit $exitCode
```

Save as `test-application.ps1` and run:

```powershell
.\test-application.ps1
```

---

## Manual Test Checklist

Use this checklist to manually verify functionality:

### Frontend Tests
- [ ] Home page loads (http://localhost:8001)
- [ ] All navigation links work
- [ ] Services display correctly
- [ ] Projects display correctly
- [ ] Testimonials carousel works
- [ ] Contact form displays
- [ ] Footer displays correctly
- [ ] No JavaScript errors (F12 → Console)

### Admin Panel Tests
- [ ] Can login with admin credentials
- [ ] Dashboard displays
- [ ] Services list displays
- [ ] Can create new service
- [ ] Can edit existing service
- [ ] Can delete service
- [ ] Projects CRUD works
- [ ] Testimonials CRUD works
- [ ] Galleries CRUD works
- [ ] Contact messages display
- [ ] Can mark contact as read
- [ ] Can delete contact message
- [ ] Site settings display
- [ ] Can update site settings

### Database Tests
- [ ] Services table populated
- [ ] Projects table populated
- [ ] Database queries complete fast
- [ ] No orphaned records
- [ ] Relationships work correctly
- [ ] Constraints enforced

### Performance Tests
- [ ] Page loads in < 3 seconds
- [ ] No 404 errors on assets
- [ ] Images are optimized
- [ ] No database errors
- [ ] Memory usage reasonable
- [ ] CPU usage reasonable

### Security Tests
- [ ] CSRF tokens present in forms
- [ ] SQL injection prevented
- [ ] XSS prevented
- [ ] Authentication required for admin
- [ ] Password hashes stored correctly
- [ ] Sessions secure
- [ ] No sensitive data in URLs

---

**Ready to run tests!** Follow the testing guide above.
