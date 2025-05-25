<?php
require_once "includes/init.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!Helper::validateCsrfToken($_POST['csrf_token'] ?? '', 'contact_form')) {
        Helper::setFlashMessage('error', 'Invalid CSRF token. Please try again.');
        header('Location: contact.php');
        exit;
    }

    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        Helper::setFlashMessage('error', 'All fields are required.');
        header('Location: contact.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Helper::setFlashMessage('error', 'Please enter a valid email address.');
        header('Location: contact.php');
        exit;
    }

    // Insert message into database
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    $stmt->execute();
    $stmt->close();

    // Prepare email to admin
    $adminEmails = ['info@gplibrary.org.bd', 'support@gplibrary.org.bd'];
    $to = implode(',', $adminEmails);
    $emailSubject = "Contact Form Message: " . $subject;
    $emailBody = "You have received a new message from the library contact form.\n\n";
    $emailBody .= "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Subject: $subject\n";
    $emailBody .= "Message:\n$message\n";

    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send email
    if (mail($to, $emailSubject, $emailBody, $headers)) {
        Helper::setFlashMessage('success', 'Your message has been sent successfully. We will get back to you soon.');
    } else {
        error_log("Failed to send contact form email to $to with subject $emailSubject");
        Helper::setFlashMessage('error', 'There was an error sending your message. Please try again later.');
    }

    // Redirect back to contact page
    header('Location: contact.php');
    exit;
} else {
    // Invalid request method
    Helper::setFlashMessage('error', 'Invalid request.');
    header('Location: contact.php');
    exit;
}
?>
