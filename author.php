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
    echo '<div class="container py-5"><div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> 
            <i class="fas fa-exclamation-circle me-2"></i> 
            <i class="fas fa-exclamation-circle me-2"></i> Author not found. 
            <a href="all-authors.php" class="alert-link">View all authors</a>
           
            <a href="all-authors.php" class="alert-link">View all authors</a>
           
            <a href="all-authors.php" class="alert-link">View all authors</a>
          </div></div>';
    include('footer.php');
    exit;
}

// Fetch books by this author
$books = $bookOps->getBooksByAuthor($authorId);
$imagePath = !empty($authorInfo['image_path']) ? $authorInfo['image_path'] : 'images/books1.png';
$bookType = !empty($authorInfo['book_type']) ? $authorInfo['book_type'] : 'Various Genres';

// Get book categories by this author
$categories = [];
if (!empty($books)) {
    foreach ($books as $book) {
        if (!empty($book['category']) && !in_array($book['category'], $categories)) {
            $categories[] = $book['category'];
        }
    }
}
?>
<div class="container py-4 fade-in">
    <!-- Back button -->
    <div class="mb-3">
        <a href="all-authors.php" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Authors
        </a>
    </div>
    
    <!-- Author Profile Header -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-3 text-center p-4 bg-light rounded-start d-flex flex-column justify-content-center align-items-center">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                         class="img-fluid rounded-circle shadow-sm author-profile-img" 
                         style="width:180px;height:180px;object-fit:cover;border:4px solid white;" 
                         alt="<?php echo htmlspecialchars($authorInfo['name']); ?>">
                    <h2 class="mt-3 mb-1"><?php echo htmlspecialchars($authorInfo['name']); ?></h2>
                    <p class="text-muted mb-2">
                        <i class="fas fa-bookmark me-1"></i> <?php echo htmlspecialchars($bookType); ?>
                    </p>
                    <div class="d-flex gap-2 mt-2">
                        <span class="badge bg-primary">
                            <i class="fas fa-book me-1"></i> <?php echo count($books); ?> Books
                        </span>
                        <?php if (!empty($categories)): ?>
                            <span class="badge bg-secondary">
                                <i class="fas fa-tags me-1"></i> <?php echo count($categories); ?> Categories
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-9 p-4">
                    <h3 class="border-bottom pb-2 mb-3">About the Author</h3>
                    <div class="author-bio mb-4">
                        <?php if (!empty($authorInfo['biography'])): ?>
                            <p class="text-secondary"><?php echo nl2br(htmlspecialchars($authorInfo['biography'])); ?></p>
                        <?php else: ?>
                            <p class="text-muted fst-italic">No biography available for this author.</p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($categories)): ?>
                    <div class="mb-3">
                        <h5 class="mb-2">Categories</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($categories as $category): ?>
                                <a href="category.php?c=<?php echo urlencode($category); ?>" class="text-decoration-none">
                                    <span class="badge bg-light text-dark p-2 border">
                                        <i class="fas fa-tag me-1"></i> <?php echo htmlspecialchars($category); ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h3 class="mb-0">
                <i class="fas fa-book me-2"></i> Books by <?php echo htmlspecialchars($authorInfo['name']); ?>
            </h3>
            <span class="badge bg-primary"><?php echo count($books); ?> Books</span>
        </div>
        <div class="card-body">
            <?php if (!empty($books)): ?>
                <div class="row">
                    <?php foreach ($books as $book): ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                            <?php echo renderBookCard($book, true); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No books available</h4>
                    <p>There are currently no books by this author in our library.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add custom CSS for animations and responsiveness -->
<style>
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.author-bio {
    max-height: 300px;
    overflow-y: auto;
}
@media (max-width: 767.98px) {
    .author-profile-img {
        width: 150px !important;
        height: 150px !important;
    }
}
</style>

<?php include('footer.php'); ?>
