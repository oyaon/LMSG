<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Gobindaganj Public Library - Discover a wide range of books and resources">
    <meta name="keywords" content="library, books, reading, education, literature">
    <meta name="description" content="Gobindaganj Public Library - Discover a wide range of books and resources">
    <meta name="keywords" content="library, books, reading, education, literature">
    <title>Gobindaganj Public Library</title>
    
    <!-- Favicon -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>

<?php 
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/Database.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Include components file
require_once __DIR__ . '/includes/components.php';
?>

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
                $specialOffers = $db->fetchAll("SELECT * FROM special_offer");
                foreach ($specialOffers as $row) { ?>
                    <div class="col-sm-6 col-md-4">
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
                                    <img src="images/home-content.png" class="img-fluid rounded-end" alt="Special Offer">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
