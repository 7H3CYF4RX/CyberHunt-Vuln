<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword)) {
        $_SESSION['error_message'] = 'Please fill in all fields.';
        redirect('/edit-profile.php');
    }
    
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = 'New passwords do not match.';
        redirect('/edit-profile.php');
    }
    
    // Weak password validation
    if (strlen($newPassword) < 4) {
        $_SESSION['error_message'] = 'Password must be at least 4 characters.';
        redirect('/edit-profile.php');
    }
    
    $db = getDB();
    $user = getCurrentUser();
    
    // Verify current password - using MD5 (weak)
    if (md5($currentPassword) !== $user['password']) {
        $_SESSION['error_message'] = 'Current password is incorrect.';
        redirect('/edit-profile.php');
    }
    
    // Update password with MD5 (weak hashing)
    $newHash = md5($newPassword);
    $db->exec("UPDATE users SET password = '$newHash' WHERE id = " . $_SESSION['user_id']);
    
    $_SESSION['success_message'] = 'Password updated successfully!';
    redirect('/profile.php');
}

redirect('/edit-profile.php');
?>
