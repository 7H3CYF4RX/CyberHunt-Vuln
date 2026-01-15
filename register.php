<?php
$pageTitle = 'Register';
require_once __DIR__ . '/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    
    // Basic validation (intentionally weak)
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 4) { // Intentionally weak password policy
        $error = 'Password must be at least 4 characters.';
    } else {
        $db = getDB();
        
        // Check if username exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $error = 'Username already exists.';
        } else {
            // Insert new user with weak password hashing
            $hashedPassword = md5($password); // Intentionally weak hashing
            
            $stmt = $db->prepare("INSERT INTO users (username, email, password, full_name, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
            
            try {
                $stmt->execute([$username, $email, $hashedPassword, $full_name]);
                $_SESSION['success_message'] = 'Account created successfully! Please login.';
                header('Location: /login.php');
                exit;
            } catch (PDOException $e) {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CyberHunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="auth-card" style="max-width: 500px;">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <h2 class="text-gradient fw-bold"><i class="bi bi-crosshair me-2"></i>CyberHunt</h2>
                </a>
                <p class="text-muted mt-2">Create your account and start shopping</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="full_name" class="form-control" placeholder="John Doe" 
                           value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username *</label>
                    <input type="text" name="username" class="form-control" placeholder="johndoe" 
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="john@example.com" 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" name="terms" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="/terms.php">Terms of Service</a> and <a href="/privacy.php">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>
            </form>
            
            <hr class="my-4">
            
            <p class="text-center mb-0">
                Already have an account? <a href="/login.php" class="text-primary fw-semibold">Login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
