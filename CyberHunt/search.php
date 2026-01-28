<?php
$pageTitle = 'Search Results';
require_once __DIR__ . '/includes/header.php';

$query = $_GET['q'] ?? '';

// WEAK XSS FILTER - Intentionally bypassable
// Filters: <script> tags (case-insensitive)
// Bypass with: <img src=x onerror=alert(1)>, <svg onload=alert(1)>, etc.
$query = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '[FILTERED]', $query);
$query = preg_replace('/<script/i', '[FILTERED]', $query);

$db = getDB();

// Vulnerable to SQL injection and reflected XSS (after weak filter)
$sql = "SELECT * FROM products WHERE name LIKE '%$query%' OR description LIKE '%$query%' ORDER BY rating DESC";

try {
    $products = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    $error = $e->getMessage();
}
?>

<div class="page-header">
    <div class="container">
        <h1>Search Results</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Search</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- Reflected XSS vulnerable output - INTENTIONALLY VULNERABLE -->
    <div class="mb-4">
        <h4>Showing results for:</h4>
        <!-- XSS Point 1: Direct output without any encoding -->
        <div class="search-term"><?php echo $query; ?></div>
        <p class="text-muted"><?php echo count($products); ?> products found</p>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($products) && !isset($error)): ?>
        <div class="text-center py-5">
            <i class="bi bi-search fs-1 text-muted"></i>
            <h4 class="mt-3">No products found</h4>
            <p class="text-muted">Try searching with different keywords</p>
            <a href="/products.php" class="btn btn-primary mt-3">Browse All Products</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
            <div class="col-md-6 col-lg-3">
                <div class="product-card h-100">
                    <div class="product-image">
                        <img src="/assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.src='https://via.placeholder.com/300x220/f8f9fc/667eea?text=Product'">
                    </div>
                    <div class="product-info">
                        <small class="text-primary fw-semibold"><?php echo htmlspecialchars($product['category']); ?></small>
                        <h6 class="fw-bold mt-1 mb-2"><?php echo htmlspecialchars($product['name']); ?></h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price-current">$<?php echo number_format($product['sale_price'], 2); ?></span>
                            <a href="/product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
