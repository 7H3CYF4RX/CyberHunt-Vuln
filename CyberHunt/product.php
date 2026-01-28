<?php
require_once __DIR__ . '/config/database.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    $_SESSION['error_message'] = 'Product not found.';
    header('Location: /products.php');
    exit;
}

$db = getDB();

// Handle review submission (before any output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isLoggedIn()) {
        $_SESSION['error_message'] = 'Please login to submit a review.';
        header('Location: /login.php');
        exit;
    }
    
    $rating = $_POST['rating'] ?? 5;
    $title = $_POST['review_title'] ?? '';
    $comment = $_POST['review_comment'] ?? '';
    
    // Intentionally vulnerable to stored XSS - but only affects the current session
    $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, rating, title, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $id, $rating, $title, $comment]);
    
    $_SESSION['success_message'] = 'Review submitted successfully!';
    header("Location: /product.php?id=$id");
    exit;
}

// Handle add to cart (before any output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        $_SESSION['error_message'] = 'Please login to add items to cart.';
        header('Location: /login.php');
        exit;
    }
    
    $quantity = (int)($_POST['quantity'] ?? 1);
    
    // Check if already in cart
    $stmt = $db->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['user_id'], $id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        $stmt = $db->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
        $stmt->execute([$quantity, $existing['id']]);
    } else {
        $stmt = $db->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $id, $quantity]);
    }
    
    $_SESSION['success_message'] = 'Product added to cart!';
    header("Location: /product.php?id=$id");
    exit;
}

// Intentionally vulnerable to SQL injection
$query = "SELECT * FROM products WHERE id = $id";

try {
    $result = $db->query($query);
    $product = $result->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $product = null;
    $error = $e->getMessage();
}

if (!$product) {
    $_SESSION['error_message'] = 'Product not found.';
    header('Location: /products.php');
    exit;
}

// Get reviews
$reviews = $db->query("SELECT r.*, u.username, u.profile_pic FROM reviews r 
                       JOIN users u ON r.user_id = u.id 
                       WHERE r.product_id = $id ORDER BY r.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $product['name'];
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products.php">Products</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            <div class="card p-4">
                <img src="/assets/images/products/<?php echo $product['image']; ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                     class="img-fluid rounded"
                     onerror="this.src='https://via.placeholder.com/500x400/f8f9fc/667eea?text=Product'">
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-7">
            <div class="card p-4">
                <span class="badge bg-primary mb-3" style="width: fit-content;"><?php echo htmlspecialchars($product['category']); ?></span>
                <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                
                <div class="d-flex align-items-center mb-3">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star-fill text-warning me-1"></i>
                    <?php endfor; ?>
                    <span class="ms-2 text-muted"><?php echo number_format($product['rating'], 1); ?> (<?php echo $product['reviews_count']; ?> reviews)</span>
                </div>
                
                <div class="mb-4">
                    <span class="fs-2 fw-bold text-primary">$<?php echo number_format($product['sale_price'], 2); ?></span>
                    <?php if ($product['sale_price'] < $product['price']): ?>
                        <span class="fs-5 text-decoration-line-through text-muted ms-2">$<?php echo number_format($product['price'], 2); ?></span>
                        <span class="badge bg-danger ms-2">
                            -<?php echo round((1 - $product['sale_price']/$product['price']) * 100); ?>%
                        </span>
                    <?php endif; ?>
                </div>
                
                <p class="text-muted mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                
                <div class="mb-4">
                    <span class="fw-semibold">Availability: </span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="text-success"><i class="bi bi-check-circle me-1"></i>In Stock (<?php echo $product['stock']; ?> available)</span>
                    <?php else: ?>
                        <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
                    <?php endif; ?>
                </div>
                
                <form method="POST" action="" class="mb-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="form-label fw-semibold mb-0">Quantity:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="form-control" style="width: 80px;">
                        </div>
                        <div class="col">
                            <button type="submit" name="add_to_cart" class="btn btn-primary btn-lg" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                <i class="bi bi-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-4">
                        <i class="bi bi-truck fs-4 text-primary"></i>
                        <small class="d-block text-muted">Free Shipping</small>
                    </div>
                    <div class="col-4">
                        <i class="bi bi-shield-check fs-4 text-primary"></i>
                        <small class="d-block text-muted">Secure Payment</small>
                    </div>
                    <div class="col-4">
                        <i class="bi bi-arrow-counterclockwise fs-4 text-primary"></i>
                        <small class="d-block text-muted">30-Day Returns</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="mt-5">
        <h3 class="fw-bold mb-4">Customer Reviews</h3>
        
        <div class="row">
            <div class="col-lg-8">
                <?php if (empty($reviews)): ?>
                    <div class="card p-4 text-center">
                        <i class="bi bi-chat-dots fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No reviews yet. Be the first to review this product!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                    <div class="card p-4 mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <img src="/uploads/profiles/<?php echo $review['profile_pic']; ?>" 
                                 alt="" class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;"
                                 onerror="this.src='/assets/images/default-avatar.svg'">
                            <div>
                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($review['username']); ?></h6>
                                <small class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                            </div>
                            <div class="ms-auto">
                                <?php for ($i = 1; $i <= $review['rating']; $i++): ?>
                                    <i class="bi bi-star-fill text-warning"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <h6 class="fw-bold"><?php echo $review['title']; ?></h6>
                        <p class="text-muted mb-0"><?php echo $review['comment']; ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Write Review Form -->
            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-4">Write a Review</h5>
                    <?php if (isLoggedIn()): ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating</label>
                            <select name="rating" class="form-select">
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Good</option>
                                <option value="3">⭐⭐⭐ Average</option>
                                <option value="2">⭐⭐ Poor</option>
                                <option value="1">⭐ Terrible</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="review_title" class="form-control" placeholder="Review title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Your Review</label>
                            <textarea name="review_comment" class="form-control" rows="4" placeholder="Share your experience..." required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn btn-primary w-100">Submit Review</button>
                    </form>
                    <?php else: ?>
                    <p class="text-muted mb-3">Please login to write a review.</p>
                    <a href="/login.php" class="btn btn-outline-primary w-100">Login to Review</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
