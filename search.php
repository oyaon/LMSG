<?php
// Include necessary files
require_once "includes/init.php";
include "header.php";
include "navbar.php";

// Get search parameters
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$author = isset($_GET['author']) ? trim($_GET['author']) : '';
$sortBy = isset($_GET['sort']) ? trim($_GET['sort']) : 'name';
$sortOrder = isset($_GET['order']) ? trim($_GET['order']) : 'asc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;

// Validate page number
if ($page < 1) {
    $page = 1;
}

// Get categories for filter
$categories = $bookOps->getCategories();

// Get authors for filter
$authors = $author->getAllAuthors();

// Prepare author options for dropdown
$authorOptions = ['' => 'All Authors'];
foreach ($authors as $authorItem) {
    $authorOptions[$authorItem['id']] = $authorItem['name'];
}

// Prepare category options for dropdown
$categoryOptions = ['' => 'All Categories'];
foreach ($categories as $categoryItem) {
    $categoryOptions[$categoryItem] = $categoryItem;
}

// Prepare sort options
$sortOptions = [
    'name_asc' => 'Title (A-Z)',
    'name_desc' => 'Title (Z-A)',
    'author_asc' => 'Author (A-Z)',
    'author_desc' => 'Author (Z-A)',
    'price_asc' => 'Price (Low to High)',
    'price_desc' => 'Price (High to Low)'
];

// Parse sort option
if (isset($_GET['sort_option']) && array_key_exists($_GET['sort_option'], $sortOptions)) {
    $sortOption = $_GET['sort_option'];
    $sortParts = explode('_', $sortOption);
    if (count($sortParts) === 2) {
        $sortBy = $sortParts[0];
        $sortOrder = $sortParts[1];
    }
}

// Search books
$searchResults = $bookOps->searchBooks($query, $category, $author, $sortBy, $sortOrder, $page, $perPage);
$books = $searchResults['books'];
$totalBooks = $searchResults['total'];
$totalPages = ceil($totalBooks / $perPage);

// Build base URL for pagination
$baseUrl = 'search.php?';
if (!empty($query)) {
    $baseUrl .= 'q=' . urlencode($query) . '&';
}
if (!empty($category)) {
    $baseUrl .= 'category=' . urlencode($category) . '&';
}
if (!empty($author)) {
    $baseUrl .= 'author=' . urlencode($author) . '&';
}
if (isset($_GET['sort_option'])) {
    $baseUrl .= 'sort_option=' . urlencode($_GET['sort_option']) . '&';
}

// Create breadcrumb items
$breadcrumbItems = [
    ['text' => 'Home', 'url' => 'index.php'],
    ['text' => 'Search Results', 'url' => '#']
];

// Prepare search filters
$searchFilters = [
    [
        'type' => 'select',
        'name' => 'category',
        'label' => 'Category',
        'options' => $categoryOptions,
        'value' => $category,
        'col' => '4'
    ],
    [
        'type' => 'select',
        'name' => 'author',
        'label' => 'Author',
        'options' => $authorOptions,
        'value' => $author,
        'col' => '4'
    ],
    [
        'type' => 'select',
        'name' => 'sort_option',
        'label' => 'Sort By',
        'options' => $sortOptions,
        'value' => $sortBy . '_' . $sortOrder,
        'col' => '4'
    ]
];
?>

<div class="container py-4 fade-in">
    <!-- Breadcrumb -->
    <?php echo renderBreadcrumb($breadcrumbItems); ?>
    
    <h1 class="mb-4">Search Results</h1>
    
    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?php echo renderSearchForm('search.php', 'Search for books, authors, or categories...', 'Search', $query, $searchFilters); ?>
        </div>
    </div>
    
    <!-- Search Results -->
    <div class="row">
        <div class="col-12">
            <?php if (empty($query) && empty($category) && empty($author)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Please enter a search term or select filters to find books.
                </div>
            <?php elseif (empty($books)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No books found matching your search criteria.
                </div>
            <?php else: ?>
                <p class="text-muted mb-4">Found <?php echo $totalBooks; ?> book<?php echo $totalBooks !== 1 ? 's' : ''; ?> matching your search criteria.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (!empty($books)): ?>
        <div class="row">
            <?php foreach ($books as $book): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <?php echo renderBookCard($book); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <?php echo renderPagination($page, $totalPages, $baseUrl); ?>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Search Tips -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Search Tips</h5>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Use specific keywords for better results</li>
                <li>Search by book title, author name, or category</li>
                <li>Use filters to narrow down your search</li>
                <li>Try different sorting options to find what you're looking for</li>
                <li>If you can't find what you're looking for, try using fewer keywords</li>
            </ul>
        </div>
    </div>
</div>

<?php include "footer.php"; ?><?php
// Include necessary files
require_once "includes/init.php";
include "header.php";
include "navbar.php";

// Get search parameters
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$author = isset($_GET['author']) ? trim($_GET['author']) : '';
$sortBy = isset($_GET['sort']) ? trim($_GET['sort']) : 'name';
$sortOrder = isset($_GET['order']) ? trim($_GET['order']) : 'asc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12;

// Validate page number
if ($page < 1) {
    $page = 1;
}

// Get categories for filter
$categories = $bookOps->getCategories();

// Get authors for filter
$authors = $author->getAllAuthors();

// Prepare author options for dropdown
$authorOptions = ['' => 'All Authors'];
foreach ($authors as $authorItem) {
    $authorOptions[$authorItem['id']] = $authorItem['name'];
}

// Prepare category options for dropdown
$categoryOptions = ['' => 'All Categories'];
foreach ($categories as $categoryItem) {
    $categoryOptions[$categoryItem] = $categoryItem;
}

// Prepare sort options
$sortOptions = [
    'name_asc' => 'Title (A-Z)',
    'name_desc' => 'Title (Z-A)',
    'author_asc' => 'Author (A-Z)',
    'author_desc' => 'Author (Z-A)',
    'price_asc' => 'Price (Low to High)',
    'price_desc' => 'Price (High to Low)'
];

// Parse sort option
if (isset($_GET['sort_option']) && array_key_exists($_GET['sort_option'], $sortOptions)) {
    $sortOption = $_GET['sort_option'];
    $sortParts = explode('_', $sortOption);
    if (count($sortParts) === 2) {
        $sortBy = $sortParts[0];
        $sortOrder = $sortParts[1];
    }
}

// Search books
$searchResults = $bookOps->searchBooks($query, $category, $author, $sortBy, $sortOrder, $page, $perPage);
$books = $searchResults['books'];
$totalBooks = $searchResults['total'];
$totalPages = ceil($totalBooks / $perPage);

// Build base URL for pagination
$baseUrl = 'search.php?';
if (!empty($query)) {
    $baseUrl .= 'q=' . urlencode($query) . '&';
}
if (!empty($category)) {
    $baseUrl .= 'category=' . urlencode($category) . '&';
}
if (!empty($author)) {
    $baseUrl .= 'author=' . urlencode($author) . '&';
}
if (isset($_GET['sort_option'])) {
    $baseUrl .= 'sort_option=' . urlencode($_GET['sort_option']) . '&';
}

// Create breadcrumb items
$breadcrumbItems = [
    ['text' => 'Home', 'url' => 'index.php'],
    ['text' => 'Search Results', 'url' => '#']
];

// Prepare search filters
$searchFilters = [
    [
        'type' => 'select',
        'name' => 'category',
        'label' => 'Category',
        'options' => $categoryOptions,
        'value' => $category,
        'col' => '4'
    ],
    [
        'type' => 'select',
        'name' => 'author',
        'label' => 'Author',
        'options' => $authorOptions,
        'value' => $author,
        'col' => '4'
    ],
    [
        'type' => 'select',
        'name' => 'sort_option',
        'label' => 'Sort By',
        'options' => $sortOptions,
        'value' => $sortBy . '_' . $sortOrder,
        'col' => '4'
    ]
];
?>

<div class="container py-4 fade-in">
    <!-- Breadcrumb -->
    <?php echo renderBreadcrumb($breadcrumbItems); ?>
    
    <h1 class="mb-4">Search Results</h1>
    
    <!-- Search Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?php echo renderSearchForm('search.php', 'Search for books, authors, or categories...', 'Search', $query, $searchFilters); ?>
        </div>
    </div>
    
    <!-- Search Results -->
    <div class="row">
        <div class="col-12">
            <?php if (empty($query) && empty($category) && empty($author)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Please enter a search term or select filters to find books.
                </div>
            <?php elseif (empty($books)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No books found matching your search criteria.
                </div>
            <?php else: ?>
                <p class="text-muted mb-4">Found <?php echo $totalBooks; ?> book<?php echo $totalBooks !== 1 ? 's' : ''; ?> matching your search criteria.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (!empty($books)): ?>
        <div class="row">
            <?php foreach ($books as $book): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <?php echo renderBookCard($book); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <?php echo renderPagination($page, $totalPages, $baseUrl); ?>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Search Tips -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Search Tips</h5>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Use specific keywords for better results</li>
                <li>Search by book title, author name, or category</li>
                <li>Use filters to narrow down your search</li>
                <li>Try different sorting options to find what you're looking for</li>
                <li>If you can't find what you're looking for, try using fewer keywords</li>
            </ul>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>