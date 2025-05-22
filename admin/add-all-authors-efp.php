<?php include 'header.php'; ?>
<?php require_once '../includes/init.php'; // Use init.php for consistency

// Ensure user is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    Helper::redirect('../login.php'); // Redirect to login if not admin
    exit;
}
?>

<div class="col-9">
    <h1>Edit Author</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                Helper::displayFlashMessage(); // Display flash messages

$id = isset($_GET["edit-id"]) ? (int)$_GET["edit-id"] : 0;
if ($id <= 0) {
                    Helper::setFlashMessage('error', 'Invalid author ID.');
                    Helper::redirect('add-all-authors.php');
                    exit;
}

// Use prepared statement to prevent SQL injection
                $row = $db->fetchOne("SELECT * FROM `authors` WHERE id = ?", "i", [$id]);

                if (!$row) {
                    Helper::setFlashMessage('error', 'Author not found.');
                    Helper::redirect('add-all-authors.php');
                    exit;
                }

                // Check if book_type column exists
                $bookType = isset($row['book_type']) ? $row['book_type'] : '';
                ?>

                <form action="add-all-authors-up.php" method="POST">
                    <?php echo Helper::csrfTokenField('edit_author_form'); ?>
                    <div class="mb-3">
<input type="hidden" class="form-control" name='id' value="<?php echo htmlspecialchars($row['id']); ?>">
<label for="authorname" class="form-label">Author Name</label>
<input type="text" class="form-control" id="authorname" name="authorname" value="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="mb-3">
<label for="authordescription" class="form-label">Author Description</label>
<textarea class="form-control" id="authordescription" name="authordescription" rows="5"><?php echo htmlspecialchars($row['biography']); ?></textarea>
                    </div>
                    <div class="mb-3">
<label for="booktype" class="form-label">Book Type</label>
<input type="text" class="form-control" id="booktype" name="booktype" value="<?php echo htmlspecialchars($bookType); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>