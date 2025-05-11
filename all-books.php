<?php
// Include necessary files
require_once "includes/init.php";
include "header.php";
// Removed include "navbar.php" to avoid duplicate navbar since header.php already includes navbar

// Get filter parameters
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;

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
</div>

<?php include "footer.php"; ?>
