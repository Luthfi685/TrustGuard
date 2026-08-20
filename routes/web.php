<?php

use App\Http\Controllers\DomainCheckController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TrustGuard
|--------------------------------------------------------------------------
*/

// 1. Landing Page
Route::get('/', [DomainCheckController::class, 'index'])->name('home');

// 2. Scanner Page (Disatukan ke Beranda)
Route::get('/scan', function (\Illuminate\Http\Request $request) {
    return redirect()->route('home', $request->query());
})->name('scan');

// 3. Detailed Analysis Result Page
Route::get('/result/{id}', [DomainCheckController::class, 'result'])->name('result');

// 4. Executive Security Dashboard
Route::get('/dashboard', [DomainCheckController::class, 'dashboard'])->name('dashboard');

// 5. Digital Safety Academy & Gamification
Route::get('/learn', [LearnController::class, 'index'])->name('learn.index');

// 6. Report Suspicious Website Module
Route::get('/report', [ReportController::class, 'index'])->name('report.index');
Route::post('/report', [ReportController::class, 'store'])->name('report.store');

// 7. AJAX Endpoints
Route::post('/api/scan-ajax', [DomainCheckController::class, 'scanAjax'])->name('api.scan');
Route::post('/api/quiz-submit', [LearnController::class, 'submitQuiz'])->name('api.quiz');
Route::get('/api/stats', [DomainCheckController::class, 'statsAjax'])->name('api.stats');
