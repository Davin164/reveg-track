<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplianceReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringLogController;
use App\Http\Controllers\PlantingRecordController;
use App\Http\Controllers\PlotController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SpeciesController;
use Illuminate\Support\Facades\Route;

// Public Portal Routes (Tanpa Login)
Route::get('/', function () {
    return redirect()->route('public.portal');
});
Route::get('/portal', [PublicPortalController::class, 'index'])->name('public.portal');
Route::get('/portal/sites/{id}', [PublicPortalController::class, 'siteDetail'])->name('public.siteDetail');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Internal Protected Operational Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sites & Plots
    Route::resource('sites', SiteController::class);
    Route::resource('plots', PlotController::class);

    // Species
    Route::resource('species', SpeciesController::class);

    // Planting Records
    Route::resource('planting', PlantingRecordController::class);

    // Monitoring & Vision AI
    Route::resource('monitoring', MonitoringLogController::class);
    Route::post('/monitoring/{id}/photos', [MonitoringLogController::class, 'uploadPhoto'])->name('monitoring.uploadPhoto');

    // Audit Compliance Reports
    Route::resource('reports', ComplianceReportController::class);
    Route::post('/sites/{site_id}/reports/generate', [ComplianceReportController::class, 'generate'])->name('reports.generate');
    Route::put('/reports/{id}/status', [ComplianceReportController::class, 'updateStatus'])->name('reports.updateStatus');

    // Complaint Management (Admin/Manager response)
    Route::get('/complaints-manage', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::put('/complaints/{id}/respond', [ComplaintController::class, 'respond'])->name('complaints.respond');
});
