@extends('layouts.app')

@section('title', 'My Borrows')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">My Borrows</h1>
    
    @if($borrowHistory->count() > 0)
        <ul class="nav nav-tabs mb-4" id="borrowTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="requested-tab" data-bs-toggle="tab" data-bs-target="#requested" type="button" role="tab" aria-controls="requested" aria-selected="false">Requested</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="issued-tab" data-bs-toggle="tab" data-bs-target="#issued" type="button" role="tab" aria-controls="issued" aria-selected="false">Issued</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="returned-tab" data-bs-toggle="tab" data-bs-target="#returned" type="button" role="tab" aria-controls="returned" aria-selected="false">Returned</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="declined-tab" data-bs-toggle="tab" data-bs-target="#declined" type="button" role="tab" aria-controls="declined" aria-selected="false">Declined</button>
            </li>
        </ul>
        
        <div class="tab-content" id="borrowTabsContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                @include('borrow.partials.borrow-table', ['borrows' => $borrowHistory])
            </div>
            
            <div class="tab-pane fade" id="requested" role="tabpanel" aria-labelledby="requested-tab">
                @php
                    $requestedBorrows = $borrowHistory->where('status', 'Requested');
                @endphp
                
                @if($requestedBorrows->count() > 0)
                    @include('borrow.partials.borrow-table', ['borrows' => $requestedBorrows])
                @else
                    <div class="alert alert-info">
                        You don't have any pending borrow requests.
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="issued" role="tabpanel" aria-labelledby="issued-tab">
                @php
                    $issuedBorrows = $borrowHistory->where('status', 'Issued');
                @endphp
                
                @if($issuedBorrows->count() > 0)
                    @include('borrow.partials.borrow-table', ['borrows' => $issuedBorrows])
                @else
                    <div class="alert alert-info">
                        You don't have any currently borrowed books.
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="returned" role="tabpanel" aria-labelledby="returned-tab">
                @php
                    $returnedBorrows = $borrowHistory->where('status', 'Returned');
                @endphp
                
                @if($returnedBorrows->count() > 0)
                    @include('borrow.partials.borrow-table', ['borrows' => $returnedBorrows])
                @else
                    <div class="alert alert-info">
                        You don't have any returned books.
                    </div>
                @endif
            </div>
            
            <div class="tab-pane fade" id="declined" role="tabpanel" aria-labelledby="declined-tab">
                @php
                    $declinedBorrows = $borrowHistory->where('status', 'Declined');
                @endphp
                
                @if($declinedBorrows->count() > 0)
                    @include('borrow.partials.borrow-table', ['borrows' => $declinedBorrows])
                @else
                    <div class="alert alert-info">
                        You don't have any declined borrow requests.
                    </div>
                @endif
            </div>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $borrowHistory->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-book-reader fa-4x text-muted mb-4"></i>
                <h3>No borrow history</h3>
                <p class="mb-4">You haven't borrowed any books yet.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i> Browse Books to Borrow
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .overdue {
        color: #dc3545;
        font-weight: bold;
    }
</style>
@endpush