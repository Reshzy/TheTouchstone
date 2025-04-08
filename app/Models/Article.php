<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category_id',
        'status',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the contributors for the article.
     */
    public function contributors()
    {
        return $this->belongsToMany(Contributor::class)
                    ->withPivot('role', 'display_order')
                    ->withTimestamps()
                    ->orderBy('display_order');
    }

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (!$article->slug) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Scope for published articles
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    // Scope for draft articles
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Scope for pending articles
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
