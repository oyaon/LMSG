<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
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
     * Display a listing of the user's payment history.
     */
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Process payment for cart items.
     */
    public function process(Request $request)
    {
        // Validate payment details
        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,paypal',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_expiry' => 'required_if:payment_method,credit_card',
            'card_cvv' => 'required_if:payment_method,credit_card',
        ]);
        
        // Get cart items
        $cartItems = Cart::with('book')
            ->where('user_id', Auth::id())
            ->active()
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }
        
        // Calculate total amount
        $totalAmount = Cart::getTotalPrice(Auth::id());
        
        // Get book IDs
        $bookIds = $cartItems->pluck('book_id')->implode(',');
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'user_email' => Auth::user()->email,
                'book_ids' => $bookIds,
                'amount' => $totalAmount,
                'payment_date' => now(),
                'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                'payment_status' => 'Completed',
            ]);
            
            // Update cart items to purchased
            foreach ($cartItems as $item) {
                $item->status = 1; // Purchased
                $item->save();
            }
            
            DB::commit();
            
            return redirect()->route('payments.success', $payment)
                ->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('cart.checkout')
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    /**
     * Display payment success page.
     */
    public function success(Payment $payment)
    {
        // Check if payment belongs to the authenticated user
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Get books from payment
        $books = $payment->books();
        
        return view('payments.success', compact('payment', 'books'));
    }

    /**
     * Display admin payment management page.
     */
    public function adminIndex()
    {
        $this->authorize('viewAny', Payment::class);
        
        $payments = Payment::with('user')
            ->latest()
            ->paginate(20);
        
        $totalRevenue = Payment::where('payment_status', 'Completed')
            ->sum('amount');
        
        return view('admin.payments.index', compact('payments', 'totalRevenue'));
    }

    /**
     * Display details of a specific payment (admin only).
     */
    public function adminShow(Payment $payment)
    {
        $this->authorize('view', $payment);
        
        // Get books from payment
        $books = $payment->books();
        
        return view('admin.payments.show', compact('payment', 'books'));
    }
}