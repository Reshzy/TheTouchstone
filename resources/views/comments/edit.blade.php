@extends('layouts.public')

@section('title', 'Edit Comment - The Touchstone')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Edit Comment</h1>
            
            <!-- Comment edit form -->
            <form action="{{ route('comments.update', $comment) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Your comment</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="4" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                        placeholder="Write your comment..."
                    >{{ old('content', $comment->content) }}</textarea>
                    
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-between">
                    <a href="{{ route('articles.show', $comment->article) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 