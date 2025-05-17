@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="fs-1">Welcome Admin User</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Books</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalBooks }}</h5>
                    <p class="card-text">Books in the library</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalUsers }}</h5>
                    <p class="card-text">Registered users</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Active Borrows</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $activeBorrows }}</h5>
                    <p class="card-text">Currently borrowed books</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">Recent Activities</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentActivities as $activity)
                            <li class="list-group-item">{{ $activity->username }} borrowed "{{ $activity->title }}" on {{ \Carbon\Carbon::parse($activity->borrow_date)->format('M d, Y') }}</li>
                        @empty
                            <li class="list-group-item">No recent activities</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-light">Quick Actions</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">Manage Books</a>
                        <a href="#" class="btn btn-outline-secondary">Manage Users</a>
                        <a href="#" class="btn btn-outline-success">View Borrow History</a>
                        <a href="#" class="btn btn-outline-warning">Backup Database</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
