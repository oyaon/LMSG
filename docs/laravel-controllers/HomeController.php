<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get featured books
        $featuredBooks = Book::with('author')
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        // Get latest books
        $latestBooks = Book::with('author')
            ->latest()
            ->limit(8)
            ->get();
        
        // Get popular authors
        $popularAuthors = Author::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(6)
            ->get();
        
        // Get book categories
        $categories = Book::getCategories();
        
        return view('home', compact('featuredBooks', 'latestBooks', 'popularAuthors', 'categories'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Process contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:200',
            'message' => 'required|string',
        ]);
        
        // Here you would typically send an email or store the contact message
        // For now, we'll just redirect with a success message
        
        return back()->with('success', 'Your message has been sent successfully.');
    }

    /**
     * Display the dashboard for authenticated users.
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Display the admin dashboard.
     */
    public function adminDashboard()
    {
        $this->authorize('viewAdminDashboard');
        
        // Get counts for dashboard
        $totalBooks = Book::count();
        $totalAuthors = Author::count();
        $totalUsers = \App\Models\User::where('user_type', 1)->count(); // Regular users
        $pendingBorrows = \App\Models\BorrowHistory::where('status', 'Requested')->count();
        
        // Get recent activities
        $recentBorrows = \App\Models\BorrowHistory::with(['user', 'book'])
            ->latest()
            ->limit(5)
            ->get();
        
        $recentPayments = \App\Models\Payment::with('user')
            ->latest()
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalBooks',
            'totalAuthors',
            'totalUsers',
            'pendingBorrows',
            'recentBorrows',
            'recentPayments'
        ));
    }
}