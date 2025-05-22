<?php
require_once 'includes/init.php';

$id = $_GET['t'];
$quantity = (int)$_GET['q'] + 1;
$book_id = $_GET['b'];

$deleteStmt = $db->query("DELETE FROM `cart` WHERE `id`=?", "i", [$id]);
if($deleteStmt){
    $updateStmt = $db->query("UPDATE `all_books` SET `quantity`=? WHERE `id`=?", "ii", [$quantity, $book_id]);
    if($updateStmt){
        header("Location: cart-page.php");
        exit();
    }
}
?>
