<?php
/**
 * Migration script to add user_tokens table for "remember me" functionality
 */

require_once __DIR__ . '/../includes/init.php';

$db = Database::getInstance();

$sql = "
CREATE TABLE IF NOT EXISTS user_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($db->getConnection()->query($sql) === TRUE) {
    echo "user_tokens table created successfully.\n";
} else {
    echo "Error creating user_tokens table: " . $db->getConnection()->error . "\n";
}
?>
