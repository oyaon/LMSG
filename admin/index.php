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

    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">New Contact Messages</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM contact_messages WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                    $result = $mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </h5>
                <p class="card-text">Messages received in last 7 days</p>
                <a href="contact_messages.php" class="btn btn-light btn-sm mt-2">View Messages</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Payment Orders</div>
            <div class="card-body">
                <h5 class="card-title">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM payments";
                    $result = $mysqli->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total'];
                    ?>
                </h5>
                <p class="card-text">Total book purchase orders</p>
                <a href="orders.php" class="btn btn-light btn-sm mt-2">View Orders</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header bg-light">Recent Borrows</div>
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
    $borrowDate = $row['borrow_date'];
    if (!empty($borrowDate) && strtotime($borrowDate) !== false) {
        $formattedDate = date('M d, Y', strtotime($borrowDate));
    } else {
        $formattedDate = 'Date not available';
    }
    echo '<li class="list-group-item">' . $row['username'] . ' borrowed "' . $row['title'] . '" on ' . $formattedDate . '</li>';
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
            <div class="card-header bg-light">Recent Payment Orders</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php
                    $sql = "SELECT p.user_email, p.amount, p.payment_date, p.transaction_id, p.payment_status
                            FROM payments p
                            ORDER BY p.payment_date DESC LIMIT 5";
                    $result = $mysqli->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $paymentDate = $row['payment_date'];
                            if (!empty($paymentDate) && strtotime($paymentDate) !== false) {
                                $formattedDate = date('M d, Y', strtotime($paymentDate));
                            } else {
                                $formattedDate = 'Date not available';
                            }
                            $status = $row['payment_status'];
                            $statusClass = 'text-success';
                            if ($status == 'Pending') {
                                $statusClass = 'text-warning';
                            } else if ($status == 'Failed') {
                                $statusClass = 'text-danger';
                            }
                            echo '<li class="list-group-item">' . 
                                 '<strong>' . $row['user_email'] . '</strong> paid ' . 
                                 '<span class="fw-bold">' . $row['amount'] . ' Tk</span> on ' . 
                                 $formattedDate . ' <span class="' . $statusClass . '">(' . $status . ')</span></li>';
                        }
                    } else {
                        echo '<li class="list-group-item">No recent payment orders</li>';
                    }
                    ?>
                </ul>
                <div class="mt-3">
                    <a href="orders.php" class="btn btn-outline-primary btn-sm">View All Orders</a>
                </div>
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
                    <a href="orders.php" class="btn btn-outline-info">View Payment Orders</a>
                    <a href="database-backup.php" class="btn btn-outline-warning">Backup Database</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
