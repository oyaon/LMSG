@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h2 class="mb-4">Payment Successful!</h2>
                    
                    <p class="lead mb-4">
                        Thank you for your purchase. Your transaction has been completed successfully.
                    </p>
                    
                    <div class="alert alert-success mb-4">
                        <div class="row">
                            <div class="col-md-6 text-md-end text-start">
                                <strong>Transaction ID:</strong>
                            </div>
                            <div class="col-md-6 text-md-start text-start">
                                {{ $payment->transaction_id }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 text-md-end text-start">
                                <strong>Amount:</strong>
                            </div>
                            <div class="col-md-6 text-md-start text-start">
                                ${{ number_format($payment->amount, 2) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 text-md-end text-start">
                                <strong>Date:</strong>
                            </div>
                            <div class="col-md-6 text-md-start text-start">
                                {{ $payment->payment_date->format('F d, Y') }}
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="mb-3">Purchased Books</h4>
                    
                    <div class="list-group mb-4">
                        @foreach($books as $book)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2 col-4 mb-2 mb-md-0">
                                        <img src="{{ $book->cover_image_url }}" alt="{{ $book->name }}" class="img-fluid rounded" style="width: 60px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-8 col-8 mb-2 mb-md-0 text-start">
                                        <h6 class="mb-1">{{ $book->name }}</h6>
                                        <p class="mb-0 text-muted">{{ $book->author }}</p>
                                    </div>
                                    <div class="col-md-2 col-12 text-md-end text-start">
                                        <span class="fw-bold">${{ number_format($book->price, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <p class="mb-4">
                        A confirmation email has been sent to <strong>{{ Auth::user()->email }}</strong>.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i> View Payment History
                        </a>
                        <a href="{{ route('books.index') }}" class="btn btn-primary">
                            <i class="fas fa-book me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection