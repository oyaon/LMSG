<?php
// Include necessary files
require_once "includes/init.php";

// Get book ID from URL
$id = isset($_GET["t"]) ? (int)$_GET["t"] : 0;

// Validate book ID
if ($id <= 0) {
    // Redirect to books page if invalid ID
    header("Location: all-books.php");
    exit;
}

// Get book details
$book = $bookOps->getBookById($id);

// Check if book exists
if (!$book) {
    // Redirect to books page if book not found
    header("Location: all-books.php");
    exit;
}

// Get author details if available
$authorInfo = null;
if (isset($book['author_id']) && $book['author_id'] > 0) {
    $authorInfo = $author->getAuthorById($book['author_id']);
}

include "header.php";
// navbar is already included in header.php, no need to include it again

// Get book cover image
$coverImage = !empty($book['cover_image']) ? "images/covers/{$book['cover_image']}" : "images/book-placeholder.jpg";

// Create breadcrumb items
$breadcrumbItems = [
    ['text' => 'Home', 'url' => 'index.php'],
    ['text' => 'Books', 'url' => 'all-books.php'],
    ['text' => htmlspecialchars($book['name']), 'url' => '#']
];
?>

<div class="container py-4 fade-in">
    <!-- Breadcrumb -->
    <?php echo renderBreadcrumb($breadcrumbItems); ?>
    
    <div class="book-details">
        <div class="card shadow-sm border-0 mb-4">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?php echo $coverImage; ?>" class="img-fluid book-cover rounded-start" alt="<?php echo htmlspecialchars($book['name']); ?>" />
                </div>

                <div class="col-md-8">
                    <div class="card-body">
                        <h1 class="book-title"><?php echo htmlspecialchars($book['name']); ?></h1>
                        <p class="book-author">by 
                            <?php if ($authorInfo): ?>
                                <a href="author.php?id=<?php echo $authorInfo['id']; ?>">
                                    <?php echo htmlspecialchars($authorInfo['name']); ?>
                                </a>
                            <?php else: ?>
                                <?php echo htmlspecialchars($book['author']); ?>
                            <?php endif; ?>
                        </p>
                        
                        <div class="book-meta">
                            <div class="book-meta-item">
                                <i class="fas fa-bookmark"></i>
                                <span><?php echo htmlspecialchars($book['category']); ?></span>
                            </div>
                            <div class="book-meta-item">
                                <i class="fas fa-cubes"></i>
                                <span><?php echo (int)$book['quantity']; ?> available</span>
                            </div>
                            <?php if (isset($book['price']) && $book['price'] > 0): ?>
                                <div class="book-meta-item">
                                    <i class="fas fa-tag"></i>
                                    <span><?php echo number_format($book['price'], 2); ?> TK</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (isset($book['rating'])): ?>
                            <div class="mb-3">
                                <?php echo renderStarRating($book['rating']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h4>Description</h4>
                        <div class="book-description">
                            <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <?php if ((int)$book['quantity'] > 0): ?>
                                <a href="borrow.php?t=<?php echo $book['id']; ?>&q=<?php echo $book['quantity']; ?>" class="btn btn-primary">
                                    <i class="fas fa-book me-2"></i>Borrow this book
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-exclamation-circle me-2"></i>Out of Stock
                                </button>
                            <?php endif; ?>

                            <?php if(!empty($book['pdf'])): ?>
                                <a class="btn btn-outline-primary" href="./pdfs/<?php echo $book['pdf']; ?>" target="_blank">
                                    <i class="fas fa-file-pdf me-2"></i>View PDF
                                </a>
                            <?php endif; ?>
                            
                            <?php if (isset($cart) && method_exists($cart, 'addToCart')): ?>
                                <a href="cart-add.php?id=<?php echo $book['id']; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book Details Tabs -->
        <?php
        // Create tabs content
        $tabsContent = [
            [
                'id' => 'details',
                'title' => 'Details',
                'content' => '
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Book Information</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Category</span>
                                    <span class="badge bg-primary rounded-pill">' . htmlspecialchars($book['category']) . '</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Available Copies</span>
                                    <span class="badge bg-' . ((int)$book['quantity'] > 0 ? 'success' : 'danger') . ' rounded-pill">' . (int)$book['quantity'] . '</span>
                                </li>
                                ' . (isset($book['price']) ? '
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Price</span>
                                    <span>' . number_format($book['price'], 2) . ' TK</span>
                                </li>' : '') . '
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Borrowing Information</h5>
                            <p>To borrow this book, you need to:</p>
                            <ol>
                                <li>Be a registered member of the library</li>
                                <li>Have no overdue books</li>
                                <li>Have less than 5 books currently borrowed</li>
                            </ol>
                            <p>The standard borrowing period is 14 days.</p>
                        </div>
                    </div>
                '
            ],
            [
                'id' => 'author',
                'title' => 'Author',
                'content' => $authorInfo ? '
                    <div class="row">
                        <div class="col-md-4">
                            ' . (isset($authorInfo['image']) && !empty($authorInfo['image']) ? 
                                '<img src="images/authors/' . $authorInfo['image'] . '" class="img-fluid rounded" alt="' . htmlspecialchars($authorInfo['name']) . '">' : 
                                '<div class="text-center p-5 bg-light rounded"><i class="fas fa-user fa-4x text-secondary"></i></div>') . '
                        </div>
                        <div class="col-md-8">
                            <h4>' . htmlspecialchars($authorInfo['name']) . '</h4>
                            <p>' . nl2br(htmlspecialchars($authorInfo['biography'] ?? 'No biography available.')) . '</p>
                            <a href="author.php?id=' . $authorInfo['id'] . '" class="btn btn-outline-primary btn-sm">View all books by this author</a>
                        </div>
                    </div>
                ' : '
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No detailed information available about the author.
                    </div>
                    <p>Author: ' . htmlspecialchars($book['author']) . '</p>
                '
            ],
            [
                'id' => 'reviews',
                'title' => 'Reviews',
                'content' => '
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Reviews feature coming soon!
                    </div>
                    <p>Be the first to review this book.</p>
                '
            ]
        ];
        
        echo renderTabs('bookTabs', $tabsContent, 'details');
        ?>
        
        <!-- Related Books -->
        <div class="mt-5">
            <h3 class="mb-4">You might also like</h3>
            <div class="row">
                <?php
                // Get related books (same category or author)
                $relatedBooks = $bookOps->getRelatedBooks($id, $book['category'], $book['author_id'] ?? 0, 3);
                
                if (!empty($relatedBooks)) {
                    foreach ($relatedBooks as $relatedBook) {
                        echo '<div class="col-md-4 mb-4">';
                        echo renderBookCard($relatedBook);
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12"><p class="text-muted">No related books found.</p></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>