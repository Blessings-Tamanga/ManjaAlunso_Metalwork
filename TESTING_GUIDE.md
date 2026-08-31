# CRUD & Functionality Testing Guide
## ManjaAlunso Metalworks Application

**Date:** August 31, 2026  
**Version:** 1.0  
**Status:** Ready for Testing

---

## 📋 Test Overview

This document provides comprehensive testing procedures for all CRUD operations and presentation aspects of the ManjaAlunso Metalworks application.

### Application Architecture
- **Backend:** Laravel 11 (PHP 8.4)
- **Database:** MySQL 8.0
- **Frontend:** Blade Templates + Vite
- **Admin Panel:** CRUD operations with authentication

### Core Entities to Test
1. Services (CRUD)
2. Projects (CRUD)
3. Testimonials (CRUD)
4. Galleries (CRUD)
5. Site Settings (Read/Update)
6. Contact Messages (Read/Delete/Mark Read)
7. User Authentication

---

## 🚀 Pre-Test Setup

### 1. Start Application

```powershell
cd "C:\Users\DELL\Documents\Github_repo\Laravel_Projects\ManjaAlunso_Metalworks"

# Start services
docker-compose up -d

# Wait 30 seconds for services to initialize
Start-Sleep -Seconds 30

# Run migrations
docker-compose exec app php artisan migrate --force

# Create test data
docker-compose exec app php artisan db:seed
```

### 2. Verify Services

```powershell
# Check all services are running
docker-compose ps

# Expected output:
# NAME      STATUS
# app       Up (healthy)
# nginx     Up
# mysql     Up (healthy)
```

### 3. Access Points

- **Application:** http://localhost:8001
- **Admin Panel:** http://localhost:8001/admin
- **Database:** localhost:3306
- **MySQL User:** root
- **MySQL Password:** (from .env.docker)

---

## ✅ Frontend Presentation Tests

### Test 1: Home Page Load

**Objective:** Verify home page renders correctly

**Steps:**
1. Navigate to http://localhost:8001
2. Wait for page to fully load

**Verification Points:**
```
✓ Page loads without 404/500 errors
✓ Header navigation is visible
✓ Hero section displays correctly
✓ Services section shows at least 3 items
✓ Projects section displays thumbnails
✓ Testimonials carousel works
✓ Footer displays with links
✓ No JavaScript console errors (F12 → Console tab)
✓ No mixed content warnings (HTTPS content)
✓ Page responds in < 2 seconds
```

**Debugging if Failed:**
```bash
# Check application logs
docker-compose logs app

# Check Nginx configuration
docker-compose logs nginx

# Verify database connection
docker-compose exec app php artisan tinker
# In tinker: \DB::connection()->getPdo(); # Should return PDO object
```

### Test 2: Navigation Menu

**Objective:** Verify all navigation links work

**Steps:**
1. From home page, click each navigation link:
   - About
   - Services
   - Projects
   - Contact

**Verification Points:**
```
✓ Each link navigates to correct page
✓ Active navigation item is highlighted
✓ All pages load without errors
✓ Back navigation works
✓ Mobile menu works (if responsive design exists)
```

### Test 3: Services Display

**Objective:** Verify services render from database

**Steps:**
1. Navigate to Services page (http://localhost:8001/services)
2. Check each service is displayed

**Verification Points:**
```
✓ Services from database display
✓ Service icons are visible
✓ Service descriptions show correctly
✓ Services are sorted by sort_order
✓ Only active services display (is_active = true)
✓ No duplicate services
```

### Test 4: Projects Display

**Objective:** Verify projects render from database

**Steps:**
1. Navigate to Projects page (http://localhost:8001/projects)
2. Check layout and details

**Verification Points:**
```
✓ Projects display with images
✓ Project titles are visible
✓ Project descriptions show
✓ Project links work (if any)
✓ Only published projects show
✓ Pagination works (if more than 12 items)
```

### Test 5: Testimonials Section

**Objective:** Verify testimonials display and carousel works

**Steps:**
1. On home page, find testimonials section
2. Scroll through carousel (if auto-scroll exists)
3. Click navigation arrows (if present)

**Verification Points:**
```
✓ Testimonials display with author names
✓ Ratings/ratings display if included
✓ Carousel auto-rotates (if feature exists)
✓ Previous/Next buttons work
✓ Active slide is highlighted
✓ Only active testimonials show
```

### Test 6: Contact Page Form

**Objective:** Verify contact form renders and validates

**Steps:**
1. Navigate to Contact page
2. View form fields

**Verification Points:**
```
✓ Form displays correctly
✓ All required fields are labeled
✓ Form layout is responsive
✓ Submit button is visible
✓ Success/error message area visible
```

---

## 🔐 Authentication Tests

### Test 7: Login Functionality

**Objective:** Verify user login works

**Steps:**
1. Navigate to http://localhost:8001/login
2. Enter test credentials:
   ```
   Email: admin@example.com
   Password: password
   ```

**Verification Points:**
```
✓ Login form displays
✓ Email field accepts valid email
✓ Password field masks input
✓ "Remember me" checkbox (if exists)
✓ Login button is clickable
✓ Successful login redirects to dashboard
✓ Failed login shows error message
✓ Invalid credentials rejected
✓ Session created (check cookies)
```

**Check Session:**
```powershell
# In browser F12 > Application > Cookies > localhost:8001
# Look for: XSRF-TOKEN, laravel_session
```

### Test 8: Invalid Login Attempts

**Objective:** Verify error handling for invalid credentials

**Steps:**
1. Try login with wrong password
2. Try login with non-existent email
3. Try login without credentials
4. Try SQL injection in email field

**Verification Points:**
```
✓ Invalid password shows: "These credentials do not match our records"
✓ Non-existent email shows same generic message (security)
✓ Empty email field shows validation error
✓ Empty password field shows validation error
✓ SQL injection attempt fails safely
✓ Error messages don't leak information
✓ Rate limiting (optional): After 5 attempts, show cooldown
```

---

## 📝 CRUD Operations - Services

### Test 9: Create Service

**Objective:** Test service creation

**Steps:**
1. Login as admin
2. Navigate to Admin → Services
3. Click "Create Service"
4. Fill form:
   ```
   Title: "Test Service"
   Slug: "test-service" (should be auto-generated)
   Icon: "fas fa-star" (or similar)
   Description: "This is a test service"
   Sort Order: 5
   Active: ☑️ (checked)
   ```
5. Click "Save"

**Verification Points:**
```
✓ All form fields render correctly
✓ Slug field validates uniqueness
✓ Icon field shows available options
✓ Description field accepts rich text (if WYSIWYG editor exists)
✓ Sort order accepts integers
✓ "Active" toggle works
✓ "Save" button is functional
✓ Redirect to services list on success
✓ Success message displays: "Service created"
✓ New service appears in list
✓ Service data is correct in database
```

**Verify in Database:**
```powershell
docker-compose exec app php artisan tinker
# In tinker:
\App\Models\Service::latest()->first()
```

### Test 10: Read Service List

**Objective:** Verify service list displays correctly

**Steps:**
1. From admin dashboard, go to Services
2. Observe the list

**Verification Points:**
```
✓ All services display in table
✓ Columns show: ID, Title, Slug, Icon, Description, Sort Order, Active, Actions
✓ Services sorted by sort_order
✓ Edit button is present
✓ Delete button is present
✓ Pagination works (if more than 15 items)
✓ "Create Service" button visible at top
✓ Table is responsive on mobile
✓ Search functionality works (if implemented)
✓ Filter by active status works (if implemented)
```

### Test 11: Update Service

**Objective:** Test service editing

**Steps:**
1. In Services list, click Edit on a service
2. Modify fields:
   - Title: "Updated Service"
   - Description: "Updated description"
   - Sort Order: 10
   - Active: Uncheck
3. Click "Update"

**Verification Points:**
```
✓ Edit form pre-populates with current data
✓ All fields are editable
✓ Slug field shows unique validation (excluding current record)
✓ Changes are saved correctly
✓ Redirect to list on success
✓ Success message shows
✓ Service list shows updated data
✓ Inactive service no longer appears on frontend
✓ Database shows updated timestamp
```

**Verify Update:**
```powershell
docker-compose exec app php artisan tinker
# In tinker:
\App\Models\Service::find(ID)->toArray()
```

### Test 12: Delete Service

**Objective:** Test service deletion

**Steps:**
1. In Services list, click Delete on a service
2. Confirm deletion (if confirmation dialog)

**Verification Points:**
```
✓ Confirmation dialog appears (recommended)
✓ Service removed from list
✓ Success message displays
✓ Cannot delete via URL tampering
✓ Database shows record is deleted (soft delete) or gone (hard delete)
✓ Frontend no longer shows deleted service
✓ No orphaned references in projects/galleries
```

**Verify Deletion:**
```powershell
docker-compose exec app php artisan tinker
# In tinker (soft delete check):
\App\Models\Service::withTrashed()->find(ID)

# In tinker (hard delete check):
\App\Models\Service::find(ID) # Returns null
```

---

## 📁 CRUD Operations - Projects

### Test 13-16: Projects CRUD

**Repeat tests 9-12 for Projects with fields:**

```
Title: "New Project"
Slug: "new-project"
Description: "Project details"
Client: "Client Name"
Category: (select from list)
Image/Thumbnail: (file upload)
Gallery: (attach gallery items)
Link: "https://example.com"
Sort Order: 1
Active: ☑️
```

**Additional Verification Points:**
```
✓ Image upload works
✓ Image preview shows
✓ Supported formats: jpg, jpeg, png, webp
✓ Image size limit enforced
✓ Gallery attachment works
✓ Gallery items display in project
✓ External links open in new tab
✓ Project appears on frontend when active
✓ Project disappears from frontend when inactive
```

---

## 🎤 CRUD Operations - Testimonials

### Test 17-20: Testimonials CRUD

**Test with fields:**

```
Author Name: "John Doe"
Position: "CEO"
Company: "Company Name"
Content: "Great service!"
Rating: 5 (if star rating exists)
Image: (profile photo)
Active: ☑️
```

**Specific Verification:**
```
✓ Star rating selector works (1-5 stars)
✓ Image upload and preview
✓ Testimonial appears on homepage carousel
✓ Testimonial carousel rotates testimonials
✓ Only active testimonials display
✓ Author details display correctly
✓ Testimonial quote formatting preserved
```

---

## 🖼️ CRUD Operations - Galleries

### Test 21-24: Galleries CRUD

**Test with fields:**

```
Title: "Gallery Name"
Description: "Gallery description"
Images: (bulk image upload)
Cover Image: (select main image)
Active: ☑️
Associated Project: (select project)
```

**Specific Verification:**
```
✓ Multi-image upload works
✓ Drag-to-reorder images works
✓ Delete individual images from gallery
✓ Set cover image as thumbnail
✓ Gallery displays in project details
✓ Lightbox/modal opens images
✓ Image navigation in lightbox works
✓ Gallery filter by project works
```

---

## ⚙️ Site Settings Tests

### Test 25: Update Site Settings

**Objective:** Verify site settings can be viewed and updated

**Steps:**
1. Go to Admin → Site Settings
2. Modify settings:
   - Site Name
   - Site Email
   - Phone Number
   - Address
   - Social Media Links
3. Save changes

**Verification Points:**
```
✓ All settings display in form
✓ Settings are editable
✓ Data persists after page reload
✓ Changes appear on frontend
✓ Phone links work (tel: protocol)
✓ Email links work (mailto: protocol)
✓ Social media links are correct
✓ Address displays on contact page/footer
✓ Updates trigger cache clear (if cache used)
```

---

## 📧 Contact Messages Tests

### Test 26: Submit Contact Form

**Objective:** Test contact message submission from frontend

**Steps:**
1. Navigate to Contact page
2. Fill form:
   ```
   Name: "Test User"
   Email: "test@example.com"
   Phone: "123-456-7890"
   Subject: "Test Subject"
   Message: "This is a test message"
   ```
3. Check CAPTCHA if present
4. Submit form

**Verification Points:**
```
✓ All fields validate correctly
✓ Email field accepts valid email
✓ Phone field accepts formats
✓ Message field accepts rich text
✓ CAPTCHA validates (if implemented)
✓ Form submits successfully
✓ Success message displays
✓ Confirmation email sent (check inbox/logs)
✓ Message saved to database
✓ Admin receives notification
```

**Verify Message Saved:**
```powershell
docker-compose exec app php artisan tinker
# In tinker:
\App\Models\ContactMessage::latest()->first()
```

### Test 27: Admin View Contact Messages

**Objective:** Verify admins can view submitted messages

**Steps:**
1. Login as admin
2. Go to Admin → Contacts
3. Observe the list

**Verification Points:**
```
✓ All messages display
✓ Unread messages highlighted (different background color)
✓ Message preview visible
✓ Sender info (name, email, phone) shown
✓ Submission date/time displayed
✓ Mark as read button works
✓ Delete button works
✓ Reply option available (if feature exists)
✓ Export messages (if feature exists)
✓ Search messages (if feature exists)
✓ Filter by read/unread status
```

### Test 28: Mark Contact Message as Read

**Objective:** Test marking messages as read

**Steps:**
1. In Contacts list, click "Mark as Read" on unread message
2. Observe the list

**Verification Points:**
```
✓ Message status changes to "Read"
✓ Message background color changes (if highlight used)
✓ Unread count decreases
✓ Action is reversible (can mark unread again)
✓ Success message displays
✓ Database timestamp updated
```

### Test 29: Delete Contact Message

**Objective:** Test message deletion

**Steps:**
1. In Contacts list, click "Delete" on a message
2. Confirm deletion

**Verification Points:**
```
✓ Confirmation dialog appears
✓ Message removed from list
✓ Database record deleted
✓ Unread count updated if was unread
✓ Cannot recover deleted message (permanent delete)
```

---

## 🔍 UI/UX & Presentation Tests

### Test 30: Responsive Design

**Objective:** Verify layout works on all screen sizes

**Steps:**
1. Open http://localhost:8001
2. Open browser DevTools (F12)
3. Toggle device toolbar (Ctrl+Shift+M)
4. Test viewport sizes:
   - Mobile: 375x667 (iPhone)
   - Tablet: 768x1024 (iPad)
   - Desktop: 1920x1080
   - Laptop: 1366x768

**Verification Points for Each Size:**
```
✓ Layout reflows correctly
✓ Navigation hamburger menu appears on mobile
✓ Text remains readable
✓ Images scale appropriately
✓ Buttons are touch-sized on mobile
✓ No horizontal scroll on mobile
✓ Forms display in single column on mobile
✓ Tables scroll horizontally on small screens
✓ Breakpoints trigger at correct sizes
```

### Test 31: Cross-Browser Compatibility

**Objective:** Test application in different browsers

**Browsers to Test:**
- Chrome/Chromium
- Firefox
- Edge
- Safari (if available)

**Verification Points:**
```
✓ Layout displays correctly
✓ Buttons and forms work
✓ CSS styling renders
✓ JavaScript functionality works
✓ No console errors
✓ Fonts display correctly
✓ Colors display correctly
✓ Animations/transitions work
```

### Test 32: Performance

**Objective:** Verify page load performance

**Steps:**
1. Open DevTools (F12)
2. Go to Network tab
3. Reload http://localhost:8001
4. Check performance metrics

**Verification Points:**
```
✓ Page load time < 3 seconds
✓ First Contentful Paint < 1.5s
✓ Largest Contentful Paint < 2.5s
✓ Cumulative Layout Shift < 0.1
✓ No 404 errors on assets
✓ Images properly compressed
✓ CSS/JS are minified
✓ Database queries are optimized
```

**Check in Console:**
```javascript
// Run in browser console:
window.performance.timing.loadEventEnd - window.performance.timing.navigationStart
// Should be < 3000ms
```

### Test 33: Accessibility

**Objective:** Verify WCAG 2.1 AA compliance

**Steps:**
1. Install WAVE accessibility extension
2. Scan each page
3. Or test manually:

**Verification Points:**
```
✓ Headings are in logical order (h1, h2, h3...)
✓ Images have alt text
✓ Form labels associated with inputs
✓ Color contrast ratio ≥ 4.5:1 for text
✓ Interactive elements keyboard accessible
✓ Tab order is logical
✓ No keyboard traps
✓ Error messages are clear
✓ Skip links present
✓ ARIA labels used where needed
```

---

## 🐛 Error Handling Tests

### Test 34: 404 Error Page

**Objective:** Verify 404 page displays correctly

**Steps:**
1. Navigate to http://localhost:8001/nonexistent-page
2. Observe the page

**Verification Points:**
```
✓ Custom 404 page displays
✓ Error message is clear
✓ Navigation links available
✓ "Go home" link works
✓ No 500 server error
✓ Logging shows 404 event
```

### Test 35: 500 Error Handling

**Objective:** Test server error handling

**Steps:**
1. Trigger server error by running:
   ```bash
   docker-compose exec app php artisan tinker
   # In tinker:
   throw new Exception("Test error");
   ```
2. Navigate to app and observe error handling

**Verification Points:**
```
✓ User-friendly error page displays
✓ Stack trace not exposed to users
✓ Error logged on server
✓ Support contact information provided
✓ Error ID/reference shown for support
✓ Navigation still available
```

### Test 36: Database Disconnection

**Objective:** Test behavior when database is unavailable

**Steps:**
1. Stop MySQL:
   ```bash
   docker-compose stop mysql
   ```
2. Try to load a page requiring database
3. Restart MySQL:
   ```bash
   docker-compose start mysql
   ```

**Verification Points:**
```
✓ Error message is informative
✓ No blank page
✓ Logging records the error
✓ Recovery is automatic after database restart
✓ No data corruption after recovery
```

---

## 🔒 Security Tests

### Test 37: CSRF Protection

**Objective:** Verify CSRF tokens protect forms

**Steps:**
1. View page source (Ctrl+Shift+I > Elements)
2. Look for CSRF token in forms

**Verification Points:**
```
✓ @csrf token present in all forms
✓ Token is different per request
✓ Removing token causes 419 error
✓ X-CSRF-TOKEN header in AJAX requests
```

### Test 38: SQL Injection Prevention

**Objective:** Verify SQL injection is prevented

**Steps:**
1. Try entering in a field: `" OR 1=1 --`
2. Try in search field: `'; DROP TABLE services; --`

**Verification Points:**
```
✓ Input treated as literal string
✓ Queries execute safely
✓ No database modification
✓ Error handling prevents info leak
✓ Logging records attempt
```

### Test 39: XSS Prevention

**Objective:** Verify Cross-Site Scripting is prevented

**Steps:**
1. Try entering in a field: `<script>alert('XSS')</script>`
2. Submit and view on page

**Verification Points:**
```
✓ Script tags are escaped
✓ No alert popup appears
✓ HTML rendered as text
✓ Data displayed safely
✓ Input sanitized on save
```

### Test 40: Authentication Bypass

**Objective:** Verify protected routes require login

**Steps:**
1. Logout
2. Try accessing http://localhost:8001/admin directly
3. Try accessing API routes directly

**Verification Points:**
```
✓ Redirects to login page
✓ After login, returns to intended page
✓ Session cannot be hijacked
✓ Token-based access works correctly
✓ Middleware enforces authentication
```

---

## 📊 Database Integrity Tests

### Test 41: Data Validation

**Objective:** Verify database constraints

**Steps:**
1. Try creating service with duplicate slug:
   ```bash
   docker-compose exec app php artisan tinker
   # In tinker:
   \App\Models\Service::create(['title' => 'Test', 'slug' => 'existing-slug'])
   ```

**Verification Points:**
```
✓ Unique constraint prevents duplicates
✓ Required fields enforced
✓ Type casting works (integers, booleans)
✓ Date fields store correctly
✓ Relationships maintain referential integrity
✓ Cascade deletes work (if configured)
```

### Test 42: Soft Deletes

**Objective:** Verify soft delete functionality (if implemented)

**Steps:**
1. Delete a service from admin panel
2. Check database:
   ```bash
   docker-compose exec app php artisan tinker
   # In tinker:
   \App\Models\Service::withTrashed()->find(ID)
   \App\Models\Service::onlyTrashed()->get()
   ```

**Verification Points:**
```
✓ deleted_at timestamp is set
✓ Record doesn't appear in regular queries
✓ withTrashed() returns soft-deleted records
✓ Restore functionality works (if available)
✓ Force delete works (permanent removal)
```

---

## 🔄 API Tests (If API exists)

### Test 43: List Services API

```bash
curl http://localhost:8001/api/services
```

**Verification Points:**
```
✓ Returns JSON response
✓ Status code 200
✓ Data structure is consistent
✓ Pagination works (if implemented)
✓ Filtering works (if implemented)
✓ Sorting works (if implemented)
```

### Test 44: Create Service API

```bash
curl -X POST http://localhost:8001/api/services \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{"title":"API Test","slug":"api-test"}'
```

**Verification Points:**
```
✓ Requires authentication
✓ Validates input
✓ Returns created resource
✓ Status code 201 (Created)
✓ Location header provided
```

---

## ✨ Success Criteria

All tests pass when:

```
✓ All 44 tests pass
✓ No critical errors in logs
✓ Frontend loads in < 3 seconds
✓ Database operations complete < 500ms
✓ No SQL errors
✓ No JavaScript errors
✓ Responsive on all screen sizes
✓ Works in all major browsers
✓ CSRF protection enabled
✓ User data is secure
✓ Admin functions restricted to authenticated users
✓ Validation prevents invalid data entry
✓ Error messages are user-friendly
✓ Navigation is intuitive
✓ All links work
✓ Forms submit successfully
✓ Deletions are permanent or recoverable as designed
✓ Updates persist
✓ Pagination works
✓ Sorting works
✓ Search works (if implemented)
```

---

## 🐛 Automated Testing Script

Create a test script `run_tests.sh`:

```bash
#!/bin/bash

echo "=== Starting Application Tests ==="

# Ensure services are running
echo "1. Checking Docker services..."
docker-compose ps | grep -E "app|nginx|mysql"

# Run Laravel tests
echo "2. Running Laravel tests..."
docker-compose exec app php artisan test

# Run migrations
echo "3. Running migrations..."
docker-compose exec app php artisan migrate --force

# Seed test data
echo "4. Seeding database..."
docker-compose exec app php artisan db:seed

# Health check
echo "5. Health check..."
curl -s http://localhost:8001 | grep -q "html" && echo "✓ App responds" || echo "✗ App not responding"

echo "=== Tests Complete ==="
```

Run with:
```bash
chmod +x run_tests.sh
./run_tests.sh
```

---

## 📝 Test Report Template

Use this template to document your test results:

```
Test Run Date: _______________
Tester Name: __________________
Application Version: __________
Database: ____________________

Test Results Summary:
├─ Frontend Tests: ___/5 passed
├─ Authentication: ___/2 passed
├─ CRUD Operations: ___/16 passed
├─ Site Settings: ___/1 passed
├─ Contact Forms: ___/4 passed
├─ UI/UX: ___/4 passed
├─ Error Handling: ___/3 passed
├─ Security: ___/4 passed
├─ Database: ___/2 passed
├─ API: ___/2 passed
└─ TOTAL: ___/44 tests passed

Critical Issues Found:
- Issue 1: _______________
- Issue 2: _______________

Recommendations:
- Recommendation 1: _______________
- Recommendation 2: _______________

Sign-Off:
Tester Signature: ______________  Date: __________
```

---

## 🔧 Quick Debug Commands

```bash
# View application logs
docker-compose logs -f app

# View database logs
docker-compose logs -f mysql

# Access application shell
docker-compose exec app bash

# Access database
docker-compose exec mysql mysql -u root -proot laravel_app

# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Run tinker for interactive testing
docker-compose exec app php artisan tinker

# Check application version
docker-compose exec app php -v

# Verify composer packages
docker-compose exec app composer show
```

---

**Document Version:** 1.0  
**Last Updated:** August 31, 2026  
**Status:** Ready for Implementation
