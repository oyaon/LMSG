<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Book;

class BorrowController extends Controller
{
    // Handle borrow request
    public function requestBorrow(Request $request, $bookId)
    {
        $user = Auth::user();
        if (!$user) {
            return Redirect::route('login');
        }
        // Example logic: insert into borrow_history
        $exists = DB::table('borrow_history')
            ->where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->whereIn('status', ['Requested', 'Approved'])
            ->exists();
        if ($exists) {
            return back()->with('status', 'You already have a pending or approved request for this book.');
        }
        DB::table('borrow_history')->insert([
            'user_id' => $user->id,
            'book_id' => $bookId,
            'issue_date' => now(),
            'status' => 'Requested',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return Redirect::route('books.index')->with('status', 'Borrow request submitted successfully.');
    }

    // Show user's borrow history
    public function history(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return Redirect::route('login');
        }
        $history = DB::table('borrow_history')
            ->join('all_books', 'borrow_history.book_id', '=', 'all_books.id')
            ->select('borrow_history.*', 'all_books.name as book_name')
            ->where('borrow_history.user_id', $user->id)
            ->orderByDesc('borrow_history.created_at')
            ->paginate(10);
        return view('borrow.history', compact('history'));
    }

    // Show borrow request form for a specific book
    public function requestForm($bookId)
    {
        $book = \App\Models\Book::findOrFail($bookId);
        return view('borrow.request', compact('book'));
    }
}
