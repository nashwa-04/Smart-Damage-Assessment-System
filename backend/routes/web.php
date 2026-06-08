<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.reports');
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('admin/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
});

Route::middleware('auth')->group(function () {
Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', function () {
        return redirect()->route('user.reports');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('user.reports');
        })->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile', [UserDashboardController::class, 'destroyProfile'])->name('profile.destroy');
    Route::get('/reports', [UserDashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/create', [UserDashboardController::class, 'create'])->name('reports.create');
    Route::post('/reports', [UserDashboardController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [UserDashboardController::class, 'show'])->name('reports.show');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
});
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/map', [AdminDashboardController::class, 'map'])->name('admin.map');
Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
Route::get('/reports/create', [AdminDashboardController::class, 'create'])->name('admin.reports.create');
Route::post('/reports', [AdminDashboardController::class, 'store'])->name('admin.reports.store');
Route::get('/reports/{report}', [AdminDashboardController::class, 'show'])->name('admin.reports.show');
Route::get('/reports/{report}/edit', [AdminDashboardController::class, 'edit'])->name('admin.reports.edit');
Route::put('/reports/{report}', [AdminDashboardController::class, 'update'])->name('admin.reports.update');
Route::delete('/reports/{report}', [AdminDashboardController::class, 'destroy'])->name('admin.reports.destroy');
Route::post('/reports/{report}/approve', [AdminDashboardController::class, 'approveReport'])->name('admin.reports.approve');
Route::post('/reports/{report}/reject', [AdminDashboardController::class, 'rejectReport'])->name('admin.reports.reject');
Route::post('/reports/{report}/damage-assessment', [AdminDashboardController::class, 'updateDamageAssessment'])->name('admin.reports.damage-assessment');
Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
Route::put('/profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
});
