<?php
$pageTitle = 'About Us';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>About CyberHunt</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">About</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- Story Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=Our+Story" alt="Our Story" class="img-fluid rounded-4 shadow">
        </div>
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">Our Story</h2>
            <p class="text-muted mb-3">
                Founded in 2020, CyberHunt began with a simple mission: to make premium technology accessible to everyone. 
                What started as a small online store has grown into a thriving e-commerce platform serving customers worldwide.
            </p>
            <p class="text-muted mb-3">
                Our team of tech enthusiasts carefully curates every product in our catalog, ensuring that you get only the best. 
                We believe in quality over quantity, and every item we sell meets our rigorous standards.
            </p>
            <p class="text-muted">
                Today, CyberHunt is proud to serve over 10,000 customers across 50 countries, delivering cutting-edge 
                technology and exceptional customer service with every order.
            </p>
        </div>
    </div>
    
    <!-- Values -->
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-4">Our Values</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3">
                            <i class="bi bi-star text-primary fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Quality First</h5>
                    <p class="text-muted mb-0">We never compromise on quality. Every product is tested and verified before it reaches you.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3">
                            <i class="bi bi-people text-success fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Customer Focused</h5>
                    <p class="text-muted mb-0">Your satisfaction is our priority. We're here to help you every step of the way.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 h-100">
                    <div class="mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3">
                            <i class="bi bi-lightning text-warning fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Innovation</h5>
                    <p class="text-muted mb-0">We stay ahead of the curve, bringing you the latest and most innovative products.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="bg-dark text-white rounded-4 p-5 mb-5">
        <div class="row text-center">
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold text-primary">10K+</h2>
                <p class="mb-0">Happy Customers</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold text-primary">500+</h2>
                <p class="mb-0">Products</p>
            </div>
            <div class="col-md-3 mb-4 mb-md-0">
                <h2 class="fw-bold text-primary">50+</h2>
                <p class="mb-0">Countries Served</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold text-primary">4.9</h2>
                <p class="mb-0">Average Rating</p>
            </div>
        </div>
    </div>
    
    <!-- Team -->
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-4">Meet Our Team</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-lg-3">
                <div class="card p-4 text-center">
                    <img src="https://via.placeholder.com/150x150/667eea/ffffff?text=CEO" class="rounded-circle mx-auto mb-3" alt="CEO" style="width: 120px; height: 120px;">
                    <h5 class="fw-bold mb-1">John Smith</h5>
                    <p class="text-primary mb-2">CEO & Founder</p>
                    <p class="text-muted small">Visionary leader with 15+ years in tech industry</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card p-4 text-center">
                    <img src="https://via.placeholder.com/150x150/764ba2/ffffff?text=CTO" class="rounded-circle mx-auto mb-3" alt="CTO" style="width: 120px; height: 120px;">
                    <h5 class="fw-bold mb-1">Sarah Johnson</h5>
                    <p class="text-primary mb-2">CTO</p>
                    <p class="text-muted small">Tech innovator driving our platform excellence</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card p-4 text-center">
                    <img src="https://via.placeholder.com/150x150/f093fb/ffffff?text=COO" class="rounded-circle mx-auto mb-3" alt="COO" style="width: 120px; height: 120px;">
                    <h5 class="fw-bold mb-1">Michael Chen</h5>
                    <p class="text-primary mb-2">COO</p>
                    <p class="text-muted small">Operations expert ensuring seamless delivery</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA -->
    <div class="text-center bg-light rounded-4 p-5">
        <h3 class="fw-bold mb-3">Ready to Experience CyberHunt?</h3>
        <p class="text-muted mb-4">Join thousands of satisfied customers and discover our premium collection.</p>
        <a href="/products.php" class="btn btn-primary btn-lg me-2">Shop Now</a>
        <a href="/contact.php" class="btn btn-outline-primary btn-lg">Contact Us</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
