<?php
/**
 * Test Page
 * 
 * This page tests the database connection and user authentication
 */

// Include initialization file
require_once 'includes/init.php';

// Page title
$pageTitle = 'Test Page';

// Include header
include 'header.php';
?>

<div class="container py-5">
    <h1>Database and Authentication Test</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h2>Database Connection</h2>
        </div>
        <div class="card-body">
            <?php
            $connection = $db->getConnection();
            if ($connection->connect_error) {
                echo '<div class="alert alert-danger">Database connection failed: ' . $connection->connect_error . '</div>';
            } else {
                echo '<div class="alert alert-success">Database connection successful!</div>';
            }
            ?>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h2>Users in Database</h2>
        </div>
        <div class="card-body">
            <?php
            $users = $db->fetchAll("SELECT id, first_name, last_name, email, user_type FROM users");
            
            if (empty($users)) {
                echo '<div class="alert alert-warning">No users found in the database.</div>';
            } else {
                echo '<table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($users as $user) {
                    echo '<tr>
                            <td>' . $user['id'] . '</td>
                            <td>' . $user['first_name'] . ' ' . $user['last_name'] . '</td>
                            <td>' . $user['email'] . '</td>
                            <td>' . ($user['user_type'] == 0 ? 'Admin' : 'Regular User') . '</td>
                          </tr>';
                }
                
                echo '</tbody>
                    </table>';
            }
            ?>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h2>Authentication Status</h2>
        </div>
        <div class="card-body">
            <?php
            if ($user->isLoggedIn()) {
                echo '<div class="alert alert-success">
                        <p><strong>Logged in as:</strong> ' . $user->getFullName() . ' (' . $user->getEmail() . ')</p>
                        <p><strong>User Type:</strong> ' . ($user->isAdmin() ? 'Admin' : 'Regular User') . '</p>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                      </div>';
            } else {
                echo '<div class="alert alert-info">
                        <p>Not logged in.</p>
                        <a href="login.php" class="btn btn-primary">Login</a>
                      </div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>