<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('authors.index', compact('authors'));
    }

    public function show($id)
    {
        $author = Author::with('books')->findOrFail($id);
        return view('authors.show', compact('author'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        // Validate and store author
        $request->validate([
            'name' => 'required|string|max:100',
            'biography' => 'nullable|string',
            'image' => 'nullable|image',
        ]);
        $author = new Author($request->only(['name', 'biography']));
        // Handle image upload if present
        if ($request->hasFile('image')) {
            $author->image = $request->file('image')->store('authors', 'public');
        }
        $author->save();
        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
    }

    public function edit($id)
    {
        $author = Author::findOrFail($id);
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'biography' => 'nullable|string',
            'image' => 'nullable|image',
        ]);
        $author->fill($request->only(['name', 'biography']));
        if ($request->hasFile('image')) {
            $author->image = $request->file('image')->store('authors', 'public');
        }
        $author->save();
        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();
        return redirect()->route('admin.authors.index')->with('success', 'Author deleted successfully.');
    }
}
