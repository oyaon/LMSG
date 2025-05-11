<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
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
     * Display a listing of the cart items.
     */
    public function index()
    {
        $cartItems = Cart::with('book')
            ->where('user_id', Auth::id())
            ->active()
            ->latest()
            ->get();
        
        $totalPrice = Cart::getTotalPrice(Auth::id());
        
        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    /**
     * Add a book to the cart.
     */
    public function add(Request $request, Book $book)
    {
        // Check if book is already in cart
        $existingItem = Cart::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->active()
            ->first();
        
        if ($existingItem) {
            return redirect()->route('cart.index')
                ->with('info', 'This book is already in your cart.');
        }
        
        // Check if book is available (quantity > 0)
        if ($book->quantity <= 0) {
            return back()->with('error', 'This book is out of stock.');
        }
        
        // Add to cart
        Cart::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'book_id' => $book->id,
            'date' => now(),
            'status' => 0, // In cart
        ]);
        
        return redirect()->route('cart.index')
            ->with('success', 'Book added to cart successfully.');
    }

    /**
     * Remove a book from the cart.
     */
    public function remove(Cart $cart)
    {
        // Check if cart item belongs to the authenticated user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Delete cart item
        $cart->delete();
        
        return redirect()->route('cart.index')
            ->with('success', 'Book removed from cart successfully.');
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        // Delete all active cart items for the user
        Cart::where('user_id', Auth::id())
            ->active()
            ->delete();
        
        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully.');
    }

    /**
     * Proceed to checkout.
     */
    public function checkout()
    {
        $cartItems = Cart::with('book')
            ->where('user_id', Auth::id())
            ->active()
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        $totalPrice = Cart::getTotalPrice(Auth::id());
        
        return view('cart.checkout', compact('cartItems', 'totalPrice'));
    }
}