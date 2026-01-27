# Web Routes Verification and Design Plan

## Executive Summary

This document outlines the verification and design of all web routes for the Smart Damage Assessment System. The system includes public pages, authentication pages, user dashboard, and admin panel.

## Current State Analysis

### Existing Routes (web.php)

- `/` → welcome.blade.php (MISSING FILE)
- `/dashboard` → dashboard.blade.php (requires auth + verified middleware)
- `/profile` → ProfileController (requires auth middleware)

### Existing Auth Routes (auth.php - NOT LOADED)

- `/register` (GET/POST) → RegisteredUserController
- `/login` (GET/POST) → AuthenticatedSessionController
- `/forgot-password` (GET/POST) → PasswordResetLinkController
- `/reset-password/{token}` (GET/POST) → NewPasswordController
- `/verify-email` → EmailVerificationPromptController
- `/verify-email/{id}/{hash}` → VerifyEmailController
- `/email/verification-notification` (POST) → EmailVerificationNotificationController
- `/confirm-password` (GET/POST) → ConfirmablePasswordController
- `/password` (PUT) → PasswordController
- `/logout` (POST) → AuthenticatedSessionController

### Missing Admin Routes (Referenced in Views)

- `/admin/dashboard` → Admin\DashboardController@index
- `/admin/map` → Admin\DashboardController@map
- `/admin/reports` → Admin\DashboardController@reports

## Issues Identified

### Critical Issues

1. **auth.php is not loaded** - The auth routes file exists but is not included in bootstrap/app.php
2. **welcome.blade.php missing** - The home page route references a non-existent view
3. **Admin routes undefined** - Admin views reference routes that don't exist in web.php

### Design Considerations

1. Admin routes should be protected with middleware (auth + role-based access)
2. Regular user dashboard should be separate from admin dashboard
3. Welcome page should serve as landing page with project information

## Proposed Route Structure

### Public Routes (No Authentication)

```
GET  /                    → Welcome Page (landing)
GET  /login               → Login Form
POST /login               → Login Action
GET  /register            → Registration Form
POST /register            → Registration Action
GET  /forgot-password     → Forgot Password Form
POST /forgot-password     → Send Reset Link
GET  /reset-password/{token} → Reset Password Form
POST /reset-password      → Reset Password Action
```

### Authenticated User Routes

```
GET  /dashboard           → User Dashboard
GET  /profile             → Profile Edit
PATCH /profile             → Profile Update
DELETE /profile            → Account Delete
GET  /verify-email        → Email Verification Notice
GET  /verify-email/{id}/{hash} → Verify Email
POST /email/verification-notification → Resend Verification
GET  /confirm-password    → Confirm Password Form
POST /confirm-password    → Confirm Password Action
PUT  /password            → Update Password
POST /logout              → Logout
```

### Admin Routes (Protected)

```
GET  /admin/dashboard     → Admin Dashboard
GET  /admin/map           → Map View
GET  /admin/reports       → Reports List
```

## Implementation Plan

### Phase 1: Load Auth Routes

**File:** `backend/bootstrap/app.php`

Add auth.php to the routing configuration:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware('web')
            ->group(base_path('routes/auth.php'));
    },
)
```

### Phase 2: Update web.php

**File:** `backend/routes/web.php`

Add admin routes and ensure proper middleware:

```php
<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes (protected with auth middleware)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/map', [AdminDashboardController::class, 'map'])->name('admin.map');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
});
```

### Phase 3: Create Welcome Page

**File:** `backend/resources/views/welcome.blade.php`

Create a professional landing page with:

- Project description
- Features overview
- Call-to-action buttons (Login/Register)
- Modern design using Tailwind CSS

### Phase 4: Route Verification

Verify all routes are accessible and properly protected:

- Public routes accessible without authentication
- Auth routes redirect unauthenticated users
- Admin routes require authentication
- Route names match view references

## Route Middleware Configuration

### Middleware Stack

- **web**: Standard web middleware (session, CSRF, etc.)
- **auth**: Requires authenticated user
- **verified**: Requires email verification
- **guest**: Only accessible by unauthenticated users
- **throttle**: Rate limiting for sensitive routes

### Admin Access Control

Consider adding role-based middleware for admin routes:

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Admin routes
});
```

## Route Naming Convention

### Pattern

- Public routes: `route_name` (e.g., `login`, `register`)
- User routes: `route_name` (e.g., `dashboard`, `profile.edit`)
- Admin routes: `admin.route_name` (e.g., `admin.dashboard`, `admin.map`)

### Benefits

- Consistent naming across the application
- Easy to generate URLs using `route()` helper
- Clear separation of concerns

## Testing Checklist

- [ ] Welcome page loads at `/`
- [ ] Login form accessible at `/login`
- [ ] Registration form accessible at `/register`
- [ ] Unauthenticated users redirected from `/dashboard` to `/login`
- [ ] Authenticated users can access `/dashboard`
- [ ] Admin dashboard accessible at `/admin/dashboard`
- [ ] Admin map accessible at `/admin/map`
- [ ] Admin reports accessible at `/admin/reports`
- [ ] Logout redirects to home page
- [ ] All route names work with `route()` helper

## Security Considerations

1. **CSRF Protection**: All POST/PUT/DELETE routes protected by web middleware
2. **Authentication**: Protected routes use `auth` middleware
3. **Email Verification**: Dashboard requires `verified` middleware
4. **Rate Limiting**: Password reset and email verification routes throttled
5. **Session Management**: Proper session regeneration on login/logout

## Future Enhancements

1. **Role-Based Access Control**: Add admin role middleware
2. **Two-Factor Authentication**: Optional 2FA for enhanced security
3. **Social Authentication**: Login with Google, Facebook, etc.
4. **API Documentation**: Public API documentation page
5. **User Settings**: Additional user preferences page

## Conclusion

This plan ensures all web routes are properly configured, protected, and documented. The implementation follows Laravel best practices and maintains clear separation between public, user, and admin areas.
