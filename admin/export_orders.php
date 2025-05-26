<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db-connect.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Unauthorized access';
    exit();
}

// Helper function to sanitize output for CSV
function sanitize_csv($value) {
    $value = str_replace('"', '""', $value);
    return '"' . $value . '"';
}

// Determine which type of export is requested: orders, borrow history, donation entry, fines
$type = isset($_GET['type']) ? $_GET['type'] : 'orders';

// Common filters for orders export
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $type . '_export_' . date('Ymd_His') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

switch ($type) {
    case 'orders':
        // Build WHERE clause for filters and search
        $where_clauses = [];
        $params = [];
        $types = '';

        if ($filter_date) {
            $where_clauses[] = "DATE(payment_date) = ?";
            $params[] = $filter_date;
            $types .= 's';
        }
        if ($filter_status) {
            $where_clauses[] = "payment_status = ?";
            $params[] = $filter_status;
            $types .= 's';
        }
        if ($filter_email) {
            $where_clauses[] = "user_email LIKE ?";
            $params[] = "%$filter_email%";
            $types .= 's';
        }
        if ($search_term) {
            $where_clauses[] = "(transaction_id LIKE ? OR user_email LIKE ?)";
            $params[] = "%$search_term%";
            $params[] = "%$search_term%";
            $types .= 'ss';
        }

        $where_sql = '';
        if (count($where_clauses) > 0) {
            $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
        }

        $query = "SELECT payment_date, user_email, amount, transaction_id, payment_status FROM payments $where_sql ORDER BY payment_date DESC";
        $stmt = $conn->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        // Output CSV header
        fputcsv($output, ['Payment Date', 'User Email', 'Amount', 'Transaction ID', 'Payment Status']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['payment_date'],
                $row['user_email'],
                $row['amount'],
                $row['transaction_id'],
                $row['payment_status'],
            ]);
        }
        break;

    case 'borrow_history':
        // Assuming a borrow_history table with relevant columns
        $query = "SELECT borrow_date, return_date, user_email, book_title, status FROM borrow_history ORDER BY borrow_date DESC";
        $result = $conn->query($query);

        fputcsv($output, ['Borrow Date', 'Return Date', 'User Email', 'Book Title', 'Status']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['borrow_date'],
                $row['return_date'],
                $row['user_email'],
                $row['book_title'],
                $row['status'],
            ]);
        }
        break;

    case 'donation_entry':
        // Assuming a donations table with relevant columns
        $query = "SELECT donation_date, donor_name, amount, message FROM donations ORDER BY donation_date DESC";
        $result = $conn->query($query);

        fputcsv($output, ['Donation Date', 'Donor Name', 'Amount', 'Message']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['donation_date'],
                $row['donor_name'],
                $row['amount'],
                $row['message'],
            ]);
        }
        break;

    case 'fines':
        // Assuming a fines table with relevant columns
        $query = "SELECT fine_date, user_email, amount, reason, status FROM fines ORDER BY fine_date DESC";
        $result = $conn->query($query);

        fputcsv($output, ['Fine Date', 'User Email', 'Amount', 'Reason', 'Status']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['fine_date'],
                $row['user_email'],
                $row['amount'],
                $row['reason'],
                $row['status'],
            ]);
        }
        break;

    default:
        // Invalid type
        header('HTTP/1.1 400 Bad Request');
        echo 'Invalid export type specified.';
        exit();
}

fclose($output);
exit();
?>
