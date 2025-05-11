<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index(Request $request)
    {
        $query = Book::with('author');
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Apply category filter if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->category($request->category);
        }
        
        // Get books with pagination
        $books = $query->latest()->paginate(12);
        
        // Get all categories for filter dropdown
        $categories = Book::getCategories();
        
        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        // Load the author relationship
        $book->load('author');
        
        // Get related books by the same author
        $relatedBooks = Book::where('author_id', $book->author_id)
            ->where('id', '!=', $book->id)
            ->latest()
            ->limit(4)
            ->get();
        
        return view('books.show', compact('book', 'relatedBooks'));
    }

    /**
     * Display books by category.
     */
    public function byCategory($category)
    {
        $books = Book::with('author')
            ->category($category)
            ->latest()
            ->paginate(12);
        
        return view('books.category', compact('books', 'category'));
    }

    /**
     * Display latest books.
     */
    public function latest()
    {
        $books = Book::with('author')
            ->latest()
            ->limit(10)
            ->get();
        
        return view('books.latest', compact('books'));
    }

    /**
     * Search for books.
     */
    public function search(Request $request)
    {
        $search = $request->input('query');
        
        $books = Book::with('author')
            ->search($search)
            ->latest()
            ->paginate(12);
        
        return view('books.search', compact('books', 'search'));
    }

    /**
     * Show the form for creating a new book (admin only).
     */
    public function create()
    {
        $this->authorize('create', Book::class);
        
        $authors = Author::orderBy('name')->get();
        $categories = Book::getCategories();
        
        return view('admin.books.create', compact('authors', 'categories'));
    }

    /**
     * Store a newly created book in storage (admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Book::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'author' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|max:2048',
        ]);
        
        // Handle author
        if (empty($validated['author_id']) && !empty($validated['author'])) {
            // Check if author exists
            $author = Author::where('name', $validated['author'])->first();
            
            if ($author) {
                $validated['author_id'] = $author->id;
            } else {
                // Create new author
                $newAuthor = Author::create(['name' => $validated['author']]);
                $validated['author_id'] = $newAuthor->id;
            }
        }
        
        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $pdfName = Str::uuid() . '.' . $request->pdf->extension();
            $request->pdf->storeAs('pdfs', $pdfName, 'public');
            $validated['pdf'] = $pdfName;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imageName = Str::uuid() . '.' . $request->cover_image->extension();
            $request->cover_image->storeAs('covers', $imageName, 'public');
            $validated['cover_image'] = $imageName;
        }
        
        // Create book
        Book::create($validated);
        
        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Show the form for editing the specified book (admin only).
     */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        
        $authors = Author::orderBy('name')->get();
        $categories = Book::getCategories();
        
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }

    /**
     * Update the specified book in storage (admin only).
     */
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'author_id' => 'nullable|exists:authors,id',
            'author' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
            'cover_image' => 'nullable|image|max:2048',
        ]);
        
        // Handle author
        if (empty($validated['author_id']) && !empty($validated['author'])) {
            // Check if author exists
            $author = Author::where('name', $validated['author'])->first();
            
            if ($author) {
                $validated['author_id'] = $author->id;
            } else {
                // Create new author
                $newAuthor = Author::create(['name' => $validated['author']]);
                $validated['author_id'] = $newAuthor->id;
            }
        }
        
        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            // Delete old file if exists
            if ($book->pdf) {
                Storage::disk('public')->delete('pdfs/' . $book->pdf);
            }
            
            $pdfName = Str::uuid() . '.' . $request->pdf->extension();
            $request->pdf->storeAs('pdfs', $pdfName, 'public');
            $validated['pdf'] = $pdfName;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old file if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete('covers/' . $book->cover_image);
            }
            
            $imageName = Str::uuid() . '.' . $request->cover_image->extension();
            $request->cover_image->storeAs('covers', $imageName, 'public');
            $validated['cover_image'] = $imageName;
        }
        
        // Update book
        $book->update($validated);
        
        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified book from storage (admin only).
     */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        
        // Delete PDF file if exists
        if ($book->pdf) {
            Storage::disk('public')->delete('pdfs/' . $book->pdf);
        }
        
        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete('covers/' . $book->cover_image);
        }
        
        // Delete book
        $book->delete();
        
        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}