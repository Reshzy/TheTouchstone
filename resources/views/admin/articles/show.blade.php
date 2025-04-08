@extends('layouts.admin')

@section('title', $article->title)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">{{ $article->title }}</h1>
    <div>
        <a href="{{ route('admin.articles.edit', $article) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
            Edit
        </a>
        <a href="{{ route('admin.articles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Articles
        </a>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6">
        <div class="flex items-center mb-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 
                   ($article->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }} mr-2">
                {{ ucfirst($article->status) }}
            </span>
            <span class="text-sm text-gray-500">
                Category: {{ $article->category->name ?? 'None' }}
            </span>
            <span class="text-sm text-gray-500 ml-4">
                Author: {{ $article->author->name }}
            </span>
            <span class="text-sm text-gray-500 ml-4">
                Created: {{ $article->created_at->format('M d, Y') }}
            </span>
            @if($article->published_at)
                <span class="text-sm text-gray-500 ml-4">
                    Published: {{ $article->published_at->format('M d, Y') }}
                </span>
            @endif
        </div>

        @if($article->featured_image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="max-w-full h-auto max-h-80">
            </div>
        @endif

        @if($article->excerpt)
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Excerpt</h3>
                <div class="text-gray-700 italic">{{ $article->excerpt }}</div>
            </div>
        @endif

        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Content</h3>
            <div class="text-gray-700 prose max-w-none">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
    </div>
</div>
@endsection 