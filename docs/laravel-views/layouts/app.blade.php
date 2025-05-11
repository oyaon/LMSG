<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Library Management System') }} - @yield('title', 'Home')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Header -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        {{ config('app.name', 'Library Management System') }}
                    </a>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('books.index') ? 'active' : '' }}" href="{{ route('books.index') }}">Books</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('authors.index') ? 'active' : '' }}" href="{{ route('authors.index') }}">Authors</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                            </li>
                        </ul>
                        
                        <!-- Search Form -->
                        <form class="d-flex mx-auto" action="{{ route('books.search') }}" method="GET">
                            <input class="form-control me-2" type="search" name="query" placeholder="Search books..." aria-label="Search" required>
                            <button class="btn btn-outline-light" type="submit">Search</button>
                        </form>
                        
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link position-relative {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart"></i> Cart
                                        @php
                                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->where('status', 0)->count();
                                        @endphp
                                        @if($cartCount > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $cartCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->first_name }}
                                    </a>
                                    
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @if(Auth::user()->isAdmin())
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-user me-2"></i> Dashboard
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user-edit me-2"></i> Profile
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{ route('borrow.index') }}">
                                            <i class="fas fa-book-reader me-2"></i> My Borrows
                                        </a>
                                        
                                        <a class="dropdown-item" href="{{ route('payments.index') }}">
                                            <i class="fas fa-money-bill me-2"></i> My Payments
                                        </a>
                                        
                                        <div class="dropdown-divider"></div>
                                        
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                        
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="container mt-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        <!-- Main Content -->
        <main class="py-4">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>{{ config('app.name', 'Library Management System') }}</h5>
                        <p>Your one-stop destination for books and knowledge. Browse our collection, borrow books, or purchase your favorites.</p>
                    </div>
                    
                    <div class="col-md-4">
                        <h5>Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
                            <li><a href="{{ route('books.index') }}" class="text-white">Books</a></li>
                            <li><a href="{{ route('authors.index') }}" class="text-white">Authors</a></li>
                            <li><a href="{{ route('about') }}" class="text-white">About Us</a></li>
                            <li><a href="{{ route('contact') }}" class="text-white">Contact Us</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-md-4">
                        <h5>Contact Us</h5>
                        <address>
                            <p><i class="fas fa-map-marker-alt me-2"></i> 123 Library Street, Book City</p>
                            <p><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                            <p><i class="fas fa-envelope me-2"></i> info@librarylms.com</p>
                        </address>
                        
                        <div class="social-icons mt-3">
                            <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Library Management System') }}. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> by LMS Team</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    @stack('scripts')
</body>
</html>