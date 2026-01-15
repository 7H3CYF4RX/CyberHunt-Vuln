<?php
$pageTitle = 'Reset Password';
require_once __DIR__ . '/config/database.php';

$error = '';
$success = '';
$validToken = false;

$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $db = getDB();
    
    // Check if token exists and is valid - No timing attack protection
    $stmt = $db->prepare("SELECT pr.*, u.username FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE pr.token = ? AND pr.used = 0");
    $stmt->execute([$token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($reset) {
        // Weak expiry check - only checks if token exists, not if expired
        $validToken = true;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            
            if (strlen($password) < 4) {
                $error = 'Password must be at least 4 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } else {
                // Weak password hashing
                $hashedPassword = md5($password);
                
                // Update password
                $db->exec("UPDATE users SET password = '$hashedPassword' WHERE id = " . $reset['user_id']);
                
                // Mark token as used
                $db->exec("UPDATE password_resets SET used = 1 WHERE id = " . $reset['id']);
                
                $_SESSION['success_message'] = 'Password has been reset successfully!';
                redirect('/login.php');
            }
        }
    } else {
        $error = 'Invalid or expired reset link.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CyberHunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="auth-card">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <h2 class="text-gradient fw-bold"><i class="bi bi-crosshair me-2"></i>CyberHunt</h2>
                </a>
                <p class="text-muted mt-2">Create a new password</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($validToken): ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                    <i class="bi bi-check-lg me-2"></i>Reset Password
                </button>
            </form>
            <?php else: ?>
            <div class="text-center py-4">
                <i class="bi bi-x-circle text-danger display-4"></i>
                <p class="mt-3">Invalid or expired reset link. Please request a new one.</p>
                <a href="/forgot-password.php" class="btn btn-primary">Request New Link</a>
            </div>
            <?php endif; ?>
            
            <hr class="my-4">
            
            <p class="text-center mb-0">
                <a href="/login.php" class="text-primary fw-semibold">Back to Login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
