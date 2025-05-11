<?php 
// Include initialization file which loads all required classes
require_once 'includes/init.php';
include ("header.php"); 
include ("top-navbar.php"); 
?>

<!-- handling form submission -->
<?php
// Display flash message if any
Helper::displayFlashMessage();

if(isset($_POST["add_book"])){
    try {
        // Sanitize input data
        $bookData = [
            'name' => Helper::sanitize($_POST['book_name']),
            'author' => Helper::sanitize($_POST['author']),
            'category' => Helper::sanitize($_POST['cat']),
            'description' => Helper::sanitize($_POST['des']),
            'quantity' => (int)$_POST['quantity'],
            'price' => isset($_POST['price']) ? (float)$_POST['price'] : 0
        ];
        
        // Add book using BookOperations class
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

				<?php if(!isset($_GET['t'])): ?>
					<div class="form-group mb-2">
						<label class="form-label" for="price">Price</label>
						<input type="number" name="price" id="price" min="0" required class="form-control">
					</div>
				<?php endif; ?>

				<div class="form-group mb-2">
					<label class="form-label" for="pdf">Book pdf</label>
					<input type="file" name="pdf" id="pdf" class="form-control">
				</div>

				<div class="form-group mt-4 d-flex justify-content-center">
					<button name="add_book" type="submit" class="btn btn-success">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php include ("footer.php"); ?>