<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleImageController extends Controller
{
    /**
     * Display the article images
     */
    public function index(Article $article)
    {
        return view('admin.articles.images', compact('article'));
    }
    
    /**
     * Store a newly created article image
     */
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:255',
        ]);
        
        // Get the highest display order
        $maxOrder = $article->images()->max('display_order') ?? 0;
        
        // Store the image
        $imagePath = $request->file('image')->store('article_images', 'public');
        
        // Create the image record
        $article->images()->create([
            'image_path' => $imagePath,
            'caption' => $request->caption,
            'display_order' => $maxOrder + 1,
        ]);
        
        return redirect()->route('admin.articles.images.index', $article)
            ->with('success', 'Image uploaded successfully.');
    }
    
    /**
     * Update an article image
     */
    public function update(Request $request, Article $article, ArticleImage $image)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        $image->update([
            'caption' => $request->caption,
            'display_order' => $request->display_order ?? $image->display_order,
        ]);
        
        return redirect()->route('admin.articles.images.index', $article)
            ->with('success', 'Image updated successfully.');
    }
    
    /**
     * Remove an article image
     */
    public function destroy(Article $article, ArticleImage $image)
    {
        // Delete the image file
        Storage::disk('public')->delete($image->image_path);
        
        // Delete the image record
        $image->delete();
        
        return redirect()->route('admin.articles.images.index', $article)
            ->with('success', 'Image deleted successfully.');
    }
    
    /**
     * Upload multiple images at once
     */
    public function uploadMultiple(Request $request, Article $article)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);
        
        // Get the highest display order
        $maxOrder = $article->images()->max('display_order') ?? 0;
        $displayOrder = $maxOrder + 1;
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Store the image
                $imagePath = $image->store('article_images', 'public');
                
                // Get caption if available
                $caption = $request->captions[$index] ?? null;
                
                // Create the image record
                $article->images()->create([
                    'image_path' => $imagePath,
                    'caption' => $caption,
                    'display_order' => $displayOrder++,
                ]);
            }
        }
        
        return redirect()->route('admin.articles.images.index', $article)
            ->with('success', 'Images uploaded successfully.');
    }
}
