<?php
require_once 'includes/init.php'; 
?>

<!-- Index All Books -->
<div class="container pb-4">
	<h3>All Books</h3>
	<div class="row">
		<?php
		$sql = "SELECT * FROM `all_books` LIMIT 8";
		$stmt = $db->query($sql);
		if ($stmt) {
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) { ?>
				<div class="col-sm-3 mb-3 mb-sm-3">
					<div class="card">
						<img src="<?php echo getBookCoverImage($row['cover_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>">
						<div class="card-body">
							<h5 class="card-title"><?php echo $row['name']; ?></h5>
							<p class="card-text">Author: <?php echo $row['author']; ?></p>
							<p class="card-text">Quantity: <b><?php echo $row['quantity']; ?></b></p>
							<p class="card-text">Description: <?php echo substr($row['description'], 0, 50); ?>...</p>
							<div class="book-rating-container"></div>
						</div>
						<div class="card-body d-flex justify-content-center">
							<a href="book-details.php?t=<?php echo $row['id']; ?>" class="btn btn-primary">View Details</a>
						</div>
					</div>
                </div>
			<?php }
			$stmt->close();
		}
		?>
	</div>
</div>
