@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2>User Profile</h2>
    <div class="card shadow-sm p-4 d-flex flex-column align-items-center">
        {{-- Profile Image --}}
        @php
            $profileImage = $user->profile_image ? asset('uploads/profile_images/' . $user->profile_image) : asset('images/avatar.png');
        @endphp
        <img src="{{ $profileImage }}" alt="Profile Image" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">

        {{-- Profile Image Upload Form (to be implemented) --}}
        <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data" class="mb-3 w-100" style="max-width: 300px;">
            @csrf
            <div class="mb-3">
                <label for="profile_image" class="form-label">Upload Profile Image</label>
                <input class="form-control" type="file" id="profile_image" name="profile_image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>

        <p><strong>Full Name:</strong> {{ $user->full_name }}</p>
        <p><strong>Username:</strong> {{ $user->user_name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>User Type:</strong> {{ $user->user_type == 0 ? 'Admin' : 'Regular User' }}</p>
    </div>
</div>
@endsection
