<?php include 'header.php'; ?>
<?php require_once '../includes/init.php'; ?>

<div class="col-9">
    <h1>Edit Book</h1>
    <div class="container">
        <?php
        // Display error messages if any
        if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
            echo '<div class="alert alert-danger">';
            if (is_array($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo '<p>' . htmlspecialchars($error) . '</p>';
                }
            } else {
                echo '<p>' . htmlspecialchars($_SESSION['errors']) . '</p>';
            }
            echo '</div>';
            unset($_SESSION['errors']);
        }
        
        // Display success message if any
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">';
            echo '<p>' . htmlspecialchars($_SESSION['success']) . '</p>';
            echo '</div>';
            unset($_SESSION['success']);
        }
        ?>
        
        <div class="row">
            <div class="col-lg-8">
                <?php
                try {
                    // Check if ID is provided
                    if (!isset($_GET['edit-id']) || empty($_GET['edit-id'])) {
                        throw new Exception("No book ID provided for editing");
                    }
                    
                    $id = (int)$_GET["edit-id"];
                    
                    // Use prepared statement to get book data
                    $row = $bookOps->getBookById($id);

                    // For compatibility with the existing form structure, let's convert the result to an associative array if it's an object
                    if (is_object($row)) {
                        $row = (array)$row;
                    }

                    if (!$row) {
                        throw new Exception("Book not found with ID: $id");
                    }
                } catch (Exception $e) {
                    // Log the error
                    Helper::logError("Error fetching book: " . $e->getMessage(), __FILE__, __LINE__);
                    
                    // Set error message and redirect
                    $_SESSION['errors'] = "An error occurred: " . $e->getMessage();
                    header("location: add-all-books.php");
                    exit;
                }
                ?>

                <form action="add-all-books-up.php" method="POST" enctype="multipart/form-data">
                    <?php echo Helper::csrfTokenField('edit_book_form'); ?>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name='id' value="<?php echo $row['id']; ?>">
                        <label for="bookname" class="form-label">Book Name</label>
                        <input type="text" class="form-control" id="bookname" name="bookname" value="<?php echo $row['name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="bookauthor" class="form-label">Book Author</label>
                        <input type="text" class="form-control" id="bookauthor" name="bookauthor" value="<?php echo $row['author']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="bookcategory" class="form-label">Book Category</label>
                        <input type="text" class="form-control" id="bookcategory" name="bookcategory" value="<?php echo $row['category']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="bookdescription" class="form-label">Book Description</label>
                        <textarea class="form-control" id="bookdescription" name="bookdescription" rows="5"><?php echo $row['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="bookquantity" class="form-label">Book Quantity</label>
                        <input type="number" class="form-control" id="bookquantity" name="bookquantity" value="<?php echo $row['quantity']; ?>" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="bookprice" class="form-label">Book Price</label>
                        <input type="number" class="form-control" id="bookprice" name="bookprice" value="<?php echo $row['price']; ?>" min="1">
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($row['isbn'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="page_count" class="form-label">Page Count</label>
                        <input type="number" class="form-control" id="page_count" name="page_count" min="1" value="<?php echo htmlspecialchars($row['page_count'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="published_date" class="form-label">Published Date</label>
                        <input type="date" class="form-control" id="published_date" name="published_date" value="<?php echo htmlspecialchars($row['published_date'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format</label>
                        <input type="text" class="form-control" id="format" name="format" value="<?php echo htmlspecialchars($row['format'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="pdf">Book pdf</label>
                        <input type="file" name="pdf" id="pdf" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Book Cover Image (leave blank to keep current)</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
