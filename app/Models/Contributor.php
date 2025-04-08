<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name'
    ];
    
    /**
     * Get the articles associated with the contributor.
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class)
            ->withPivot('role', 'display_order')
            ->withTimestamps();
    }
}
