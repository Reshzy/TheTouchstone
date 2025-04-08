<div class="bg-white p-4 rounded-lg shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-900">Categories</h3>
    <div class="space-y-2">
        @foreach($categories as $cat)
            <a href="{{ route('articles.category', $cat) }}" 
               class="flex items-center justify-between group hover:bg-gray-50 p-2 rounded-md {{ isset($category) && $category->id === $cat->id ? 'bg-blue-50' : '' }}">
                <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $cat->name }}</span>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $cat->articles_count }}</span>
            </a>
        @endforeach
    </div>
</div> 