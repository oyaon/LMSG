<?php
    // Get current page for active link highlighting
    $currentPage = basename($_SERVER['PHP_SELF']);
    
    // Check if we're in the admin section
    $isAdminPage = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;

    // Check if user is logged in
    $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    $isAdmin = $isLoggedIn && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 0;

    // Ensure Notification and User classes are loaded
    require_once __DIR__ . '/includes/Notification.php';
    require_once __DIR__ . '/includes/User.php';

    global $user; // Attempt to use the global $user object from init.php

    // If the global $user isn't an instance of User (e.g., init.php wasn't included or $user is different)
    // then create a local one. This provides a fallback.
    if (!isset($user) || !($user instanceof User)) {
        $user = new User(); // Local fallback
    }

    $notification = new Notification();
    $unreadCount = 0;
    $recentNotifications = [];
    $profileImage = 'images/avatar.png';
    $cartCount = 0;
    if ($user->isLoggedIn()) {
        $unreadCount = $notification->getUnreadCount($user->getId());
        $recentNotifications = $notification->getUserNotifications($user->getId(), 5);
        $db = Database::getInstance();
        $userData = $db->fetchOne("SELECT profile_image FROM users WHERE id = ?", "i", [$user->getId()]);
        if ($userData && !empty($userData['profile_image'])) {
            $profileImage = 'uploads/profile_images/' . $userData['profile_image'];
        }
        
        // Get cart count
        $userEmail = $_SESSION["email"] ?? '';
        if (!empty($userEmail)) {
            $cartResult = $db->fetchOne("SELECT COUNT(*) as count FROM cart WHERE user_email = ? AND status = 0", "s", [$userEmail]);
            if ($cartResult) {
                $cartCount = $cartResult['count'];
            }
        }
    }
    ?>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" role="navigation" aria-label="Main navigation">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php" aria-label="Gobindaganj Public Library Home">
                <i class="fas fa-book-open me-2" aria-hidden="true"></i>
                Gobindaganj Library
            </a>
            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" role="menubar">
                    <li class="nav-item" role="none">
                        <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php" aria-label="Home" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Home" role="menuitem" tabindex="0">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link <?php echo $currentPage == 'all-books.php' ? 'active' : ''; ?>" href="all-books.php" aria-label="Books" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Books" role="menuitem" tabindex="0">
                            <i class="fas fa-book"></i>
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <!-- Removed all-authors.php link as per user request -->
                    </li>
                    <li class="nav-item" role="none">
                        <!-- Removed all-authors.php link as per user request -->
                    </li>
                    <li class="nav-item" role="none">
                        <!-- Removed all-authors.php link as per user request -->
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link <?php echo $currentPage == 'special-offers.php' ? 'active' : ''; ?>" href="special-offers.php" aria-label="Special Offers" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Special Offers" role="menuitem" tabindex="0">
                            <i class="fas fa-tags"></i>
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>" href="about.php" aria-label="About Us" data-bs-toggle="tooltip" data-bs-placement="bottom" title="About Us" role="menuitem" tabindex="0">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>" href="contact.php" aria-label="Contact" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Contact" role="menuitem" tabindex="0">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li class="nav-item" role="none">
                        <a class="nav-link <?php echo $currentPage == 'borrow_history.php' ? 'active' : ''; ?>" href="borrow_history.php" aria-label="Borrow History" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Borrow History" role="menuitem" tabindex="0">
                            <i class="fas fa-history"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <!-- User Menu -->
                <ul class="navbar-nav align-items-center" style="gap:0.2rem;">
                    <?php if ($user->isLoggedIn()): ?>
                        <!-- Notification Bell Dropdown -->
                        <li class="nav-item dropdown" role="none">
                            <a class="nav-link position-relative dropdown-toggle" href="#" id="notificationBell" aria-label="Notifications" data-bs-toggle="dropdown" aria-expanded="false" tabindex="0" role="menuitem">
                                <i class="fas fa-bell"></i>
                                <?php if ($unreadCount > 0): ?>
                                <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $unreadCount; ?>
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationBell" role="menu">
                                <?php if (count($recentNotifications) > 0): ?>
                                    <?php foreach ($recentNotifications as $note): ?>
                                        <li>
                                            <span class="dropdown-item<?php if (!$note['is_read']) echo ' fw-bold'; ?>">
                                                <?php echo htmlspecialchars($note['message']); ?>
                                                <small class="text-muted d-block"><?php echo date('M d, Y H:i', strtotime($note['created_at'])); ?></small>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                    <li><hr class="dropdown-divider my-2"></li>
                                    <li>
                                        <div class="d-flex justify-content-between px-3 py-1">
                                            <a href="notifications.php" class="btn btn-sm btn-primary">
                                                <i class="fas fa-bell me-1"></i> View All
                                            </a>
                                            <form action="mark_notifications_read.php" method="POST" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-check-double me-1"></i> Mark All Read
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                <?php else: ?>
                                    <li><span class="dropdown-item-text">No new notifications</span></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <!-- Profile Dropdown with Avatar -->
                        <li class="nav-item dropdown" role="none">
                            <a class="nav-link dropdown-toggle d-flex align-items-center position-relative" href="#" id="profileDropdown" aria-label="Account" data-bs-toggle="dropdown" aria-expanded="false" tabindex="0" role="menuitem">
                                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="rounded-circle me-1" style="width:28px;height:28px;object-fit:cover;">
                                <?php if ($isAdmin): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning" style="font-size: 0.6rem;" title="Admin">
                                    <i class="fas fa-crown" style="font-size: 0.6rem;"></i>
                                    <span class="visually-hidden">Admin user</span>
                                </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" role="menu">
                                <li><a class="dropdown-item" href="user_profile.php" role="menuitem"><i class="fas fa-user me-2"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="notifications.php" role="menuitem"><i class="fas fa-bell me-2"></i> Notifications</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center text-primary small fw-bold" style="letter-spacing:0.5px;">Shopping</li>
                                
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="cart.php" role="menuitem">
                                        <span><i class="fas fa-shopping-cart me-2"></i> Shopping Cart</span>
                                        <?php if ($cartCount > 0): ?>
                                        <span class="badge rounded-pill bg-danger"><?php echo $cartCount; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="order_history.php" role="menuitem"><i class="fas fa-receipt me-2"></i> Order History</a></li>
                                
                                <?php if ($isAdmin): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center text-warning small fw-bold" style="letter-spacing:0.5px;">Admin</li>
                                <li><a class="dropdown-item" href="admin/index.php" role="menuitem"><i class="fas fa-cog me-2"></i> Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li class="text-center text-danger small fw-bold" style="letter-spacing:0.5px;">Account</li>
                                <li>
                                    <!-- JavaScript-based logout with confirmation -->
                                    <button type="button" class="dropdown-item text-danger fw-bold d-flex align-items-center" id="logoutConfirmBtn" role="menuitem">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                    <form id="logoutForm" action="logout.php" method="POST" class="d-none"></form>
                                </li>
                                <!-- Direct logout link as fallback -->
                                <!-- Removed duplicate logout link to fix duplicate logout buttons issue -->
                                <!--
                                <li>
                                    <a href="logout.php" class="dropdown-item text-muted small">
                                        <i class="fas fa-external-link-alt me-1"></i> Direct Logout
                                    </a>
                                </li>
                                -->
                            </ul>
                        </li>
                        <!-- Emergency Logout Link (only visible on mobile) -->
                        <li class="nav-item d-lg-none" role="none">
                            <a class="nav-link text-danger" href="logout.php" aria-label="Emergency Logout" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Emergency Logout" role="menuitem" tabindex="0">
                                <i class="fas fa-power-off"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item" role="none">
                            <a class="nav-link <?php echo $currentPage == 'login.php' ? 'active' : ''; ?>" href="login.php" aria-label="Login" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Login" role="menuitem" tabindex="0">
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                        </li>
                        <li class="nav-item" role="none">
                            <a class="nav-link <?php echo $currentPage == 'registration_page.php' ? 'active' : ''; ?>" href="registration_page.php" aria-label="Register" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Register" role="menuitem" tabindex="0">
                                <i class="fas fa-user-plus"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <!-- Dark Mode Toggle Button -->
                <button id="darkModeToggle" class="btn btn-outline-light ms-2" aria-label="Toggle dark mode">
                    <i id="darkModeIcon" class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>
    <!-- Spacer for fixed navbar -->
    <div style="height: 72px;"></div>
    <!-- Back to Top Button (styles moved to CSS block) -->
    <button id="backToTop" aria-label="Back to top" style="display:none;">
        <i class="fas fa-arrow-up"></i>
    </button>
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to log out?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Logout</button>
          </div>
        </div>
      </div>
    </div>
    <style>
    /* Minimal Flat Icon Navbar Styles with deep blue background */
    .navbar {
        background: #0a2342 !important; /* Deep blue */
        border-bottom: 2px solid #19376d;
        box-shadow: none;
        border-radius: 0;
        transition: background 0.2s;
    }
    .navbar .container {
        padding-left: 0 !important;
        margin-left: 0 !important;
        max-width: 100%;
    }
    .navbar-nav .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        width: 38px;
        font-size: 1.05rem;
        padding: 0;
        margin: 0 0.35rem;
        border-radius: 50%;
        background: transparent;
        color: #4fc3f7 !important;
        transition: background 0.25s, color 0.25s, box-shadow 0.25s, transform 0.15s;
        border: 2px solid transparent;
        outline: none;
    }
    .navbar-nav .nav-link:focus {
        outline: 2px solid #ffc107;
        outline-offset: 2px;
    }
    .navbar-nav .nav-link i {
        display: block;
        margin: 0;
        font-size: 1em;
        line-height: 1;
        color: #4fc3f7;
        transition: color 0.2s;
    }
    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link:focus {
        background: #19376d;
        color: #fff !important;
        border-color: #4fc3f7;
        box-shadow: 0 2px 8px rgba(79,195,247,0.15);
        transform: scale(1.12);
    }
    
    /* Add a visual indicator for active page */
    .navbar-nav .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 5px;
        height: 5px;
        background-color: #ffc107;
        border-radius: 50%;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { transform: translateX(-50%) scale(1); opacity: 1; }
        50% { transform: translateX(-50%) scale(1.5); opacity: 0.7; }
        100% { transform: translateX(-50%) scale(1); opacity: 1; }
    }
    .navbar-nav .nav-link.active i,
    .navbar-nav .nav-link:hover i,
    .navbar-nav .nav-link:focus i {
        color: #fff;
    }

    .navbar-brand {
        color: #4fc3f7 !important;
        font-size: 1.5rem;
        font-weight: bold;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: 0 !important;
        padding-left: 1rem !important;
    }

    /* Responsive tweaks for icon nav */
    @media (max-width: 991.98px) {
        .navbar-nav .nav-link {
            margin: 0.25rem 0;
            width: 32px;
            height: 32px;
            font-size: 0.95rem;
        }
        .navbar-brand {
            padding-left: 0.5rem !important;
        }
        /* Improve mobile dropdown appearance */
        .navbar-collapse {
            background: rgba(10, 35, 66, 0.98);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        /* Adjust spacing for user menu on mobile */
        .navbar-nav.align-items-center {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        /* Make dark mode toggle more visible on mobile */
        #darkModeToggle {
            margin-top: 1rem;
            width: 100%;
        }
    }

    /* Dropdown menu animation */
    .dropdown-menu {
        opacity: 0;
        transform: translateY(10px) scale(0.98);
        transition: opacity 0.25s cubic-bezier(.4,2,.6,1), transform 0.25s cubic-bezier(.4,2,.6,1);
        pointer-events: none;
        visibility: hidden;
        min-width: 180px;
    }
    .dropdown.show .dropdown-menu,
    .dropdown-menu.show {
        display: block !important;
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
        visibility: visible;
    }
    .dropdown-menu .dropdown-item:focus {
        background: #e3f0ff;
        color: #0a2342;
        outline: 2px solid #ffc107;
    }
    #backToTop {
        /* display: none; /* Initial display is controlled by inline style on the button element, JS toggles to 'flex' or 'none' */
        position: fixed;
        bottom: 32px;
        right: 32px;
        z-index: 9999;
        background: #0a2342;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        font-size: 1.3rem;
        align-items: center; /* Effective when JS sets display: flex */
        justify-content: center; /* Effective when JS sets display: flex */
        transition: background 0.2s;
    }
    #backToTop:focus {
        outline: 2px solid #ffc107;
    }

    /* Dark mode styles */
    body.dark-mode {
        background-color: #181a1b !important;
        color: #e0e0e0 !important;
    }
    .dark-mode .navbar {
        background-color: #23272b !important;
    }
    .dark-mode .navbar .nav-link,
    .dark-mode .navbar .navbar-brand {
        color: #e0e0e0 !important;
    }
    .dark-mode .navbar .nav-link.active {
        color: #ffc107 !important;
    }
    .dark-mode .form-control {
        background-color: #23272b;
        color: #e0e0e0;
        border-color: #444;
    }
    .dark-mode .form-control:focus {
        background-color: #23272b;
        color: #fff;
    }
    .dark-mode .btn-outline-light {
        color: #ffc107;
        border-color: #ffc107;
    }
    .dark-mode .btn-outline-light:hover,
    .dark-mode .btn-outline-light:focus {
        background-color: #ffc107;
        color: #23272b;
        border-color: #ffc107;
    }
    </style>
    <script>
    // Dark mode toggle logic
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');

    function setDarkMode(enabled) {
        if (enabled) {
            document.body.classList.add('dark-mode');
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
            darkModeToggle.setAttribute('aria-label', 'Switch to light mode');
        } else {
            document.body.classList.remove('dark-mode');
            darkModeIcon.classList.remove('fa-sun');
            darkModeIcon.classList.add('fa-moon');
            darkModeToggle.setAttribute('aria-label', 'Switch to dark mode');
        }
    }

    // Load preference
    const darkModePref = localStorage.getItem('darkMode') === 'true';
    setDarkMode(darkModePref);

    darkModeToggle.addEventListener('click', () => {
        const enabled = !document.body.classList.contains('dark-mode');
        setDarkMode(enabled);
        localStorage.setItem('darkMode', enabled);
    });

    // Initialize Bootstrap tooltips and dropdowns
    window.addEventListener('DOMContentLoaded', function() {
        // Bootstrap component initializations (Tooltips, Dropdowns, Modals)
        // are now primarily handled by footer.php to ensure Bootstrap JS is loaded first.

        // Remove hardcoded notification count (if this was a dynamic update, it's fine)
        var badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.style.display = 'inline-block';
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            // Bootstrap 5 typically handles closing dropdowns on outside clicks if they are correctly initialized.
            // This custom logic might be redundant or conflict.
            // If dropdowns are not closing as expected after relying on footer.php for initialization,
            // this can be re-evaluated. For now, let Bootstrap handle it.
            // if (!e.target.closest('.dropdown')) {
            //     document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
            //         menu.classList.remove('show');
            //         var toggle = menu.previousElementSibling; // Assuming toggle is direct sibling
            //         if (toggle && toggle.matches('[data-bs-toggle="dropdown"]')) {
            //             toggle.setAttribute('aria-expanded', 'false');
            //         }
            //     });
            // }
        });
        // Back to Top button logic
        var backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 200) {
                backToTop.style.display = 'flex';
                backToTop.setAttribute('aria-hidden', 'false');
            } else {
                backToTop.style.display = 'none';
                backToTop.setAttribute('aria-hidden', 'true');
            }
        });
        backToTop.addEventListener('click', function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
        
        // Logout confirmation modal logic
        const logoutConfirmBtnEl = document.getElementById('logoutConfirmBtn');
        const logoutModalEl = document.getElementById('logoutModal');
        const confirmLogoutModalBtnEl = document.getElementById('confirmLogoutBtn');
        const logoutFormEl = document.getElementById('logoutForm');
        
        if (logoutConfirmBtnEl && logoutModalEl && confirmLogoutModalBtnEl && logoutFormEl) {
            logoutConfirmBtnEl.addEventListener('click', function(e) {
                e.preventDefault();
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    // Modal instance should be created by footer.php or Bootstrap's auto-init.
                    // We get or create it here to ensure it's available.
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(logoutModalEl);
                    modalInstance.show();
                } else {
                    // Fallback if Bootstrap modal JS isn't working as expected
                    if (confirm('Are you sure you want to log out? (JavaScript for modal is not fully functional)')) {
                        logoutFormEl.submit();
                    }
                }
            });
            
            confirmLogoutModalBtnEl.addEventListener('click', function() {
                try {
                    logoutFormEl.submit();
                } catch (e) {
                    console.error('Error submitting logout form:', e);
                    window.location.href = 'logout.php'; // Fallback direct redirect
                }
            });
        } else {
            // Fallback if some logout modal elements are missing
            if (logoutConfirmBtnEl) {
                logoutConfirmBtnEl.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to log out?')) {
                        window.location.href = 'logout.php';
                    }
                });
            }
            console.warn('Some logout elements are missing. Using direct logout instead.');
        }
    });
    </script>
