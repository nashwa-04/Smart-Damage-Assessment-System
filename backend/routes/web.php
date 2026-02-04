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

// Admin routes (protected with auth and admin middleware)
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
});
