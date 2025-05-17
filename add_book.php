<?php 
// Include initialization file which loads all required classes
require_once 'includes/init.php';

if (isset($_GET['t']) && $_GET['t'] === 'donation') {
    // Redirect to donate.php for unified donation page
    header('Location: donate.php');
    exit;
} else {
    // Check if user is logged in and is admin
    if (!$user->isLoggedIn() || !$user->isAdmin()) {
        // Redirect to login page or show access denied
        header('Location: login.php');
        exit;
    }
}

include ("header.php"); 
// Removed top-navbar.php to avoid duplicate navigation bar
// include ("top-navbar.php"); 
?>

<!-- handling form submission -->
<?php
// Display flash message if any
Helper::displayFlashMessage();

if(isset($_POST["add_book"])){
    try {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'add_book_form')) {
            throw new Exception('Invalid CSRF token. Please refresh the page and try again.');
        }

        // Sanitize and validate input data
        $bookName = Helper::sanitize($_POST['book_name']);
        $author = Helper::sanitize($_POST['author']);
        $category = Helper::sanitize($_POST['cat']);
        $description = Helper::sanitize($_POST['des']);
        $quantity = (int)$_POST['quantity'];

        // Validate required fields
        if (empty($bookName) || empty($author) || empty($category) || empty($description)) {
            throw new Exception('Please fill in all required fields.');
        }

        // Validate string lengths
        if (strlen($bookName) > 255) {
            throw new Exception('Book name must be less than 256 characters.');
        }
        if (strlen($author) > 255) {
            throw new Exception('Author name must be less than 256 characters.');
        }
        if (strlen($category) > 100) {
            throw new Exception('Category must be less than 101 characters.');
        }
        if (strlen($description) > 1000) {
            throw new Exception('Description must be less than 1001 characters.');
        }

        // Validate numeric values
        if ($quantity < 1) {
            throw new Exception('Quantity must be at least 1.');
        }

        $bookData = [
            'name' => $bookName,
            'author' => $author,
            'category' => $category,
            'description' => $description,
            'quantity' => $quantity
        ];

        // Pass $_FILES data to addBook for file uploads
        $bookId = $bookOps->addBook($bookData);

        if($bookId) {
            // Set success message
            Helper::setFlashMessage('success', 'Book added successfully!');

            // Redirect to prevent form resubmission
            header('Location: ' . $_SERVER['PHP_SELF'] . (isset($_GET['t']) ? '?t=1' : ''));
            exit;
        } else {
            throw new Exception("Failed to add book");
        }
    } catch (Exception $e) {
        // Handle the error using our improved error handling
        Helper::handleException($e, true, Helper::getDatabaseErrorMessage('create', 'book'));
    }
}
?>

<div class="row my-4">
	<div class="container">
		<div class="col-12">
			<h1 class="text-center my-4"><?php echo isset($_GET['t']) ? "Book donation form" : "Add books" ?></h1>

<form action="" method="POST" class="mx-auto p-4" style="width: 550px; border: 1px solid gray; border-radius: 23px;" enctype="multipart/form-data">
                <?php echo Helper::csrfTokenField('add_book_form'); ?>
				<div class="form-group mb-2">
					<label class="form-label" for="name">Book name</label>
					<input type="text" name="book_name" id="name" required class="form-control">
				</div>

				<div class="form-group mb-2">
					<label class="form-label" for="author">Author name</label>
					<input type="text" name="author" id="author" required class="form-control">
				</div>

				<div class="form-group mb-2">
					<label class="form-label" for="cat">Category</label>
					<input type="text" name="cat" id="cat" required class="form-control">
				</div>

				<div class="form-group mb-2">
					<label class="form-label" for="des">Description</label>
					<textarea type="text" name="des" id="des" required class="form-control"></textarea>
				</div>

				<div class="form-group mb-2">
					<label class="form-label" for="quantity">Quantity</label>
					<input type="number" name="quantity" id="quantity" min="1" value="1" required class="form-control">
				</div>

				<!-- Removed price input field -->

				<div class="form-group mb-2">
					<label class="form-label" for="pdf">Book pdf</label>
					<input type="file" name="pdf" id="pdf" class="form-control">
				</div>

				<div class="form-group mb-2">
					<label class="form-label" for="cover_image">Book image</label>
					<input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
				</div>

				<div class="form-group mt-4 d-flex justify-content-center">
					<button name="add_book" type="submit" class="btn btn-success">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include ("footer.php"); ?>
