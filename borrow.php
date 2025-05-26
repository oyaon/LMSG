<?php
require_once 'includes/init.php';
require_once 'includes/Borrow.php';

//session_start();

if (!isset($_GET['t'])) {
    echo '<script type="text/javascript">window.history.go(-1);</script>';
    exit;
}

$bookId = (int)$_GET['t'];
$userEmail = $_SESSION['email'];

$borrow = new Borrow();
$result = $borrow->requestBorrow($userEmail, $bookId);

if ($result === true) {
    echo '<script type="text/javascript">
            alert("Borrow request submitted successfully");
            window.location.assign("all-books.php");
          </script>';
} else {
    echo '<script type="text/javascript">
            alert("' . htmlspecialchars($result, ENT_QUOTES) . '");
            window.history.go(-1);
          </script>';
}
?>
