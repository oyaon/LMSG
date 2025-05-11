@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Books</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Authors</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAuthors }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-edit fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Registered Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Borrow Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingBorrows }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Borrow Requests -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Borrow Requests</h6>
                <a href="{{ route('admin.borrows.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentBorrows->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Book</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBorrows as $borrow)
                                    <tr>
                                        <td>{{ $borrow->user->full_name }}</td>
                                        <td>{{ $borrow->book->name }}</td>
                                        <td>
                                            @if($borrow->status == 'Requested')
                                                <span class="badge bg-warning text-dark">Requested</span>
                                            @elseif($borrow->status == 'Issued')
                                                <span class="badge bg-success">Issued</span>
                                            @elseif($borrow->status == 'Returned')
                                                <span class="badge bg-info">Returned</span>
                                            @else
                                                <span class="badge bg-danger">Declined</span>
                                            @endif
                                        </td>
                                        <td>{{ $borrow->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">No recent borrow requests.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->user->full_name }}</td>
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
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">No recent payments.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.books.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus me-2"></i> Add New Book
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.authors.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-plus me-2"></i> Add New Author
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.borrows.index') }}" class="btn btn-info btn-block text-white">
                            <i class="fas fa-tasks me-2"></i> Manage Borrows
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-users me-2"></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection