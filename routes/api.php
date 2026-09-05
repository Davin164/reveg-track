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

// Public Endpoints
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/public/stats', [PublicPortalController::class, 'stats']);
Route::get('/public/sites', [SiteController::class, 'index']);
Route::get('/public/sites/{id}', [SiteController::class, 'show']);

Route::get('/complaints', [ComplaintController::class, 'index']);
Route::post('/complaints', [ComplaintController::class, 'store']);

// Authenticated API Endpoints
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::apiResource('sites', SiteController::class);
    Route::get('/sites/{site_id}/plots', [PlotController::class, 'index']);
    Route::post('/sites/{site_id}/plots', [PlotController::class, 'store']);
    Route::apiResource('plots', PlotController::class)->except(['index', 'store']);

    Route::apiResource('species', SpeciesController::class);

    Route::get('/plots/{plot_id}/planting-records', [PlantingRecordController::class, 'index']);
    Route::post('/plots/{plot_id}/planting-records', [PlantingRecordController::class, 'store']);
    Route::get('/planting-records/{id}', [PlantingRecordController::class, 'show']);

    Route::get('/planting-records/{id}/monitoring-logs', [MonitoringLogController::class, 'index']);
    Route::post('/planting-records/{id}/monitoring-logs', [MonitoringLogController::class, 'store']);
    Route::get('/monitoring-logs/{id}', [MonitoringLogController::class, 'show']);
    Route::post('/monitoring-logs/{id}/photos', [MonitoringLogController::class, 'uploadPhoto']);

    Route::get('/sites/{site_id}/reports', [ComplianceReportController::class, 'index']);
    Route::post('/sites/{site_id}/reports/generate', [ComplianceReportController::class, 'generate']);
    Route::get('/reports/{id}', [ComplianceReportController::class, 'show']);
    Route::put('/reports/{id}/status', [ComplianceReportController::class, 'updateStatus']);

    Route::put('/complaints/{id}/respond', [ComplaintController::class, 'respond']);
});
