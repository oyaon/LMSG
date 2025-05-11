<?php include 'header.php'; ?>
<?php require 'db-connect.php'; ?>

<?php
$idErr = $nameErr = $descriptionErr = $typeErr = "";
$id = $name = $description = $type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["id"])) {
        $idErr = "ID is required";
    } else {
        $id = test_input($_POST["id"]);
    }

    if (empty($_POST["authorname"])) {
        $nameErr = "Author Name is required";
    } else {
        $name = test_input($_POST["authorname"]);
    }

    if (empty($_POST["authordescription"])) {
        $descriptionErr = "Author Description is required";
    } else {
        $description = test_input($_POST["authordescription"]);
    }

    if (empty($_POST["booktype"])) {
        $typeErr = "Book Type is required";
    } else {
        $type = test_input($_POST["booktype"]);
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (!empty($id) && !empty($name) && !empty($description) && !empty($type)) {
    // Check if the authors table has a book_type column
    $check_column = "SHOW COLUMNS FROM `authors` LIKE 'book_type'";
    $column_result = $conn->query($check_column);

    if ($column_result->num_rows == 0) {
        // Add book_type column if it doesn't exist
        $add_column = "ALTER TABLE `authors` ADD COLUMN `book_type` VARCHAR(50)";
        $conn->query($add_column);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE `authors` SET name=?, biography=?, book_type=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $description, $type, $id);
    
    if ($stmt->execute()) {
        echo "Author record updated successfully";
        header("location: add-all-authors.php");
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>

<?php include 'footer.php'; ?>
