<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     */
    public function index()
    {
        $authors = Author::withCount('books')
            ->orderBy('name')
            ->paginate(12);
        
        return view('authors.index', compact('authors'));
    }

    /**
     * Display the specified author.
     */
    public function show(Author $author)
    {
        // Load the books relationship
        $books = $author->books()->latest()->paginate(8);
        
        return view('authors.show', compact('author', 'books'));
    }

    /**
     * Show the form for creating a new author (admin only).
     */
    public function create()
    {
        $this->authorize('create', Author::class);
        
        return view('admin.authors.create');
    }

    /**
     * Store a newly created author in storage (admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Author::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'biography' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = Str::uuid() . '.' . $request->image->extension();
            $request->image->storeAs('authors', $imageName, 'public');
            $validated['image'] = $imageName;
        }
        
        // Create author
        Author::create($validated);
        
        return redirect()->route('admin.authors.index')
            ->with('success', 'Author created successfully.');
    }

    /**
     * Show the form for editing the specified author (admin only).
     */
    public function edit(Author $author)
    {
        $this->authorize('update', $author);
        
        return view('admin.authors.edit', compact('author'));
    }

    /**
     * Update the specified author in storage (admin only).
     */
    public function update(Request $request, Author $author)
    {
        $this->authorize('update', $author);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'biography' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($author->image) {
                Storage::disk('public')->delete('authors/' . $author->image);
            }
            
            $imageName = Str::uuid() . '.' . $request->image->extension();
            $request->image->storeAs('authors', $imageName, 'public');
            $validated['image'] = $imageName;
        }
        
        // Update author
        $author->update($validated);
        
        return redirect()->route('admin.authors.index')
            ->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified author from storage (admin only).
     */
    public function destroy(Author $author)
    {
        $this->authorize('delete', $author);
        
        // Delete image if exists
        if ($author->image) {
            Storage::disk('public')->delete('authors/' . $author->image);
        }
        
        // Delete author
        $author->delete();
        
        return redirect()->route('admin.authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}