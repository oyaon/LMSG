<?php
// Get current page for active link highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 0;
?>

<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-book-open me-2"></i>
            Gobindaganj Library
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" 
                       href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'all-books.php' ? 'active' : ''; ?>" 
                       href="all-books.php">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'special-offers.php' ? 'active' : ''; ?>" 
                       href="special-offers.php">Special Offers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>" 
                       href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>" 
                       href="contact.php">Contact</a>
                </li>
            </ul>
            
            <!-- Search Form -->
            <form class="d-flex me-2" action="search.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search books..." 
                           aria-label="Search" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- User Menu -->
            <ul class="navbar-nav">
                <?php if ($isLoggedIn): ?>
                    <!-- Cart Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative <?php echo $currentPage == 'cart.php' ? 'active' : ''; ?>" 
                           href="cart.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cart">
                            <i class="fas fa-shopping-cart"></i>
                            <?php
                            // Get cart count from database if available
                            if (isset($cart) && method_exists($cart, 'getCartCount')) {
                                $cartCount = $cart->getCartCount($_SESSION['email']);
                                if ($cartCount > 0) {
                                    echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . 
                                         $cartCount . '<span class="visually-hidden">items in cart</span></span>';
                                }
                            }
                            ?>
                        </a>
                    </li>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Account'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user me-2"></i>My Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="borrow_history.php">
                                <i class="fas fa-history me-2"></i>Borrow History</a>
                            </li>
                            <?php if ($isAdmin): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="admin/index.php">
                                    <i class="fas fa-cog me-2"></i>Admin Panel</a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout_page.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'login_page.php' ? 'active' : ''; ?>" 
                           href="login_page.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'registration_page.php' ? 'active' : ''; ?>" 
                           href="registration_page.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="height: 72px;"></div>
// Get current page for active link highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 0;
?>

<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-book-open me-2"></i>
            Gobindaganj Library
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" 
                       href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'all-books.php' ? 'active' : ''; ?>" 
                       href="all-books.php">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'special-offers.php' ? 'active' : ''; ?>" 
                       href="special-offers.php">Special Offers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>" 
                       href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>" 
                       href="contact.php">Contact</a>
                </li>
            </ul>
            
            <!-- Search Form -->
            <form class="d-flex me-2" action="search.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search books..." 
                           aria-label="Search" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- User Menu -->
            <ul class="navbar-nav">
                <?php if ($isLoggedIn): ?>
                    <!-- Cart Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative <?php echo $currentPage == 'cart.php' ? 'active' : ''; ?>" 
                           href="cart.php" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cart">
                            <i class="fas fa-shopping-cart"></i>
                            <?php
                            // Get cart count from database if available
                            if (isset($cart) && method_exists($cart, 'getCartCount')) {
                                $cartCount = $cart->getCartCount($_SESSION['email']);
                                if ($cartCount > 0) {
                                    echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . 
                                         $cartCount . '<span class="visually-hidden">items in cart</span></span>';
                                }
                            }
                            ?>
                        </a>
                    </li>
                    
                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Account'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">
                                <i class="fas fa-user me-2"></i>My Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="borrow_history.php">
                                <i class="fas fa-history me-2"></i>Borrow History</a>
                            </li>
                            <?php if ($isAdmin): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="admin/index.php">
                                    <i class="fas fa-cog me-2"></i>Admin Panel</a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout_page.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'login_page.php' ? 'active' : ''; ?>" 
                           href="login_page.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'registration_page.php' ? 'active' : ''; ?>" 
                           href="registration_page.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div style="height: 72px;"></div>