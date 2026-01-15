<?php
$pageTitle = 'Returns & Refunds';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Returns & Refunds</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Returns</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 p-md-5">
                <h4 class="fw-bold mb-4">Return Policy</h4>
                
                <p class="text-muted mb-4">
                    We want you to be completely satisfied with your purchase. If you're not happy with your order, 
                    we're here to help with our hassle-free return process.
                </p>
                
                <h5 class="fw-bold mt-4">30-Day Return Window</h5>
                <p class="text-muted">
                    You have 30 days from the date of delivery to return most items for a full refund. 
                    Items must be in their original, unused condition with all packaging and tags intact.
                </p>
                
                <h5 class="fw-bold mt-4">Return Process</h5>
                <ol class="text-muted">
                    <li class="mb-2">Log into your CyberHunt account and go to "My Orders"</li>
                    <li class="mb-2">Find the order containing the item you wish to return</li>
                    <li class="mb-2">Click "Request Return" and select the reason for return</li>
                    <li class="mb-2">Print the prepaid return shipping label</li>
                    <li class="mb-2">Pack the item securely and attach the label</li>
                    <li>Drop off at any authorized shipping location</li>
                </ol>
                
                <h5 class="fw-bold mt-4">Refund Processing</h5>
                <p class="text-muted">
                    Once we receive your returned item, we will inspect it and process your refund within 
                    5-7 business days. Refunds will be credited to your original payment method.
                </p>
                
                <h5 class="fw-bold mt-4">Non-Returnable Items</h5>
                <p class="text-muted">The following items cannot be returned:</p>
                <ul class="text-muted">
                    <li>Opened software or digital products</li>
                    <li>Items marked as "Final Sale"</li>
                    <li>Gift cards</li>
                    <li>Personalized or customized items</li>
                    <li>Items damaged due to misuse</li>
                </ul>
                
                <h5 class="fw-bold mt-4">Exchanges</h5>
                <p class="text-muted">
                    If you'd like to exchange an item for a different size, color, or product, please return 
                    the original item for a refund and place a new order for the desired item.
                </p>
                
                <h5 class="fw-bold mt-4">Damaged or Defective Items</h5>
                <p class="text-muted">
                    If you receive a damaged or defective item, please contact us within 48 hours of delivery. 
                    We will arrange for a replacement or full refund at no additional cost to you.
                </p>
                
                <div class="bg-light rounded-4 p-4 mt-4">
                    <h5 class="fw-bold mb-2">Need help with a return?</h5>
                    <p class="text-muted mb-3">Contact our support team for assistance.</p>
                    <a href="/contact.php" class="btn btn-primary">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
