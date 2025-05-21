<?php
	if(isset($_GET['t'])){
		require_once("./admin/db-connect.php");
		session_start();

		$book_id = $_GET['t'];
		$user_email = $_SESSION['email'];
		$date = date("Y-m-d");

		$stmt = $conn->prepare("INSERT INTO `cart` (id, user_email, book_id, date, status) VALUES (0, ?, ?, ?, 0)");
		$stmt->bind_param("sis", $user_email, $book_id, $date);
		if(!$stmt->execute()){
			echo "Something went wrong. Please try again.";
		}
		else{
			$quantity = (int)$_GET['q'] - 1;
			$stmt2 = $conn->prepare("UPDATE `all_books` SET `quantity`=? WHERE `id`=?");
			$stmt2->bind_param("ii", $quantity, $book_id);
			if(!$stmt2->execute()){
				echo "Something went wrong. Please try again.";
			}
			else{ ?>
				<script type="text/javascript">
					alert("Added to cart successfully");
					window.location.assign("all-books.php");
				</script>
			<?php }
		}
	}
	else{ ?>
		<script type="text/javascript">
			window.history.go(-1);
		</script>
	<?php }
?>