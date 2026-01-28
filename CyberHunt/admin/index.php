<?php
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../config/database.php';

// Broken Access Control - only checks if user is logged in, not if admin
// Should check: if (!isAdmin()) redirect...
if (!isLoggedIn()) {
    redirect('/login.php');
}

$db = getDB();

// Get statistics
$totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $db->query("SELECT SUM(total_amount) FROM orders WHERE status != 'cancelled'")->fetchColumn() ?? 0;

$recentOrders = $db->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$recentUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CyberHunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
        }
        .main-content {
            margin-left: 280px;
            padding: 30px;
            background: #f8f9fc;
            min-height: 100vh;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="/" class="d-block mb-4 text-decoration-none">
            <h4 class="text-white fw-bold"><i class="bi bi-crosshair me-2"></i>CyberHunt</h4>
            <small class="text-white-50">Admin Panel</small>
        </a>
        
        <nav class="mt-4">
            <a href="/admin/" class="sidebar-link active">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
            <a href="/admin/users.php" class="sidebar-link">
                <i class="bi bi-people"></i>Users
            </a>
            <a href="/admin/products.php" class="sidebar-link">
                <i class="bi bi-box"></i>Products
            </a>
            <a href="/admin/orders.php" class="sidebar-link">
                <i class="bi bi-cart"></i>Orders
            </a>
            <a href="/admin/reviews.php" class="sidebar-link">
                <i class="bi bi-star"></i>Reviews
            </a>
            <a href="/admin/messages.php" class="sidebar-link">
                <i class="bi bi-envelope"></i>Messages
            </a>
            <a href="/admin/settings.php" class="sidebar-link">
                <i class="bi bi-gear"></i>Settings
            </a>
            <a href="/admin/logs.php" class="sidebar-link">
                <i class="bi bi-file-text"></i>Activity Logs
            </a>
            <hr class="border-white-50 my-4">
            <a href="/" class="sidebar-link">
                <i class="bi bi-house"></i>Back to Site
            </a>
            <a href="/logout.php" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-left"></i>Logout
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Dashboard</h3>
                <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>
            <div class="text-muted">
                <i class="bi bi-calendar me-1"></i><?php echo date('F d, Y'); ?>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Users</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($totalUsers); ?></h3>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Products</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($totalProducts); ?></h3>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Orders</p>
                            <h3 class="fw-bold mb-0"><?php echo number_format($totalOrders); ?></h3>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Revenue</p>
                            <h3 class="fw-bold mb-0">$<?php echo number_format($totalRevenue, 0); ?></h3>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Recent Orders</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                        <td><?php echo htmlspecialchars($order['username']); ?></td>
                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td><span class="badge bg-<?php echo $order['status'] === 'delivered' ? 'success' : 'warning'; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                        <td><?php echo date('M d', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Users -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">New Users</h5>
                        <?php foreach ($recentUsers as $user): ?>
                        <div class="d-flex align-items-center mb-3">
                            <img src="/uploads/profiles/<?php echo $user['profile_pic'] ?? 'default.jpg'; ?>" 
                                 alt="" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover;"
                                 onerror="this.src='/assets/images/default-avatar.svg'">
                            <div class="flex-grow-1">
                                <h6 class="mb-0"><?php echo htmlspecialchars($user['username']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
