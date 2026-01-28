<?php
$pageTitle = 'Login';
require_once __DIR__ . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $db = getDB();
        
        // Intentionally vulnerable to SQL injection
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '" . md5($password) . "'";
        
        try {
            $result = $db->query($query);
            $user = $result->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Update last login
                $db->exec("UPDATE users SET last_login = datetime('now') WHERE id = " . $user['id']);
                
                $_SESSION['success_message'] = 'Welcome back, ' . htmlspecialchars($user['full_name'] ?? $user['username']) . '!';
                
                // Set flag to show WELCOME10 coupon popup
                $_SESSION['show_welcome_coupon'] = true;
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /admin/');
                    exit;
                } else {
                    header('Location: /');
                    exit;
                }
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CyberHunt</title>
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
                <p class="text-muted mt-2">Welcome back! Please login to your account.</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" 
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="/forgot-password.php" class="text-primary text-decoration-none">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>
            
            <hr class="my-4">
            
            <p class="text-center mb-0">
                Don't have an account? <a href="/register.php" class="text-primary fw-semibold">Sign Up</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
