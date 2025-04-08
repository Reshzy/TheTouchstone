<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Contributor;
use Illuminate\Http\Request;

class ArticleContributorController extends Controller
{
    /**
     * Show the contributors management page for an article
     */
    public function index(Article $article)
    {
        $contributors = Contributor::orderBy('name')->get();
        return view('admin.articles.contributors', compact('article', 'contributors'));
    }
    
    /**
     * Add a contributor to an article
     */
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'contributor_id' => 'required|exists:contributors,id',
            'role' => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ]);
        
        // Check if this contributor+role already exists
        $exists = $article->contributors()
            ->wherePivot('contributor_id', $request->contributor_id)
            ->wherePivot('role', $request->role)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('error', 'This contributor already has this role for this article.');
        }
        
        // Get the highest display order if not provided
        if (!$request->has('display_order')) {
            $maxOrder = $article->contributors()->max('display_order') ?? 0;
            $request->merge(['display_order' => $maxOrder + 1]);
        }
        
        $article->contributors()->attach($request->contributor_id, [
            'role' => $request->role,
            'display_order' => $request->display_order,
        ]);
        
        return redirect()->route('admin.articles.contributors.index', $article)
            ->with('success', 'Contributor added successfully.');
    }
    
    /**
     * Update a contributor's role or display order
     */
    public function update(Request $request, Article $article, Contributor $contributor)
    {
        $request->validate([
            'role' => 'required|string|max:255',
            'display_order' => 'required|integer|min:0',
        ]);
        
        $article->contributors()->updateExistingPivot($contributor->id, [
            'role' => $request->role,
            'display_order' => $request->display_order,
        ]);
        
        return redirect()->route('admin.articles.contributors.index', $article)
            ->with('success', 'Contributor updated successfully.');
    }
    
    /**
     * Remove a contributor from an article
     */
    public function destroy(Article $article, Contributor $contributor, Request $request)
    {
        $role = $request->input('role');
        
        if ($role) {
            // Remove only the specific role
            $article->contributors()
                ->wherePivot('contributor_id', $contributor->id)
                ->wherePivot('role', $role)
                ->detach();
        } else {
            // Remove all roles for this contributor
            $article->contributors()->detach($contributor->id);
        }
        
        return redirect()->route('admin.articles.contributors.index', $article)
            ->with('success', 'Contributor removed successfully.');
    }
}
