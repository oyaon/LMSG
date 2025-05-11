<?php
session_start();  // Start the session
include("admin/db-connect.php");  // Include the database connection

require_once __DIR__ . '/../includes/init.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) {
    // Not logged in or not admin, redirect to login page
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gobindaganj Public Library</title>
    
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Optional Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Navigation Bar (Sticky) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gobindaganj Public Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all-books.php">All Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="special-offers.php">Special Offers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Section -->
    <div class="container mt-5 pt-5">
        <h1 class="text-center">Welcome to Gobindaganj Public Library</h1>
        <p class="text-center">Discover a wide range of books, enjoy special offers, and browse our bestsellers!</p>

        <!-- Special Offers Section -->
        <div class="container pb-4">
            <h3 class="text-center">Special Offers</h3>
            <div class="row">
                <?php
                // Fetching special offers from the database
                $sql = "SELECT * FROM special_offer";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-md-5 d-flex align-items-center">
                                        <div class="card-body">
                                            <p class="card-text fs-5"><?php echo $row['header_top']; ?></p>
                                            <h5 class="card-title fs-4"><?php echo $row['header']; ?></h5>
                                            <p class="card-text fs-6"><?php echo $row['header_bottom']; ?></p>
                                            <a href="all-books.php" class="btn btn-primary">Browse Now</a>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <img src="images/home-content.png" class="img-fluid rounded-end" alt="Special Offer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else {
                    echo "<p>No special offers available at the moment.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2025 Gobindaganj Public Library. All rights reserved.</p>
        <p><a href="privacy-policy.php" class="text-white">Privacy Policy</a> | <a href="terms-of-service.php" class="text-white">Terms of Service</a></p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybL3+XenZ2R9Kx1wKvMSTt5nMw8d40U1P4aZ1Ay0S4fF2AUmS" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-CzSmQsI+2D1cHt5ngw53vJtLhbQfylwO33xu1e0i4RDXP7HV2S+aSeAghZAf8tD0" crossorigin="anonymous"></script>
</body>

</html>
