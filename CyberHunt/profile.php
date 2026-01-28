<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/includes/header.php';

if (!isLoggedIn()) {
    $_SESSION['error_message'] = 'Please login to view your profile.';
    redirect('/login.php');
}

$db = getDB();

// IDOR vulnerability - user can view other profiles by changing user_id parameter
$userId = $_GET['user_id'] ?? $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error_message'] = 'User not found.';
    redirect('/');
}

// Get user's orders
$orders = $db->query("SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Get user's reviews
$reviews = $db->query("SELECT r.*, p.name as product_name FROM reviews r 
                       JOIN products p ON r.product_id = p.id 
                       WHERE r.user_id = $userId ORDER BY r.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header" style="padding-bottom: 100px;">
    <div class="container">
        <h1>My Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container" style="margin-top: -80px;">
    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card p-4 text-center">
                <img src="/uploads/profiles/<?php echo $user['profile_pic'] ?? 'default.jpg'; ?>" 
                     alt="Profile Picture" class="rounded-circle mx-auto mb-3" 
                     style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #667eea;"
                     onerror="this.src='/assets/images/default-avatar.svg'">
                <h4 class="fw-bold"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h4>
                <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></p>
                
                <div class="d-flex justify-content-center gap-4 my-3">
                    <div class="text-center">
                        <h5 class="fw-bold mb-0"><?php echo count($orders); ?></h5>
                        <small class="text-muted">Orders</small>
                    </div>
                    <div class="text-center">
                        <h5 class="fw-bold mb-0"><?php echo count($reviews); ?></h5>
                        <small class="text-muted">Reviews</small>
                    </div>
                    <div class="text-center">
                        <h5 class="fw-bold mb-0">$<?php echo number_format($user['balance'], 0); ?></h5>
                        <small class="text-muted">Balance</small>
                    </div>
                </div>
                
                <?php if ($userId == $_SESSION['user_id']): ?>
                <a href="/edit-profile.php" class="btn btn-primary w-100 mt-3">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Contact Info -->
            <div class="card p-4 mt-4">
                <h5 class="fw-bold mb-3">Contact Information</h5>
                <div class="mb-3">
                    <small class="text-muted d-block">Email</small>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Phone</small>
                    <span><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Address</small>
                    <span><?php echo htmlspecialchars($user['address'] ?? 'Not provided'); ?></span>
                </div>
                <div>
                    <small class="text-muted d-block">Location</small>
                    <span><?php echo htmlspecialchars(($user['city'] ?? '') . ', ' . ($user['country'] ?? '')); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Bio -->
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3">About Me</h5>
                <!-- Bio displays user content directly - potential stored XSS -->
                <p class="text-muted mb-0"><?php echo $user['bio'] ?? 'No bio provided.'; ?></p>
            </div>
            
            <!-- Recent Orders -->
            <div class="card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="/orders.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <?php if (empty($orders)): ?>
                    <p class="text-muted mb-0">No orders yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><a href="/order.php?id=<?php echo $order['id']; ?>"><?php echo htmlspecialchars($order['order_number']); ?></a></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] === 'delivered' ? 'success' : ($order['status'] === 'cancelled' ? 'danger' : 'warning'); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Recent Reviews -->
            <div class="card p-4">
                <h5 class="fw-bold mb-3">My Reviews</h5>
                <?php if (empty($reviews)): ?>
                    <p class="text-muted mb-0">No reviews yet.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold"><?php echo htmlspecialchars($review['product_name']); ?></h6>
                            <div>
                                <?php for ($i = 1; $i <= $review['rating']; $i++): ?>
                                    <i class="bi bi-star-fill text-warning small"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($review['title']); ?></p>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
