<?php
$pageTitle = 'Help Center';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Help Center</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Help</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- Search -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-6">
            <div class="card p-4 text-center">
                <h4 class="fw-bold mb-3">How can we help you?</h4>
                <form action="/search.php" method="GET">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control form-control-lg" placeholder="Search for help...">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Categories -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card p-4 h-100 text-center">
                <i class="bi bi-box fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold">Orders & Shipping</h5>
                <p class="text-muted small">Track orders, shipping info, delivery issues</p>
                <a href="#orders" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 text-center">
                <i class="bi bi-arrow-counterclockwise fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold">Returns & Refunds</h5>
                <p class="text-muted small">Return policy, refund process, exchanges</p>
                <a href="#returns" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 h-100 text-center">
                <i class="bi bi-person fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold">Account & Profile</h5>
                <p class="text-muted small">Account settings, password, profile</p>
                <a href="#account" class="stretched-link"></a>
            </div>
        </div>
    </div>
    
    <!-- FAQ -->
    <h3 class="fw-bold mb-4" id="faq">Frequently Asked Questions</h3>
    
    <div class="accordion mb-5" id="faqAccordion">
        <div class="accordion-item border-0 mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                    How do I track my order?
                </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    You can track your order by logging into your account and visiting the "My Orders" section. 
                    Click on any order to see detailed tracking information. You'll also receive email updates 
                    with tracking links when your order ships.
                </div>
            </div>
        </div>
        
        <div class="accordion-item border-0 mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                    What is your return policy?
                </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    We offer a 30-day return policy on most items. Products must be in original condition with 
                    all packaging and tags. To initiate a return, go to your order details and click "Request Return". 
                    Refunds are processed within 5-7 business days after we receive the item.
                </div>
            </div>
        </div>
        
        <div class="accordion-item border-0 mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                    How long does shipping take?
                </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    Standard shipping takes 5-7 business days within the continental US. Express shipping 
                    (2-3 business days) is available at checkout. International shipping varies by location, 
                    typically 7-14 business days. Free shipping is available on orders over $50.
                </div>
            </div>
        </div>
        
        <div class="accordion-item border-0 mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                    How do I change my password?
                </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    To change your password, log into your account and go to Settings > Security. 
                    Click "Change Password" and enter your current password followed by your new password. 
                    If you forgot your password, use the "Forgot Password" link on the login page.
                </div>
            </div>
        </div>
        
        <div class="accordion-item border-0 mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                    Do you ship internationally?
                </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    Yes! We ship to over 50 countries worldwide. International shipping rates are calculated 
                    at checkout based on your location. Please note that customs duties and taxes may apply 
                    depending on your country's regulations.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Support -->
    <div class="bg-light rounded-4 p-5 text-center">
        <h4 class="fw-bold mb-3">Still need help?</h4>
        <p class="text-muted mb-4">Our support team is here to assist you 24/7</p>
        <a href="/contact.php" class="btn btn-primary btn-lg">Contact Support</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
