<?php
require_once __DIR__ . '/../includes/init.php';

// Check if user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    // Redirect to login page if not logged in or not admin
header('Location: ../login_page.php');
    exit;
}

include("header.php");
include("sidebar.php");
?>

<h1 class="fs-1">Welcome Admin User</h1>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Total Books</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM all_books";
                    $result = $mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </h5>
                <p class="card-text">Books in the library</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Users</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM users WHERE user_type = 1";
                    $result = $mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </h5>
                <p class="card-text">Registered users</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Active Borrows</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM borrow_history WHERE status = 'Issued' AND return_date IS NULL";
                    $result = $mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </h5>
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
                    <?php
                    $sql = "SELECT b.name as title, u.user_name as username, bh.issue_date as borrow_date 
                            FROM borrow_history bh
                            JOIN all_books b ON bh.book_id = b.id
                            JOIN users u ON bh.user_email = u.email
                            ORDER BY bh.issue_date DESC LIMIT 5";
                    $result = $mysqli->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li class="list-group-item">' . $row['username'] . ' borrowed "' . $row['title'] . '" on ' . date('M d, Y', strtotime($row['borrow_date'])) . '</li>';
                        }
                    } else {
                        echo '<li class="list-group-item">No recent activities</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">Quick Actions</div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="add-all-books.php" class="btn btn-outline-primary">Manage Books</a>
                    <a href="users.php" class="btn btn-outline-secondary">Manage Users</a>
                    <a href="history.php" class="btn btn-outline-success">View Borrow History</a>
                    <a href="database-backup.php" class="btn btn-outline-warning">Backup Database</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
