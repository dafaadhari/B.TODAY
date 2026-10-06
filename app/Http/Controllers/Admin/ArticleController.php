<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('q');

        $articlesQuery = Article::with(['category', 'author', 'reviewer']);

        if ($status) {
            $articlesQuery->where('status', $status);
        }

        if ($search) {
            $articlesQuery->where('title', 'LIKE', "%{$search}%");
        }

        $articles = $articlesQuery->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'pending' => Article::where('status', 'pending_review')->count(),
            'draft' => Article::where('status', 'draft')->count(),
        ];

        return view('admin.articles.index', compact('articles', 'stats', 'status', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(ArticleRequest $request)
    {
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $thumbnailPath = $file->storeAs('thumbnails', $filename, 'public');
        }

        $article = Article::create([
            'author_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'content' => $request->content,
            'thumbnail_path' => $thumbnailPath ? Storage::url($thumbnailPath) : null,
            'status' => $request->status,
        ]);

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_article',
            'subject_type' => Article::class,
            'subject_id' => $article->id,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat!');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $thumbnailPath = $file->storeAs('thumbnails', $filename, 'public');
            $article->thumbnail_path = Storage::url($thumbnailPath);
        }

        $article->title = $request->title;
        $article->category_id = $request->category_id;
        $article->content = $request->content;
        $article->status = $request->status;
        $article->save();

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_article',
            'subject_type' => Article::class,
            'subject_id' => $article->id,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function approve(Request $request, Article $article)
    {
        $currentUserId = Auth::id();

        // Guard self-review
        if ($article->author_id && $article->author_id === $currentUserId) {
            return redirect()->back()->with('error', 'Gagal! Anda tidak dapat menyetujui/memublikasikan artikel yang Anda tulis sendiri.');
        }

        $article->reviewed_by = $currentUserId;
        $article->reviewed_at = now();
        $article->status = 'published';
        $article->published_at = now();
        $article->save();

        AuditLog::create([
            'user_id' => $currentUserId,
            'action' => 'publish_article',
            'subject_type' => Article::class,
            'subject_id' => $article->id,
        ]);

        return redirect()->back()->with('success', 'Artikel berhasil disetujui dan diterbitkan!');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_article',
            'subject_type' => Article::class,
            'subject_id' => $article->id,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus (soft delete).');
    }
}
