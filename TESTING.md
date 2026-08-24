# ManjaAlunso Metalworks - Testing Guide

## Prerequisites

- PHP 8.4+
- Composer dependencies installed (`composer install`)
- NPM dependencies installed (`npm install`)
- Database configured in `.env`
- Migrations run (`php artisan migrate`)
- Vite assets built (`npm run build`)
- Storage symlink created (`php artisan storage:link`)
- OAuth credentials configured in `.env` (Google/Facebook)

## Starting the Application

```bash
# Terminal 1: Start Laravel server
php artisan serve --port=8001

# Terminal 2 (optional): Start Vite dev server for hot reloading
npm run dev
```

Then visit: `http://127.0.0.1:8001`

---

## Authentication

### Login (`/login`)
- **Expected**: HTTP 200
- **Should show**: Email/password form + Google and Facebook OAuth buttons
- **No registration link** (removed for security)

### OAuth Login
- **Google**: Click "Google" button → redirects to Google → callback to `/auth/google/callback`
- **Facebook**: Click "Facebook" button → redirects to Facebook → callback to `/auth/facebook/callback`
- **Note**: Requires valid `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `FACEBOOK_CLIENT_ID`, `FACEBOOK_CLIENT_SECRET` in `.env`

### Logout (`POST /logout`)
- **Expected**: Redirects to home page
- **Session invalidated**

### Admin Protection
- All `/admin/*` routes require authentication
- Unauthenticated users are redirected to `/login` (302)
- After login, users are redirected to `/admin/dashboard`

### Test User
- Email: `admin@test.com`
- Password: `password`

---

## Frontend Pages (Public)

### 1. Homepage (`/`)
- **Expected**: HTTP 200
- **Should show**:
  - Hero section with dynamic background (if set in admin)
  - About Us section with dynamic image (if set in admin)
  - Services cards (if services exist)
  - Featured Projects (if featured projects exist)
  - Dynamic Gallery (if gallery items exist)
  - Industries served
  - Why Choose Us section with dynamic media (if set in admin)
  - Testimonials (if approved testimonials exist)
  - Statistics
  - Contact form

### 2. About Page (`/about`)
- **Expected**: HTTP 200
- **Should show**: About us content with dynamic image (if set in admin)

### 3. Services Page (`/services`)
- **Expected**: HTTP 200
- **Should show**: Grid of active services ordered by `sort_order`

### 4. Projects Page (`/projects`)
- **Expected**: HTTP 200
- **Should show**: Paginated list of all projects (9 per page)

### 5. Contact Page (`/contact`)
- **Expected**: HTTP 200
- **Should show**: Contact form + contact info + map
- **Test submission**: Fill form and submit → should redirect back with success message

---

## Admin Panel

Access admin routes at `/admin/*` (requires authentication).

### Dashboard (`/admin`)
- **Expected**: HTTP 200 (when authenticated)
- **Should show**:
  - Stats: Services count, Projects count, Testimonials count, Unread Messages count
  - Recent Messages table (last 5 contact messages)

### Site Settings (`/admin/site-settings`)
- **Expected**: HTTP 200
- **Should show**:
  - Hero Background Image upload
  - About Us Image upload
  - Why Choose Us Image/Video upload
- **Test**: Upload an image → should save and display preview

### Gallery (`/admin/galleries`)
- **Expected**: HTTP 200
- **Should show**: Table of gallery images with thumbnails, titles, sort order
- **CRUD**: Create, edit, delete gallery items with image upload

---

## Dynamic Content Testing

### Hero Background
1. Login to admin
2. Go to `/admin/site-settings`
3. Upload an image for "Hero Background Image"
4. Visit homepage `/`
5. **Verify**: Hero section uses uploaded image as background

### About Image
1. Go to `/admin/site-settings`
2. Upload an image for "About Us Image"
3. Visit `/about` or homepage
4. **Verify**: About section uses uploaded image

### Gallery
1. Go to `/admin/galleries`
2. Click "Add Image"
3. Upload image, set title and sort order
4. Visit homepage `/`
5. **Verify**: Gallery section shows uploaded images

### Why Choose Us Media
1. Go to `/admin/site-settings`
2. Upload an image for "Why Choose Us Image/Video"
3. Visit homepage `/`
4. **Verify**: Why Choose Us section uses uploaded media

---

## JavaScript Features

- **Theme Toggle**: Click moon/sun icon in admin header or navbar → toggles dark/light theme
- **Mobile Menu**: Hamburger button toggles navigation on mobile
- **Back to Top**: Button appears after scrolling 500px, click to scroll to top
- **Fade-in Animations**: Sections fade in as you scroll
- **Stat Counter**: Numbers animate from 0 to target when scrolled into view
- **Contact Form**: Submit shows "Sending..." → "Request Sent!" feedback
- **Smooth Scroll**: Anchor links scroll smoothly to sections

---

## Database Verification via Tinker

```bash
php artisan tinker
```

### Test Gallery CRUD
```php
// Create
App\Models\Gallery::create([
    'title' => 'Steel Assembly',
    'image_path' => 'galleries/image.jpg',
    'sort_order' => 1
]);

// Read
App\Models\Gallery::orderBy('sort_order')->get();

// Update
App\Models\Gallery::where('id', 1)->update(['title' => 'Updated Title']);

// Delete
App\Models\Gallery::where('id', 1)->delete();
```

### Test Site Settings
```php
// Create
App\Models\SiteSetting::create([
    'key' => 'hero_background',
    'value' => 'settings/hero.jpg',
    'type' => 'image'
]);

// Read
App\Models\SiteSetting::all()->keyBy('key');

// Update
App\Models\SiteSetting::where('key', 'hero_background')->update(['value' => 'settings/new-hero.jpg']);
```

---

## Known Issues / Notes

- OAuth requires valid provider credentials in `.env`
- Image uploads require `public/storage` symlink (`php artisan storage:link`)
- Theme toggle works via session cookie
- Mobile responsive breakpoints: 900px and 500px
- No email verification required for OAuth logins

---

## Quick Health Check Script

```bash
#!/bin/bash
# Run all pages and check status

echo "Testing Frontend..."
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/ | grep -q 200 && echo "Home: OK" || echo "Home: FAIL"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/about | grep -q 200 && echo "About: OK" || echo "About: FAIL"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/services | grep -q 200 && echo "Services: OK" || echo "Services: FAIL"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/projects | grep -q 200 && echo "Projects: OK" || echo "Projects: FAIL"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/contact | grep -q 200 && echo "Contact: OK" || echo "Contact: FAIL"

echo "Testing Admin (should redirect to login)..."
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/admin | grep -q 302 && echo "Dashboard redirect: OK" || echo "Dashboard redirect: FAIL"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/admin/galleries | grep -q 302 && echo "Galleries redirect: OK" || echo "Galleries redirect: FAIL"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8001/admin/site-settings | grep -q 302 && echo "Settings redirect: OK" || echo "Settings redirect: FAIL"
```
