<?php 
// Include initialization file which loads all required classes
require_once 'includes/init.php';
include 'header.php';
?>

<div class="container">
 <h1 class="pt-2"> All Authors</h1>
    <div class="row">
        <div class="col-lg-9"></div> 
        <div class="col-lg-3">
            <a class="btn btn-primary mb-3" href="add-all-authors-afp.php" role="button"> Add New Author</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
        <div class="table-responsive">
            <table class="table">
            <thead>
            <tr>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Type</th>
                  <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php
                try {
                    // Get all authors using the Author class
                    $authors = $author->getAllAuthors('id', 'ASC');
                    
                    if (!empty($authors)) {
                        foreach ($authors as $row) {
                            // Check if book_type column exists, if not use empty string
                            $bookType = isset($row['book_type']) ? $row['book_type'] : '';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['biography'], 0, 50) . "..."); ?></td>
                                <td><?php echo htmlspecialchars($bookType); ?></td>
                                <td>
                                    <a href="add-all-authors-efp.php?edit-id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                    <a href="add-all-authors-dp.php?del-id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5'>No authors found</td></tr>";
                    }
                } catch (Exception $e) {
                    // Use Helper class for error handling
                    Helper::handleException($e, true, Helper::getDatabaseErrorMessage('fetch', 'authors'));
                    // Display user-friendly error message
                    echo "<tr><td colspan='5' class='text-danger'>An error occurred while fetching authors. Please try again later.</td></tr>";
                }
                ?>
            </tbody>
         </table>
     </div>
    </div>
</div>


<?php include 'footer.php'; ?>

            
         