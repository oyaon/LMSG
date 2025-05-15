<?php
header('Content-Type: application/json');
include("admin/db-connect.php");

try {
    $stmt = $conn->prepare("SELECT * FROM special_offer WHERE end_date > NOW()");
    $stmt->execute();
    $result = $stmt->get_result();

    $offers = [];
    while ($row = $result->fetch_assoc()) {
        $offers[] = [
            'id' => $row['id'],
            'header_top' => $row['header_top'],
            'header' => $row['header'],
            'header_bottom' => $row['header_bottom'],
            'image_path' => !empty($row['image_path']) ? $row['image_path'] : 'images/placeholder-offer.jpg',
            'highlight' => !empty($row['highlight']),
            'end_date' => $row['end_date']
        ];
    }
    echo json_encode(['success' => true, 'offers' => $offers]);
} catch (Exception $e) {
    error_log("Special offers data error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Unable to load offers.']);
}
?>
