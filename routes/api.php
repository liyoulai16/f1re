<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AdminArticleController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\UserArticleController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);

// Authenticated routes
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Image upload
    Route::post('/upload/image', [UploadController::class, 'image']);

    // User's own articles management
    Route::get('/my-articles', [UserArticleController::class, 'index']);
    Route::post('/my-articles', [UserArticleController::class, 'store']);
    Route::get('/my-articles/{id}', [UserArticleController::class, 'show']);
    Route::put('/my-articles/{id}', [UserArticleController::class, 'update']);
    Route::delete('/my-articles/{id}', [UserArticleController::class, 'destroy']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'active', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users', [AdminUserController::class, 'store']);
    Route::get('/users/{user}', [AdminUserController::class, 'show']);
    Route::put('/users/{user}', [AdminUserController::class, 'update']);
    Route::patch('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive']);
    Route::get('/stats', [AdminDashboardController::class, 'stats']);

    // Admin article management
    Route::get('/articles', [AdminArticleController::class, 'index']);
    Route::post('/articles', [AdminArticleController::class, 'store']);
    Route::get('/articles/{id}', [AdminArticleController::class, 'show']);
    Route::put('/articles/{id}', [AdminArticleController::class, 'update']);
    Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy']);
});
