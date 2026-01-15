<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

$db = getDB();

// Handle cart actions - BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_item'])) {
        $cartId = $_POST['cart_id'];
        $db->exec("DELETE FROM cart WHERE id = $cartId"); // SQL injection
    }
    
    if (isset($_POST['update_quantity'])) {
        $cartId = $_POST['cart_id'];
        $quantity = $_POST['quantity'];
        // Vulnerable - no validation on quantity (negative values allowed)
        $db->exec("UPDATE cart SET quantity = $quantity WHERE id = $cartId");
    }
    
    header('Location: /cart.php');
    exit;
}

// Get cart items
$cartItems = $db->query("SELECT c.*, p.name, p.sale_price, p.image, p.stock 
                         FROM cart c 
                         JOIN products p ON c.product_id = p.id 
                         WHERE c.user_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_ASSOC);

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['sale_price'] * $item['quantity'];
}
$shipping = $subtotal > 50 ? 0 : 9.99;
$tax = $subtotal * 0.08;
$total = $subtotal + $shipping + $tax;

$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Shopping Cart</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Cart</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($cartItems)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart3 display-1 text-muted"></i>
            <h3 class="mt-4">Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
            <a href="/products.php" class="btn btn-primary mt-3">
                <i class="bi bi-bag me-2"></i>Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card p-4 mb-4">
                    <h5 class="fw-bold mb-4"><?php echo count($cartItems); ?> Items in Cart</h5>
                    
                    <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="/assets/images/products/<?php echo $item['image']; ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             class="rounded me-3" style="width: 100px; height: 100px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/100x100/f8f9fc/667eea?text=Product'">
                        
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                            <p class="text-muted small mb-2">In Stock: <?php echo $item['stock']; ?></p>
                            <span class="text-primary fw-bold">$<?php echo number_format($item['sale_price'], 2); ?></span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <form method="POST" action="" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                       class="form-control form-control-sm text-center" style="width: 70px;">
                                <button type="submit" name="update_quantity" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>
                            
                            <span class="fw-bold" style="min-width: 80px;">
                                $<?php echo number_format($item['sale_price'] * $item['quantity'], 2); ?>
                            </span>
                            
                            <form method="POST" action="">
                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="remove_item" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <a href="/products.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping</span>
                        <span><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free'; ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax (8%)</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <!-- Coupon Code -->
                    <div class="mb-4">
                        <form method="POST" action="/apply-coupon.php" class="d-flex gap-2">
                            <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code">
                            <button type="submit" class="btn btn-outline-primary">Apply</button>
                        </form>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5 text-primary">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <a href="/checkout.php" class="btn btn-primary w-100 py-3">
                        <i class="bi bi-lock me-2"></i>Proceed to Checkout
                    </a>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>Secure checkout powered by SSL
                        </small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
