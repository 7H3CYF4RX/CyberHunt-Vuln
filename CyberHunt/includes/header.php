<?php
require_once __DIR__ . '/../config/database.php';

$currentUser = getCurrentUser() ?: [];
$cartCount = 0;

if (isLoggedIn() && !empty($_SESSION['user_id'])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $cartCount = $result['count'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CyberHunt - Your Premier Online Shopping Destination for Tech & Lifestyle Products">
    <meta name="keywords" content="online shopping, electronics, gadgets, tech products, accessories">
    <meta name="author" content="CyberHunt Team">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>CyberHunt</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --dark-gradient: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 100%);
            --accent-color: #667eea;
            --accent-secondary: #764ba2;
            --text-primary: #1a1a2e;
            --text-secondary: #6c757d;
            --bg-light: #f8f9fc;
            --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
            --hover-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: var(--bg-light);
            min-height: 100vh;
        }
        
        /* Navbar Styles */
        .navbar-cyber {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0,0,0,0.1);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-cyber.scrolled {
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.98);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--text-primary) !important;
            padding: 10px 20px !important;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--accent-color) !important;
        }
        
        .nav-link.active {
            background: var(--primary-gradient);
            color: white !important;
        }
        
        .btn-cyber {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-cyber:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-cyber-outline {
            background: transparent;
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            padding: 10px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-cyber-outline:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
        }
        
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary-gradient);
            color: white;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 50px;
            font-weight: 700;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        var(--primary-gradient) border-box;
        }
        
        .dropdown-menu-cyber {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            padding: 15px;
            min-width: 220px;
        }
        
        .dropdown-item {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--accent-color);
        }
        
        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
        }
        
        /* Search Bar */
        .search-bar {
            position: relative;
            max-width: 400px;
        }
        
        .search-bar input {
            background: var(--bg-light);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 12px 20px 12px 50px;
            width: 100%;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .search-bar input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .search-bar i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }
        
        /* Alert Styles */
        .alert-cyber {
            border: none;
            border-radius: 15px;
            padding: 20px 25px;
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }
</style>
</head>
<body>
    <!-- GRAND20 Promotional Banner - Shown Everywhere -->
    <div class="py-2 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <span style="color: #ffffff; font-weight: bold;">🎉 GRAND SALE! Use code <span class="badge px-3 py-2 mx-2" style="font-size: 1rem; background-color: #ffc107; color: #000000;">GRAND20</span> for 20% OFF on all orders! 🛒</span>
        </div>
    </div>

    <!-- WELCOME10 Popup Modal - Shows after login -->
    <?php if (isset($_SESSION['show_welcome_coupon']) && $_SESSION['show_welcome_coupon']): ?>
    <div class="modal fade" id="welcomeCouponModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title"><i class="bi bi-gift me-2"></i>Welcome Gift!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-ticket-perforated display-1 text-primary"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Here's 10% OFF just for you!</h3>
                    <p class="text-muted">Use this exclusive welcome coupon on your first order</p>
                    <div class="bg-light p-3 rounded-3 my-4">
                        <h2 class="mb-0 text-primary fw-bold" id="welcomeCode">WELCOME10</h2>
                    </div>
                    <button class="btn btn-primary btn-lg px-5" onclick="copyWelcomeCode()">
                        <i class="bi bi-clipboard me-2"></i>Copy Code
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeCouponModal'));
            welcomeModal.show();
        });
        function copyWelcomeCode() {
            navigator.clipboard.writeText('WELCOME10');
            document.querySelector('#welcomeCouponModal .btn-primary').innerHTML = '<i class="bi bi-check2 me-2"></i>Copied!';
        }
    </script>
    <?php unset($_SESSION['show_welcome_coupon']); endif; ?>

    <!-- Top Bar -->
    <div class="bg-dark text-white py-2 d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small><i class="bi bi-envelope me-2"></i>support@cyberhunt.com | <i class="bi bi-telephone ms-3 me-2"></i>+1 (555) 123-4567</small>
                </div>
                <div class="col-md-6 text-end">
                    <small><i class="bi bi-truck me-2"></i>Free Shipping on Orders Over $50 | <i class="bi bi-shield-check ms-3 me-2"></i>Secure Payments</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-cyber sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-crosshair me-2"></i>CyberHunt
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Search Bar -->
                <form class="search-bar mx-auto d-none d-lg-block" action="/search.php" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Search products..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                </form>
                
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="bi bi-house me-1"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products.php"><i class="bi bi-grid me-1"></i>Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact.php"><i class="bi bi-chat me-1"></i>Contact</a>
                    </li>
                    
                    <?php if (isLoggedIn()): ?>
                        <!-- Cart -->
                        <li class="nav-item me-2">
                            <a class="nav-link position-relative" href="/cart.php">
                                <i class="bi bi-cart3 fs-5"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="cart-badge"><?php echo $cartCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                                <img src="/uploads/profiles/<?php echo $currentUser['profile_pic'] ?? 'default.jpg'; ?>" 
                                     alt="Profile" class="user-avatar me-2" 
                                     onerror="this.src='/assets/images/default-avatar.svg'">
                                <span class="d-none d-lg-inline"><?php echo htmlspecialchars($currentUser['username'] ?? 'User'); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-cyber dropdown-menu-end">
                                <li class="mb-2 px-3">
                                    <small class="text-muted">Welcome back!</small>
                                    <div class="fw-bold"><?php echo htmlspecialchars($currentUser['full_name'] ?? $currentUser['username'] ?? 'User'); ?></div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/profile.php"><i class="bi bi-person"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="/orders.php"><i class="bi bi-box"></i>My Orders</a></li>
                                <li><a class="dropdown-item" href="/messages.php"><i class="bi bi-envelope"></i>Messages</a></li>
                                <li><a class="dropdown-item" href="/settings.php"><i class="bi bi-gear"></i>Settings</a></li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-primary" href="/admin/"><i class="bi bi-speedometer2"></i>Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a class="btn btn-cyber-outline" href="/login.php">Login</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-cyber" href="/register.php">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="container mt-4">
                <div class="alert alert-success alert-cyber alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="container mt-4">
                <div class="alert alert-danger alert-cyber alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
