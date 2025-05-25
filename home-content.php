<?php require_once 'includes/init.php'; ?>

<!-- Hero Section: Mobile-friendly & Accessible -->
<div class="container-fluid px-0 mb-4">
  <section class="hero-section position-relative d-flex align-items-center justify-content-center text-center text-white" aria-label="Library welcome banner" style="min-height: 320px; background: linear-gradient(rgba(20,50,80,0.8),rgba(20,50,80,0.8)), url('images/library-interior.jpg') center/cover no-repeat; animation: fadeIn 2s ease-in-out;">
  <style>
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
    <div class="p-4 w-100" style="max-width: 700px;"> <h1 class="display-5 fw-bold mb-3" style="color: white; text-shadow: 0 2px 8px rgba(0,0,0,0.25);">Welcome to Public Library Gobindaganj</h1>
      <p class="lead mb-4" style="color: var(--light-color); text-shadow: 0 1px 4px rgba(0,0,0,0.18);">Discover a wide range of books, enjoy special offers, and browse our bestsellers!</p>
      <a href="all-books.php" class="btn btn-lg btn-primary shadow" aria-label="Browse all books">Browse Books</a>
    </div>
  </section>
</div>

<!-- Special Offers -->
<div class="container pb-4">
    <h3 class="text-center">Special Offers</h3>
    <div class="row special-offers-list">
        <?php
        // Prepare the query
        $sql = "SELECT * FROM special_offer";
        
        // Execute the query
        $resultStmt = $db->query($sql);
        if ($resultStmt) {
            $result = $resultStmt->get_result();
            // Check if there are any rows
            if ($result->num_rows > 0) {
                // Loop through each row
                while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-sm-6 col-md-12">
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="card-body">
                                        <p class="card-text fs-5"><?php echo htmlspecialchars($row['header_top']); ?></p>
                                        <h5 class="card-title fs-4"><?php echo htmlspecialchars($row['header']); ?></h5>
                                        <p class="card-text fs-6"><?php echo htmlspecialchars($row['header_bottom']); ?></p>
                                        <a href="all-books.php" class="btn btn-primary">Browse Now</a>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <?php 
                                    // Check if image URL is available in the database
                                    $imagePath = !empty($row['image_path']) ? $row['image_path'] : 'images/home-content.png'; 
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" class="img-fluid rounded-end" alt="...">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo "<p>No special offers available at the moment.</p>";
            }
            $result->free();
            $resultStmt->close();
        } else {
            echo "<p>Error fetching special offers.</p>";
        }
        ?>
    </div>
</div>
