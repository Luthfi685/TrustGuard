<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes - TrustGuard
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Login
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'loginPost'])->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/scans', [AdminController::class, 'scans'])->name('scans');
        Route::delete('/scans/{id}', [AdminController::class, 'deleteScan'])->name('scans.delete');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::post('/reports/{id}/verify', [AdminController::class, 'verifyReport'])->name('reports.verify');
        Route::post('/reports/{id}/reject', [AdminController::class, 'rejectReport'])->name('reports.reject');
        Route::delete('/reports/{id}', [AdminController::class, 'deleteReport'])->name('reports.delete');
    });

});
