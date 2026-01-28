<?php
$pageTitle = 'FAQ';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">FAQ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Ordering -->
            <h4 class="fw-bold mb-4">Ordering</h4>
            <div class="accordion mb-5" id="orderingFaq">
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#o1">
                            How do I place an order?
                        </button>
                    </h2>
                    <div id="o1" class="accordion-collapse collapse show" data-bs-parent="#orderingFaq">
                        <div class="accordion-body text-muted">
                            Simply browse our products, add items to your cart, and proceed to checkout. You'll need to create 
                            an account or log in, enter your shipping information, and complete payment to place your order.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o2">
                            Can I modify my order after placing it?
                        </button>
                    </h2>
                    <div id="o2" class="accordion-collapse collapse" data-bs-parent="#orderingFaq">
                        <div class="accordion-body text-muted">
                            Orders can be modified within 1 hour of placement if they haven't been processed yet. 
                            Contact our support team immediately if you need to make changes.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#o3">
                            What payment methods do you accept?
                        </button>
                    </h2>
                    <div id="o3" class="accordion-collapse collapse" data-bs-parent="#orderingFaq">
                        <div class="accordion-body text-muted">
                            We accept all major credit cards (Visa, Mastercard, American Express), PayPal, and bank transfers. 
                            All payments are processed securely with SSL encryption.
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Shipping -->
            <h4 class="fw-bold mb-4">Shipping</h4>
            <div class="accordion mb-5" id="shippingFaq">
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#s1">
                            How long does shipping take?
                        </button>
                    </h2>
                    <div id="s1" class="accordion-collapse collapse" data-bs-parent="#shippingFaq">
                        <div class="accordion-body text-muted">
                            Standard shipping: 5-7 business days. Express shipping: 2-3 business days. 
                            International shipping: 7-14 business days depending on location.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#s2">
                            Do you offer free shipping?
                        </button>
                    </h2>
                    <div id="s2" class="accordion-collapse collapse" data-bs-parent="#shippingFaq">
                        <div class="accordion-body text-muted">
                            Yes! We offer free standard shipping on all orders over $50 within the continental United States.
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Returns -->
            <h4 class="fw-bold mb-4">Returns & Refunds</h4>
            <div class="accordion mb-5" id="returnsFaq">
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#r1">
                            What is your return policy?
                        </button>
                    </h2>
                    <div id="r1" class="accordion-collapse collapse" data-bs-parent="#returnsFaq">
                        <div class="accordion-body text-muted">
                            We offer a 30-day return policy on most items. Products must be in original, unused condition 
                            with all packaging and tags. Some items like electronics may have specific return conditions.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#r2">
                            How do I request a refund?
                        </button>
                    </h2>
                    <div id="r2" class="accordion-collapse collapse" data-bs-parent="#returnsFaq">
                        <div class="accordion-body text-muted">
                            Go to your order details and click "Request Return". Follow the instructions to ship the item back. 
                            Refunds are processed within 5-7 business days after we receive the returned item.
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account -->
            <h4 class="fw-bold mb-4">Account</h4>
            <div class="accordion mb-5" id="accountFaq">
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
                            How do I reset my password?
                        </button>
                    </h2>
                    <div id="a1" class="accordion-collapse collapse" data-bs-parent="#accountFaq">
                        <div class="accordion-body text-muted">
                            Click "Forgot Password" on the login page and enter your email. You'll receive a link to reset 
                            your password. The link expires after 1 hour for security.
                        </div>
                    </div>
                </div>
                <div class="accordion-item border-0 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
                            Can I delete my account?
                        </button>
                    </h2>
                    <div id="a2" class="accordion-collapse collapse" data-bs-parent="#accountFaq">
                        <div class="accordion-body text-muted">
                            Yes, you can delete your account from Settings > Danger Zone. Note that this action is 
                            irreversible and all your data will be permanently removed.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center bg-light rounded-4 p-4">
                <p class="mb-3">Didn't find what you're looking for?</p>
                <a href="/contact.php" class="btn btn-primary">Contact Support</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
