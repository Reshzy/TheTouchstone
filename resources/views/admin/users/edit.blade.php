@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Edit User: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
        Back to Users
    </a>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                   required>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                   required>
        </div>
        
        <div class="mb-4 border-t pt-4">
            <h3 class="text-md font-medium text-gray-900 mb-2">Change Password</h3>
            <p class="text-sm text-gray-500 mb-4">Leave these fields blank to keep the current password.</p>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" id="password" 
                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>
        
        <div class="mb-4 border-t pt-4">
            <h3 class="text-md font-medium text-gray-900 mb-2">Role</h3>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_admin" id="is_admin" value="1" 
                       {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                       {{ $user->id === Auth::id() && $user->is_admin ? 'disabled' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_admin" class="ml-2 block text-sm text-gray-900">
                    Admin Privileges
                </label>
            </div>
            @if($user->id === Auth::id() && $user->is_admin)
                <p class="text-xs text-red-500 mt-1">You cannot remove your own admin privileges.</p>
                <input type="hidden" name="is_admin" value="1">
            @else
                <p class="text-xs text-gray-500 mt-1">Admin users can access the admin dashboard and manage all content.</p>
            @endif
        </div>
        
        <div class="border-t pt-4 mb-4">
            <h3 class="text-md font-medium text-gray-900 mb-2">Account Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-sm font-medium text-gray-700">Created:</span>
                    <span class="block text-sm text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div>
                    <span class="block text-sm font-medium text-gray-700">Last Updated:</span>
                    <span class="block text-sm text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update User
            </button>
        </div>
    </form>
</div>
@endsection 