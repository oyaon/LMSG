<?php
require_once("db-connect.php");

if(isset($_GET["a"])){
	$a = $_GET["a"];
	$t = isset($_GET["t"]) ? (int)$_GET["t"] : 0;
	$fine = isset($_GET["fine"]) ? floatval($_GET["fine"]) : 0;
	$b_id = isset($_GET["b_id"]) ? (int)$_GET["b_id"] : 0;

	if($a == "issue"){
		$date = date("Y-m-d");
		$stmt = $conn->prepare("UPDATE `borrow_history` SET `status`='Issued', `issue_date`=? WHERE `id`=?");
		$stmt->bind_param("si", $date, $t);
		$query = $stmt->execute();
		$stmt->close();
		if($query){ ?>
			<script type="text/javascript">
				alert("Issued successfully");
				window.history.go(-1);
			</script>
		<?php }
	}
	else if($a == "decline"){
		$stmt = $conn->prepare("UPDATE `borrow_history` SET `status`='Declined' WHERE `id`=?");
		$stmt->bind_param("i", $t);
		$query = $stmt->execute();
		$stmt->close();
		if($query){ ?>
			<script type="text/javascript">
				alert("Declined successfully");
				window.history.go(-1);
			</script>
		<?php }
	}
	else if($a == "return"){
		$stmt = $conn->prepare("UPDATE `borrow_history` SET `status`='Returned', `fine`=? WHERE `id`=?");
		$stmt->bind_param("di", $fine, $t);
		$query = $stmt->execute();
		$stmt->close();
		if($query){
			$stmt2 = $conn->prepare("UPDATE `all_books` SET `quantity`=`quantity`+1 WHERE `id`=?");
			$stmt2->bind_param("i", $b_id);
			$query2 = $stmt2->execute();
			$stmt2->close();
			if($query2){ ?>
				<script type="text/javascript">
					alert("Status updated successfully");
					window.history.go(-1);
				</script>
			<?php }
		}
	}
}
else{
	echo "Bad request. Error: 101";
	exit();
}
?>