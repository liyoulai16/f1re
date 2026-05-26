<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $perPage = min(max((int)$perPage, 5), 50);

        $articles = Article::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($articles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'body' => 'required|string',
            'cover_image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $slug = Str::slug($request->title) . '-' . Str::random(6);

        $article = Article::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'cover_image' => $request->cover_image,
            'is_published' => $request->input('is_published', false),
            'published_at' => $request->input('is_published', false) ? now() : null,
        ]);

        return response()->json($article, 201);
    }

    public function show(Request $request, $id)
    {
        $article = Article::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json(['data' => $article]);
    }

    public function update(Request $request, $id)
    {
        $article = Article::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'body' => 'required|string',
            'cover_image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $article->title = $request->title;
        $article->excerpt = $request->excerpt;
        $article->body = $request->body;
        $article->cover_image = $request->cover_image;

        $wasPublished = $article->is_published;
        $article->is_published = $request->input('is_published', false);

        if (!$wasPublished && $article->is_published) {
            $article->published_at = now();
        } elseif (!$article->is_published) {
            $article->published_at = null;
        }

        $article->save();

        return response()->json($article);
    }

    public function destroy(Request $request, $id)
    {
        $article = Article::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $article->delete();

        return response()->json(['message' => '文章已删除']);
    }
}
