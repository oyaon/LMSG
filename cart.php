<?php 
require_once 'includes/init.php';
require_once 'includes/Cart.php';
include ("header.php"); 

if (!isset($_SESSION["email"])) {
    echo '<script type="text/javascript">
        alert("Login first!");
        window.location.assign("login_page.php");
    </script>';
    exit();
}

$user_email = $_SESSION["email"];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;

// Get recent orders for the user
$cart = new Cart();
$recentOrders = $cart->getOrderHistory($user_email);
$recentOrders = array_slice($recentOrders, 0, 3); // Get only the 3 most recent orders

// Count total items
$countQuery = "SELECT COUNT(*) as total FROM cart WHERE user_email=?";
$countResultStmt = $db->query($countQuery, "s", [$user_email]);
$total = 0;
if ($countResultStmt) {
    $result = $countResultStmt->get_result();
    $row = $result->fetch_assoc();
    $total = (int)$row['total'];
    $countResultStmt->close();
}
$totalPages = ceil($total / $perPage);

// Fetch paginated results
$offset = ($page - 1) * $perPage;
$q = "SELECT all_books.id as book_id, all_books.name, all_books.price, all_books.quantity as book_quantity, cart.book_id, cart.id as cart_id, cart.date, cart.user_email, cart.status FROM `all_books` INNER JOIN `cart` ON all_books.id=cart.book_id AND cart.user_email=? AND cart.status=0 LIMIT ? OFFSET ?";
$resultStmt = $db->query($q, "sii", [$user_email, $perPage, $offset]);

$total_price = 0;
$ids = "";
?>

<style type="text/css">
	.table {
		width: 100%;
		max-width: 900px;
		margin: 2.5rem auto;
	}

	td, th {
		text-align: center;
		vertical-align: middle;
	}

	@media (max-width: 768px) {
		.table {
			font-size: 0.9rem;
		}

		.table img {
			width: 64px;
		}
	}
</style>

<div class="container my-5">
	<h1 class="text-center mb-4"><u>Your Cart</u></h1>
	
	<?php if (!empty($recentOrders)): ?>
	<div class="card mb-4">
		<div class="card-header bg-primary text-white">
			<h5 class="mb-0">Recent Orders & Payment Status</h5>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead class="table-light">
						<tr>
							<th>Order ID</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Payment Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($recentOrders as $order): ?>
							<tr>
								<td>#<?php echo $order['id']; ?></td>
								<td><?php echo date('M d, Y', strtotime($order['payment_date'])); ?></td>
								<td><?php echo $order['amount']; ?> TK</td>
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
								<td>
									<a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="text-end mt-2">
				<a href="order_history.php" class="btn btn-outline-primary btn-sm">View All Orders</a>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<table class="table table-hover table-stripped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Date</th>
				<th scope="col">Image</th>
				<th scope="col">Book name</th>
				<th scope="col">Price</th>
				<th scope="col">Quantity</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$i = 0;
			if ($resultStmt) {
                $result = $resultStmt->get_result();
				while ($data = $result->fetch_array()) {
					$ids .= $data['book_id'] . ",";
			?>
					<tr>
						<th scope="row"><?php echo ++$i; ?></th>

						<td><?php echo $data["date"]; ?></td>

						<td>
							<img width="96px" src="./images/books1.png" alt="book_img">
						</td>

						<td><?php echo $data["name"]; ?></td>

						<td><?php echo $data["price"]; ?>/=</td>

						<td>
							<form method="POST" action="update_cart_quantity.php" class="d-inline">
								<input type="hidden" name="cart_id" value="<?php echo $data['cart_id']; ?>">
								<input type="number" name="quantity" value="1" min="1" max="<?php echo $data['book_quantity']; ?>" style="width: 60px;">
								<button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
							</form>
						</td>

						<td>
							<a class="btn btn-outline-danger" href="delete_cart.php?t=<?php echo $data["cart_id"]; ?>&b=<?php echo $data["book_id"]; ?>&q=<?php echo $data["book_quantity"]; ?>">Remove</a>
						</td>
					</tr>
			<?php
					$total_price += $data["price"];
				}
                $resultStmt->close();
			}
			?>
		</tbody>
	</table>

	<!-- Calculation -->
	<?php if ($total > 0): ?>
		<!-- divider -->
		<div class="row">
			<div class="col-8"></div>
			<div class="col-sm-12 col-md-4">
				<hr class="border border-danger border-3 opacity-50 w-100">
			</div>
		</div>
		<!-- divider -->

		<div class="row mb-2">
			<div class="col-sm-12 col-md-8"></div>
			<div class="col-sm-12 col-md-4">
				<div class="row">
					<div class="col-6 fs-4">Total</div>
					<div class="col-6 fs-4"><?php echo $total_price; ?> TK</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-8"></div>
			<div class="col-sm-12 col-md-4">
				<div class="row">
					<div class="col-12 text-center fs-4">
						<form method="POST" action="payment.php">
							<input type="hidden" name="ids" value="<?php echo $ids; ?>">
							<input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

							<button type="submit" class="btn bg-danger-subtle mt-2 rounded-pill">Proceed to Checkout</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<nav aria-label="Page navigation">
		<ul class="pagination justify-content-center">
			<?php if ($page > 1): ?>
				<li class="page-item">
					<a href="?page=<?php echo $page - 1; ?>" class="page-link">Previous</a>
				</li>
			<?php endif; ?>

			<?php for ($i = 1; $i <= $totalPages; $i++): ?>
				<li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
					<a href="?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
				</li>
			<?php endfor; ?>

			<?php if ($page < $totalPages): ?>
				<li class="page-item">
					<a href="?page=<?php echo $page + 1; ?>" class="page-link">Next</a>
				</li>
			<?php endif; ?>
		</ul>
	</nav>
</div>

<?php include ("footer.php"); ?>
