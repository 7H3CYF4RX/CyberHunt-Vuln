<?php
$pageTitle = 'My Wishlist';
require_once __DIR__ . '/includes/header.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

// Wishlist functionality placeholder
$wishlistItems = [];
?>

<div class="page-header">
    <div class="container">
        <h1>My Wishlist</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Wishlist</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="text-center py-5">
        <i class="bi bi-heart display-1 text-muted"></i>
        <h3 class="mt-4">Your wishlist is empty</h3>
        <p class="text-muted">Save items you love for later by clicking the heart icon on any product.</p>
        <a href="/products.php" class="btn btn-primary mt-3">
            <i class="bi bi-bag me-2"></i>Browse Products
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
