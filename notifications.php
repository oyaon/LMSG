<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Include necessary files
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/User.php';
require_once __DIR__ . '/includes/Notification.php';

// Initialize objects
$user = new User();
$notification = new Notification();
$db = Database::getInstance();

// Mark all notifications as read when visiting this page
$notification->markAllAsRead($user->getId());

// Get all notifications for the user
$allNotifications = $notification->getUserNotifications($user->getId(), 100); // Get up to 100 notifications

// Include header
include 'header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Your Notifications</h1>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Notifications</h5>
                    <span class="badge bg-light text-primary"><?php echo count($allNotifications); ?> Total</span>
                </div>
                <div class="card-body p-0">
                    <?php if (count($allNotifications) > 0): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($allNotifications as $note): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start p-3">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><?php echo htmlspecialchars($note['message']); ?></div>
                                        <small class="text-muted"><?php echo date('F d, Y h:i A', strtotime($note['created_at'])); ?></small>
                                    </div>
                                    <?php if (!$note['is_read']): ?>
                                        <span class="badge bg-primary rounded-pill">New</span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center p-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="lead">You don't have any notifications yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>