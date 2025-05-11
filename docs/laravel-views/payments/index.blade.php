@extends('layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Payment History</h1>
    
    @if($payments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Payments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if($payment->payment_status == 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($payment->payment_status == 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $payment->id }}">
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Payment Details Modal -->
                                <div class="modal fade" id="paymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $payment->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentModalLabel{{ $payment->id }}">Payment Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4 fw-bold">Transaction ID:</div>
                                                    <div class="col-md-8">{{ $payment->transaction_id }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 fw-bold">Date:</div>
                                                    <div class="col-md-8">{{ $payment->payment_date->format('F d, Y') }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 fw-bold">Amount:</div>
                                                    <div class="col-md-8">${{ number_format($payment->amount, 2) }}</div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4 fw-bold">Status:</div>
                                                    <div class="col-md-8">
                                                        @if($payment->payment_status == 'Completed')
                                                            <span class="badge bg-success">Completed</span>
                                                        @elseif($payment->payment_status == 'Pending')
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @else
                                                            <span class="badge bg-danger">Failed</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <h6 class="mt-4 mb-3">Purchased Books</h6>
                                                
                                                @php
                                                    $bookIds = explode(',', $payment->book_ids);
                                                    $books = \App\Models\Book::whereIn('id', $bookIds)->get();
                                                @endphp
                                                
                                                <div class="list-group">
                                                    @foreach($books as $book)
                                                        <div class="list-group-item">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-2 col-4 mb-2 mb-md-0">
                                                                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->name }}" class="img-fluid rounded" style="width: 60px; height: 80px; object-fit: cover;">
                                                                </div>
                                                                <div class="col-md-8 col-8 mb-2 mb-md-0">
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
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-money-bill-wave fa-4x text-muted mb-4"></i>
                <h3>No payment history</h3>
                <p class="mb-4">You haven't made any payments yet.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i> Browse Books
                </a>
            </div>
        </div>
    @endif
</div>
@endsection