<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's borrow history.
     */
    public function index()
    {
        $borrowHistory = BorrowHistory::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('borrow.index', compact('borrowHistory'));
    }

    /**
     * Request to borrow a book.
     */
    public function request(Request $request, Book $book)
    {
        // Check if book is available (quantity > 0)
        if ($book->quantity <= 0) {
            return back()->with('error', 'This book is not available for borrowing.');
        }
        
        // Check if user already has an active borrow request for this book
        $existingRequest = BorrowHistory::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['Requested', 'Issued'])
            ->first();
        
        if ($existingRequest) {
            return back()->with('info', 'You already have an active borrow request for this book.');
        }
        
        // Create borrow request
        BorrowHistory::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'book_id' => $book->id,
            'status' => 'Requested',
        ]);
        
        return redirect()->route('borrow.index')
            ->with('success', 'Borrow request submitted successfully.');
    }

    /**
     * Cancel a borrow request.
     */
    public function cancel(BorrowHistory $borrow)
    {
        // Check if borrow record belongs to the authenticated user
        if ($borrow->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if status is 'Requested'
        if ($borrow->status !== 'Requested') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }
        
        // Delete borrow request
        $borrow->delete();
        
        return redirect()->route('borrow.index')
            ->with('success', 'Borrow request cancelled successfully.');
    }

    /**
     * Return a borrowed book.
     */
    public function return(BorrowHistory $borrow)
    {
        // Check if borrow record belongs to the authenticated user
        if ($borrow->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if status is 'Issued'
        if ($borrow->status !== 'Issued') {
            return back()->with('error', 'Only issued books can be returned.');
        }
        
        // Update borrow record
        $borrow->status = 'Returned';
        $borrow->return_date = now();
        
        // Calculate fine if overdue
        $fine = $borrow->calculateFine();
        if ($fine > 0) {
            $borrow->fine = $fine;
        }
        
        $borrow->save();
        
        // Increase book quantity
        $book = $borrow->book;
        $book->quantity += 1;
        $book->save();
        
        return redirect()->route('borrow.index')
            ->with('success', 'Book returned successfully.');
    }

    /**
     * Display admin borrow management page.
     */
    public function adminIndex()
    {
        $this->authorize('viewAny', BorrowHistory::class);
        
        $pendingRequests = BorrowHistory::with(['user', 'book'])
            ->requested()
            ->latest()
            ->get();
        
        $issuedBooks = BorrowHistory::with(['user', 'book'])
            ->issued()
            ->latest()
            ->get();
        
        return view('admin.borrow.index', compact('pendingRequests', 'issuedBooks'));
    }

    /**
     * Approve a borrow request (admin only).
     */
    public function approve(BorrowHistory $borrow)
    {
        $this->authorize('update', $borrow);
        
        // Check if status is 'Requested'
        if ($borrow->status !== 'Requested') {
            return back()->with('error', 'Only pending requests can be approved.');
        }
        
        // Check if book is available
        $book = $borrow->book;
        if ($book->quantity <= 0) {
            return back()->with('error', 'This book is not available for borrowing.');
        }
        
        // Update borrow record
        $borrow->status = 'Issued';
        $borrow->issue_date = now();
        $borrow->return_date = now()->addDays(14); // 2 weeks borrowing period
        $borrow->save();
        
        // Decrease book quantity
        $book->quantity -= 1;
        $book->save();
        
        return back()->with('success', 'Borrow request approved successfully.');
    }

    /**
     * Decline a borrow request (admin only).
     */
    public function decline(BorrowHistory $borrow)
    {
        $this->authorize('update', $borrow);
        
        // Check if status is 'Requested'
        if ($borrow->status !== 'Requested') {
            return back()->with('error', 'Only pending requests can be declined.');
        }
        
        // Update borrow record
        $borrow->status = 'Declined';
        $borrow->save();
        
        return back()->with('success', 'Borrow request declined successfully.');
    }
}