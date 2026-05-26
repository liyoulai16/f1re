<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $perPage = min(max((int)$perPage, 5), 50);

        $query = Article::with('user:id,name,email', 'category:id,name,slug')
            ->withCount('likes', 'favorites');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::with('user:id,name,email', 'category:id,name,slug')->findOrFail($id);

        return response()->json(['data' => $article]);
    }

    public function store(Request $request)
    {
        $isPublishing = $request->boolean('is_published');

        $request->validate([
            'title' => $isPublishing ? 'required|string|max:255' : 'nullable|string|max:255',
            'excerpt' => $isPublishing ? 'required|string|max:500' : 'nullable|string|max:500',
            'body' => $isPublishing ? 'required|string' : 'nullable|string',
            'cover_image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $slug = Str::slug($request->title ?: 'draft') . '-' . Str::random(6);

        $article = Article::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title ?? '',
            'slug' => $slug,
            'excerpt' => $request->excerpt ?? '',
            'body' => $request->body ?? '',
            'cover_image' => $request->cover_image,
            'is_published' => $request->input('is_published', false),
            'published_at' => $request->input('is_published', false) ? now() : null,
        ]);

        return response()->json($article, 201);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $isPublishing = $request->boolean('is_published');

        $request->validate([
            'title' => $isPublishing ? 'required|string|max:255' : 'nullable|string|max:255',
            'excerpt' => $isPublishing ? 'required|string|max:500' : 'nullable|string|max:500',
            'body' => $isPublishing ? 'required|string' : 'nullable|string',
            'cover_image' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $article->title = $request->title ?? '';
        $article->excerpt = $request->excerpt ?? '';
        $article->body = $request->body ?? '';
        $article->cover_image = $request->cover_image;
        $article->category_id = $request->category_id;

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

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['message' => '文章已删除']);
    }
}
