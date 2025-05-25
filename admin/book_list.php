<?php require_once("db-connect.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
        }
        .table {
            width: 100%;
            margin: 0 auto;
        }
        td, th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Book ID</th>
                <th scope="col">Book Name</th>
                <th scope="col">Price</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if (isset($_GET['t']) && !empty($_GET['t'])) {
                $ids = $_GET['t'];
                // Sanitize and prepare book IDs
                $idArray = array_filter(explode(',', $ids));
                
                if (count($idArray) > 0) {
                    $i = 0;
                    foreach ($idArray as $bookId) {
                        $bookId = intval($bookId);
                        $q = "SELECT id, name, price FROM `all_books` WHERE `id` = $bookId;";
                        $result = $conn->query($q);
                        
                        if ($result && $result->num_rows > 0) {
                            $data = $result->fetch_assoc();
                            ?>
                            <tr>
                                <th scope="row"><?php echo ++$i; ?></th>
                                <td><?php echo $data["id"]; ?></td>
                                <td><?php echo $data["name"]; ?></td>
                                <td><?php echo $data["price"]; ?> Tk</td>
                            </tr>
                            <?php
                        }
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center">No books found</td></tr>';
                }
            } else {
                echo '<tr><td colspan="4" class="text-center">No book IDs provided</td></tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>