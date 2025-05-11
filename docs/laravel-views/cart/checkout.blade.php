@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Checkout</h1>
    
    @if($cartItems->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                <h3>Your cart is empty</h3>
                <p class="mb-4">You need to add books to your cart before checking out.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i> Browse Books
                </a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('payments.process') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <h6>Payment Method</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="fab fa-cc-visa me-2"></i>
                                        <i class="fab fa-cc-mastercard me-2"></i>
                                        <i class="fab fa-cc-amex me-2"></i>
                                        Credit Card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                    <label class="form-check-label" for="paypal">
                                        <i class="fab fa-paypal me-2"></i>
                                        PayPal
                                    </label>
                                </div>
                            </div>
                            
                            <div id="credit-card-details">
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="card_expiry" class="form-label">Expiration Date</label>
                                        <input type="text" class="form-control @error('card_expiry') is-invalid @enderror" id="card_expiry" name="card_expiry" placeholder="MM/YY" required>
                                        @error('card_expiry')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="card_cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control @error('card_cvv') is-invalid @enderror" id="card_cvv" name="card_cvv" placeholder="123" required>
                                        @error('card_cvv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="card_name" class="form-label">Name on Card</label>
                                    <input type="text" class="form-control" id="card_name" name="card_name" placeholder="John Doe" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <textarea class="form-control" id="billing_address" name="billing_address" rows="3" placeholder="Enter your billing address" required></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Cart
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-lock me-2"></i> Complete Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            @foreach($cartItems as $item)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-0">{{ Str::limit($item->book->name, 20) }}</h6>
                                        <small class="text-muted">{{ Str::limit($item->book->author, 20) }}</small>
                                    </div>
                                    <span>${{ number_format($item->book->price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%)</span>
                            <span>${{ number_format($totalPrice * 0.1, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-0 fw-bold">
                            <span>Total</span>
                            <span>${{ number_format($totalPrice * 1.1, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Secure Checkout</h6>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-lock me-2"></i> Your payment information is secure. We use industry-standard encryption to protect your data.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
        const creditCardDetails = document.getElementById('credit-card-details');
        
        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'credit_card') {
                    creditCardDetails.style.display = 'block';
                } else {
                    creditCardDetails.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection