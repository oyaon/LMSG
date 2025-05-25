<?php
include("header.php");
require_once 'includes/init.php';
require_once 'includes/Cart.php';

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

// Get payment details
$paymentQuery = "SELECT * FROM payments WHERE id = ? AND user_email = ?";
$stmt = $db->query($paymentQuery, "is", [$order_id, $user_email]);
if (!$stmt) {
    echo '<script type="text/javascript">
        alert("Order not found.");
        window.location.assign("order_history.php");
    </script>';
    exit();
}
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script type="text/javascript">
        alert("Order not found.");
        window.location.assign("order_history.php");
    </script>';
    exit();
}

$payment = $result->fetch_assoc();
$stmt->close();

// Get book details
$bookIds = explode(',', $payment['book_ids']);
$bookData = [];
$total_price = 0;

foreach ($bookIds as $bookId) {
    if (empty($bookId)) continue;
    
    $bookQuery = "SELECT id, name, price FROM all_books WHERE id = ?";
    $stmt = $db->query($bookQuery, "i", [(int)$bookId]);
    if ($stmt) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $book = $result->fetch_assoc();
            
            // Count occurrences of this book ID
            $count = 0;
            foreach ($bookIds as $id) {
                if ($id == $bookId) {
                    $count++;
                }
            }
            
            // If this book ID hasn't been processed yet
            if (!isset($bookData[$bookId])) {
                $book['quantity'] = $count;
                $book['subtotal'] = $book['price'] * $count;
                $bookData[$bookId] = $book;
                $total_price += $book['subtotal'];
            }
        }
        $stmt->close();
    }
}
?>

<div class="container my-5">
    <h1 class="text-center mb-4"><u>Order Details</u></h1>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Order Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($payment['id']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('F d, Y', strtotime($payment['payment_date'])); ?></p>
                    <p><strong>Total Amount:</strong> <?php echo htmlspecialchars($payment['amount']); ?> TK</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Transaction ID:</strong> <?php echo !empty($payment['transaction_id']) ? htmlspecialchars($payment['transaction_id']) : 'N/A'; ?></p>
                    <?php 
                        $status = isset($payment['payment_status']) ? $payment['payment_status'] : 'Completed';
                        $badgeClass = 'bg-success';
                        if ($status == 'Pending') {
                            $badgeClass = 'bg-warning text-dark';
                        } else if ($status == 'Failed') {
                            $badgeClass = 'bg-danger';
                        }
                    ?>
                    <p><strong>Payment Status:</strong> <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Purchased Books</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book ID</th>
                            <th scope="col">Book Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($bookData as $book) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo ++$i; ?></th>
                            <td><?php echo htmlspecialchars($book['id']); ?></td>
                            <td><?php echo htmlspecialchars($book['name']); ?></td>
                            <td><?php echo $book['price']; ?> TK</td>
                            <td><?php echo $book['quantity']; ?></td>
                            <td><?php echo $book['subtotal']; ?> TK</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total:</strong></td>
                            <td><strong><?php echo $total_price; ?> TK</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="order_history.php" class="btn btn-primary">Back to Order History</a>
        <a href="cart.php" class="btn btn-outline-secondary">Back to Cart</a>
    </div>
</div>

<?php include("footer.php"); ?>
