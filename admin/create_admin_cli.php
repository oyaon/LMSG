<?php
// CLI script to create admin user with email and password

$mysqli = new mysqli('localhost', 'root', '', 'bms');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$email = 'admin@example.com';
$password = 'Admin123';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format\n");
}

if (strlen($password) < 8) {
    die("Password must be at least 8 characters\n");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmtCheck = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
$stmtCheck->bind_param('s', $email);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck && $resultCheck->num_rows > 0) {
    echo "User with this email already exists.\n";
    $stmtCheck->close();
    exit(0);
}
$stmtCheck->close();

// Fix: Set user_type to 0 for admin user
$stmt = $mysqli->prepare("INSERT INTO users (email, password, user_type) VALUES (?, ?, 0)");
$stmt->bind_param('ss', $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Admin user created successfully.\n";
} else {
    echo "Error creating admin user: " . $stmt->error . "\n";
}

$stmt->close();
$mysqli->close();
?>
