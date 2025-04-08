@extends('layouts.admin')

@section('title', 'Create Article')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Create New Article</h1>
    <a href="{{ route('admin.articles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Back to Articles
    </a>
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
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}"
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
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
            <textarea name="content" id="content" rows="10"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                required>{{ old('content') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('excerpt') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">A short summary of the article. Leave blank to auto-generate from content.</p>
        </div>

        <div class="mb-4">
            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
            <input type="file" name="featured_image" id="featured_image"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x630 pixels. Max file size: 2MB.</p>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" id="status"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                required>
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>

        <div class="mb-4 published-at-container" style="{{ old('status') != 'published' ? 'display: none;' : '' }}">
            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <p class="text-xs text-gray-500 mt-1">When to publish the article. Leave blank for immediate publication.</p>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Article
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