<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ArticleFavoriteController;
use App\Http\Controllers\Api\ArticleLikeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminCommentController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AdminArticleController;
use App\Http\Controllers\Api\AdminCategoryController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\UserArticleController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{slug}', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/articles/{slug}/comments', [CommentController::class, 'index']);

// Authenticated routes
Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Image upload
    Route::post('/upload/image', [UploadController::class, 'image']);
    Route::post('/upload/editor-image', [UploadController::class, 'editorImage']);

    // User's own articles management
    Route::get('/my-articles', [UserArticleController::class, 'index']);
    Route::post('/my-articles', [UserArticleController::class, 'store']);
    Route::get('/my-articles/{id}', [UserArticleController::class, 'show']);
    Route::put('/my-articles/{id}', [UserArticleController::class, 'update']);
    Route::delete('/my-articles/{id}', [UserArticleController::class, 'destroy']);

    // Article likes & favorites
    Route::post('/articles/{slug}/like', [ArticleLikeController::class, 'toggle']);
    Route::post('/articles/{slug}/favorite', [ArticleFavoriteController::class, 'toggle']);
    Route::get('/articles/{slug}/interactions', [ArticleLikeController::class, 'status']);
    Route::get('/my-favorites', [ArticleFavoriteController::class, 'index']);

    // Comments
    Route::post('/articles/{slug}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
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

    // Admin category management
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::get('/categories/{id}', [AdminCategoryController::class, 'show']);
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

    // Admin comment management
    Route::get('/comments', [AdminCommentController::class, 'index']);
    Route::delete('/comments/{id}', [AdminCommentController::class, 'destroy']);
    Route::get('/articles/{id}/comments', [CommentController::class, 'articleComments']);
});
