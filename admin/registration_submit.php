<?php
require('db-connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (empty($_POST['firstname']) || empty($_POST['lastname']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        echo "All fields are required.";
        die();
    }

    // Sanitize input data
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        die();
    }

    // Check if email is already registered
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Email already registered.";
        die();
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $stmt = $conn->prepare("INSERT INTO users (id, first_name, last_name, user_name, email, password, user_type) VALUES (0, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $hashed_password);
    if ($stmt->execute()) {
        header("Location: ../login_page.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}


?>