<?php
include("header.php");
require_once("./admin/db-connect.php");

session_start();
if (!isset($_SESSION["email"])) {
    echo '<script type="text/javascript">
        alert("Login first!");
        window.location.assign("login_page.php");
    </script>';
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<script type="text/javascript">
        alert("Invalid order ID.");
        window.location.assign("order_history.php");
    </script>';
    exit();
}

$order_id = (int)$_GET['id'];
$user_email = $_SESSION["email"];

// Fetch order details
$orderQuery = "SELECT o.id as order_id, o.date as order_date, ob.book_id, ab.name as book_name, ab.price, COUNT(ob.book_id) as quantity
               FROM orders o
               JOIN order_books ob ON o.id = ob.order_id
               JOIN all_books ab ON ob.book_id = ab.id
               WHERE o.id = ? AND o.user_email = ?
               GROUP BY ob.book_id, ab.name, ab.price, o.id, o.date";

$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("is", $order_id, $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script type="text/javascript">
        alert("Order not found.");
        window.location.assign("order_history.php");
    </script>';
    exit();
}

$orderData = [];
$orderDate = "";
while ($row = $result->fetch_assoc()) {
    $orderDate = $row['order_date'];
    $orderData[] = $row;
}

$total_price = 0;
?>

<div class="container my-5">
    <h1 class="text-center mb-4"><u>Order Details</u></h1>
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id); ?></p>
    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($orderDate); ?></p>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Book Name</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($orderData as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total_price += $subtotal;
            ?>
            <tr>
                <th scope="row"><?php echo ++$i; ?></th>
                <td><?php echo htmlspecialchars($item['book_name']); ?></td>
                <td><?php echo $item['price']; ?> TK</td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $subtotal; ?> TK</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="row">
        <div class="col-8"></div>
        <div class="col-4 fs-4 text-end">
            <strong>Total Price: </strong> <?php echo $total_price; ?> TK
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
