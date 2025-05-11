<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Admin - {{ config('app.name', 'Library Management System') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading p-3 border-bottom">
                <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                    <i class="fas fa-book-reader me-2"></i> LMS Admin
                </a>
            </div>
            
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                
                <a href="{{ route('admin.books.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i> Books
                </a>
                
                <a href="{{ route('admin.authors.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit me-2"></i> Authors
                </a>
                
                <a href="{{ route('admin.borrows.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.borrows.*') ? 'active' : '' }}">
                    <i class="fas fa-book-reader me-2"></i> Borrows
                </a>
                
                <a href="{{ route('admin.payments.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill me-2"></i> Payments
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action bg-dark text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
                
                <div class="border-top mt-3 pt-3">
                    <a href="{{ route('home') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="fas fa-home me-2"></i> Back to Site
                    </a>
                    
                    <a href="{{ route('logout') }}" class="list-group-item list-group-item-action bg-dark text-white" 
                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    
                    <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->first_name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('admin-logout-form-2').submit();">
                                            Logout
                                        </a>
                                        <form id="admin-logout-form-2" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Flash Messages -->
            <div class="container-fluid px-4 py-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            
            <!-- Page Content -->
            <div class="container-fluid px-4">
                <h1 class="mt-2 mb-4">@yield('title', 'Dashboard')</h1>
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    
    <script>
        // Toggle sidebar
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
        });
    </script>
    
    @stack('scripts')
</body>
</html>