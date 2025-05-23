<?php
require_once 'includes/init.php';
include('header.php');

// Get author ID from URL
$authorId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($authorId <= 0) {
    header('Location: all-authors.php');
    exit;
}

// Fetch author info
$authorInfo = $author->getAuthorById($authorId);
if (!$authorInfo) {
    echo '<div class="container py-5"><div class="alert alert-danger">Author not found.</div></div>';
    include('footer.php');
    exit;
}

// Fetch books by this author
$books = $bookOps->getBooksByAuthor($authorId);
$imagePath = !empty($authorInfo['image_path']) ? $authorInfo['image_path'] : 'images/books1.png';
?>
<div class="container py-4 fade-in">
  <div class="row mb-4 align-items-center">
    <div class="col-md-3 text-center mb-3 mb-md-0">
      <img src="<?php echo htmlspecialchars($imagePath); ?>" class="img-fluid rounded-circle shadow" alt="<?php echo htmlspecialchars($authorInfo['name']); ?>">
    </div>
    <div class="col-md-9">
      <h1 class="mb-2"><?php echo htmlspecialchars($authorInfo['name']); ?></h1>
      <p class="text-secondary mb-3"><?php echo nl2br(htmlspecialchars($authorInfo['biography'])); ?></p>
      <span class="badge bg-primary">Total Books: <?php echo count($books); ?></span>
    </div>
  </div>
  <h3 class="mb-3">Books by <?php echo htmlspecialchars($authorInfo['name']); ?></h3>
  <div class="row">
    <?php if (!empty($books)): ?>
      <?php foreach ($books as $book): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
          <?php echo renderBookCard($book, false); ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12"><p class="text-muted">No books found for this author.</p></div>
    <?php endif; ?>
  </div>
</div>
<?php include('footer.php'); ?>
