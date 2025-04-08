@extends('layouts.admin')

@section('title', $category->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">{{ $category->name }}</h1>
    <div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
            Edit
        </a>
        <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to Categories
        </a>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Details</h3>
                <div class="mb-4">
                    <span class="block text-sm font-medium text-gray-700">Name:</span>
                    <span class="block text-sm text-gray-900">{{ $category->name }}</span>
                </div>
                <div class="mb-4">
                    <span class="block text-sm font-medium text-gray-700">Slug:</span>
                    <span class="block text-sm text-gray-900">{{ $category->slug }}</span>
                </div>
                <div class="mb-4">
                    <span class="block text-sm font-medium text-gray-700">Created:</span>
                    <span class="block text-sm text-gray-900">{{ $category->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="mb-4">
                    <span class="block text-sm font-medium text-gray-700">Last Updated:</span>
                    <span class="block text-sm text-gray-900">{{ $category->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                <div class="text-sm text-gray-700">
                    {{ $category->description ?? 'No description available.' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Articles in this Category</h2>
        <a href="{{ route('admin.articles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add New Article
        </a>
    </div>
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($articles as $article)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $article->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $article->author->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 
                                   ($article->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <a href="{{ route('admin.articles.show', $article) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No articles found in this category. <a href="{{ route('admin.articles.create') }}" class="text-indigo-600 hover:text-indigo-900">Create one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</div>
@endsection 