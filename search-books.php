<?php
require_once 'includes/init.php';
include("header.php");
include("top-navbar.php");

$bookOps = new BookOperations();

$searchQuery = $_POST['s-book-name'] ?? '';
$categoryFilter = $_POST['category'] ?? '';
$authorFilter = $_POST['author'] ?? '';
$sortBy = $_POST['sort_by'] ?? 'name';
$sortOrder = $_POST['sort_order'] ?? 'asc';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$perPage = 12;

$searchResults = $bookOps->searchBooks($searchQuery, $categoryFilter, $authorFilter, $sortBy, $sortOrder, $page, $perPage);
$books = $searchResults['books'];
$total = $searchResults['total'];
$totalPages = ceil($total / $perPage);

?>

<div class="container py-4">
    <div class="row">
        <div class="col-sm-3 col-md-2 mb-3 mb-sm-3">
            <h3>Search Books</h3>
        </div>
        <div class="col-sm-9 col-md-10 mb-3 mb-sm-3">
            <form action="" method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search Books" name="s-book-name" value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Category" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Author" name="author" value="<?php echo htmlspecialchars($authorFilter); ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="sort_by">
                        <option value="name" <?php if ($sortBy === 'name') echo 'selected'; ?>>Name</option>
                        <option value="author" <?php if ($sortBy === 'author') echo 'selected'; ?>>Author</option>
                        <option value="category" <?php if ($sortBy === 'category') echo 'selected'; ?>>Category</option>
                        <option value="price" <?php if ($sortBy === 'price') echo 'selected'; ?>>Price</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="sort_order">
                        <option value="asc" <?php if ($sortOrder === 'asc') echo 'selected'; ?>>Ascending</option>
                        <option value="desc" <?php if ($sortOrder === 'desc') echo 'selected'; ?>>Descending</option>
                    </select>
                </div>
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <hr class="border border-primary border-3 opacity-100 w-100">

    <div class="row">
        <?php if (!empty($books)) : ?>
            <?php foreach ($books as $row) : ?>
                <div class="col-sm-3 mb-3 mb-sm-3">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($row['cover_image'] ?? 'images/books1.png'); ?>" class="card-img-top" alt="Book cover">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text">Author: <?php echo htmlspecialchars($row['author_name'] ?? $row['author']); ?></p>
                            <p class="card-text">Quantity: <?php echo htmlspecialchars($row['quantity']); ?></p>
                            <p class="card-text">Description: <?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Category: <?php echo htmlspecialchars($row['category']); ?></li>
                            <li class="list-group-item">Price: $<?php echo htmlspecialchars(number_format($row['price'], 2)); ?></li>
                        </ul>
                        <div class="card-body">
                            <a href="book-details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
                            <a href="add_cart.php?book_id=<?php echo $row['id']; ?>" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1) : ?>
                            <li class="page-item">
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="s-book-name" value="<?php echo htmlspecialchars($searchQuery); ?>">
                                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>">
                                    <input type="hidden" name="author" value="<?php echo htmlspecialchars($authorFilter); ?>">
                                    <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sortBy); ?>">
                                    <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sortOrder); ?>">
                                    <input type="hidden" name="page" value="<?php echo $page - 1; ?>">
                                    <button type="submit" class="page-link">Previous</button>
                                </form>
                            </li>
                        <?php endif; ?>
                        <?php if ($page < $totalPages) : ?>
                            <li class="page-item">
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="s-book-name" value="<?php echo htmlspecialchars($searchQuery); ?>">
                                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryFilter); ?>">
                                    <input type="hidden" name="author" value="<?php echo htmlspecialchars($authorFilter); ?>">
                                    <input type="hidden" name="sort_by" value="<?php echo htmlspecialchars($sortBy); ?>">
                                    <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sortOrder); ?>">
                                    <input type="hidden" name="page" value="<?php echo $page + 1; ?>">
                                    <button type="submit" class="page-link">Next</button>
                                </form>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php else : ?>
            <p>No search results found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include("footer.php"); ?>
