<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        
        // Hash the password
        $data['password'] = Hash::make($data['password']);
        
        // Set default for is_admin if not provided
        $data['is_admin'] = $data['is_admin'] ?? false;
        
        User::create($data);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Get articles by this user
        $articles = $user->articles()->with('category')->latest()->paginate(5);
        
        return view('admin.users.show', compact('user', 'articles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        
        // Only update password if it was provided
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        // Set is_admin as false if not provided
        $data['is_admin'] = $data['is_admin'] ?? false;
        
        // Prevent a user from removing their own admin privileges
        if ($user->id === Auth::id() && $user->is_admin && !$data['is_admin']) {
            return redirect()->back()
                ->with('error', 'You cannot remove your own admin privileges.');
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Check if user has articles before deleting
        if ($user->articles()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'This user has published articles and cannot be deleted.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
