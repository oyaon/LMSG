<?php
include 'db-connect.php';

// Get year parameter or default to current year
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Prepare data array for CSV
$entry_n_fines = [];

// Fetch entry fee data filtered by year
$sql = "SELECT `entry_fee_stat`, `entry_fee_date` FROM `users` WHERE `entry_fee_stat`=1 AND YEAR(`entry_fee_date`) = $selected_year ORDER BY `entry_fee_date` ASC;";
$result = $conn->query($sql);
$x = [];
while ($data = $result->fetch_array()) {
    $date = explode("-", $data["entry_fee_date"]);
    $time = date("F", mktime(0, 0, 0, (int)$date[1])) . ", $date[0]";
    $entry_n_fines[$time] = [
        "entry" => 0,
        "fine" => 0
    ];
    $x[] = $data;
}
foreach ($x as $k) {
    $date = explode("-", $k["entry_fee_date"]);
    $time = date("F", mktime(0, 0, 0, (int)$date[1])) . ", $date[0]";
    $entry_n_fines[$time]["entry"]++;
}

// Fetch fine data filtered by year
$sql = "SELECT `issue_date`, `fine` FROM `borrow_history` WHERE NOT `fine`=0 AND YEAR(`issue_date`) = $selected_year ORDER BY `issue_date` ASC;";
$result = $conn->query($sql);
$x = [];
while ($data = $result->fetch_array()) {
    $x[] = $data;
}
foreach ($x as $k) {
    $date = explode("-", $k["issue_date"]);
    $time = date("F", mktime(0, 0, 0, (int)$date[1])) . ", $date[0]";
    if (!isset($entry_n_fines[$time])) {
        $entry_n_fines[$time] = ["entry" => 0, "fine" => 0];
    }
    $entry_n_fines[$time]["fine"] += $k["fine"];
}

// Prepare CSV output
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="entry_and_fines_' . $selected_year . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['#', 'Duration', 'Entry Fee (Tk)', 'Fine (Tk)']);

$total_entries = 0;
$total_fine = 0;
$i = 0;

foreach ($entry_n_fines as $time => $values) {
    $i++;
    $entry_fee = $values['entry'] * 100;
    $fine = $values['fine'];
    fputcsv($output, [$i, $time, $entry_fee, $fine]);
    $total_entries += $entry_fee;
    $total_fine += $fine;
}

// Write totals row
fputcsv($output, ['', 'Total', $total_entries, $total_fine]);

fclose($output);
exit;
?>
