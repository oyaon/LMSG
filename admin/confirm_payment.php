<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../admin/db-connect.php'); // Adjust path as needed

function confirmPayment($transactionID, $userOrderID) {
    // Check if admin is logged in and authorized
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) {
        return ['success' => false, 'message' => 'Unauthorized: Admin access required.'];
    }

    global $conn;

    // Input validation
    if (empty($transactionID) || !is_string($transactionID)) {
        return ['success' => false, 'message' => 'Invalid Transaction ID.'];
    }
    if (empty($userOrderID) || !is_numeric($userOrderID)) {
        return ['success' => false, 'message' => 'Invalid User Order ID.'];
    }
    $userOrderID = (int)$userOrderID;

    // Verify if the Transaction ID exists in payment records
    $stmt = $conn->prepare("SELECT * FROM payments WHERE transaction_id = ?");
    $stmt->bind_param("s", $transactionID);
    $stmt->execute();
    $paymentResult = $stmt->get_result();
    if ($paymentResult->num_rows === 0) {
        return ['success' => false, 'message' => 'Transaction ID is invalid or does not exist.'];
    }

    // Check for duplicate transaction IDs
    $stmtCount = $conn->prepare("SELECT COUNT(*) as count FROM payments WHERE transaction_id = ?");
    $stmtCount->bind_param("s", $transactionID);
    $stmtCount->execute();
    $countResult = $stmtCount->get_result();
    $countRow = $countResult->fetch_assoc();
    if ($countRow['count'] > 1) {
        return ['success' => false, 'message' => 'Duplicate Transaction IDs found. Cannot confirm payment. Please contact support.'];
    }

    $payment = $paymentResult->fetch_assoc();

    // Check payment status
    if ($payment['payment_status'] === 'Completed') {
        return ['success' => false, 'message' => 'Payment is already confirmed.'];
    }

    // Fetch corresponding user order details using User Order ID
    $stmtOrder = $conn->prepare("SELECT * FROM payments WHERE id = ?");
    $stmtOrder->bind_param("i", $userOrderID);
    $stmtOrder->execute();
    $orderResult = $stmtOrder->get_result();
    if ($orderResult->num_rows === 0) {
        return ['success' => false, 'message' => 'User Order ID does not exist.'];
    }
    $order = $orderResult->fetch_assoc();

    // Check if User Order ID matches the Transaction ID
    if ($order['transaction_id'] !== $transactionID) {
        return ['success' => false, 'message' => 'User Order ID does not match the Transaction ID.'];
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update payment status to Completed
        $stmtUpdatePayment = $conn->prepare("UPDATE payments SET payment_status = 'Completed' WHERE transaction_id = ?");
        $stmtUpdatePayment->bind_param("s", $transactionID);
        $stmtUpdatePayment->execute();

        // Update order status in user's order history (assuming orders table or payments table has order status)
        // Here assuming payments table has order_status column, if not adjust accordingly
        // Removed update of order_status column as it does not exist in payments table
        // $stmtUpdateOrder = $conn->prepare("UPDATE payments SET order_status = 'Payment Confirmed' WHERE id = ?");
        // $stmtUpdateOrder->bind_param("i", $userOrderID);
        // $stmtUpdateOrder->execute();

        // Commit transaction
        $conn->commit();

        // Logging the confirmation action
        $adminUserID = $_SESSION['user_id'];
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "Admin ID: $adminUserID confirmed payment. Transaction ID: $transactionID, User Order ID: $userOrderID at $timestamp\n";
        file_put_contents(__DIR__ . '/../logs/payment_confirmations.log', $logMessage, FILE_APPEND);

        return ['success' => true, 'message' => 'Payment confirmed and order status updated successfully.'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['success' => false, 'message' => 'Error confirming payment: ' . $e->getMessage()];
    }
}

// Handle POST request from confirm payment form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['transaction_id']) || !isset($_POST['user_order_id'])) {
        $_SESSION['confirm_payment_message'] = 'Missing required parameters.';
        header('Location: orders.php');
        exit();
    }

    $transactionID = $_POST['transaction_id'];
    $userOrderID = $_POST['user_order_id'];

    $result = confirmPayment($transactionID, $userOrderID);

    $_SESSION['confirm_payment_message'] = $result['message'];

    header('Location: orders.php');
    exit();
}
?>
