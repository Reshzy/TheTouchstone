@extends('layouts.admin')

@section('title', 'Manage Article Contributors')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Manage Contributors for "{{ $article->title }}"</h1>
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
    <!-- Current Contributors -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Current Contributors</h2>
        
        @if($article->contributors->count() > 0)
            <table class="min-w-full divide-y divide-gray-200 mb-4">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($article->contributors->groupBy('pivot.contributor_id') as $contributorGroup)
                        @foreach($contributorGroup as $contributor)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $contributor->name }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contributor->pivot->role }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contributor->pivot->display_order }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button type="button" 
                                                data-id="{{ $contributor->id }}"
                                                data-name="{{ $contributor->name }}"
                                                data-role="{{ $contributor->pivot->role }}"
                                                data-order="{{ $contributor->pivot->display_order }}"
                                                class="edit-btn text-blue-600 hover:text-blue-900">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.articles.contributors.destroy', [$article, $contributor]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this contributor?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="role" value="{{ $contributor->pivot->role }}">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Remove</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">No contributors added yet.</p>
        @endif
    </div>

    <!-- Add/Edit Contributor -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4" id="formTitle">Add Contributor</h2>
        
        <form id="contributorForm" action="{{ route('admin.articles.contributors.store', $article) }}" method="POST">
            @csrf
            <div id="methodField"></div>

            <div class="mb-4">
                <label for="contributor_id" class="block text-sm font-medium text-gray-700 mb-1">Contributor</label>
                <select name="contributor_id" id="contributor_id"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    required>
                    <option value="">Select a contributor</option>
                    @foreach($contributors as $contributor)
                    <option value="{{ $contributor->id }}">{{ $contributor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    required>
                    <option value="">Select a role</option>
                    <option value="Author">Author</option>
                    <option value="Co-Author">Co-Author</option>
                    <option value="Layout">Layout</option>
                    <option value="Photography">Photography</option>
                    <option value="Illustration">Illustration</option>
                    <option value="Graphics">Graphics</option>
                    <option value="Research">Research</option>
                    <option value="Editing">Editing</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" name="display_order" id="display_order" value="0" min="0"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Lower numbers will be displayed first.</p>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" id="cancelButton" onclick="resetForm()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded" style="display: none;">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Contributor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Store routes for use in JavaScript
    const updateRoute = "{{ route('admin.articles.contributors.update', [$article, ':id']) }}";
    const storeRoute = "{{ route('admin.articles.contributors.store', $article) }}";
    
    // Add event listeners to all edit buttons
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const role = this.getAttribute('data-role');
                const order = this.getAttribute('data-order');
                editContributor(id, name, role, order);
            });
        });
    });

    function editContributor(id, name, role, order) {
        // Update form action and method
        const form = document.getElementById('contributorForm');
        form.action = updateRoute.replace(':id', id);
        
        // Add method field
        const methodField = document.getElementById('methodField');
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Update form title
        document.getElementById('formTitle').innerText = 'Edit Contributor';
        
        // Set form values
        document.getElementById('contributor_id').value = id;
        document.getElementById('contributor_id').disabled = true;
        
        // Select the correct option
        const roleSelect = document.getElementById('role');
        for (let i = 0; i < roleSelect.options.length; i++) {
            if (roleSelect.options[i].value === role) {
                roleSelect.selectedIndex = i;
                break;
            }
        }
        
        document.getElementById('display_order').value = order;
        
        // Show cancel button and update submit button
        document.getElementById('cancelButton').style.display = 'block';
        document.querySelector('button[type="submit"]').innerText = 'Update Contributor';
    }
    
    function resetForm() {
        // Reset form action and method
        const form = document.getElementById('contributorForm');
        form.action = storeRoute;
        
        // Remove method field
        document.getElementById('methodField').innerHTML = '';
        
        // Reset form title
        document.getElementById('formTitle').innerText = 'Add Contributor';
        
        // Clear form values
        document.getElementById('contributor_id').value = '';
        document.getElementById('contributor_id').disabled = false;
        document.getElementById('role').selectedIndex = 0;
        document.getElementById('display_order').value = '0';
        
        // Hide cancel button and update submit button
        document.getElementById('cancelButton').style.display = 'none';
        document.querySelector('button[type="submit"]').innerText = 'Add Contributor';
    }
</script>
@endsection 