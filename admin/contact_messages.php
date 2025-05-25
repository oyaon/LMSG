<?php
require_once __DIR__ . '/../includes/init.php';

// Check if user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: ../login_page.php');
    exit;
}

$pageTitle = "Contact Messages";
include("header.php");

$db = Database::getInstance();
$conn = $db->getConnection();

$query = "SELECT id, name, email, subject, message, created_at FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<main id="main-content" role="main" class="container py-5">
    <h1 class="mb-4">Contact Messages</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Received At</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No contact messages found.</p>
    <?php endif; ?>
</main>

<?php include("footer.php"); ?>
