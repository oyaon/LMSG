<?php
require_once 'includes/init.php';

if (isset($_GET['a'])) {
    $action = $_GET['a'];
    $transactionId = isset($_GET['t']) ? (int)$_GET['t'] : 0;
    $bookId = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

    switch ($action) {
        case 'reissue':
            $stmt = $db->getConnection()->prepare("UPDATE `borrow_history` SET `status` = ? WHERE `id` = ?");
            $status = 'Requested';
            $stmt->bind_param('si', $status, $transactionId);
            if ($stmt->execute()) {
                echo '<script>alert("Re-issued successfully"); window.history.go(-1);</script>';
            }
            $stmt->close();
            break;

        case 'delete':
            $stmt = $db->getConnection()->prepare("DELETE FROM `borrow_history` WHERE `id` = ?");
            $stmt->bind_param('i', $transactionId);
            if ($stmt->execute()) {
                $stmt->close();

                $updateStmt = $db->getConnection()->prepare("UPDATE `all_books` SET `quantity` = `quantity` + 1 WHERE `id` = ?");
                $updateStmt->bind_param('i', $bookId);
                if ($updateStmt->execute()) {
                    echo '<script>alert("Request deleted successfully"); window.history.go(-1);</script>';
                }
                $updateStmt->close();
            }
            break;

        default:
            echo 'Invalid action.';
            break;
    }
} else {
    echo 'Bad request. Error: 101';
    exit();
}
?>
