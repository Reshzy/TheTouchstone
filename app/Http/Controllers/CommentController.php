<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This is not used since comments are displayed with articles
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->article_id = $article->id;
        $comment->parent_id = $request->parent_id;
        $comment->is_approved = true; // Set to false if you want admin approval first
        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        // Check if user is authorized to edit this comment
        if (Gate::denies('update', $comment)) {
            abort(403);
        }
        
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if user is authorized to update this comment
        if (Gate::denies('update', $comment)) {
            abort(403);
        }
        
        $request->validate([
            'content' => 'required|min:2|max:1000',
        ]);

        $comment->content = $request->content;
        $comment->save();

        return redirect()->route('articles.show', $comment->article)->with('success', 'Comment updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Check if user is authorized to delete this comment
        if (Gate::denies('delete', $comment)) {
            abort(403);
        }
        
        $article = $comment->article;
        $comment->delete();

        return redirect()->route('articles.show', $article)->with('success', 'Comment deleted!');
    }

    /**
     * Reply to a comment.
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|min:2|max:1000',
        ]);

        $reply = new Comment();
        $reply->content = $request->content;
        $reply->user_id = Auth::id();
        $reply->article_id = $comment->article_id;
        $reply->parent_id = $comment->id;
        $reply->is_approved = true; // Set to false if you want admin approval first
        $reply->save();

        return redirect()->back()->with('success', 'Reply added successfully!');
    }
}
