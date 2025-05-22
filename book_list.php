<?php include ("header.php"); ?>
<?php require_once 'includes/init.php'; ?>

<style type="text/css">
	.table{
		width: 700px;
		margin: 2.5rem auto;
	}

	td,th{
		text-align: center;
	}
</style>

<table class="table table-hover table-stripped">
	<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col">Image</th>
			<th scope="col">Book name</th>
			<th scope="col">Price</th>
			<th scope="col">Quantity</th>
		</tr>
	</thead>

	<tbody>
		<?php
		$user_email = $_SESSION["email"];
		$ids = $_GET['t'];
		// Sanitize and prepare placeholders for IN clause
		$idArray = array_filter(array_map('intval', explode(',', $ids)));
		if (count($idArray) > 0) {
			$placeholders = implode(',', array_fill(0, count($idArray), '?'));
			$q = "SELECT id, name, price FROM `all_books` WHERE `id` IN ($placeholders);";
			$stmt = $db->query($q, str_repeat('i', count($idArray)), $idArray);
			if ($stmt) {
				$result = $stmt->get_result();
				$i = 0;

				$freq = [];
				foreach($idArray as $k){
					$freq[$k] = 0;
				}
				foreach($idArray as $k){
					$freq[$k]++;
				}

				while ($data = $result->fetch_array()): ?>
					<tr>
						<th scope="row"><?php echo ++$i; ?></th>

						<td>
							<img width="96px" src="./images/books1.png" alt="book_img">
						</td>

						<td><?php echo $data["name"]; ?></td>

						<td><?php echo $data["price"]; ?>/=</td>

						<td><?php echo $freq[$data["id"]]; ?></td>
					</tr>
				<?php endwhile;
				$stmt->close();
			}
		}
		?>
	</tbody>
</table>
