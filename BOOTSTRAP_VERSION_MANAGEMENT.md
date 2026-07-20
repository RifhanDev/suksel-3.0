# Bootstrap Version Management Guide

This document provides step-by-step instructions for managing Bootstrap versions in the Suksel 3.0 project.

---

## Table of Contents
1. [Current Setup Overview](#current-setup-overview)
2. [How to Revert to Bootstrap 3/4](#how-to-revert-to-bootstrap-34)
3. [How to Upgrade to Bootstrap 5](#how-to-upgrade-to-bootstrap-5)
4. [Troubleshooting](#troubleshooting)

---

## Current Setup Overview

The project currently supports **dual Bootstrap versions**:
- **Bootstrap 4** - Used by the original/legacy layouts
- **Bootstrap 5** - Used by the modern layout

### File Structure
```
resources/
├── js/
│   ├── app.js          # Original app (Bootstrap 4)
│   └── modern.js       # Modern app (Bootstrap 5)
├── sass/
│   ├── app.scss        # Original styles (Bootstrap 4)
│   └── modern.scss     # Modern styles (Bootstrap 5)
└── views/
    └── layouts/
        ├── default.blade.php     # Uses Bootstrap 4
        └── modern.blade.php      # Uses Bootstrap 5

public/
├── css/
│   ├── app.css         # Compiled Bootstrap 4 styles
│   └── modern.css      # Compiled Bootstrap 5 styles
└── js/
    ├── app.js          # Compiled Bootstrap 4 scripts
    └── modern.js       # Compiled Bootstrap 5 scripts
```

### Package Configuration
```json
"bootstrap": "^4.1.0",           // Bootstrap 4 for legacy
"bootstrap5": "npm:bootstrap@^5.3.0"  // Bootstrap 5 aliased
```

---

## How to Revert to Bootstrap 3/4

Follow these steps if you need to revert the entire project to use only Bootstrap 3 or 4.

### Step 1: Backup Current Files
```bash
# Create backup directory
mkdir -p backups/bootstrap5

# Backup Bootstrap 5 files
cp resources/js/modern.js backups/bootstrap5/
cp resources/sass/modern.scss backups/bootstrap5/
cp resources/views/layouts/modern.blade.php backups/bootstrap5/
cp webpack.mix.js backups/bootstrap5/
```

### Step 2: Update package.json

**Option A: Revert to Bootstrap 4 Only**
```json
{
  "devDependencies": {
    "axios": "^1.12.2",
    "bootstrap": "^4.6.2",
    "cross-env": "^5.1",
    "jquery": "^3.2",
    "laravel-mix": "^6.0.49",
    "lodash": "^4.17.5",
    "popper.js": "^1.12",
    "resolve-url-loader": "^5.0.0",
    "sass": "^1.32.0",
    "sass-loader": "^12.0.0",
    "vue": "^3.5.22",
    "vue-loader": "^16.8.3"
  }
}
```

**Option B: Revert to Bootstrap 3**
```json
{
  "devDependencies": {
    "axios": "^1.12.2",
    "bootstrap-sass": "^3.4.1",
    "cross-env": "^5.1",
    "jquery": "^3.2",
    "laravel-mix": "^6.0.49",
    "lodash": "^4.17.5",
    "resolve-url-loader": "^5.0.0",
    "sass": "^1.32.0",
    "sass-loader": "^12.0.0",
    "vue": "^3.5.22",
    "vue-loader": "^16.8.3"
  }
}
```

### Step 3: Update webpack.mix.js

Remove the modern build and keep only the original app:

```javascript
const mix = require('laravel-mix');

// Original app only
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');
```

### Step 4: Update Blade Layouts

**For Bootstrap 4:**
Ensure your main layout (`resources/views/layouts/default.blade.php`) uses:
```html
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<script src="{{ asset('js/app.js') }}"></script>
```

**For Bootstrap 3:**
Update `resources/sass/app.scss`:
```scss
// Bootstrap 3
@import "~bootstrap-sass/assets/stylesheets/bootstrap";
```

### Step 5: Reinstall Dependencies
```bash
# Remove existing dependencies
rm -rf node_modules package-lock.json

# Install fresh dependencies
npm install

# Compile assets
npm run dev
```

### Step 6: Update Views

- Remove or comment out references to `modern.css` and `modern.js`
- Update all views to use the single layout
- Remove Bootstrap 5 specific classes (if reverting to Bootstrap 3/4)

### Step 7: Clean Up (Optional)
```bash
# Archive modern layout files
mv resources/js/modern.js resources/js/modern.js.bak
mv resources/sass/modern.scss resources/sass/modern.scss.bak
mv resources/views/layouts/modern.blade.php resources/views/layouts/modern.blade.php.bak

# Remove compiled Bootstrap 5 assets
rm public/css/modern.css
rm public/js/modern.js
```

---

## How to Upgrade to Bootstrap 5

Follow these steps to upgrade from Bootstrap 3/4 to Bootstrap 5 (dual setup).

### Step 1: Update package.json

Add Bootstrap 5 alongside existing Bootstrap:

```json
{
  "devDependencies": {
    "axios": "^1.12.2",
    "bootstrap": "^4.6.2",
    "bootstrap5": "npm:bootstrap@^5.3.0",
    "cross-env": "^5.1",
    "jquery": "^3.2",
    "laravel-mix": "^6.0.49",
    "lodash": "^4.17.5",
    "popper.js": "^1.12",
    "resolve-url-loader": "^5.0.0",
    "sass": "^1.32.0",
    "sass-loader": "^12.0.0",
    "vue": "^3.5.22",
    "vue-loader": "^16.8.3"
  }
}
```

**Key Change:** The `bootstrap5` alias allows you to have both versions installed:
```json
"bootstrap5": "npm:bootstrap@^5.3.0"
```

### Step 2: Install Dependencies

```bash
# Clean install
rm -rf node_modules package-lock.json
npm install
```

### Step 3: Create Modern JavaScript Entry

Create `resources/js/modern.js`:

```javascript
/**
 * Modern App - Bootstrap 5
 */

// Load Bootstrap 5
import 'bootstrap5';

// Load Vue 3 (if needed)
import { createApp } from 'vue';

// Your modern app components here
const app = createApp({});

// Mount Vue app
app.mount('#app');

// Modern JavaScript functionality
console.log('Modern app loaded with Bootstrap 5');
```

### Step 4: Create Modern Styles

Create `resources/sass/modern.scss`:

```scss
// Bootstrap 5 Variables Override (optional)
// $primary: #your-color;

// Import Bootstrap 5
@import '~bootstrap5/scss/bootstrap';

// Your custom modern styles
body {
    font-family: 'Nunito', sans-serif;
}

// Modern layout specific styles
.modern-layout {
    // Your custom styles
}
```

### Step 5: Update webpack.mix.js

Add the modern build pipeline:

```javascript
const mix = require('laravel-mix');

// Original app (Bootstrap 3/4)
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

// Modern layout (Bootstrap 5)
mix.js('resources/js/modern.js', 'public/js')
    .vue()
    .sass('resources/sass/modern.scss', 'public/css');
```

### Step 6: Create Modern Layout

Create `resources/views/layouts/modern.blade.php`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Bootstrap 5 Styles -->
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div id="app">
        @include('partials.modern-navbar')

        <main class="py-4">
            @yield('content')
        </main>

        @include('partials.modern-footer')
    </div>

    <!-- Bootstrap 5 Scripts -->
    <script src="{{ asset('js/modern.js') }}"></script>

    @stack('scripts')
</body>
</html>
```

### Step 7: Compile Assets

```bash
# Development build
npm run dev

# Or for production
npm run prod
```

### Step 8: Update Controllers/Routes

Choose which layout to use per route:

```php
// Use modern layout (Bootstrap 5)
return view('pages.dashboard')->layout('layouts.modern');

// Use legacy layout (Bootstrap 3/4)
return view('pages.legacy-page')->layout('layouts.default');
```

### Step 9: Bootstrap 5 Migration Checklist

When creating new views with Bootstrap 5, note these major changes:

**Class Changes:**
- `ml-*` / `mr-*` → `ms-*` / `me-*` (margin)
- `pl-*` / `pr-*` → `ps-*` / `pe-*` (padding)
- `float-left` / `float-right` → `float-start` / `float-end`
- `form-group` → removed (use margin utilities)
- `form-row` → `row g-2`
- `custom-control` → `form-check`
- `custom-select` → `form-select`
- `badge-*` → `bg-*` (e.g., `badge-primary` → `badge bg-primary`)
- `media` → removed (use flexbox/grid)
- `jumbotron` → removed (use cards or custom styles)

**JavaScript Changes:**
- jQuery no longer required
- Data attributes: `data-toggle` → `data-bs-toggle`
- Data attributes: `data-target` → `data-bs-target`
- All BS5 events are namespaced with `.bs.*`

**Forms:**
```html
<!-- Bootstrap 4 -->
<div class="form-group">
    <label>Name</label>
    <input type="text" class="form-control">
</div>

<!-- Bootstrap 5 -->
<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" class="form-control">
</div>
```

---

## Troubleshooting

### Issue 1: Webpack Configuration Errors

**Error:**
```
Invalid configuration object. Webpack has been initialised using a configuration
object that does not match the API schema.
```

**Solution:**
1. Ensure compatible versions:
   - `sass`: `^1.32.0` or higher
   - `sass-loader`: `^12.0.0` or higher
   - `laravel-mix`: `^6.0.49`

2. Clean install:
```bash
rm -rf node_modules package-lock.json
npm install
```

### Issue 2: Vue Components Not Compiling

**Error:**
```
Module parse failed: Unexpected token
You may need an appropriate loader to handle this file type
```

**Solution:**
Add `.vue()` to your webpack.mix.js chains:
```javascript
mix.js('resources/js/app.js', 'public/js')
    .vue()  // Add this
    .sass('resources/sass/app.scss', 'public/css');
```

Install vue-loader if missing:
```bash
npm install --save-dev vue-loader@^16.8.0
```

### Issue 3: Bootstrap 5 Styles Not Loading

**Symptoms:**
- Modern layout looks unstyled
- Console shows 404 for modern.css

**Solution:**
1. Verify webpack.mix.js has the modern build
2. Compile assets: `npm run dev`
3. Clear Laravel cache: `php artisan cache:clear`
4. Check blade template references correct CSS file

### Issue 4: Conflicting Bootstrap Versions

**Symptoms:**
- Styles look broken
- Mix of Bootstrap 4 and 5 styles appearing

**Solution:**
1. Ensure separate entry points (app.js vs modern.js)
2. Don't import both Bootstrap versions in same file
3. Use layout system to separate concerns:
   - `layouts/default.blade.php` → Bootstrap 4 → `app.css`
   - `layouts/modern.blade.php` → Bootstrap 5 → `modern.css`

### Issue 5: npm install Fails

**Error:**
```
npm ERR! peer dependency conflict
```

**Solution:**
Use legacy peer deps flag:
```bash
npm install --legacy-peer-deps
```

Or update to npm 7+ and use:
```bash
npm install --force
```

---

## Quick Reference Commands

```bash
# Clean install
rm -rf node_modules package-lock.json && npm install

# Development build (watch for changes)
npm run watch

# Production build (minified)
npm run prod

# Check webpack/laravel-mix versions
npm list webpack laravel-mix

# View package.json dependencies
cat package.json

# Clear Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Version History

| Date | Bootstrap Version | Notes |
|------|------------------|-------|
| 2024 | Bootstrap 3/4 | Original implementation |
| 2025 | Dual (4 + 5) | Added Bootstrap 5 modern layout |

---

## Additional Resources

- [Bootstrap 5 Migration Guide](https://getbootstrap.com/docs/5.3/migration/)
- [Laravel Mix Documentation](https://laravel-mix.com/docs/6.0/installation)
- [Webpack Configuration](https://webpack.js.org/configuration/)

---

**Last Updated:** December 2, 2025
**Maintained By:** Development Team
