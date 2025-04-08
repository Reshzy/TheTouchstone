@extends('layouts.public')

@section('title', 'The Touchstone - All Articles')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="w-full lg:w-2/3">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">All Articles</h1>
                <p class="text-gray-600 mt-2">Browse all published articles from The Touchstone</p>
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
                    <p class="text-gray-700 mb-4">No articles have been published yet.</p>
                    <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                        Return to Home
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 space-y-6">
            @include('partials.category-sidebar')
            
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Search</h3>
                <form action="{{ route('articles.index') }}" method="GET">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search articles..." 
                               class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 