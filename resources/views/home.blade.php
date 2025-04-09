@extends('layouts.public')

@section('title', 'The Touchstone - Home')

@section('content')
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Hero Section with Featured Articles -->
    <section class="mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach($featuredArticles->take(5) as $index => $article)
            @if($index === 0)
            <div class="lg:col-span-2 relative rounded-lg overflow-hidden shadow-lg h-96">
                @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                @else
                <div class="bg-gray-300 w-full h-full flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 text-white">
                    @if($article->category)
                    <a href="{{ route('articles.category', $article->category) }}" class="text-xs font-medium text-blue-300 uppercase tracking-wider mb-1 inline-block">
                        {{ $article->category->name }}
                    </a>
                    @endif
                    <a href="{{ route('articles.show', $article) }}" class="block">
                        <h2 class="text-2xl md:text-3xl font-bold hover:text-blue-300 mb-2">{{ $article->title }}</h2>
                    </a>
                    <p class="text-gray-300 mb-3 line-clamp-2">
                        @if($article->excerpt)
                        {{ $article->excerpt }}
                        @else
                        {{ Str::limit(strip_tags($article->content), 150) }}
                        @endif
                    </p>
                    <div class="flex items-center text-sm text-gray-300">
                        <span>{{ $article->published_at->format('M d, Y') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $article->author->name }}</span>
                    </div>
                </div>
            </div>
            @else
            <div class="relative rounded-lg overflow-hidden shadow-lg h-96">
                @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                @else
                <div class="bg-gray-300 w-full h-full flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4 text-white">
                    @if($article->category)
                    <a href="{{ route('articles.category', $article->category) }}" class="text-xs font-medium text-blue-300 uppercase tracking-wider mb-1 inline-block">
                        {{ $article->category->name }}
                    </a>
                    @endif
                    <a href="{{ route('articles.show', $article) }}" class="block">
                        <h2 class="text-xl font-bold hover:text-blue-300 mb-2">{{ $article->title }}</h2>
                    </a>
                    <div class="flex items-center text-sm text-gray-300">
                        <span>{{ $article->published_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>

    <!-- Main Content and Sidebar -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="w-full lg:w-2/3">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Latest Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($latestArticles as $article)
                    @include('partials.article-card', ['article' => $article])
                    @endforeach
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('articles.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md">
                        View All Articles
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 space-y-6">
            @include('partials.category-sidebar')

            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">About</h3>
                <p class="text-sm text-gray-700 mb-4">
                    The Touchstone is Cagayan State University - Sanchez Mira's premier source for news, information and campus happenings. Stay informed with our latest articles and updates.
                </p>
                @guest
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-700 mb-2">Join our community:</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('login') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium py-2 px-4 rounded">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded">
                            Register
                        </a>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </div>
@endsection