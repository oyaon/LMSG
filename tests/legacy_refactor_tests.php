<?php
// Automated tests for legacy PHP refactorings

function test_registration_submit() {
    // Simulate POST data for registration
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [
        'firstname' => 'Test',
        'lastname' => 'User',
        'username' => 'testuser' . time(), // unique username to avoid duplicate
        'email' => 'testuser' . time() . '@example.com', // unique email to avoid duplicate
        'password' => 'TestPass123',
        'confirm_password' => 'TestPass123'
    ];

    // Clear output buffer before including to avoid header issues
    while (ob_get_level()) {
        ob_end_clean();
    }

    ob_start();
    include __DIR__ . '/../admin/registration_submit.php';
    $output = ob_get_clean();

    // Check for redirect header or error messages
    if (strpos($output, 'All fields are required.') !== false ||
        strpos($output, 'Passwords do not match.') !== false ||
        strpos($output, 'Email already registered.') !== false) {
        echo "Registration validation error: " . $output . "\n";
        return false;
    }
    echo "Registration submit test passed.\n";
    return true;
}

function test_add_cart() {
    // Simulate session and GET parameters
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    session_start();
    $_SESSION['email'] = 'testuser@example.com';
    $_GET['t'] = 1;
    $_GET['q'] = 2;

    // Clear output buffer before including to avoid header issues
    while (ob_get_level()) {
        ob_end_clean();
    }

    ob_start();
    include __DIR__ . '/../cart-add.php';
    $output = ob_get_clean();

    if (strpos($output, 'Something went wrong') !== false) {
        echo "Add cart error: " . $output . "\n";
        return false;
    }
    echo "Add cart test passed.\n";
    return true;
}

function test_payment() {
    // Simulate session and POST parameters
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    session_start();
    $_SESSION['email'] = 'testuser@example.com';
    $_POST = [
        'submit' => '1',
        'ids' => '1,2,',
        'amount' => 100,
        'trans_id' => 'TX123456'
    ];

    // Clear output buffer before including to avoid header issues
    while (ob_get_level()) {
        ob_end_clean();
    }

    ob_start();
    include __DIR__ . '/../payment.php';
    $output = ob_get_clean();

    if (strpos($output, 'Payment Successful') === false) {
        echo "Payment processing error: " . $output . "\n";
        return false;
    }
    echo "Payment processing test passed.\n";
    return true;
}

// Run tests
echo "Starting legacy refactor tests...\n";
test_registration_submit();
test_add_cart();
test_payment();
echo "Tests completed.\n";
?>
