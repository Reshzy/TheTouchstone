<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contributor;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contributors = Contributor::orderBy('name')->paginate(20);
        return view('admin.contributors.index', compact('contributors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contributors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        Contributor::create($request->all());
        
        return redirect()->route('admin.contributors.index')
            ->with('success', 'Contributor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor)
    {
        return view('admin.contributors.show', compact('contributor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contributor $contributor)
    {
        return view('admin.contributors.edit', compact('contributor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contributor $contributor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $contributor->update($request->all());
        
        return redirect()->route('admin.contributors.index')
            ->with('success', 'Contributor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor)
    {
        $contributor->delete();
        
        return redirect()->route('admin.contributors.index')
            ->with('success', 'Contributor deleted successfully.');
    }
}
