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
	<h1 class="text-center mb-4"><u>Borrow history</u></h1>

	<div class="text-center mb-3">
		<a href="export_borrow_history.php" class="btn btn-primary">Generate Report</a>
	</div>

	<table class="table table-hover table-stripped">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Issuer</th>
				<th scope="col">Book name</th>
				<th scope="col">Issued date</th>
				<th scope="col">Return date</th>
				<th scope="col">Remainnig days</th>
				<th scope="col">Fine</th>
				<th scope="col">Status</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
$q = "SELECT *, borrow_history.id as id, all_books.name FROM `borrow_history` INNER JOIN `all_books` ON borrow_history.book_id=all_books.id ";

if(isset($_GET['t'])){
    $bookId = (int)$_GET['t'];
    $stmt = $conn->prepare($q . " WHERE borrow_history.book_id = ? ORDER BY FIELD(borrow_history.status, 'Requested', 'Issued', 'Returned') ASC, borrow_history.issue_date DESC");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($q . " ORDER BY FIELD(borrow_history.status, 'Requested', 'Issued', 'Returned') ASC, borrow_history.issue_date DESC");
}
			$i = 0;
			while ($data = $result->fetch_array()): ?>
				<tr>
					<th scope="row"><?php echo ++$i; ?></th>

					<td>
						<a href="user_issue_history.php?u=<?php echo $data["user_email"]; ?>"><?php echo $data["user_email"]; ?></a>
					</td>

					<td><?php echo $data["name"]; ?></td>

					<td><?php echo $data["issue_date"]; ?></td>

					<td>
						<?php
							if(!empty($data["issue_date"])){
								$date = strtotime($data["issue_date"]);
								$date = strtotime("+7days", $date);
								echo date("Y-m-d", $date);
							}
						?>
					</td>

					<td>
						<?php
							$rem_days = 0;
							if(!empty($data["issue_date"])){
								$rem_days = $date - strtotime(date("Y-m-d"));
								$rem_days = round($rem_days/86400);
								echo ($rem_days>0) ? $rem_days : 0;
								echo " days";
							}
						?>
					</td>

					<td>
						<?php
							if($data["fine"] == 0){
								$fine = ($rem_days<0) ? (abs($rem_days)+1)*2.5 : 0;
							}
							else $fine=0;

							echo $fine;
						?> Tk
					</td>

					<td>
						<?php echo $data["status"]; ?>
					</td>

					<td>
						<?php if($data["status"] == "Requested"): ?>
					<button class="btn btn-success issue-btn" data-id="<?php echo $data["id"]; ?>">Issue</button>

					<button class="btn btn-danger decline-btn" data-id="<?php echo $data["id"]; ?>">Decline</button>

						<?php elseif($data["status"] == "Issued"): ?>
							<a href="admin_actions.php?a=return&t=<?php echo $data["id"]; ?>&fine=<?php echo $fine; ?>&b_id=<?php echo $data["book_id"]; ?>" class="btn btn-primary">Book returned</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  function sendAction(action, id) {
    fetch(`admin_actions.php?a=${action}&t=${id}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        alert(data.message);
        // Update the row status and buttons
        const row = document.querySelector(`button[data-id='${id}']`).closest('tr');
        const statusCell = row.querySelector('td:nth-child(8)');
        const actionsCell = row.querySelector('td:nth-child(9)');
        statusCell.textContent = data.status;

        if(data.status === 'Issued') {
          actionsCell.innerHTML = `<a href="admin_actions.php?a=return&t=${id}&fine=0&b_id=0" class="btn btn-primary">Book returned</a>`;
        } else if(data.status === 'Declined') {
          actionsCell.innerHTML = '';
        }
      } else {
        alert('Action failed');
      }
    })
    .catch(() => alert('Error performing action'));
  }

  document.querySelectorAll('.issue-btn').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      sendAction('issue', id);
    });
  });

  document.querySelectorAll('.decline-btn').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      sendAction('decline', id);
    });
  });
});
</script>

<!-- book list modal -->
<div class="modal modal-lg fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<iframe id="bookList" style="width: 100%; height: 100%;"></iframe>
			</div>
		</div>
	</div>
</div>
<!-- book list modal -->

<?php include ("footer.php"); ?>