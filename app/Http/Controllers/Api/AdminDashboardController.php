<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\ArticleFavorite;
use App\Models\Comment;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_articles' => Article::count(),
            'published_articles' => Article::where('is_published', true)->count(),
            'draft_articles' => Article::where('is_published', false)->count(),
            'total_comments' => Comment::count(),
            'total_likes' => ArticleLike::count(),
            'total_favorites' => ArticleFavorite::count(),
        ]);
    }
}
