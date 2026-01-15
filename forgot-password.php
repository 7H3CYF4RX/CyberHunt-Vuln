<?php
$pageTitle = 'Forgot Password';
require_once __DIR__ . '/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Weak token generation - predictable
            $token = md5($user['id'] . time());
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store token
            $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $token, $expiry]);
            
            // In a real app, this would send an email
            $resetLink = "/reset-password.php?token=$token";
            
            $success = "Password reset link has been sent to your email. <br><small class='text-muted'>(For demo: <a href='$resetLink'>$resetLink</a>)</small>";
        } else {
            // Information disclosure - reveals if email exists
            $error = 'No account found with that email address.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - CyberHunt</title>
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
                <p class="text-muted mt-2">Enter your email to reset your password</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                    <i class="bi bi-send me-2"></i>Send Reset Link
                </button>
            </form>
            
            <hr class="my-4">
            
            <p class="text-center mb-0">
                Remember your password? <a href="/login.php" class="text-primary fw-semibold">Login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
