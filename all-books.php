<?php require_once "includes/init.php"; ?>
<?php include ("header.php"); ?>

<?php
// Get filter parameters
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 12;
if ($perPage < 1) {
    $perPage = 12;
}

// Validate page number
if ($page < 1) {
    $page = 1;
}

// Get categories for filter
$categories = $bookOps->getCategories();

// Prepare category options for dropdown
$categoryOptions = ['' => 'All Categories'];
foreach ($categories as $categoryItem) {
    $categoryOptions[$categoryItem] = $categoryItem;
}

// Get books with pagination
$searchResults = $bookOps->searchBooks($query, $category, '', 'name', 'asc', $page, $perPage);
$books = $searchResults['books'];
$totalBooks = $searchResults['total'];
$totalPages = ceil($totalBooks / $perPage);

// Build base URL for pagination
$baseUrl = 'all-books.php?';
if (!empty($query)) {
    $baseUrl .= 'q=' . urlencode($query) . '&';
}
if (!empty($category)) {
    $baseUrl .= 'category=' . urlencode($category) . '&';
}
if ($perPage != 12) {
    $baseUrl .= 'per_page=' . $perPage . '&';
}

// Create breadcrumb items
$breadcrumbItems = [
    ['text' => 'Home', 'url' => 'index.php'],
    ['text' => 'Books', 'url' => '#']
];

// Prepare search filters
$searchFilters = [
    [
        'type' => 'select',
        'name' => 'category',
        'label' => 'Category',
        'options' => $categoryOptions,
        'value' => $category,
        'col' => '6'
    ],
    [
        'type' => 'select',
        'name' => 'per_page',
        'label' => 'Items per page',
        'options' => [
            12 => '12',
            24 => '24',
            48 => '48',
            96 => '96'
        ],
        'value' => $perPage,
        'col' => '6'
    ]
];
?>

<div class="container py-4 fade-in">
    <!-- Breadcrumb -->
    <?php echo renderBreadcrumb($breadcrumbItems); ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Books</h1>
        
        <?php if (!empty($query) || !empty($category)): ?>
            <a href="all-books.php" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>Clear Filters
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Search and Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?php echo renderSearchForm('all-books.php', 'Search for books...', 'Search', $query, $searchFilters); ?>
        </div>
    </div>

    <!-- Book Listing -->
    <div id="book-list" class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (count($books) > 0): ?>
            <?php foreach ($books as $book): ?>
                <div class="col">
                    <div class="card h-100 book-card">
                        <!-- Replace hardcoded image logic with the reusable function -->
                        <img src="<?php echo getBookCoverImage($book['cover_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($book['name'] ?? 'Unknown Book', ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="card-body">
                            <h5 class="card-title" data-bs-toggle="tooltip" title="<?php echo $book['name']; ?>">
                                <?php echo $book['name']; ?>
                            </h5>
                            <p class="card-text">By <?php echo $book['author']; ?></p>
                            <a href="book-details.php?t=<?php echo $book['id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No books found matching your criteria.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination Controls -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center flex-wrap">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $baseUrl . 'page=' . ($page - 1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php
            // Display page numbers with a window of 2 pages around current page
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo $baseUrl . 'page=' . $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $baseUrl . 'page=' . ($page + 1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- LazyLoad Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/17.6.1/lazyload.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lazyLoadInstance = new LazyLoad({
            elements_selector: ".lazyload"
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<style>
.book-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
</style>

<?php include ("footer.php"); ?>
