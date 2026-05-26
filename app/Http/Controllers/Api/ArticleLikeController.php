<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\Request;

class ArticleLikeController extends Controller
{
    public function toggle(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $userId = $request->user()->id;

        $existing = ArticleLike::where('user_id', $userId)
            ->where('article_id', $article->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            ArticleLike::create([
                'user_id' => $userId,
                'article_id' => $article->id,
            ]);
            $isLiked = true;
        }

        $likesCount = ArticleLike::where('article_id', $article->id)->count();

        return response()->json([
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
        ]);
    }

    public function status(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $userId = $request->user()->id;

        return response()->json([
            'is_liked' => ArticleLike::where('user_id', $userId)->where('article_id', $article->id)->exists(),
            'is_favorited' => \App\Models\ArticleFavorite::where('user_id', $userId)->where('article_id', $article->id)->exists(),
        ]);
    }
}
