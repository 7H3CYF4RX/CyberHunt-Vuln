<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$featuredProducts = $db->query("SELECT * FROM products ORDER BY rating DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
$categories = $db->query("SELECT DISTINCT category FROM products")->fetchAll(PDO::FETCH_COLUMN);
?>

<!--
    API Endpoints:
    - /api/v2/graphql.php
    - /api/internal/config.json
    - /backup/db_dump.zip
    - /api/webhook/shipping.php
    - /api/debug/logs.php
    - /logs/access.txt
    - /api.php
    - /api/v1/orders.php
    - /config/settings.xml
    - /api/internal/users.json
    - /backup/users_export.zip
    - /api/admin/backup.php
    - /api/v1/products.php
    - /logs/error.txt
    - /api/webhook/payment.php
    - /api/debug/phpinfo.php
    - /config/database.xml
    
    TODO: Remove before production
-->

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title mb-4">
                    Discover Premium<br>
                    <span class="text-gradient">Tech Products</span>
                </h1>
                <p class="hero-subtitle mb-4">
                    Your ultimate destination for cutting-edge technology and lifestyle products. 
                    Experience quality, innovation, and unbeatable prices.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/products.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-grid me-2"></i>Shop Now
                    </a>
                    <a href="/about.php" class="btn btn-outline-light btn-lg">
                        Learn More
                    </a>
                </div>
                <div class="row mt-5 pt-4">
                    <div class="col-4 text-center">
                        <h3 class="text-white fw-bold mb-0">10K+</h3>
                        <small class="text-white-50">Happy Customers</small>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-white fw-bold mb-0">500+</h3>
                        <small class="text-white-50">Products</small>
                    </div>
                    <div class="col-4 text-center">
                        <h3 class="text-white fw-bold mb-0">4.9</h3>
                        <small class="text-white-50">Rating</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-5 mt-lg-0">
                <img src="/assets/images/hero-image.png" alt="Tech Products" class="img-fluid" 
                     style="max-height: 500px;" onerror="this.src='https://via.placeholder.com/500x500/667eea/ffffff?text=CyberHunt'">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Shop by Category</h2>
            <p class="text-muted">Find exactly what you're looking for</p>
        </div>
        <div class="row g-4">
            <?php 
            $categoryIcons = [
                'Electronics' => 'bi-cpu',
                'Accessories' => 'bi-bag',
                'Home Office' => 'bi-house',
                'Storage' => 'bi-hdd',
                'Furniture' => 'bi-lamp',
                'Stationery' => 'bi-pencil',
                'Bags' => 'bi-briefcase'
            ];
            foreach ($categories as $category): 
                $icon = $categoryIcons[$category] ?? 'bi-box';
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/products.php?category=<?php echo urlencode($category); ?>" class="text-decoration-none">
                    <div class="stat-card h-100">
                        <i class="bi <?php echo $icon; ?> fs-1 text-primary mb-3 d-block"></i>
                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($category); ?></h5>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1">Featured Products</h2>
                <p class="text-muted mb-0">Top rated products by our customers</p>
            </div>
            <a href="/products.php" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="product-card h-100">
                    <div class="product-image">
                        <img src="/assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='https://via.placeholder.com/300x220/f8f9fc/667eea?text=Product'">
                        <?php if ($product['sale_price'] < $product['price']): ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                                -<?php echo round((1 - $product['sale_price']/$product['price']) * 100); ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <small class="text-primary fw-semibold"><?php echo htmlspecialchars($product['category']); ?></small>
                        <h6 class="fw-bold mt-1 mb-2"><?php echo htmlspecialchars($product['name']); ?></h6>
                        <div class="d-flex align-items-center mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star-fill text-warning small"></i>
                            <?php endfor; ?>
                            <small class="text-muted ms-2">(<?php echo $product['reviews_count']; ?>)</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="price-current">$<?php echo number_format($product['sale_price'], 2); ?></span>
                                <?php if ($product['sale_price'] < $product['price']): ?>
                                    <small class="price-original ms-2">$<?php echo number_format($product['price'], 2); ?></small>
                                <?php endif; ?>
                            </div>
                            <a href="/product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <i class="bi bi-truck fs-2 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">Free Shipping</h5>
                    <p class="text-muted small mb-0">On orders over $50</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <i class="bi bi-shield-check fs-2 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">Secure Payment</h5>
                    <p class="text-muted small mb-0">100% secure checkout</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <i class="bi bi-arrow-counterclockwise fs-2 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">Easy Returns</h5>
                    <p class="text-muted small mb-0">30-day return policy</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card h-100">
                    <i class="bi bi-headset fs-2 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold">24/7 Support</h5>
                    <p class="text-muted small mb-0">Here to help anytime</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h3 class="fw-bold mb-3">Subscribe to Our Newsletter</h3>
                <p class="text-white-50 mb-4">Get the latest updates on new products and upcoming sales</p>
                <form class="d-flex gap-2" action="/subscribe.php" method="POST">
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
