<div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow transition-shadow duration-200">
    @if($article->featured_image)
        <a href="{{ route('articles.show', $article) }}" class="block relative h-48 overflow-hidden">
            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="object-cover object-center w-full h-full">
        </a>
    @endif
    <div class="p-4">
        @if($article->category)
            <a href="{{ route('articles.category', $article->category) }}" class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1 inline-block">
                {{ $article->category->name }}
            </a>
        @endif
        <a href="{{ route('articles.show', $article) }}" class="block">
            <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 mb-2">{{ $article->title }}</h3>
        </a>
        @if($article->excerpt)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $article->excerpt }}</p>
        @else
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit(strip_tags($article->content), 120) }}</p>
        @endif
        <div class="flex items-center justify-between text-xs text-gray-500">
            <span>{{ $article->published_at->format('M d, Y') }}</span>
            <span>{{ $article->author->name }}</span>
        </div>
    </div>
</div> 