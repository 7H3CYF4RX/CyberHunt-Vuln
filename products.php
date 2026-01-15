<?php
$pageTitle = 'Products';
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Get filter parameters
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$search = $_GET['q'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

// Build query - Intentionally vulnerable to SQL injection in sort parameter
$query = "SELECT * FROM products WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}

if (!empty($category)) {
    $query .= " AND category = '$category'";
}

if (!empty($minPrice)) {
    $query .= " AND sale_price >= $minPrice";
}

if (!empty($maxPrice)) {
    $query .= " AND sale_price <= $maxPrice";
}

// Sort - vulnerable to SQL injection
switch ($sort) {
    case 'price_low':
        $query .= " ORDER BY sale_price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY sale_price DESC";
        break;
    case 'rating':
        $query .= " ORDER BY rating DESC";
        break;
    case 'name':
        $query .= " ORDER BY name ASC";
        break;
    case 'newest':
        $query .= " ORDER BY created_at DESC";
        break;
    default:
        $query .= " ORDER BY $sort"; // Vulnerable injection point for custom sort values
}

try {
    $products = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    $error = $e->getMessage();
}

$categories = $db->query("SELECT DISTINCT category FROM products")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="page-header">
    <div class="container">
        <h1>Our Products</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Products</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">Error: <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-4">Filters</h5>
                
                <form method="GET" action="">
                    <!-- Search within results -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="q" class="form-control" placeholder="Search products..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" 
                                        <?php echo $category === $cat ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Price Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control" placeholder="Min" 
                                       value="<?php echo htmlspecialchars($minPrice); ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control" placeholder="Max" 
                                       value="<?php echo htmlspecialchars($maxPrice); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Sort By</label>
                        <select name="sort" class="form-select">
                            <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Top Rated</option>
                            <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="/products.php" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                </form>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="mb-0 text-muted">Showing <?php echo count($products); ?> products</p>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h4 class="mt-3">No products found</h4>
                    <p class="text-muted">Try adjusting your filters or search terms</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="product-card h-100">
                            <div class="product-image">
                                <img src="/assets/images/products/<?php echo $product['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     onerror="this.src='https://via.placeholder.com/300x220/f8f9fc/667eea?text=Product'">
                                <?php if ($product['sale_price'] < $product['price']): ?>
                                    <span class="badge bg-danger position-absolute top-0 start-0 m-3">Sale</span>
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
                                            <small class="price-original ms-1">$<?php echo number_format($product['price'], 2); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <a href="/product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
