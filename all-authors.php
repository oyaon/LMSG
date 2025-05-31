<?php 
// Include initialization file which loads all required classes
require_once 'includes/init.php';
include ("header.php"); 
// Removed include of top-navbar.php to avoid duplicate navigation bar

// Get all query parameters except sort and order for preserving in links
$queryParams = $_GET;
unset($queryParams['sort'], $queryParams['order']);

// Get search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'ASC';

// Validate sort parameters
$validSortFields = ['name', 'id'];
if (!in_array($sortBy, $validSortFields)) {
    $sortBy = 'name';
}

$validOrders = ['ASC', 'DESC'];
if (!in_array($order, $validOrders)) {
    $order = 'ASC';
}

// Toggle order for links
$toggleOrder = ($order === 'ASC') ? 'DESC' : 'ASC';
?>

<div class="container py-4 fade-in">
    <!-- Page Header with Search -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">Authors</h2>
            <p class="text-muted">Discover our collection of talented authors</p>
        </div>
        <div class="col-md-6">
            <form action="all-authors.php" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search authors..." name="search" value="<?php echo htmlspecialchars($search); ?>" aria-label="Search authors">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sorting Options -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="text-muted me-2">Sort by:</span>
            <div class="btn-group btn-group-sm">
                <?php
                // Build query string preserving all parameters except sort and order
                function buildSortLink($sortField, $currentSortBy, $currentOrder, $queryParams) {
                    $order = 'ASC';
                    if ($currentSortBy === $sortField) {
                        $order = ($currentOrder === 'ASC') ? 'DESC' : 'ASC';
                    }
                    $params = array_merge($queryParams, ['sort' => $sortField, 'order' => $order]);
                    return '?' . http_build_query($params);
                }
                ?>
                <a href="<?php echo buildSortLink('name', $sortBy, $order, $queryParams); ?>" 
                   class="btn <?php echo $sortBy === 'name' ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                    Name <?php echo $sortBy === 'name' ? ($order === 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
                    
                    
                <a href="<?php echo buildSortLink('id', $sortBy, $order, $queryParams); ?>" 
                   class="btn <?php echo $sortBy === 'id' ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                    Newest <?php echo $sortBy === 'id' ? ($order === 'ASC' ? '↑' : '↓') : ''; ?>
                </a>
            </div>
        </div>
        <div id="authors-count" class="badge bg-secondary"></div>
    </div>

    <!-- Authors Grid -->
    <div class="row" id="authors-grid">
        <?php
        try {
            // Get authors with search and sorting
            if (!empty($search)) {
                $authors = $author->searchAuthors($search);
                
                // Manual sorting since searchAuthors doesn't support sorting
                if ($sortBy === 'name') {
                    usort($authors, function($a, $b) use ($order) {
                        return $order === 'ASC' ? 
                            strcmp($a['name'], $b['name']) : 
                            strcmp($b['name'], $a['name']);
                    });
                } else if ($sortBy === 'id') {
                    usort($authors, function($a, $b) use ($order) {
                        return $order === 'ASC' ? 
                            $a['id'] - $b['id'] : 
                            $b['id'] - $a['id'];
                    });
                }
            } else {
                $authors = $author->getAllAuthors($sortBy, $order);
            }
            
            if (!empty($authors)) {
                // Display count with JavaScript
                echo '<script>document.getElementById("authors-count").textContent = "' . count($authors) . ' authors found";</script>';
                
                foreach ($authors as $row) {
                    $imagePath = !empty($row['image_path']) ? $row['image_path'] : 'images/books1.png';
                    $bioShort = mb_strlen($row['biography']) > 100 ? mb_substr($row['biography'], 0, 100) . '...' : $row['biography'];
                    $totalBooks = $bookOps->getBooksCountByAuthor((int)$row['id']);
                    $bookType = !empty($row['book_type']) ? $row['book_type'] : 'Various Genres';
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm author-card hover-effect" tabindex="0" aria-label="Author: <?php echo htmlspecialchars($row['name']); ?>">
                            <div class="position-relative">
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top rounded-circle mx-auto mt-3" 
                                     style="width:120px;height:120px;object-fit:cover;border:3px solid #f8f9fa;" 
                                     alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-primary mt-2 me-2">
                                    <i class="fas fa-book me-1"></i> <?php echo $totalBooks; ?>
                                </span>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-subtitle mb-2 text-muted small">
                                    <i class="fas fa-bookmark me-1"></i> <?php echo htmlspecialchars($bookType); ?>
                                </p>
                                <p class="card-text small text-truncate" title="<?php echo htmlspecialchars($row['biography']); ?>">
                                    <?php echo htmlspecialchars($bioShort); ?>
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="author.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-outline-primary btn-sm" 
                                       aria-label="View profile for <?php echo htmlspecialchars($row['name']); ?>">
                                        <i class="fas fa-user me-1"></i> View Profile
                                    </a>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" 
                                            data-bs-target="#authorModal<?php echo (int)$row['id']; ?>">
                                        <i class="fas fa-book-open me-1"></i> Read Bio
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Author Bio Modal -->
                    <div class="modal fade" id="authorModal<?php echo (int)$row['id']; ?>" tabindex="-1" 
                         aria-labelledby="authorModalLabel<?php echo (int)$row['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="authorModalLabel<?php echo (int)$row['id']; ?>">
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center mb-3">
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="rounded-circle mb-3" 
                                             style="width:100px;height:100px;object-fit:cover;border:3px solid #f8f9fa;" 
                                             alt="<?php echo htmlspecialchars($row['name']); ?>">
                                        <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                                        <p class="text-muted small">
                                            <i class="fas fa-bookmark me-1"></i> <?php echo htmlspecialchars($bookType); ?> | 
                                            <i class="fas fa-book me-1"></i> <?php echo $totalBooks; ?> Books
                                        </p>
                                    </div>
                                    <div class="biography-content">
                                        <?php if (!empty($row['biography'])): ?>
                                            <p><?php echo nl2br(htmlspecialchars($row['biography'])); ?></p>
                                        <?php else: ?>
                                            <p class="text-muted text-center">No biography available for this author.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="author.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-book me-1"></i> View Books
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                if (!empty($search)) {
                    echo '<div class="col-12 text-center py-5">';
                    echo '<div class="alert alert-info">';
                    echo '<i class="fas fa-search fa-2x mb-3"></i>';
                    echo '<h4>No authors found matching "' . htmlspecialchars($search) . '"</h4>';
                    echo '<p>Try a different search term or <a href="all-authors.php" class="alert-link">view all authors</a>.</p>';
                    echo '</div></div>';
                } else {
                    echo '<div class="col-12 text-center py-5">';
                    echo '<div class="alert alert-info">';
                    echo '<i class="fas fa-users fa-2x mb-3"></i>';
                    echo '<h4>No authors found</h4>';
                    echo '<p>There are currently no authors in the database.</p>';
                    echo '</div></div>';
                }
            }
        } catch (Exception $e) {
            Helper::handleException($e, true, Helper::getDatabaseErrorMessage('fetch', 'authors'));
            echo '<div class="col-12 text-center text-danger"><p>An error occurred while fetching authors. Please try again later.</p></div>';
        }
        ?>
    </div>
</div>

<!-- Add custom CSS for hover effects -->
<style>
.hover-effect {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.biography-content {
    max-height: 300px;
    overflow-y: auto;
}
</style>

<?php include ("footer.php"); ?>
