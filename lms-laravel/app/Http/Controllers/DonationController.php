<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DonationController extends Controller
{
    // Show the donation page
    public function index()
    {
        $books = DB::table('all_books')->orderBy('name')->get();
        return view('donate.index', compact('books'));
    }

    // Handle money donation
    public function donateMoney(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string',
            'donor_email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'trans_id' => 'required|string',
        ]);
        DB::table('money_donations')->insert([
            'user_email' => $request->donor_email,
            'amount' => $request->amount,
            'donation_date' => now(),
            'transaction_id' => $request->trans_id,
            'donation_status' => 'Completed',
        ]);
        return Redirect::route('donate.index')->with('status', 'Thank you for your generous donation!');
    }

    // Handle book donation
    public function donateBooks(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string',
            'donor_email' => 'required|email',
            'book_ids' => 'required|array',
            'quantities' => 'required|array',
        ]);
        $allValid = true;
        foreach ($request->book_ids as $i => $book_id) {
            $quantity = (int)($request->quantities[$i] ?? 0);
            if ($book_id <= 0 || $quantity <= 0) {
                $allValid = false;
                break;
            }
            DB::table('book_donations')->insert([
                'user_email' => $request->donor_email,
                'book_id' => $book_id,
                'quantity' => $quantity,
                'donation_date' => now(),
                'status' => 'Pending',
            ]);
        }
        $msg = $allValid ? 'Thank you for your book donation! We will contact you with further details.' : 'Failed to process your book donation. Please try again.';
        return Redirect::route('donate.index')->with('status', $msg);
    }
}
