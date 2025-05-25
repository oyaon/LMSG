<?php
require_once __DIR__ . '/../includes/init.php';

// Check if user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: ../login_page.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        // Check if user already exists
        $sqlCheck = "SELECT * FROM users WHERE email = ?";
        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtCheck->bind_param('s', $email);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck && $resultCheck->num_rows > 0) {
            $message = "User with this email already exists.";
        } else {
            // Create new admin user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Fix: set user_type to 0 for admin
            $sql = "INSERT INTO users (email, password, user_type) VALUES (?, ?, 0)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('ss', $email, $hashedPassword);
            if ($stmt->execute()) {
                $message = "Admin user created successfully.";
            } else {
                $message = "Error creating admin user.";
            }
            $stmt->close();
        }
        $stmtCheck->close();
    } else {
        $message = "Please provide a valid email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create Admin User</title>
    <link href="../css/style.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h1>Create Admin User</h1>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="8" />
            </div>
            <button type="submit" class="btn btn-primary">Create Admin</button>
        </form>
    </div>
</body>
</html>
