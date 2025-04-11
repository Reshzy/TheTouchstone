<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'article_id',
        'image_path',
        'caption',
        'display_order'
    ];
    
    /**
     * Get the article that owns the image.
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
