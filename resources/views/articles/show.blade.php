<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $article->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8" 
                data-user-id="{{ Auth::check() ? Auth::id() : '' }}" 
                data-is-admin="{{ Auth::check() && Auth::user()->is_admin ? 'true' : 'false' }}">
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
                    
                    <!-- Comments Section -->
                    <div class="mt-8 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Comments ({{ $article->comments->count() }})</h2>

                            @auth
                                <!-- Comment form -->
                                <div class="mb-8">
                                    <form action="{{ route('comments.store', $article) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Leave a comment</label>
                                            <textarea 
                                                id="content" 
                                                name="content" 
                                                rows="3" 
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                                placeholder="Join the discussion..."
                                            >{{ old('content') }}</textarea>
                                            
                                            @error('content')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                                Post Comment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="mb-8 bg-gray-50 rounded-md p-4 text-center">
                                    <p class="text-gray-600">
                                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Sign in</a> 
                                        to join the discussion.
                                    </p>
                                </div>
                            @endauth

                            <!-- Comments list -->
                            @if($article->comments->count() > 0)
                                <div class="space-y-6">
                                    @foreach($article->comments as $comment)
                                        <x-comment :comment="$comment" :article="$article" />
                                    @endforeach
                                </div>
                            @else
                                <div class="text-gray-500 text-center py-8">
                                    <p>No comments yet. Be the first to share your thoughts!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
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
        </div>
    </div>

    @if($article->images->count() > 0)
    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
        <button id="closeModal" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <div class="flex w-full h-full max-w-7xl mx-auto">
            <!-- Left side with image -->
            <div class="flex-1 flex flex-col relative">
                <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                
                <div class="flex-1 flex items-center justify-center p-4">
                    <img id="modalImage" class="max-h-[80vh] object-contain" src="" alt="">
                </div>
                
                <div id="modalCaption" class="text-white text-center p-2 text-lg"></div>
            </div>
            
            <!-- Right side with comments -->
            <div class="w-96 bg-white h-full overflow-hidden flex flex-col relative hidden md:block">
                <div class="px-4 py-3 border-b">
                    <h3 class="font-bold text-lg">Comments</h3>
                </div>
                
                <div id="imageCommentsContainer" class="flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Comments will be loaded here -->
                    <div id="image-comments-placeholder" class="text-center py-8 text-gray-500">
                        Loading comments...
                    </div>
                </div>
                
                @auth
                <div class="border-t p-4 bg-gray-50">
                    <form id="imageCommentForm" class="space-y-2">
                        <textarea id="imageCommentContent" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write a comment..."></textarea>
                        <div class="flex justify-end">
                            <button type="submit" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Post</button>
                        </div>
                    </form>
                </div>
                @else
                <div class="border-t p-4 bg-gray-50">
                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Sign in</a> 
                        to comment on this image.
                    </p>
                </div>
                @endauth
            </div>
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
            const imageCommentsContainer = document.getElementById('imageCommentsContainer');
            const imageCommentForm = document.getElementById('imageCommentForm');
            
            // Get auth data from the main container
            const mainContainer = document.querySelector('.flex-col.lg\\:flex-row');
            
            // Store image data for modal
            const images = JSON.parse(`[
                @foreach($article->images->sortBy('display_order') as $image)
                {
                    "id": {{ $image->id }},
                    "src": "{{ asset('storage/' . $image->image_path) }}",
                    "caption": "{{ $image->caption }}"
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ]`);
            
            let currentIndex = 0;
            let currentImageId = null;
            
            // Open modal when clicking on a gallery image
            galleryImages.forEach(img => {
                img.addEventListener('click', function() {
                    currentIndex = parseInt(this.dataset.index);
                    updateModalImage();
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    loadImageComments();
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
                loadImageComments();
            });
            
            // Next image
            nextImage.addEventListener('click', function() {
                currentIndex = (currentIndex + 1) % images.length;
                updateModalImage();
                loadImageComments();
            });
            
            // Update modal image and caption
            function updateModalImage() {
                modalImage.src = images[currentIndex].src;
                modalCaption.textContent = images[currentIndex].caption || '';
                currentImageId = images[currentIndex].id;
            }
            
            // Load comments for the current image
            function loadImageComments() {
                if (!currentImageId) return;
                
                imageCommentsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">Loading comments...</div>';
                
                fetch(`/article-images/${currentImageId}/comments`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.comments.length > 0) {
                            imageCommentsContainer.innerHTML = '';
                            data.comments.forEach(comment => {
                                renderImageComment(comment, imageCommentsContainer);
                            });
                            // Attach event listeners to the newly created buttons
                            attachImageCommentEventListeners();
                        } else {
                            imageCommentsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">No comments yet. Be the first to comment!</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading comments:', error);
                        imageCommentsContainer.innerHTML = '<div class="text-center py-8 text-red-500">Error loading comments. Please try again.</div>';
                    });
            }
            
            // Render a single comment with its replies
            function renderImageComment(comment, container, level = 0, maxLevel = 3) {
                const commentDiv = document.createElement('div');
                commentDiv.id = `image-comment-${comment.id}`;
                commentDiv.className = `border-l-4 ${level === 0 ? 'border-gray-300' : 'border-gray-200'} pl-3 py-2 ${level > 0 ? 'mt-2' : ''}`;
                
                // Create comment content
                commentDiv.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div class="flex items-start mb-1">
                            <div class="flex-shrink-0 mr-2">
                                <div class="h-7 w-7 bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-gray-500">${comment.author.name.substr(0, 2).toUpperCase()}</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-medium text-sm text-gray-800">${comment.author.name}</div>
                                <div class="text-xs text-gray-500">${formatCommentDate(comment.created_at)}</div>
                                <div class="mt-1 text-sm text-gray-700" id="image-comment-content-${comment.id}">${comment.content}</div>
                            </div>
                        </div>
                        <div class="flex text-xs space-x-2 text-gray-500">
                            ${isCurrentUserAuthorOrAdmin(comment.user_id) ? `
                                <button class="edit-image-comment hover:text-blue-600" data-comment-id="${comment.id}">Edit</button>
                                <button class="delete-image-comment hover:text-red-600" data-comment-id="${comment.id}">Delete</button>
                            ` : ''}
                            ${level < maxLevel ? `
                                <button class="reply-image-comment hover:text-blue-600" data-comment-id="${comment.id}">Reply</button>
                            ` : ''}
                        </div>
                    </div>
                    
                    ${isCurrentUserAuthorOrAdmin(comment.user_id) ? `
                        <div id="edit-image-comment-form-${comment.id}" class="edit-image-comment-form hidden mt-2">
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2">${comment.content}</textarea>
                            <div class="flex justify-end space-x-2 mt-1">
                                <button type="button" class="cancel-edit-image-comment px-3 py-1 text-xs text-gray-600 hover:text-gray-800" data-comment-id="${comment.id}">Cancel</button>
                                <button type="button" class="save-image-comment-edit px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700" data-comment-id="${comment.id}">Save</button>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${level < maxLevel ? `
                        <div id="reply-image-comment-form-${comment.id}" class="reply-image-comment-form hidden mt-2">
                            <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Write a reply..."></textarea>
                            <div class="flex justify-end space-x-2 mt-1">
                                <button type="button" class="cancel-reply-image-comment px-3 py-1 text-xs text-gray-600 hover:text-gray-800" data-comment-id="${comment.id}">Cancel</button>
                                <button type="button" class="post-image-comment-reply px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700" data-comment-id="${comment.id}" data-parent-id="${comment.id}">Reply</button>
                            </div>
                        </div>
                    ` : ''}
                    
                    <div class="replies-container-${comment.id} ${comment.replies && comment.replies.length > 0 ? 'ml-2 mt-2' : ''}"></div>
                `;
                
                container.appendChild(commentDiv);
                
                // Render replies if any
                if (comment.replies && comment.replies.length > 0) {
                    const repliesContainer = commentDiv.querySelector(`.replies-container-${comment.id}`);
                    comment.replies.forEach(reply => {
                        renderImageComment(reply, repliesContainer, level + 1, maxLevel);
                    });
                }
            }
            
            // Format comment date
            function formatCommentDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) {
                    return 'just now';
                } else if (diffInSeconds < 3600) {
                    const minutes = Math.floor(diffInSeconds / 60);
                    return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
                } else if (diffInSeconds < 86400) {
                    const hours = Math.floor(diffInSeconds / 3600);
                    return `${hours} ${hours === 1 ? 'hour' : 'hours'} ago`;
                } else if (diffInSeconds < 604800) {
                    const days = Math.floor(diffInSeconds / 86400);
                    return `${days} ${days === 1 ? 'day' : 'days'} ago`;
                } else {
                    return date.toLocaleDateString();
                }
            }
            
            // Check if current user is author or admin
            function isCurrentUserAuthorOrAdmin(authorId) {
                const currentUserId = parseInt(mainContainer.dataset.userId) || null;
                const isUserAdmin = mainContainer.dataset.isAdmin === 'true';
                return currentUserId === authorId || isUserAdmin;
            }
            
            // Handle comment form submission
            if (imageCommentForm) {
                imageCommentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const content = document.getElementById('imageCommentContent').value;
                    if (!content.trim()) return;
                    
                    const formData = new FormData();
                    formData.append('content', content);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    fetch(`/article-images/${currentImageId}/comments`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear the form
                            document.getElementById('imageCommentContent').value = '';
                            
                            // Reload comments
                            loadImageComments();
                        }
                    })
                    .catch(error => {
                        console.error('Error posting comment:', error);
                    });
                });
            }
            
            // Close modal with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                } else if (e.key === 'ArrowLeft') {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    updateModalImage();
                    loadImageComments();
                } else if (e.key === 'ArrowRight') {
                    currentIndex = (currentIndex + 1) % images.length;
                    updateModalImage();
                    loadImageComments();
                }
            });

            // Attach event listeners to dynamically created comment buttons
            function attachImageCommentEventListeners() {
                // Reply buttons
                document.querySelectorAll('.reply-image-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const replyForm = document.getElementById(`reply-image-comment-form-${commentId}`);
                        
                        // Hide all other reply and edit forms first
                        document.querySelectorAll('.reply-image-comment-form, .edit-image-comment-form').forEach(form => {
                            if (form.id !== `reply-image-comment-form-${commentId}`) {
                                form.classList.add('hidden');
                            }
                        });
                        
                        // Toggle this form
                        replyForm.classList.toggle('hidden');
                        
                        // Focus on textarea when form is shown
                        if (!replyForm.classList.contains('hidden')) {
                            replyForm.querySelector('textarea').focus();
                        }
                    });
                });
                
                // Cancel reply buttons
                document.querySelectorAll('.cancel-reply-image-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const replyForm = document.getElementById(`reply-image-comment-form-${commentId}`);
                        replyForm.classList.add('hidden');
                    });
                });
                
                // Post reply buttons
                document.querySelectorAll('.post-image-comment-reply').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const parentId = this.getAttribute('data-parent-id');
                        const replyForm = document.getElementById(`reply-image-comment-form-${commentId}`);
                        const content = replyForm.querySelector('textarea').value;
                        
                        if (!content.trim()) return;
                        
                        const formData = new FormData();
                        formData.append('content', content);
                        formData.append('parent_id', parentId);
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        fetch(`/article-images/${currentImageId}/comments`, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Clear the form and hide it
                                replyForm.querySelector('textarea').value = '';
                                replyForm.classList.add('hidden');
                                
                                // Reload comments
                                loadImageComments();
                            }
                        })
                        .catch(error => {
                            console.error('Error posting reply:', error);
                        });
                    });
                });
                
                // Edit buttons
                document.querySelectorAll('.edit-image-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const editForm = document.getElementById(`edit-image-comment-form-${commentId}`);
                        const contentElement = document.getElementById(`image-comment-content-${commentId}`);
                        
                        // Hide all other reply and edit forms first
                        document.querySelectorAll('.reply-image-comment-form, .edit-image-comment-form').forEach(form => {
                            if (form.id !== `edit-image-comment-form-${commentId}`) {
                                form.classList.add('hidden');
                            }
                        });
                        
                        // Show this form
                        editForm.classList.remove('hidden');
                        
                        // Focus on textarea
                        editForm.querySelector('textarea').focus();
                    });
                });
                
                // Cancel edit buttons
                document.querySelectorAll('.cancel-edit-image-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const editForm = document.getElementById(`edit-image-comment-form-${commentId}`);
                        editForm.classList.add('hidden');
                    });
                });
                
                // Save edit buttons
                document.querySelectorAll('.save-image-comment-edit').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.getAttribute('data-comment-id');
                        const editForm = document.getElementById(`edit-image-comment-form-${commentId}`);
                        const content = editForm.querySelector('textarea').value;
                        
                        if (!content.trim()) return;
                        
                        const formData = new FormData();
                        formData.append('content', content);
                        formData.append('_method', 'PUT');
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        // Use the right endpoint for comments
                        const endpoint = `/image-comments/${commentId}`;
                        
                        fetch(endpoint, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Update the comment text without reloading all comments
                                const contentElement = document.getElementById(`image-comment-content-${commentId}`);
                                if (contentElement) {
                                    contentElement.textContent = content;
                                }
                                
                                // Hide form
                                editForm.classList.add('hidden');
                            } else {
                                console.error('Error updating comment:', data.message || 'Unknown error');
                                alert('Failed to update comment. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating comment:', error);
                            alert('Failed to update comment. Please try again.');
                        });
                    });
                });
                
                // Delete buttons
                document.querySelectorAll('.delete-image-comment').forEach(button => {
                    button.addEventListener('click', function() {
                        if (!confirm('Are you sure you want to delete this comment?')) return;
                        
                        const commentId = this.getAttribute('data-comment-id');
                        
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        // Use the right endpoint for image comments
                        const endpoint = `/image-comments/${commentId}`;
                        
                        fetch(endpoint, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Reload comments
                                loadImageComments();
                            } else {
                                console.error('Error deleting comment:', data.message || 'Unknown error');
                                alert('Failed to delete comment. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting comment:', error);
                            alert('Failed to delete comment. Please try again.');
                        });
                    });
                });
            }
        });
    </script>
    @endif

    <!-- Comments JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle reply forms
            document.querySelectorAll('.reply-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    const replyForm = document.getElementById('reply-form-' + commentId);
                    
                    // Hide all other reply forms first
                    document.querySelectorAll('.reply-form').forEach(form => {
                        if (form.id !== 'reply-form-' + commentId) {
                            form.classList.add('hidden');
                        }
                    });
                    
                    // Toggle this form
                    replyForm.classList.toggle('hidden');
                    
                    // Focus on textarea when form is shown
                    if (!replyForm.classList.contains('hidden')) {
                        replyForm.querySelector('textarea').focus();
                    }
                });
            });
            
            // Cancel reply buttons
            document.querySelectorAll('.cancel-reply').forEach(button => {
                button.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    const replyForm = document.getElementById('reply-form-' + commentId);
                    replyForm.classList.add('hidden');
                });
            });
        });
    </script>
</x-app-layout>
