@extends('layouts.admin')

@section('title', 'Edit Article')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Edit Article</h1>
    <div class="flex space-x-2">
        <a href="{{ route('admin.articles.images.index', $article) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
            Manage Images
        </a>
        <a href="{{ route('admin.articles.contributors.index', $article) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Manage Contributors
        </a>
        <a href="{{ route('admin.articles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Articles
        </a>
    </div>
</div>

@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                required>
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id" id="category_id"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                required>
                <option value="">Select a category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
            <textarea name="content" id="content" rows="10"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                required>{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('excerpt', $article->excerpt) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">A short summary of the article. Leave blank to auto-generate from content.</p>
        </div>

        <div class="mb-4">
            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
            @if($article->featured_image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-48 h-auto">
                <p class="text-xs text-gray-500 mt-1">Current featured image</p>
            </div>
            @endif
            <input type="file" name="featured_image" id="featured_image"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <p class="text-xs text-gray-500 mt-1">Upload a new image to replace the current one. Recommended size: 1200x630 pixels. Max file size: 2MB.</p>
        </div>
        
        <div class="mb-4">
            <label for="additional_images" class="block text-sm font-medium text-gray-700 mb-1">Additional Images</label>
            <input type="file" name="additional_images[]" id="additional_images" multiple
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <p class="text-xs text-gray-500 mt-1">You can select multiple images. These will be displayed as a gallery at the bottom of the article.</p>
            
            @if($article->images->count() > 0)
            <div class="mt-2">
                <p class="text-sm text-gray-700">Current additional images: {{ $article->images->count() }}</p>
                <div class="flex space-x-2 mt-1">
                    <a href="{{ route('admin.articles.images.index', $article) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Manage existing images
                    </a>
                </div>
            </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" id="status"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                required>
                <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ old('status', $article->status) == 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>

        <div class="mb-4 published-at-container" style="{{ old('status', $article->status) != 'published' ? 'display: none;' : '' }}">
            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
            <input type="datetime-local" name="published_at" id="published_at"
                value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <p class="text-xs text-gray-500 mt-1">When to publish the article. Current date/time will be used if left empty.</p>
        </div>

        <div class="flex justify-end mt-6">
            <div class="flex items-center mr-4">
                <input id="manage_images" name="manage_images" type="checkbox" value="1" {{ old('manage_images') ? 'checked' : '' }}
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label for="manage_images" class="ml-2 block text-sm text-gray-900">
                    Manage images after update
                </label>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update Article
            </button>
        </div>
    </form>
</div>

<script>
    // Show/hide published_at field based on status
    document.getElementById('status').addEventListener('change', function() {
        const publishedAtContainer = document.querySelector('.published-at-container');
        if (this.value === 'published') {
            publishedAtContainer.style.display = 'block';
        } else {
            publishedAtContainer.style.display = 'none';
        }
    });
</script>
@endsection