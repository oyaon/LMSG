<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Basic check if user is admin - redirect if not.
// A more robust solution would be in an admin-specific init.php or controller.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) { // 0 for admin
    // Redirect to login or a 'not authorized' page
    // Assuming login.php is in the parent directory (LMS/LMS/)
    header('Location: ../login_page.php?error=unauthorized_admin_access'); // Changed to login_page.php
    exit;
}

$adminCurrentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">LMS Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarSupportedContent" aria-controls="adminNavbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($adminCurrentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($adminCurrentPage == 'user_issue_history.php') ? 'active' : ''; ?>" href="user_issue_history.php">Issue History</a>
                </li>
                <!-- Add more admin links like Users, Books management here -->
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><span class="navbar-text me-3">Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin'; ?>!</span></li>
                <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>