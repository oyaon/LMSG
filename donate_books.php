<?php
session_start();
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || !Helper::validateCsrfToken($_POST['csrf_token'], 'donate_books_form')) {
        Helper::setFlashMessage('error', 'Invalid CSRF token. Please refresh the page and try again.');
        header("Location: donate.php");
        exit();
    }

    $donor_name = isset($_POST['donor_name']) ? trim($_POST['donor_name']) : '';
    $donor_email = isset($_POST['donor_email']) ? trim($_POST['donor_email']) : '';
    $book_ids = isset($_POST['book_ids']) ? $_POST['book_ids'] : [];
    $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

    if (empty($donor_name) || empty($donor_email)) {
        Helper::setFlashMessage('error', 'Please provide your name and email.');
        header("Location: donate.php");
        exit();
    }

    if (empty($book_ids) || empty($quantities) || count($book_ids) !== count($quantities)) {
        Helper::setFlashMessage('error', 'Please select books and specify quantities correctly.');
        header("Location: donate.php");
        exit();
    }

    $db = Database::getInstance();
    $conn = $db->getConnection();

    $allValid = true;
    $stmt = $conn->prepare("INSERT INTO book_donations (user_email, book_id, quantity, donation_date, status) VALUES (?, ?, ?, CURDATE(), 'Pending')");

    for ($i = 0; $i < count($book_ids); $i++) {
        $book_id = intval($book_ids[$i]);
        $quantity = intval($quantities[$i]);

        if ($book_id <= 0 || $quantity <= 0) {
            $allValid = false;
            break;
        }

        $stmt->bind_param("sii", $donor_email, $book_id, $quantity);
        if (!$stmt->execute()) {
            $allValid = false;
            break;
        }
    }

    $stmt->close();

    if ($allValid) {
        Helper::setFlashMessage('success', 'Thank you for your book donation! We will contact you with further details.');
    } else {
        Helper::setFlashMessage('error', 'Failed to process your book donation. Please try again.');
    }

    header("Location: donate.php");
    exit();
} else {
    header("Location: donate.php");
    exit();
}
?>
