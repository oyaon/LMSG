<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Services\BookService;
use Exception;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    // List all books with search, filter, and pagination
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $category = $request->input('category', '');
        $perPage = (int) $request->input('per_page', 12);
        $perPage = $perPage > 0 ? $perPage : 12;
        $booksQuery = Book::query();
        if ($query) {
            $booksQuery->where('name', 'like', "%$query%")
                ->orWhere('author', 'like', "%$query%")
                ->orWhere('category', 'like', "%$query%")
                ->orWhere('description', 'like', "%$query%")
                ;
        }
        if ($category) {
            $booksQuery->where('category', $category);
        }
        $books = $booksQuery->orderBy('name', 'asc')->paginate($perPage)->withQueryString();
        $categories = Book::select('category')->distinct()->pluck('category');
        return view('books.index', compact('books', 'categories', 'query', 'category', 'perPage'));
    }

    // Show book details
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show', compact('book'));
    }

    // Store a new book
    public function store(Request $request)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'cat' => 'required|string|max:100',
            'des' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
            'cover_image' => 'nullable|image|max:5120', // max 5MB
        ]);

        try {
            $bookData = [
                'name' => $request->input('book_name'),
                'author' => $request->input('author'),
                'category' => $request->input('cat'),
                'description' => $request->input('des'),
                'quantity' => $request->input('quantity'),
                'price' => $request->input('price', 0),
            ];

            $pdfFile = $request->file('pdf');
            $coverImageFile = $request->file('cover_image');

            $book = $this->bookService->addBook($bookData, $pdfFile, $coverImageFile);

            return redirect()->route('books.index')->with('success', 'Book added successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to add book: ' . $e->getMessage()])->withInput();
        }
    }
}
