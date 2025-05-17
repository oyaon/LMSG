<?php
require_once("./admin/db-connect.php");

if (!isset($_SESSION["email"])) {
    echo '<script type="text/javascript">
        alert("Login first!");
        window.location.assign("login_page.php");
    </script>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if ($cartId <= 0 || $quantity <= 0) {
        echo '<script type="text/javascript">
            alert("Invalid input.");
            window.history.back();
        </script>';
        exit();
    }

    // Fetch the cart item and book info
    $cartQuery = "SELECT book_id FROM cart WHERE id = ? AND user_email = ? AND status = 0";
    $stmt = $conn->prepare($cartQuery);
    $stmt->bind_param("is", $cartId, $_SESSION["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo '<script type="text/javascript">
            alert("Cart item not found.");
            window.history.back();
        </script>';
        exit();
    }
    $cartItem = $result->fetch_assoc();
    $bookId = $cartItem['book_id'];

    // Fetch current quantity in cart for this book
    $countQuery = "SELECT COUNT(*) as count FROM cart WHERE user_email = ? AND book_id = ? AND status = 0";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param("si", $_SESSION["email"], $bookId);
    $stmt->execute();
    $countResult = $stmt->get_result();
    $countRow = $countResult->fetch_assoc();
    $currentQuantity = (int)$countRow['count'];

    if ($quantity == $currentQuantity) {
        // No change needed
        header("Location: cart.php");
        exit();
    } elseif ($quantity > $currentQuantity) {
        // Need to add more items to cart
        $addCount = $quantity - $currentQuantity;

        // Check book availability
        $bookQuery = "SELECT quantity FROM all_books WHERE id = ?";
        $stmt = $conn->prepare($bookQuery);
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $bookResult = $stmt->get_result();
        $book = $bookResult->fetch_assoc();

        if (!$book || $book['quantity'] < $addCount) {
            echo '<script type="text/javascript">
                alert("Not enough stock available.");
                window.history.back();
            </script>';
            exit();
        }

        // Add items to cart and update book quantity
        $conn->begin_transaction();
        try {
            $today = date('Y-m-d');
            $insertStmt = $conn->prepare("INSERT INTO cart (user_email, book_id, date, status) VALUES (?, ?, ?, 0)");
            for ($i = 0; $i < $addCount; $i++) {
                $insertStmt->bind_param("sis", $_SESSION["email"], $bookId, $today);
                $insertStmt->execute();
            }

            $updateStmt = $conn->prepare("UPDATE all_books SET quantity = quantity - ? WHERE id = ?");
            $updateStmt->bind_param("ii", $addCount, $bookId);
            $updateStmt->execute();

            $conn->commit();
            header("Location: cart.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo '<script type="text/javascript">
                alert("Failed to update cart quantity.");
                window.history.back();
            </script>';
            exit();
        }
    } else {
        // Need to remove items from cart
        $removeCount = $currentQuantity - $quantity;

        // Remove items from cart and update book quantity
        $conn->begin_transaction();
        try {
            // Get cart item ids to remove
            $selectStmt = $conn->prepare("SELECT id FROM cart WHERE user_email = ? AND book_id = ? AND status = 0 LIMIT ?");
            $selectStmt->bind_param("sii", $_SESSION["email"], $bookId, $removeCount);
            $selectStmt->execute();
            $idsResult = $selectStmt->get_result();
            $idsToRemove = [];
            while ($row = $idsResult->fetch_assoc()) {
                $idsToRemove[] = $row['id'];
            }
            if (count($idsToRemove) < $removeCount) {
                throw new Exception("Not enough items in cart to remove.");
            }

            // Delete cart items
            $placeholders = implode(',', array_fill(0, count($idsToRemove), '?'));
            $types = str_repeat('i', count($idsToRemove));
            $deleteQuery = "DELETE FROM cart WHERE id IN ($placeholders)";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param($types, ...$idsToRemove);
            $deleteStmt->execute();

            // Update book quantity
            $updateStmt = $conn->prepare("UPDATE all_books SET quantity = quantity + ? WHERE id = ?");
            $updateStmt->bind_param("ii", $removeCount, $bookId);
            $updateStmt->execute();

            $conn->commit();
            header("Location: cart.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo '<script type="text/javascript">
                alert("Failed to update cart quantity.");
                window.history.back();
            </script>';
            exit();
        }
    }
} else {
    header("Location: cart.php");
    exit();
}
