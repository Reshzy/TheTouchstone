<?php

namespace App\Http\Controllers;

use App\Models\ArticleImage;
use App\Models\ImageComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ImageCommentController extends Controller
{
    /**
     * Get comments for a specific image.
     */
    public function getComments(ArticleImage $image)
    {
        $comments = $image->comments()
            ->with('author')
            ->with(['replies' => function($q) {
                $q->with('author')->orderBy('created_at', 'asc');
            }])
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'comments' => $comments,
            'count' => $comments->count()
        ]);
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request, ArticleImage $image)
    {
        $validated = $request->validate([
            'content' => 'required|min:2|max:1000',
            'parent_id' => 'nullable|exists:image_comments,id'
        ]);

        $comment = new ImageComment();
        $comment->content = $validated['content'];
        $comment->user_id = Auth::id();
        $comment->article_image_id = $image->id;
        $comment->parent_id = $request->parent_id;
        $comment->is_approved = true;
        $comment->save();

        // Load the author relation
        $comment->load('author');
        
        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, ImageComment $comment)
    {
        if (Gate::denies('update', $comment)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'content' => 'required|min:2|max:1000',
        ]);

        $comment->content = $validated['content'];
        $comment->save();

        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(ImageComment $comment)
    {
        if (Gate::denies('delete', $comment)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
