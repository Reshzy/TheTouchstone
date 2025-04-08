<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get published articles, latest first
        $featuredArticles = Article::with('category', 'author')
            ->published()
            ->latest('published_at')
            ->take(5)
            ->get();
            
        // Get latest articles
        $latestArticles = Article::with('category', 'author')
            ->published()
            ->latest('published_at')
            ->take(8)
            ->get();
            
        // Get all categories with article count
        $categories = Category::withCount(['articles' => function($query) {
            $query->published();
        }])
        ->having('articles_count', '>', 0)
        ->orderBy('name')
        ->get();
        
        return view('home', compact('featuredArticles', 'latestArticles', 'categories'));
    }
}
