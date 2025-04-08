<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $query = Article::with('category', 'author')
            ->published()
            ->latest('published_at');
            
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }
        
        $articles = $query->paginate(12)->withQueryString();
            
        $categories = Category::withCount(['articles' => function($query) {
            $query->published();
        }])
        ->having('articles_count', '>', 0)
        ->orderBy('name')
        ->get();
        
        return view('articles.index', compact('articles', 'categories'));
    }

    /**
     * Display articles by category.
     */
    public function byCategory(Category $category)
    {
        $articles = $category->articles()
            ->with('author')
            ->published()
            ->latest('published_at')
            ->paginate(12);
            
        $categories = Category::withCount(['articles' => function($query) {
            $query->published();
        }])
        ->having('articles_count', '>', 0)
        ->orderBy('name')
        ->get();
        
        return view('articles.category', compact('articles', 'categories', 'category'));
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        // If article is not published, show 404
        if ($article->status !== 'published') {
            abort(404);
        }
        
        // Get related articles from the same category
        $relatedArticles = Article::with('author')
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();
            
        // Get categories for sidebar
        $categories = Category::withCount(['articles' => function($query) {
            $query->published();
        }])
        ->having('articles_count', '>', 0)
        ->orderBy('name')
        ->get();
        
        return view('articles.show', compact('article', 'relatedArticles', 'categories'));
    }
}
