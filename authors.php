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
    ?>
    <div class="col-sm-3 mb-3 mb-sm-3">
      <div class="card p-3 author-card book-card-inner">
        <img src="images/books1.png" class="card-img-top rounded-circle author-photo border-gradient" alt="...">
        <div class="card-body text-center">
          <h5 class="card-title"><?php echo $row['name']; ?></h5>
          <p class="card-text author-bio"><?php echo htmlspecialchars($row['biography']); ?></p>
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
