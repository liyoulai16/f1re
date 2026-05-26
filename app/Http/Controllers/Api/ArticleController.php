<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 6);
        $perPage = min(max((int)$perPage, 3), 50);

        $query = Article::published()
            ->with('user:id,name', 'category:id,name,slug')
            ->withCount('likes');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json($articles);
    }

    public function show($slug)
    {
        $article = Article::published()
            ->with('user:id,name', 'category:id,name,slug')
            ->withCount('likes')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json(['data' => $article]);
    }
}
