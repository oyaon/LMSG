<?php 
// Include initialization file which loads all required classes
require_once 'includes/init.php';
include ("header.php"); 
// Removed include of top-navbar.php to avoid duplicate navigation bar
?>

<div class="container pb-4">
    <h3 class="text-center">Authors</h3>
    <div class="row">
        <?php
        try {
            // Get all authors using the Author class
            $authors = $author->getAllAuthors();
            
            if (!empty($authors)) {
                foreach ($authors as $row) {
                    // Determine image path - use default if not set
                    $imagePath = !empty($row['image_path']) ? $row['image_path'] : 'images/books1.png';
                    ?>
                    <div class="col-sm-6 col-md-4 mb-3 mb-sm-3">
                        <div class="card p-3">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top rounded-circle" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['biography']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>No authors found</p></div>';
            }
        } catch (Exception $e) {
            // Use Helper class for error handling
            Helper::handleException($e, true, Helper::getDatabaseErrorMessage('fetch', 'authors'));
            // Display user-friendly error message
            echo '<div class="col-12 text-center text-danger"><p>An error occurred while fetching authors. Please try again later.</p></div>';
        }
        ?>
    </div>
</div>

<?php include ("footer.php"); ?>
