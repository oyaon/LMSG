<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Gobindaganj Public Library - Discover a wide range of books and resources" />
    <meta name="keywords" content="library, books, reading, education, literature" />
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Gobindaganj Public Library' : 'Gobindaganj Public Library'; ?></title>

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
    <?php
    require_once __DIR__ . '/includes/init.php';
    include 'navbar.php';

    // The init.php script (included by pages like index.php, contact.php, etc., before this header.php)
    // is responsible for loading all necessary core files such as Helper.php, Database.php, components.php.
    // It also handles starting the session.
    // Therefore, direct require_once calls for those files are not needed here.
    Helper::displayFlashMessage();
    ?>

</body>
</html>
