<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\InsightController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\AboutController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index.alias');
Route::get('/cluster', [ClusterController::class, 'index'])->name('cluster.index');
Route::get('/operational', [OperationalController::class, 'index'])->name('operational.index');
Route::get('/insights', [InsightController::class, 'index'])->name('insights.index');
Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
Route::get('/dataset', [DatasetController::class, 'index'])->name('dataset.index');
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
