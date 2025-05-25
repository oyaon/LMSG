<?php 
// Include initialization file which loads all required classes
require_once 'includes/init.php';
include ("header.php"); 
// Removed include of top-navbar.php to avoid duplicate navigation bar
?>

<div class="container pb-4">
    <h3 class="text-center">Authors</h3>
    <div class="row">
        <?php
        try {
            $authors = $author->getAllAuthors();
            if (!empty($authors)) {
                foreach ($authors as $row) {
                    $imagePath = !empty($row['image_path']) ? $row['image_path'] : 'images/books1.png';
                    $bioShort = mb_strlen($row['biography']) > 80 ? mb_substr($row['biography'], 0, 80) . '...' : $row['biography'];
                    $totalBooks = $bookOps->getBooksCountByAuthor((int)$row['id']);
                    ?>
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card p-3 h-100 text-center author-card" tabindex="0" aria-label="Author: <?php echo htmlspecialchars($row['name']); ?>">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top rounded-circle mx-auto mt-2" style="width:100px;height:100px;object-fit:cover;" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($row['name']); ?></h5>
                                <p class="card-text text-secondary small mb-2">
                                  <a href="#" class="author-bio-link" data-bs-toggle="modal" data-bs-target="#authorModal<?php echo (int)$row['id']; ?>">Read Bio</a>
                                </p>
                                <p class="text-muted small mb-1">Total Books: <?php echo $totalBooks; ?> books found for this author</p>
                                <a href="author.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-outline-primary btn-sm mt-2" aria-label="View profile for <?php echo htmlspecialchars($row['name']); ?>">View Profile</a>
                            </div>
                        </div>
                    </div>
                    <!-- Author Bio Modal -->
                    <div class="modal fade" id="authorModal<?php echo (int)$row['id']; ?>" tabindex="-1" aria-labelledby="authorModalLabel<?php echo (int)$row['id']; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="authorModalLabel<?php echo (int)$row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?> - Bio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>" class="rounded-circle mb-3" style="width:80px;height:80px;object-fit:cover;" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <p><?php echo nl2br(htmlspecialchars($row['biography'])); ?></p>
                          </div>
                          <div class="modal-footer">
                            <a href="author.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary">View Books</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p>No authors found</p></div>';
            }
        } catch (Exception $e) {
            Helper::handleException($e, true, Helper::getDatabaseErrorMessage('fetch', 'authors'));
            echo '<div class="col-12 text-center text-danger"><p>An error occurred while fetching authors. Please try again later.</p></div>';
        }
        ?>
    </div>
</div>

<?php include ("footer.php"); ?>
