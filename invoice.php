<?php
/**
 * CyberHunt Invoice Generator
 * Generates PDF-style invoice (HTML version)
 */
$pageTitle = 'Invoice';
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

$orderId = $_GET['id'] ?? '';

if (empty($orderId)) {
    redirect('/orders.php');
}

$db = getDB();

// IDOR - No ownership verification
$order = $db->query("SELECT o.*, u.full_name, u.email, u.phone, u.address 
                     FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     WHERE o.id = $orderId")->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    redirect('/orders.php');
}

$items = $db->query("SELECT oi.*, p.name FROM order_items oi 
                     JOIN products p ON oi.product_id = p.id 
                     WHERE oi.order_id = $orderId")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo $order['order_number']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .invoice { max-width: 800px; margin: 40px auto; background: white; padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        @media print { body { background: white; } .invoice { box-shadow: none; margin: 0; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <h2 class="fw-bold text-primary">CyberHunt</h2>
                <p class="text-muted mb-0">123 Tech Street<br>Silicon Valley, CA 94025<br>support@cyberhunt.com</p>
            </div>
            <div class="text-end">
                <h3 class="fw-bold mb-2">INVOICE</h3>
                <p class="mb-0"><strong>Invoice #:</strong> <?php echo $order['order_number']; ?></p>
                <p class="mb-0"><strong>Date:</strong> <?php echo date('F d, Y', strtotime($order['created_at'])); ?></p>
                <p><strong>Status:</strong> <span class="badge bg-success"><?php echo ucfirst($order['status']); ?></span></p>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-md-6">
                <h6 class="fw-bold text-muted">BILL TO</h6>
                <p class="mb-0">
                    <strong><?php echo htmlspecialchars($order['full_name']); ?></strong><br>
                    <?php echo htmlspecialchars($order['email']); ?><br>
                    <?php echo htmlspecialchars($order['phone']); ?><br>
                    <?php echo htmlspecialchars($order['address']); ?>
                </p>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-muted">SHIP TO</h6>
                <p class="mb-0"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
            </div>
        </div>
        
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end">$<?php echo number_format($item['price'], 2); ?></td>
                    <td class="text-end">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                    <td class="text-end">$<?php echo number_format($order['total_amount'] * 0.92, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Tax (8%)</strong></td>
                    <td class="text-end">$<?php echo number_format($order['total_amount'] * 0.08, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Shipping</strong></td>
                    <td class="text-end">Free</td>
                </tr>
                <tr class="table-primary">
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td class="text-end"><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="mt-5 pt-4 border-top">
            <p class="text-muted text-center mb-0">
                Thank you for shopping with CyberHunt!<br>
                <small>For questions about this invoice, please contact support@cyberhunt.com</small>
            </p>
        </div>
        
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer me-2"></i>Print Invoice
            </button>
            <a href="/orders.php" class="btn btn-outline-secondary ms-2">Back to Orders</a>
        </div>
    </div>
</body>
</html>
