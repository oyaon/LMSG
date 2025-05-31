<?php require_once 'includes/init.php'; ?>

<div class="container pb-4">
  <h3 class="text-center">Authors</h3>
  <div class="row">
    <?php
    // Use prepared statement for better security
    $stmt = $db->query("SELECT * FROM authors LIMIT 8");
    if ($stmt) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $authorImage = !empty($row['image']) ? htmlspecialchars($row['image']) : 'books1.png';
            $bioPreview = htmlspecialchars(mb_strimwidth($row['biography'], 0, 150, '...'));
    ?>
    <div class="col-sm-3 mb-3 mb-sm-3">
      <div class="card p-3 author-card book-card-inner">
        <img src="images/authors/<?php echo $authorImage; ?>" class="card-img-top rounded-circle author-photo border-gradient" alt="<?php echo htmlspecialchars($row['name']); ?>">
        <div class="card-body text-center">
          <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
          <p class="card-text author-bio" style="max-height: 100px; overflow: hidden; transition: max-height 0.3s ease;">
            <?php echo $bioPreview; ?>
          </p>
          <?php if (mb_strlen($row['biography']) > 150): ?>
            <button class="btn btn-link bio-expand-btn p-0" type="button">Read More <i class="fas fa-chevron-down"></i></button>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php
        }
        $stmt->close();
    }
    ?>
  </div>
  <p class="text-center"><a href="all-authors.php" class="btn btn-primary">View All Authors</a></p>
</div>
