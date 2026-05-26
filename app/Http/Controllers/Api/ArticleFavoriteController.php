<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleFavorite;
use Illuminate\Http\Request;

class ArticleFavoriteController extends Controller
{
    public function toggle(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $userId = $request->user()->id;

        $existing = ArticleFavorite::where('user_id', $userId)
            ->where('article_id', $article->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorited = false;
        } else {
            ArticleFavorite::create([
                'user_id' => $userId,
                'article_id' => $article->id,
            ]);
            $isFavorited = true;
        }

        return response()->json([
            'is_favorited' => $isFavorited,
        ]);
    }

    public function index(Request $request)
    {
        $perPage = min(max((int)$request->input('per_page', 10), 3), 50);

        $articles = $request->user()->favoritedArticles()
            ->published()
            ->with('user:id,name', 'category:id,name,slug')
            ->withCount('likes')
            ->orderByPivot('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($articles);
    }
}
