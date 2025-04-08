<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $pendingArticles = Article::where('status', 'pending')->count();
        $totalUsers = User::count();
        $recentArticles = Article::with('author')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalArticles',
            'publishedArticles',
            'pendingArticles',
            'totalUsers',
            'recentArticles'
        ));
    }
}
