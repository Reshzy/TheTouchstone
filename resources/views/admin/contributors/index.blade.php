@extends('layouts.admin')

@section('title', 'Contributors')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Contributors</h1>
    <a href="{{ route('admin.contributors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Add New Contributor
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    ID
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($contributors as $contributor)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $contributor->id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $contributor->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.contributors.edit', $contributor) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form action="{{ route('admin.contributors.destroy', $contributor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this contributor?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            
            @if($contributors->count() === 0)
            <tr>
                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    No contributors found.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $contributors->links() }}
</div>
@endsection 