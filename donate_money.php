<?php
session_start();
require_once __DIR__ . '/includes/Database.php';
require_once __DIR__ . '/includes/Helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $donor_name = isset($_POST['donor_name']) ? trim($_POST['donor_name']) : '';
    $donor_email = isset($_POST['donor_email']) ? trim($_POST['donor_email']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $trans_id = isset($_POST['trans_id']) ? trim($_POST['trans_id']) : '';

    if (empty($donor_name) || empty($donor_email)) {
        Helper::setFlashMessage('error', 'Please provide your name and email.');
        header("Location: donate.php");
        exit();
    }

    if ($amount <= 0) {
        Helper::setFlashMessage('error', 'Please enter a valid donation amount.');
        header("Location: donate.php");
        exit();
    }

    if (empty($trans_id)) {
        Helper::setFlashMessage('error', 'Transaction ID is required.');
        header("Location: donate.php");
        exit();
    }

    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO money_donations (user_email, amount, donation_date, transaction_id, donation_status) VALUES (?, ?, CURDATE(), ?, 'Completed')");
    $stmt->bind_param("sds", $donor_email, $amount, $trans_id);

    if ($stmt->execute()) {
        Helper::setFlashMessage('success', 'Thank you for your generous donation!');
    } else {
        Helper::setFlashMessage('error', 'Failed to process your donation. Please try again.');
    }

    $stmt->close();
    header("Location: donate.php");
    exit();
} else {
    header("Location: donate.php");
    exit();
}
?>
