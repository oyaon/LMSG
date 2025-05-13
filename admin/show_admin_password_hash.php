<?php
require_once __DIR__ . '/../admin/db-connect.php';

$email = 'admin@example.com';

$stmt = $mysqli->prepare("SELECT password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($passwordHash);
if ($stmt->fetch()) {
    echo "Password hash for {$email}: " . htmlspecialchars($passwordHash);
} else {
    echo "Admin user not found.";
}
$stmt->close();
$mysqli->close();
?>
