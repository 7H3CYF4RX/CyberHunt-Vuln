<?php
$pageTitle = 'Order Details';
require_once __DIR__ . '/includes/header.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

$orderId = $_GET['id'] ?? '';

if (empty($orderId)) {
    redirect('/orders.php');
}

$db = getDB();

// IDOR vulnerability - no check if order belongs to current user
$order = $db->query("SELECT * FROM orders WHERE id = $orderId")->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    $_SESSION['error_message'] = 'Order not found.';
    redirect('/orders.php');
}

// Get order items
$items = $db->query("SELECT oi.*, p.name, p.image FROM order_items oi 
                     JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = $orderId")->fetchAll(PDO::FETCH_ASSOC);

// Get customer info
$customer = $db->query("SELECT * FROM users WHERE id = " . $order['user_id'])->fetch(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <div class="container">
        <h1>Order Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/orders.php">Orders</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($order['order_number']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Order Info -->
        <div class="col-lg-8">
            <div class="card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($order['order_number']); ?></h4>
                        <p class="text-muted mb-0">Placed on <?php echo date('F d, Y \a\t h:i A', strtotime($order['created_at'])); ?></p>
                    </div>
                    <?php
                    $statusColors = [
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $color = $statusColors[$order['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $color; ?> fs-6 py-2 px-3">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
                
                <!-- Order Progress -->
                <div class="mb-4">
                    <div class="progress" style="height: 8px;">
                        <?php
                        $progress = ['pending' => 25, 'processing' => 50, 'shipped' => 75, 'delivered' => 100, 'cancelled' => 0];
                        $progressValue = $progress[$order['status']] ?? 0;
                        ?>
                        <div class="progress-bar bg-<?php echo $color; ?>" style="width: <?php echo $progressValue; ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2 small text-muted">
                        <span>Order Placed</span>
                        <span>Processing</span>
                        <span>Shipped</span>
                        <span>Delivered</span>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3">Order Items</h5>
                <?php foreach ($items as $item): ?>
                <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                    <img src="/assets/images/products/<?php echo $item['image']; ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                         class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/80x80/f8f9fc/667eea?text=Product'">
                    <div class="flex-grow-1">
                        <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                        <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                    </div>
                    <span class="fw-bold">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Shipping Address -->
            <div class="card p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Shipping Address</h5>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="order-summary">
                <h5 class="fw-bold mb-4">Order Summary</h5>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment Method</span>
                    <span><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-5 text-primary">$<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                
                <a href="/orders.php" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-arrow-left me-2"></i>Back to Orders
                </a>
                
                <?php if ($order['status'] === 'delivered'): ?>
                <a href="/invoice.php?id=<?php echo $order['id']; ?>" class="btn btn-primary w-100">
                    <i class="bi bi-download me-2"></i>Download Invoice
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Customer Info - Visible due to IDOR -->
            <div class="card p-4 mt-4">
                <h5 class="fw-bold mb-3">Customer Info</h5>
                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($customer['full_name'] ?? $customer['username']); ?></p>
                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
                <p class="mb-0"><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
