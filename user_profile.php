<?php
require_once 'includes/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    // Redirect to login page if not logged in
    header('Location: login.php?redirect=user_profile.php');
    exit;
}

$pageTitle = 'User Profile';
include 'header.php';
?>

<div class="container my-5">
    <h2>User Profile</h2>
    <div class="card shadow-sm p-4 d-flex flex-column align-items-center">
        <?php
        $profileImage = 'images/avatar.png'; // default avatar
        $db = Database::getInstance();
        $userData = $db->fetchOne("SELECT profile_image FROM users WHERE id = ?", "i", [$user->getId()]);
        if ($userData && !empty($userData['profile_image'])) {
            $profileImage = 'uploads/profile_images/' . $userData['profile_image'];
        }
        ?>
        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">

        <form action="upload_profile_image.php" method="POST" enctype="multipart/form-data" class="mb-3 w-100" style="max-width: 300px;">
            <div class="mb-3">
                <label for="profile_image" class="form-label">Upload Profile Image</label>
                <input class="form-control" type="file" id="profile_image" name="profile_image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>

        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user->getFullName()); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user->getUserName()); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user->getEmail()); ?></p>
        <p><strong>User Type:</strong> <?php echo $user->getUserType() == 0 ? 'Admin' : 'Regular User'; ?></p>
        <a href="edit_profile.php" class="btn btn-primary mt-3">Edit Profile</a>
    </div>
</div>

<?php include 'footer.php'; ?>
