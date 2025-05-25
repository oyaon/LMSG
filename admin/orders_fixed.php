<?php include ("header.php"); ?>
<?php require_once("db-connect.php"); ?>

<style type="text/css">
	.table{
		width: 700px;
		margin: 2.5rem auto;
	}

	td,th{
		text-align: center;
	}
</style>

<div class="container my-5">
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
			$q = "SELECT * FROM `payments` ORDER BY payment_date DESC;";
			$result = $conn->query($q);
			$i = 0;
			while ($data = $result->fetch_array()): ?>
				<tr>
					<th scope="row"><?php echo ++$i; ?></th>

					<td><?php echo $data["payment_date"]; ?></td>
					
					<td><?php echo $data["user_email"]; ?></td>

					<td><?php echo $data["amount"]; ?> Tk</td>

					<td><?php echo $data["transaction_id"]; ?></td>
					
					<td><?php echo $data["payment_status"]; ?></td>

					<td>
						<button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-primary" onclick="document.getElementById('bookList').setAttribute('src','./book_list.php?t=<?php echo $data["book_ids"]; ?>')">View book list</button>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
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
				<iframe id="bookList" style="width: 100%; height: 400px;"></iframe>
			</div>
		</div>
	</div>
</div>
<!-- book list modal -->

<?php include ("footer.php"); ?>