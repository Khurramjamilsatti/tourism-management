<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TourismDataController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\VisitPurposeController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\UserController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tourism Data CRUD
    Route::resource('tourism-data', TourismDataController::class)->except(['show'])->parameters(['tourism-data' => 'tourismData']);

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // Master Data
    Route::resource('countries', CountryController::class)->except(['show']);
    Route::resource('visit-purposes', VisitPurposeController::class)->except(['show']);

    // Weather API
    Route::get('/api/weather', [WeatherController::class, 'getWeather'])->name('weather');

    // Admin only routes
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
