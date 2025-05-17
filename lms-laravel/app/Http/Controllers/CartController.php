<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Show the user's cart
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $page = (int) $request->input('page', 1);
        $perPage = 10;
        $cartQuery = DB::table('cart')
            ->join('all_books', 'cart.book_id', '=', 'all_books.id')
            ->select('cart.*', 'all_books.name', 'all_books.price', 'all_books.quantity as book_quantity')
            ->where('cart.user_email', $user->email)
            ->where('cart.status', 0)
            ->orderByDesc('cart.date');
        $total = $cartQuery->count();
        $cartItems = $cartQuery->forPage($page, $perPage)->get();
        $totalPages = ceil($total / $perPage);
        $totalPrice = $cartItems->sum('price');
        return view('cart.index', compact('cartItems', 'total', 'totalPages', 'page', 'perPage', 'totalPrice'));
    }

    // Add a book to the cart
    public function add(Request $request, $bookId)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $book = DB::table('all_books')->where('id', $bookId)->first();
        if (!$book || $book->quantity <= 0) {
            return back()->with('status', 'Book is out of stock.');
        }
        DB::beginTransaction();
        try {
            DB::table('cart')->insert([
                'user_email' => $user->email,
                'book_id' => $bookId,
                'date' => now(),
                'status' => 0,
            ]);
            DB::table('all_books')->where('id', $bookId)->decrement('quantity');
            DB::commit();
            return redirect()->route('cart.index')->with('status', 'Book added to cart.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', 'Failed to add book to cart.');
        }
    }

    // Update cart item quantity (simplified: remove and re-add for demo)
    public function update(Request $request, $cartId)
    {
        $user = Auth::user();
        $quantity = (int) $request->input('quantity', 1);
        if ($cartId <= 0 || $quantity <= 0) {
            return back()->with('status', 'Invalid input.');
        }
        $cartItem = DB::table('cart')->where('id', $cartId)->where('user_email', $user->email)->where('status', 0)->first();
        if (!$cartItem) {
            return back()->with('status', 'Cart item not found.');
        }
        // For demo: remove all and re-add with new quantity
        DB::beginTransaction();
        try {
            DB::table('cart')->where('id', $cartId)->delete();
            for ($i = 0; $i < $quantity; $i++) {
                DB::table('cart')->insert([
                    'user_email' => $user->email,
                    'book_id' => $cartItem->book_id,
                    'date' => now(),
                    'status' => 0,
                ]);
            }
            DB::commit();
            return back()->with('status', 'Cart updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', 'Failed to update cart.');
        }
    }

    // Remove a cart item
    public function remove(Request $request, $cartId)
    {
        $user = Auth::user();
        $cartItem = DB::table('cart')->where('id', $cartId)->where('user_email', $user->email)->first();
        if ($cartItem) {
            DB::table('cart')->where('id', $cartId)->delete();
            DB::table('all_books')->where('id', $cartItem->book_id)->increment('quantity');
        }
        return back()->with('status', 'Cart item removed.');
    }
}
