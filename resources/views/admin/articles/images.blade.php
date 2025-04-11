@extends('layouts.admin')

@section('title', 'Manage Article Images')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Manage Images for "{{ $article->title }}"</h1>
    <a href="{{ route('admin.articles.edit', $article) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Back to Article
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Current Images -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Images</h2>
        
        @if($article->images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                @foreach($article->images->sortBy('display_order') as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->caption }}" 
                            class="w-full h-32 object-cover rounded cursor-pointer edit-image-btn"
                            data-id="{{ $image->id }}"
                            data-caption="{{ $image->caption }}"
                            data-order="{{ $image->display_order }}">
                        
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                            <button type="button" 
                                class="edit-image-btn bg-blue-500 hover:bg-blue-600 text-white rounded-full p-2"
                                data-id="{{ $image->id }}"
                                data-caption="{{ $image->caption }}"
                                data-order="{{ $image->display_order }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>
                            <form action="{{ route('admin.articles.images.destroy', [$article, $image]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        @if($image->caption)
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-1 truncate">
                                {{ $image->caption }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No additional images added yet.</p>
        @endif
    </div>

    <!-- Add Image -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div id="single-upload-form">
            <h2 class="text-xl font-semibold text-gray-900 mb-4" id="formTitle">Add Image</h2>
            
            <form id="imageForm" action="{{ route('admin.articles.images.store', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="caption" class="block text-sm font-medium text-gray-700 mb-1">Caption (Optional)</label>
                    <input type="text" name="caption" id="caption"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                    <input type="number" name="display_order" id="display_order" value="{{ $article->images->count() > 0 ? $article->images->max('display_order') + 1 : 1 }}" min="1"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <p class="text-xs text-gray-500 mt-1">Lower numbers will be displayed first.</p>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" id="cancelButton" onclick="resetForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded" style="display: none;">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Image
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-8 pt-6 border-t">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload Multiple Images</h2>
            
            <form action="{{ route('admin.articles.images.uploadMultiple', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Select Images</label>
                    <input type="file" name="images[]" id="images" accept="image/*" multiple
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <p class="text-xs text-gray-500 mt-1">You can select multiple images at once.</p>
                </div>
                
                <div class="mb-4">
                    <p class="block text-sm font-medium text-gray-700 mb-1">Captions (Optional)</p>
                    <p class="text-xs text-gray-500 mb-2">You can add captions individually after uploading.</p>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Upload Images
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Store routes as variables
    const updateRoute = "{{ route('admin.articles.images.update', [$article, ':id']) }}";
    const storeRoute = "{{ route('admin.articles.images.store', $article) }}";
    
    // Add event listeners when the DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event listeners to all edit image buttons
        document.querySelectorAll('.edit-image-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const caption = this.getAttribute('data-caption');
                const order = this.getAttribute('data-order');
                editImage(id, caption, order);
            });
        });
    });
    
    function editImage(id, caption, order) {
        // Update form action and method
        const form = document.getElementById('imageForm');
        form.action = updateRoute.replace(':id', id);
        
        // Add method field
        const methodField = document.getElementById('methodField');
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Update form title
        document.getElementById('formTitle').innerText = 'Edit Image';
        
        // Set form values
        document.getElementById('caption').value = caption;
        document.getElementById('display_order').value = order;
        
        // Hide image field as we're not replacing the image
        document.getElementById('image').parentElement.style.display = 'none';
        
        // Show cancel button and update submit button
        document.getElementById('cancelButton').style.display = 'block';
        document.querySelector('#imageForm button[type="submit"]').innerText = 'Update Image';
    }
    
    function resetForm() {
        // Reset form action and method
        const form = document.getElementById('imageForm');
        form.action = storeRoute;
        
        // Remove method field
        document.getElementById('methodField').innerHTML = '';
        
        // Reset form title
        document.getElementById('formTitle').innerText = 'Add Image';
        
        // Show image field
        document.getElementById('image').parentElement.style.display = 'block';
        
        // Clear form values
        document.getElementById('image').value = '';
        document.getElementById('caption').value = '';
        document.getElementById('display_order').value = "{{ $article->images->count() > 0 ? $article->images->max('display_order') + 1 : 1 }}";
        
        // Hide cancel button and update submit button
        document.getElementById('cancelButton').style.display = 'none';
        document.querySelector('#imageForm button[type="submit"]').innerText = 'Add Image';
    }
</script>
@endsection 