<?php
require_once __DIR__ . '/../includes/User.php';

$user = new User();

if ($user->loadUserByEmail('admin@example.com') && $user->getUserType() == 0) {
    echo "Admin user exists with user_type 0";
} else {
    echo "Admin user does not exist or user_type is not 0";
}
?>
