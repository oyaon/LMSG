<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Gobindaganj Public Library - Discover a wide range of books and resources" />
    <meta name="keywords" content="library, books, reading, education, literature" />
    <title>Gobindaganj Public Library</title>

    <!-- Favicon -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;300;400;500;700&display=swap" rel="stylesheet" />

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="css/style.css" />

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Library",
      "name": "Gobindaganj Public Library",
      "url": "http://yourdomain.com",
      "logo": "http://yourdomain.com/images/favicon.ico",
      "sameAs": [
        "https://www.facebook.com/yourlibrary",
        "https://twitter.com/yourlibrary",
        "https://www.instagram.com/yourlibrary"
      ],
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Library St",
        "addressLocality": "Gobindaganj",
        "addressRegion": "Region",
        "postalCode": "5740",
        "addressCountry": "Bangladesh"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+1-555-555-5555",
        "contactType": "Customer Service"
      }
    }
    </script>
</head>

<body class="bg-light text-dark">
    <a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
    <?php include 'navbar.php'; ?>
    <?php
    // Start session if not already started
    // Removed session_start() here to avoid headers already sent warning
    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }

    require_once __DIR__ . '/includes/Database.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // Include components file
    require_once __DIR__ . '/includes/components.php';
    ?>

    <?php
    require_once __DIR__ . '/includes/Helper.php';
    Helper::displayFlashMessage();
    ?>

    <!-- Main Content Section -->    <main id="main-content" class="container mt-5 pt-5">
        <h1 class="text-center mb-3">Welcome to Gobindaganj Public Library</h1>
        <p class="text-center mb-4">Discover a wide range of books, enjoy special offers, and browse our bestsellers!</p>

        <!-- Special Offers Section -->
        <section class="container pb-4" aria-label="Special Offers">
            <h3 class="text-center mb-4">Special Offers</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                <?php
                $specialOffers = $db->fetchAll("SELECT * FROM special_offer");
                foreach ($specialOffers as $row) { ?>
                    <article class="col">
                        <div class="card h-100 shadow-sm animate__animated animate__fadeIn">
                            <div class="row g-0">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="card-body">
                                        <p class="card-text fs-5"><?php echo htmlspecialchars($row['header_top']); ?></p>
                                        <h5 class="card-title fs-4"><?php echo htmlspecialchars($row['header']); ?></h5>
                                        <p class="card-text fs-6"><?php echo htmlspecialchars($row['header_bottom']); ?></p>
                                        <a href="all-books.php" class="btn btn-primary" role="button"
                                            aria-label="Browse all books">Browse Now</a>
                                    </div>
                                </div>
                <div class="col-md-7">
                    <img src="images/home-content.png" class="img-fluid rounded-end" alt="Special Offer Image"
                        loading="lazy">
                </div>
            </div>
        </div>
    </article>
<?php } ?>
            </div>
        </section>
    </main>

</body>
</html>
