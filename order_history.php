<?php
include("header.php");
require_once("includes/Cart.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["email"])) {
    echo '<script type="text/javascript">
        alert("Login first!");
        window.location.assign("login_page.php");
    </script>';
    exit();
}

$userEmail = $_SESSION["email"];
$cart = new Cart();
$orders = $cart->getOrderHistory($userEmail);
?>

<div class="container my-5">
    <h1 class="text-center mb-4"><u>Your Order History</u></h1>

    <?php if (empty($orders)): ?>
        <p class="text-center">You have no past orders.</p>
    <?php else: ?>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['amount']); ?> TK</td>
                        <td><?php echo date('M d, Y', strtotime($order['payment_date'])); ?></td>
                        <td><?php echo !empty($order['transaction_id']) ? htmlspecialchars($order['transaction_id']) : 'N/A'; ?></td>
                        <td>
                            <?php 
                                $status = isset($order['payment_status']) ? $order['payment_status'] : 'Completed';
                                $badgeClass = 'bg-success';
                                if ($status == 'Pending') {
                                    $badgeClass = 'bg-warning text-dark';
                                } else if ($status == 'Failed') {
                                    $badgeClass = 'bg-danger';
                                }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                        </td>
                        <td><a href="order_details.php?id=<?php echo urlencode($order['id']); ?>" class="btn btn-primary btn-sm">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include("footer.php"); ?>
