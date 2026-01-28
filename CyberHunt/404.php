<?php
$pageTitle = '404 - Page Not Found';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="py-5">
                <h1 class="display-1 fw-bold text-primary" style="font-size: 8rem;">404</h1>
                <h2 class="fw-bold mb-4">Page Not Found</h2>
                <p class="text-muted mb-4 fs-5">
                    Oops! The page you're looking for doesn't exist or has been moved.
                </p>
                
                <div class="d-flex gap-3 justify-content-center mb-5">
                    <a href="/" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-house me-2"></i>Go Home
                    </a>
                    <a href="/products.php" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-grid me-2"></i>Browse Products
                    </a>
                </div>
                
                <div class="card p-4 text-start" style="background: rgba(102, 126, 234, 0.05);">
                    <h6 class="fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Helpful Links</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="/" class="text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Home Page</a></li>
                        <li class="mb-2"><a href="/products.php" class="text-decoration-none"><i class="bi bi-chevron-right me-2"></i>All Products</a></li>
                        <li class="mb-2"><a href="/contact.php" class="text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Contact Support</a></li>
                        <li><a href="/login.php" class="text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Login / Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
