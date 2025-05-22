<?php
require_once 'includes/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    header('Location: login.php?redirect=edit_profile.php');
    exit;
}

$db = Database::getInstance();
$errors = [];
$success = false;

// Fetch current user data
$currentUserData = $db->fetchOne("SELECT first_name, last_name, user_name, email FROM users WHERE id = ?", "i", [$user->getId()]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate inputs
    if (empty($firstName)) {
        $errors[] = 'First name is required.';
    }
    if (empty($lastName)) {
        $errors[] = 'Last name is required.';
    }
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Check if username or email already exists for other users
    $existingUser = $db->fetchOne("SELECT id FROM users WHERE (user_name = ? OR email = ?) AND id != ?", "ssi", [$username, $email, $user->getId()]);
    if ($existingUser) {
        $errors[] = 'Username or email already taken by another user.';
    }

    if (empty($errors)) {
        // Update user data
        $updated = $db->update(
            "UPDATE users SET first_name = ?, last_name = ?, user_name = ?, email = ? WHERE id = ?",
            "ssssi",
            [$firstName, $lastName, $username, $email, $user->getId()]
        );

        if ($updated) {
            $success = true;
            // Refresh current user data
            $currentUserData = $db->fetchOne("SELECT first_name, last_name, user_name, email FROM users WHERE id = ?", "i", [$user->getId()]);
        } else {
            $errors[] = 'Failed to update profile. Please try again.';
        }
    }
}

$pageTitle = 'Edit Profile';
include 'header.php';
?>

<div class="container my-5">
    <h2>Edit Profile</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Profile updated successfully.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit_profile.php" novalidate>
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control" required
                value="<?php echo htmlspecialchars($currentUserData['first_name'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control" required
                value="<?php echo htmlspecialchars($currentUserData['last_name'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" required
                value="<?php echo htmlspecialchars($currentUserData['user_name'] ?? ''); ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required
                value="<?php echo htmlspecialchars($currentUserData['email'] ?? ''); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="user_profile.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>

<?php include 'footer.php'; ?>
