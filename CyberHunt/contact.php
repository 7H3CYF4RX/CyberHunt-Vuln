<?php
require_once __DIR__ . '/config/database.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Vulnerable to command injection when saving to log file
    $logFile = '/tmp/contact_' . date('Y-m-d') . '.log';
    $logEntry = "Name: $name | Email: $email | Subject: $subject | Message: $message";
    
    // Command injection vulnerability
    $cmd = "echo '$logEntry' >> $logFile";
    shell_exec($cmd);
    
    // Also save to database
    $db = getDB();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    
    $stmt = $db->prepare("INSERT INTO contact_submissions (name, email, subject, message, ip_address) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $subject, $message, $ip]);
    
    $success = true;
}

$pageTitle = 'Contact Us';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8">
            <div class="card p-4 p-md-5">
                <h3 class="fw-bold mb-4">Send us a Message</h3>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Thank you for your message! We'll get back to you within 24 hours.
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject</label>
                        <select name="subject" class="form-select" required>
                            <option value="">Select a subject</option>
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Order Issue">Order Issue</option>
                            <option value="Product Question">Product Question</option>
                            <option value="Return Request">Return Request</option>
                            <option value="Technical Support">Technical Support</option>
                            <option value="Feedback">Feedback</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" class="form-control" rows="6" placeholder="How can we help you?" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-send me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Contact Info -->
        <div class="col-lg-4">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-4">Contact Information</h5>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-geo-alt text-primary fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Address</h6>
                        <p class="text-muted mb-0">123 Tech Street, Silicon Valley<br>CA 94025, United States</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-envelope text-primary fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email</h6>
                        <p class="text-muted mb-0">support@cyberhunt.com<br>sales@cyberhunt.com</p>
                    </div>
                </div>
                
                <div class="d-flex mb-4">
                    <div class="me-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-telephone text-primary fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Phone</h6>
                        <p class="text-muted mb-0">+1 (555) 123-4567<br>Mon-Fri, 9am-6pm PST</p>
                    </div>
                </div>
            </div>
            
            <div class="card p-4">
                <h5 class="fw-bold mb-4">Business Hours</h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Monday - Friday</span>
                        <span>9:00 AM - 6:00 PM</span>
                    </li>
                    <li class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Saturday</span>
                        <span>10:00 AM - 4:00 PM</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Sunday</span>
                        <span>Closed</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="mt-5">
        <h3 class="fw-bold mb-4 text-center">Frequently Asked Questions</h3>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How long does shipping take?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Standard shipping takes 5-7 business days. Express shipping is available for 2-3 business days delivery. Free shipping on orders over $50.
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
                                We offer a 30-day return policy for all products. Items must be in original condition with tags attached. Contact our support team to initiate a return.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Do you offer international shipping?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Yes, we ship to over 50 countries worldwide. International shipping rates and delivery times vary by location. Check our shipping page for details.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
