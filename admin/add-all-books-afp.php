<?php include ("header.php"); ?>
<?php require_once '../includes/init.php'; ?>

<?php
// Ensure user is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    Helper::redirect('../login.php'); // Redirect to login if not admin
    exit;
}
?>
<div class="container">
	<h1 class="pt-2">Add Book</h1>
	
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
		<div class="col-6">
			<form method="POST" action="add-all-books-asp.php" enctype="multipart/form-data">
                <?php echo Helper::csrfTokenField('add_book_form'); ?>
				<div class="mb-3">
					<label class="form-label">Book Name</label>
					<input type="text" class="form-control" id="bookname" name="bookname">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Author</label>
					<input type="text" class="form-control" id="book-author" name="bookauthor">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Category</label>
					<input type="text" class="form-control" id="book-category" name="bookcategory">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Description</label>
					<textarea class="form-control" id="book-description" name="bookdescription" rows="5"></textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Book Quantity</label>
					<input type="number" class="form-control" id="book-quantity" name="bookquantity" min="1">
				</div>
				<div class="mb-3">
					<label class="form-label">Book Price</label>
					<input type="number" class="form-control" id="book-price" name="bookprice" min="1">
				</div>
				<div class="mb-3">
					<label class="form-label" for="pdf">Book PDF</label>
					<input type="file" name="pdf" id="pdf" class="form-control">
				</div>
				<div class="mb-3">
					<label class="form-label" for="coverimage">Book Cover Image</label>
					<input type="file" name="coverimage" id="coverimage" class="form-control">
					<small class="form-text text-muted">Recommended size: 300x450 pixels. Max size: 5MB. Formats: JPG, PNG, GIF</small>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>

</div>

<?php include ("footer.php"); ?>