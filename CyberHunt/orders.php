<?php
$pageTitle = 'My Orders';
require_once __DIR__ . '/includes/header.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

$db = getDB();

// IDOR - can view other users' orders by modifying user_id in session or through order.php
$orders = $db->query("SELECT * FROM orders WHERE user_id = " . $_SESSION['user_id'] . " ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-header">
    <div class="container">
        <h1>My Orders</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-box display-1 text-muted"></i>
            <h3 class="mt-4">No orders yet</h3>
            <p class="text-muted">You haven't placed any orders yet.</p>
            <a href="/products.php" class="btn btn-primary mt-3">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Order Number</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Payment</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="py-3 px-4">
                                <a href="/order.php?id=<?php echo $order['id']; ?>" class="fw-bold text-primary">
                                    <?php echo htmlspecialchars($order['order_number']); ?>
                                </a>
                            </td>
                            <td class="py-3"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td class="py-3 fw-bold">$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td class="py-3">
                                <?php 
                                $paymentIcons = [
                                    'credit_card' => 'bi-credit-card',
                                    'paypal' => 'bi-paypal',
                                    'bank_transfer' => 'bi-bank'
                                ];
                                $icon = $paymentIcons[$order['payment_method']] ?? 'bi-cash';
                                ?>
                                <i class="bi <?php echo $icon; ?> me-1"></i>
                                <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?>
                            </td>
                            <td class="py-3">
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
                                <span class="badge bg-<?php echo $color; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td class="py-3 text-end px-4">
                                <a href="/order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
