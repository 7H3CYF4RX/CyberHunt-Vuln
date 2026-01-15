<?php
$pageTitle = 'Shipping Information';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Shipping Information</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Shipping</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 p-md-5">
                <h4 class="fw-bold mb-4">Shipping Options</h4>
                
                <div class="table-responsive mb-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Shipping Method</th>
                                <th>Delivery Time</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard Shipping</td>
                                <td>5-7 Business Days</td>
                                <td>$9.99 (Free over $50)</td>
                            </tr>
                            <tr>
                                <td>Express Shipping</td>
                                <td>2-3 Business Days</td>
                                <td>$19.99</td>
                            </tr>
                            <tr>
                                <td>Overnight Shipping</td>
                                <td>1 Business Day</td>
                                <td>$29.99</td>
                            </tr>
                            <tr>
                                <td>International Standard</td>
                                <td>7-14 Business Days</td>
                                <td>Starting at $24.99</td>
                            </tr>
                            <tr>
                                <td>International Express</td>
                                <td>3-5 Business Days</td>
                                <td>Starting at $49.99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h4 class="fw-bold mb-4">Shipping Policy</h4>
                
                <h5 class="fw-bold mt-4">Processing Time</h5>
                <p class="text-muted">
                    Orders are typically processed within 1-2 business days. During peak seasons or sales events, 
                    processing may take up to 3 business days. You will receive an email confirmation once your 
                    order ships with tracking information.
                </p>
                
                <h5 class="fw-bold mt-4">Free Shipping</h5>
                <p class="text-muted">
                    We offer free standard shipping on all orders over $50 within the continental United States. 
                    This offer does not apply to express or overnight shipping options.
                </p>
                
                <h5 class="fw-bold mt-4">International Shipping</h5>
                <p class="text-muted">
                    We ship to over 50 countries worldwide. International shipping rates are calculated at checkout 
                    based on destination and package weight. Please note that customs duties and import taxes may 
                    apply and are the responsibility of the customer.
                </p>
                
                <h5 class="fw-bold mt-4">Order Tracking</h5>
                <p class="text-muted">
                    Once your order ships, you will receive a confirmation email with your tracking number. 
                    You can also track your order by logging into your account and visiting the "My Orders" section.
                </p>
                
                <h5 class="fw-bold mt-4">Delivery Issues</h5>
                <p class="text-muted">
                    If you experience any issues with delivery, please contact our customer support team within 
                    7 days of the expected delivery date. We will work with the shipping carrier to resolve 
                    any problems.
                </p>
                
                <div class="bg-light rounded-4 p-4 mt-4">
                    <h5 class="fw-bold mb-2">Questions about shipping?</h5>
                    <p class="text-muted mb-3">Our support team is happy to help!</p>
                    <a href="/contact.php" class="btn btn-primary">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
