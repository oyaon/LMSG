@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="fs-1">Welcome Admin User</h1>
    <div class="row mt-4">
        <div class="col-md-4">
            <x-stat-card title="Total Books" :value="$totalBooks" description="Books in the library" bgClass="bg-primary" />
        </div>
        <div class="col-md-4">
            <x-stat-card title="Total Users" :value="$totalUsers" description="Registered users" bgClass="bg-success" />
        </div>
        <div class="col-md-4">
            <x-stat-card title="Active Borrows" :value="$activeBorrows" description="Currently borrowed books" bgClass="bg-info" />
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <x-list-card title="Recent Activities" :items="$recentActivities" emptyMessage="No recent activities">
                @foreach($recentActivities as $activity)
                    <li class="list-group-item">{{ $activity->username }} borrowed "{{ $activity->title }}" on {{ \Carbon\Carbon::parse($activity->borrow_date)->format('M d, Y') }}</li>
                @endforeach
            </x-list-card>
        </div>
        <div class="col-md-6">
            <x-action-card title="Quick Actions" :actions="[
                ['label' => 'Manage Books', 'class' => 'btn-outline-primary', 'url' => '#'],
                ['label' => 'Manage Users', 'class' => 'btn-outline-secondary', 'url' => '#'],
                ['label' => 'View Borrow History', 'class' => 'btn-outline-success', 'url' => '#'],
                ['label' => 'Backup Database', 'class' => 'btn-outline-warning', 'url' => '#'],
            ]" />
        </div>
    </div>
</div>
@endsection
