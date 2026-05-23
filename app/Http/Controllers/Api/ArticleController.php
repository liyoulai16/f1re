<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return response()->json($articles);
    }

    public function show($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        return response()->json(['data' => $article]);
    }
}
