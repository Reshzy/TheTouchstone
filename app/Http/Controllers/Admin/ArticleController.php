<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with('category', 'author')
            ->latest()
            ->paginate(10);
            
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $data = $request->validated();
        
        // Set user_id to current user
        $data['user_id'] = Auth::id();
        
        // Generate slug
        $data['slug'] = Str::slug($data['title']);
        
        // Handle image upload if provided
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                ->store('featured_images', 'public');
        }
        
        // Set published_at if status is published
        if ($data['status'] === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = now();
        }
        
        // Debug statement to check what's in $data
        \Illuminate\Support\Facades\Log::info('Article data before create:', $data);
        
        $article = Article::create($data);
        
        // Check if we should redirect to the contributors page
        if ($request->has('manage_contributors')) {
            return redirect()->route('admin.articles.contributors.index', $article)
                ->with('success', 'Article created successfully. You can now add contributors.');
        }
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $data = $request->validated();
        
        // Generate slug if title changed
        if ($article->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        // Handle image upload if provided
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            
            $data['featured_image'] = $request->file('featured_image')
                ->store('featured_images', 'public');
        }
        
        // Set published_at if status is published and it wasn't before
        if ($data['status'] === 'published' && 
            ($article->status !== 'published' || !$article->published_at)) {
            $data['published_at'] = now();
        }
        
        $article->update($data);
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        // Delete featured image if exists
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
