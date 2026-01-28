<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

$db = getDB();
$user = getCurrentUser();

// Get cart items
$cartItems = $db->query("SELECT c.*, p.name, p.sale_price, p.image 
                         FROM cart c 
                         JOIN products p ON c.product_id = p.id 
                         WHERE c.user_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    header('Location: /cart.php');
    exit;
}

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['sale_price'] * $item['quantity'];
}

// Apply discount from session if exists
$discount = $_SESSION['discount'] ?? 0;
$shipping = $subtotal > 50 ? 0 : 9.99;
$tax = ($subtotal - $discount) * 0.08;
$total = $subtotal - $discount + $shipping + $tax;

// Handle POST - BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['zip'];
    $payment_method = $_POST['payment_method'] ?? 'credit_card';
    
    // Business logic flaw - price taken from hidden field (can be manipulated)
    $final_total = $_POST['total_amount'] ?? $total;
    
    // Create order
    $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    
    $stmt = $db->prepare("INSERT INTO orders (user_id, order_number, total_amount, status, shipping_address, payment_method) VALUES (?, ?, ?, 'pending', ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $orderNumber, $final_total, $shipping_address, $payment_method]);
    
    $orderId = $db->lastInsertId();
    
    // Add order items
    foreach ($cartItems as $item) {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['sale_price']]);
    }
    
    // Clear cart
    $db->exec("DELETE FROM cart WHERE user_id = " . $_SESSION['user_id']);
    
    // Clear discount
    unset($_SESSION['discount']);
    
    $_SESSION['success_message'] = "Order placed successfully! Order number: $orderNumber";
    header('Location: /order.php?id=' . $orderId);
    exit;
}

$pageTitle = 'Checkout';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Checkout</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/cart.php">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <form method="POST" action="">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Shipping Information -->
                <div class="card p-4 mb-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-truck me-2"></i>Shipping Information</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">First Name</label>
                            <input type="text" name="first_name" class="form-control" 
                                   value="<?php echo htmlspecialchars(explode(' ', $user['full_name'] ?? '')[0] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Last Name</label>
                            <input type="text" name="last_name" class="form-control" 
                                   value="<?php echo htmlspecialchars(explode(' ', $user['full_name'] ?? '')[1] ?? ''); ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="tel" name="phone" class="form-control" 
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control" 
                               value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">ZIP Code</label>
                            <input type="text" name="zip" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Country</label>
                            <select name="country" class="form-select" required>
                                <option value="United States">United States</option>
                                <option value="Canada">Canada</option>
                                <option value="United Kingdom">United Kingdom</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="card p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Payment Method</h5>
                    
                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input" type="radio" name="payment_method" value="credit_card" id="credit_card" checked>
                        <label class="form-check-label w-100" for="credit_card">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-credit-card-2-front me-2"></i>Credit Card</span>
                                <span class="text-muted small">Visa, Mastercard, Amex</span>
                            </div>
                        </label>
                    </div>
                    
                    <div id="card-details" class="ps-4 mb-3">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Card Number</label>
                                <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">CVV</label>
                                <input type="text" name="cvv" class="form-control" placeholder="123">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" name="expiry" class="form-control" placeholder="MM/YY">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cardholder Name</label>
                                <input type="text" name="card_name" class="form-control" placeholder="John Doe">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3 p-3 border rounded">
                        <input class="form-check-input" type="radio" name="payment_method" value="paypal" id="paypal">
                        <label class="form-check-label" for="paypal">
                            <i class="bi bi-paypal me-2"></i>PayPal
                        </label>
                    </div>
                    
                    <div class="form-check p-3 border rounded">
                        <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="bank">
                        <label class="form-check-label" for="bank">
                            <i class="bi bi-bank me-2"></i>Bank Transfer
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    
                    <?php foreach ($cartItems as $item): ?>
                    <div class="d-flex align-items-center mb-3">
                        <img src="/assets/images/products/<?php echo $item['image']; ?>" 
                             alt="" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/60x60/f8f9fc/667eea?text=P'">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 small"><?php echo htmlspecialchars($item['name']); ?></h6>
                            <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                        </div>
                        <span>$<?php echo number_format($item['sale_price'] * $item['quantity'], 2); ?></span>
                    </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <?php if ($discount > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount</span>
                        <span>-$<?php echo number_format($discount, 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free'; ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <!-- Hidden field with total - vulnerable to manipulation -->
                    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                    
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-lock me-2"></i>Place Order
                    </button>
                    
                    <p class="text-muted small text-center mt-3 mb-0">
                        By placing this order, you agree to our Terms of Service
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
