<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db-connect.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) {
    header('Location: login_page.php');
    exit();
}

include 'header.php';

// Sanitize output function to prevent XSS
function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Pagination setup
$limit = 10; // orders per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filtering and search parameters
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$filter_email = isset($_GET['filter_email']) ? $_GET['filter_email'] : '';
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Build WHERE clause for filters and search
$where_clauses = [];
$params = [];

if ($filter_date) {
    $where_clauses[] = "DATE(payment_date) = ?";
    $params[] = $filter_date;
}
if ($filter_status) {
    $where_clauses[] = "payment_status = ?";
    $params[] = $filter_status;
}
if ($filter_email) {
    $where_clauses[] = "user_email LIKE ?";
    $params[] = "%$filter_email%";
}
if ($search_term) {
    $where_clauses[] = "(transaction_id LIKE ? OR user_email LIKE ?)";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Prepare total count query for pagination
$count_query = "SELECT COUNT(*) as total FROM `payments` $where_sql";
$stmt_count = $conn->prepare($count_query);
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_orders = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_orders / $limit);

// Prepare main query with limit and offset
$query = "SELECT * FROM `payments` $where_sql ORDER BY payment_date DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
if ($params) {
    $types = str_repeat('s', count($params)) . 'ii';
    // bind_param requires variables passed by reference, so create an array of references
    $bind_names[] = $types;
    foreach ($params as $key => $value) {
        $bind_name = 'bind' . $key;
        $$bind_name = $value;
        $bind_names[] = &$$bind_name;
    }
    $bind_names[] = &$limit;
    $bind_names[] = &$offset;
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

?>
<style type="text/css">
	.table{
		width: 100%;
		margin: 2.5rem auto;
		border-collapse: collapse;
	}

	td,th{
		text-align: center;
		padding: 10px;
		border: 1px solid #ddd;
	}

	.badge-confirmed {
		background-color: #28a745;
		color: white;
		padding: 5px 10px;
		border-radius: 5px;
	}

	.badge-pending {
		background-color: #dc3545;
		color: white;
		padding: 5px 10px;
		border-radius: 5px;
	}

	.btn {
		padding: 6px 12px;
		border-radius: 4px;
		font-size: 14px;
	}

	.btn-primary {
		background-color: #007bff;
		color: white;
		border: none;
	}

	.btn-secondary {
		background-color: #6c757d;
		color: white;
		border: none;
	}

	.btn-success {
		background-color: #28a745;
		color: white;
		border: none;
	}

	.btn-outline-primary {
		color: #007bff;
		border: 1px solid #007bff;
		background-color: transparent;
	}

	.btn-outline-primary:hover {
		background-color: #007bff;
		color: white;
	}

	@media (max-width: 768px) {
		.table {
			width: 100%;
			font-size: 12px;
		}
	}
</style>

<div class="container my-5">
	<?php if (isset($_SESSION['confirm_payment_message'])): ?>
		<div class="alert alert-info text-center" role="alert">
			<?php
				echo sanitize_output($_SESSION['confirm_payment_message']);
				unset($_SESSION['confirm_payment_message']);
			?>
		</div>
	<?php endif; ?>

	<!-- Filters and Search -->
	<form method="GET" class="mb-3 d-flex flex-wrap justify-content-center gap-2">
		<input type="date" name="filter_date" value="<?php echo sanitize_output($filter_date); ?>" class="form-control" placeholder="Filter by Date">
		<select name="filter_status" class="form-control">
			<option value="">All Statuses</option>
			<option value="Pending" <?php if ($filter_status === 'Pending') echo 'selected'; ?>>Pending</option>
			<option value="Confirmed" <?php if ($filter_status === 'Confirmed') echo 'selected'; ?>>Confirmed</option>
		</select>
		<input type="text" name="filter_email" value="<?php echo sanitize_output($filter_email); ?>" class="form-control" placeholder="Filter by Buyer Email">
		<input type="text" name="search_term" value="<?php echo sanitize_output($search_term); ?>" class="form-control" placeholder="Search Transaction ID or Email">
		<button type="submit" class="btn btn-primary">Apply</button>
		<a href="orders.php" class="btn btn-secondary">Reset</a>
		<a href="export_orders.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success ms-2">Export CSV</a>
	</form>

	<table class="table table-hover table-stripped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Date</th>
				<th scope="col">Buyer</th>
				<th scope="col">Amount</th>
				<th scope="col">Transaction ID</th>
				<th scope="col">Status</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$i = $offset;
			while ($data = $result->fetch_assoc()): ?>
				<tr>
					<th scope="row"><?php echo ++$i; ?></th>

					<td><?php echo sanitize_output($data["payment_date"]); ?></td>
					
					<td><?php echo sanitize_output($data["user_email"]); ?></td>

					<td><?php echo sanitize_output($data["amount"]); ?> Tk</td>

					<td><?php echo sanitize_output($data["transaction_id"]); ?></td>
					
					<td>
						<?php 
						$status = strtolower(trim($data["payment_status"]));
						// Debug log payment status
						file_put_contents(__DIR__ . '/../logs/payment_status_debug.log', date('Y-m-d H:i:s') . " Payment ID: " . $data['id'] . " Status: " . $data["payment_status"] . "\n", FILE_APPEND);
						if ($status === 'completed'): ?>
							<span class="badge-confirmed">Confirmed</span>
						<?php else: ?>
							<span class="badge-pending">Pending</span>
						<?php endif; ?>
					</td>

					<td>
						<button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-primary" onclick="document.getElementById('bookList').setAttribute('src','./book_list.php?t=<?php echo sanitize_output($data["book_ids"]); ?>')">View book list</button>
						<?php if ($data["payment_status"] === 'Pending'): ?>
							<form method="POST" action="confirm_payment.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to confirm this payment?');">
								<input type="hidden" name="transaction_id" value="<?php echo sanitize_output($data["transaction_id"]); ?>">
								<input type="hidden" name="user_order_id" value="<?php echo sanitize_output($data["id"]); ?>">
								<button type="submit" class="btn btn-success btn-sm ms-2">Confirm Payment</button>
							</form>
						<?php endif; ?>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>

	<!-- Pagination -->
	<nav aria-label="Page navigation example" class="d-flex justify-content-center">
		<ul class="pagination">
			<?php if ($page > 1): ?>
				<li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Previous</a></li>
			<?php endif; ?>
			<?php for ($p = 1; $p <= $total_pages; $p++): ?>
				<li class="page-item <?php if ($p == $page) echo 'active'; ?>">
					<a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $p])); ?>"><?php echo $p; ?></a>
				</li>
			<?php endfor; ?>
			<?php if ($page < $total_pages): ?>
				<li class="page-item"><a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a></li>
			<?php endif; ?>
		</ul>
	</nav>
</div>

<!-- book list modal -->
<div class="modal modal-lg fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Books Purchased</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div id="loadingIndicator" style="display:none; text-align:center; margin-bottom:10px;">Loading...</div>
				<iframe id="bookList" style="width: 100%; height: 400px;" onload="document.getElementById('loadingIndicator').style.display='none';"></iframe>
			</div>
		</div>
	</div>
</div>
<!-- book list modal -->

<script>
	document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
		button.addEventListener('click', () => {
			document.getElementById('loadingIndicator').style.display = 'block';
		});
	});
</script>

<?php include ("footer.php"); ?>
