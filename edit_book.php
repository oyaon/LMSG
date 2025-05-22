<?php
require_once 'includes/init.php';

if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: login.php');
    exit;
}

include("header.php");

$bookId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($bookId <= 0) {
    header('Location: all-books.php');
    exit;
}

$bookOps = new BookOperations();
$book = $bookOps->getBookById($bookId);
if (!$book) {
    header('Location: all-books.php');
    exit;
}

Helper::displayFlashMessage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'edit_book_form')) {
            throw new Exception('Invalid CSRF token. Please refresh the page and try again.');
        }

        $bookName = Helper::sanitize($_POST['book_name']);
        $author = Helper::sanitize($_POST['author']);
        $category = Helper::sanitize($_POST['cat']);
        $description = Helper::sanitize($_POST['des']);
        $quantity = (int)$_POST['quantity'];

        if (empty($bookName) || empty($author) || empty($category) || empty($description)) {
            throw new Exception('Please fill in all required fields.');
        }

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
        if ($quantity < 1) {
            throw new Exception('Quantity must be at least 1.');
        }

        $bookData = [
            'name' => $bookName,
            'author' => $author,
            'category' => $category,
            'description' => $description,
            'quantity' => $quantity,
            'price' => $book['price'] ?? 0
        ];

        // Pass $_FILES data to updateBook for file uploads
        $success = $bookOps->updateBook($bookId, $bookData);

        if ($success) {
            Helper::setFlashMessage('success', 'Book updated successfully!');
            header('Location: book-details.php?t=' . $bookId);
            exit;
        } else {
            throw new Exception('Failed to update book.');
        }
    } catch (Exception $e) {
        Helper::handleException($e, true, Helper::getDatabaseErrorMessage('update', 'book'));
    }
}
?>

<div class="container my-4">
    <h1 class="text-center mb-4">Edit Book</h1>
    <form action="" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: auto;">
        <?php echo Helper::csrfTokenField('edit_book_form'); ?>
        <div class="mb-3">
            <label for="book_name" class="form-label">Book Name</label>
            <input type="text" name="book_name" id="book_name" class="form-control" required value="<?php echo htmlspecialchars($book['name']); ?>">
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author Name</label>
            <input type="text" name="author" id="author" class="form-control" required value="<?php echo htmlspecialchars($book['author']); ?>">
        </div>
        <div class="mb-3">
            <label for="cat" class="form-label">Category</label>
            <input type="text" name="cat" id="cat" class="form-control" required value="<?php echo htmlspecialchars($book['category']); ?>">
        </div>
        <div class="mb-3">
            <label for="des" class="form-label">Description</label>
            <textarea name="des" id="des" class="form-control" required><?php echo htmlspecialchars($book['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required value="<?php echo (int)$book['quantity']; ?>">
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Book Cover Image (leave blank to keep current)</label>
            <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
            <?php if (!empty($book['cover_image'])): ?>
                <img src="uploads/covers/<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Current Cover" style="max-width: 150px; margin-top: 10px;">
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary">Update Book</button>
        </div>
    </form>
</div>

<?php include("footer.php"); ?>
