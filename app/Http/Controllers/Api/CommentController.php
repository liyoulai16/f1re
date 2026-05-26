<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $comments = Comment::where('article_id', $article->id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        $tree = $this->buildTree($comments);

        return response()->json(['data' => $tree]);
    }

    public function store(Request $request, $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($request->parent_id) {
            $parent = Comment::findOrFail($request->parent_id);
            if ((int)$parent->article_id !== (int)$article->id) {
                return response()->json(['message' => '无效的回复目标'], 422);
            }
            $depth = $this->getCommentDepth($parent);
            if ($depth >= 3) {
                return response()->json(['message' => '已达到最大回复层级'], 422);
            }
        }

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ]);

        $comment->load('user:id,name');

        return response()->json($comment, 201);
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($request->user()->id !== $comment->user_id && !$request->user()->isAdmin()) {
            return response()->json(['message' => '无权删除此评论'], 403);
        }

        $comment->delete();

        return response()->json(['message' => '评论已删除']);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($request->user()->id !== $comment->user_id && !$request->user()->isAdmin()) {
            return response()->json(['message' => '无权编辑此评论'], 403);
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment->body = $request->body;
        $comment->save();
        $comment->load('user:id,name');

        return response()->json($comment);
    }

    public function articleComments(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $comments = Comment::where('article_id', $article->id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get();

        $tree = $this->buildTree($comments);

        return response()->json(['data' => $tree]);
    }

    private function buildTree($comments, $parentId = null)
    {
        $branch = [];
        foreach ($comments as $comment) {
            if ($comment->parent_id == $parentId) {
                $children = $this->buildTree($comments, $comment->id);
                $comment->replies = $children;
                $branch[] = $comment;
            }
        }
        return $branch;
    }

    private function getCommentDepth($comment)
    {
        $depth = 0;
        $current = $comment;
        while ($current->parent_id !== null) {
            $depth++;
            $current = Comment::find($current->parent_id);
            if (!$current) break;
        }
        return $depth;
    }
}
