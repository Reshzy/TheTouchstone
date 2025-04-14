<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ArticleContributorController;
use App\Http\Controllers\Admin\ArticleImageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContributorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImageCommentController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/category/{category:slug}', [ArticleController::class, 'byCategory'])->name('articles.category');

// Simple testing logout route
Route::get('/logout-test', function() {
    Auth::logout();
    return redirect('/login');
});

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->is_admin) {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('home');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Comment routes
    Route::post('/articles/{article:slug}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    
    // Image Comment routes
    Route::get('/article-images/{image}/comments', [ImageCommentController::class, 'getComments'])->name('image.comments.index');
    Route::post('/article-images/{image}/comments', [ImageCommentController::class, 'store'])->name('image.comments.store');
    Route::put('/image-comments/{comment}', [ImageCommentController::class, 'update'])->name('image.comments.update');
    Route::delete('/image-comments/{comment}', [ImageCommentController::class, 'destroy'])->name('image.comments.destroy');
});

// Admin routes
Route::prefix('admin')
    ->middleware(['auth', 'verified'])
    ->middleware(IsAdminMiddleware::class)
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('articles', AdminArticleController::class)->names('admin.articles');
        Route::resource('categories', CategoryController::class)->names('admin.categories');
        Route::resource('users', UserController::class)->names('admin.users');
        Route::resource('contributors', ContributorController::class)->names('admin.contributors');
        
        // Article Contributors routes
        Route::get('articles/{article}/contributors', [ArticleContributorController::class, 'index'])
            ->name('admin.articles.contributors.index');
        Route::post('articles/{article}/contributors', [ArticleContributorController::class, 'store'])
            ->name('admin.articles.contributors.store');
        Route::put('articles/{article}/contributors/{contributor}', [ArticleContributorController::class, 'update'])
            ->name('admin.articles.contributors.update');
        Route::delete('articles/{article}/contributors/{contributor}', [ArticleContributorController::class, 'destroy'])
            ->name('admin.articles.contributors.destroy');
            
        // Article Images routes
        Route::get('articles/{article}/images', [ArticleImageController::class, 'index'])
            ->name('admin.articles.images.index');
        Route::post('articles/{article}/images', [ArticleImageController::class, 'store'])
            ->name('admin.articles.images.store');
        Route::post('articles/{article}/images/multiple', [ArticleImageController::class, 'uploadMultiple'])
            ->name('admin.articles.images.uploadMultiple');
        Route::put('articles/{article}/images/{image}', [ArticleImageController::class, 'update'])
            ->name('admin.articles.images.update');
        Route::delete('articles/{article}/images/{image}', [ArticleImageController::class, 'destroy'])
            ->name('admin.articles.images.destroy');
    });

require __DIR__.'/auth.php';
