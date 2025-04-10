@extends('layouts.public')

@section('title', $article->title . ' - The Touchstone')

@section('content')
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="w-full lg:w-2/3">
            <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if($article->featured_image)
                    <div class="relative h-64 md:h-96 overflow-hidden">
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
                
                <div class="p-6">
                    <div class="mb-4">
                        @if($article->category)
                            <a href="{{ route('articles.category', $article->category) }}" class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1 inline-block">
                                {{ $article->category->name }}
                            </a>
                        @endif
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
                        <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                <span>{{ $article->published_at->format('M d, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>By {{ $article->author->name }}</span>
                            </div>
                            
                            @if($article->contributors->count() > 0)
                                <div class="text-left text-sm text-gray-600">
                                    @php
                                        $contributorsByRole = $article->contributors->groupBy('pivot.role');
                                    @endphp
                                    
                                    @foreach($contributorsByRole as $role => $contributors)
                                        <div class="flex items-center justify-start mt-1">
                                            <span class="font-medium">{{ $role }}:</span>
                                            <span class="ml-1">
                                                {{ $contributors->pluck('name')->implode(', ') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($article->excerpt)
                        <div class="mb-6 text-lg text-gray-700 italic font-medium border-l-4 border-blue-500 pl-4">
                            {{ $article->excerpt }}
                        </div>
                    @endif
                    
                    <div class="prose max-w-none text-gray-800">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                    
                    @if($article->images->count() > 0)
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Gallery</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($article->images->sortBy('display_order') as $index => $image)
                            <div class="relative aspect-square overflow-hidden rounded-lg cursor-pointer gallery-image"
                                data-index="{{ $index }}">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                    alt="{{ $image->caption }}" 
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                
                                @if($image->caption)
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-2 truncate">
                                    {{ $image->caption }}
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-8 pt-6 border-t">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $article->author->name }}</p>
                                <div class="text-sm text-gray-500">
                                    <p>Published on {{ $article->published_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            
            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedArticles as $relatedArticle)
                            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow transition-shadow duration-200">
                                @if($relatedArticle->featured_image)
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="block relative h-40 overflow-hidden">
                                        <img src="{{ asset('storage/' . $relatedArticle->featured_image) }}" alt="{{ $relatedArticle->title }}" class="object-cover object-center w-full h-full">
                                    </a>
                                @endif
                                <div class="p-4">
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="block">
                                        <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600 mb-2">{{ $relatedArticle->title }}</h3>
                                    </a>
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <span>{{ $relatedArticle->published_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 space-y-6">
            @include('partials.category-sidebar')
            
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">Share This Article</h3>
                <div class="flex space-x-4">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" target="_blank" class="text-blue-400 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="mailto:?subject={{ urlencode($article->title) }}&body={{ urlencode('Check out this article: ' . request()->url()) }}" class="text-gray-600 hover:text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@if($article->images->count() > 0)
<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <button id="closeModal" class="absolute top-4 right-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <button id="prevImage" class="absolute left-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    
    <button id="nextImage" class="absolute right-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    
    <div class="max-w-5xl max-h-full overflow-auto p-4">
        <img id="modalImage" class="mx-auto max-h-[80vh] object-contain" src="" alt="">
        <div id="modalCaption" class="text-white text-center mt-2 text-lg"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        const closeModal = document.getElementById('closeModal');
        const prevImage = document.getElementById('prevImage');
        const nextImage = document.getElementById('nextImage');
        const galleryImages = document.querySelectorAll('.gallery-image');
        
        // Store image data for modal
        const images = JSON.parse(`[
            @foreach($article->images->sortBy('display_order') as $image)
            {
                "src": "{{ asset('storage/' . $image->image_path) }}",
                "caption": "{{ $image->caption }}"
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]`);
        
        let currentIndex = 0;
        
        // Open modal when clicking on a gallery image
        galleryImages.forEach(img => {
            img.addEventListener('click', function() {
                currentIndex = parseInt(this.dataset.index);
                updateModalImage();
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Close modal
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        });
        
        // Previous image
        prevImage.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateModalImage();
        });
        
        // Next image
        nextImage.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % images.length;
            updateModalImage();
        });
        
        // Update modal image and caption
        function updateModalImage() {
            modalImage.src = images[currentIndex].src;
            modalCaption.textContent = images[currentIndex].caption || '';
        }
        
        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            } else if (e.key === 'ArrowLeft') {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                updateModalImage();
            } else if (e.key === 'ArrowRight') {
                currentIndex = (currentIndex + 1) % images.length;
                updateModalImage();
            }
        });
    });
</script>
@endif 