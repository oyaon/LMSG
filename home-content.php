<?php require_once 'includes/init.php'; ?>

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
