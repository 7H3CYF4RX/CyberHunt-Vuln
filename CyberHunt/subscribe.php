<?php
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // In a real application, this would save to database and send confirmation
        $_SESSION['success_message'] = 'Thank you for subscribing! Check your email for confirmation.';
    } else {
        $_SESSION['error_message'] = 'Please enter a valid email address.';
    }
}

redirect('/');
?>
