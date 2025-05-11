@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Edit Profile</h1>
            
            <div class="card mb-4">
                <div class="card-header">Profile Information</div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">First Name</label>
                            
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->first_name) }}" required autocomplete="first_name" autofocus>
                                
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">Last Name</label>
                            
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->last_name) }}" required autocomplete="last_name">
                                
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="user_name" class="col-md-4 col-form-label text-md-end">Username</label>
                            
                            <div class="col-md-6">
                                <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name', $user->user_name) }}" required autocomplete="user_name">
                                
                                @error('user_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email Address</label>
                            
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Update Password</div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <label for="current_password" class="col-md-4 col-form-label text-md-end">Current Password</label>
                            
                            <div class="col-md-6">
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current-password">
                                
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">New Password</label>
                            
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                <div class="form-text">
                                    Password must be at least 8 characters and include uppercase, lowercase, numbers, and symbols.
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">Confirm New Password</label>
                            
                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Account Summary</div>
                
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $user->full_name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>Account Type</h6>
                        <p>
                            @if($user->isAdmin())
                                <span class="badge bg-danger">Administrator</span>
                            @else
                                <span class="badge bg-primary">Regular User</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Member Since</h6>
                        <p>{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6>Activity Summary</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('borrow.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Borrowed Books
                                <span class="badge bg-primary rounded-pill">{{ $user->currentlyBorrowedBooks()->count() }}</span>
                            </a>
                            <a href="{{ route('cart.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Cart Items
                                <span class="badge bg-primary rounded-pill">{{ $user->activeCartItems()->count() }}</span>
                            </a>
                            <a href="{{ route('payments.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                Completed Payments
                                <span class="badge bg-primary rounded-pill">{{ $user->completedPayments()->count() }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection