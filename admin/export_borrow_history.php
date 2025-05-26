<?php
require_once("db-connect.php");

// Set headers to force download of CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=borrow_history_report.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('#', 'Issuer', 'Book name', 'Issued date', 'Return date', 'Remaining days', 'Fine', 'Status'));

// Fetch borrow history data
$query = "SELECT borrow_history.id as id, user_email, all_books.name, issue_date FROM borrow_history INNER JOIN all_books ON borrow_history.book_id=all_books.id ORDER BY FIELD(borrow_history.status, 'Requested', 'Issued', 'Returned') ASC, borrow_history.issue_date DESC";
$result = $conn->query($query);

$i = 0;
while ($row = $result->fetch_assoc()) {
    $i++;

    // Calculate return date (7 days after issue_date)
    $return_date = '';
    if (!empty($row['issue_date'])) {
        $return_date = date('Y-m-d', strtotime($row['issue_date'] . ' +7 days'));
    }

    // Calculate remaining days
    $remaining_days = 0;
    if (!empty($row['issue_date'])) {
        $rem_days = strtotime($return_date) - strtotime(date('Y-m-d'));
        $remaining_days = ($rem_days > 0) ? round($rem_days / 86400) : 0;
    }

    // Calculate fine
    $fine = 0;
    if (!empty($row['issue_date'])) {
        $rem_days = strtotime($return_date) - strtotime(date('Y-m-d'));
        $fine = ($rem_days < 0) ? (abs($rem_days) + 1) * 2.5 : 0;
    }

    // Fetch status for this borrow_history id
    $status_query = "SELECT status FROM borrow_history WHERE id = " . intval($row['id']);
    $status_result = $conn->query($status_query);
    $status = '';
    if ($status_result && $status_row = $status_result->fetch_assoc()) {
        $status = $status_row['status'];
    }

    // Output row data
    fputcsv($output, array(
        $i,
        $row['user_email'],
        $row['name'],
        $row['issue_date'],
        $return_date,
        $remaining_days . ' days',
        $fine . ' Tk',
        $status
    ));
}

fclose($output);
exit;
?>
