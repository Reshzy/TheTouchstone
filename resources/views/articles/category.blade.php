@extends('layouts.public')

@section('title', 'The Touchstone - ' . $category->name)

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="w-full lg:w-2/3">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600 mt-2">
                    {{ $category->description ?: 'Browse all articles in the ' . $category->name . ' category' }}
                </p>
            </div>
            
            @if($articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @foreach($articles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                    <p class="text-gray-700 mb-4">No articles found in this category.</p>
                    <a href="{{ route('articles.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        Browse All Articles
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 space-y-6">
            @include('partials.category-sidebar')
            
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">About This Category</h3>
                @if($category->description)
                    <p class="text-sm text-gray-700 mb-4">{{ $category->description }}</p>
                @else
                    <p class="text-sm text-gray-700 mb-4">Articles related to {{ $category->name }}.</p>
                @endif
                <div class="text-sm text-gray-500">
                    <p>{{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection 