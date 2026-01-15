    </main>

    <!-- Footer -->
    <footer class="footer-cyber mt-5">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <h3 class="footer-brand">
                            <i class="bi bi-crosshair me-2"></i>CyberHunt
                        </h3>
                        <p class="text-muted mt-3">
                            Your premier destination for cutting-edge technology and lifestyle products. 
                            We curate the best products to enhance your digital life.
                        </p>
                        <div class="social-links mt-4">
                            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="/">Home</a></li>
                            <li><a href="/products.php">Products</a></li>
                            <li><a href="/about.php">About Us</a></li>
                            <li><a href="/contact.php">Contact</a></li>
                            <li><a href="/faq.php">FAQ</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <h5 class="footer-title">Categories</h5>
                        <ul class="footer-links">
                            <li><a href="/products.php?category=Electronics">Electronics</a></li>
                            <li><a href="/products.php?category=Accessories">Accessories</a></li>
                            <li><a href="/products.php?category=Home+Office">Home Office</a></li>
                            <li><a href="/products.php?category=Storage">Storage</a></li>
                            <li><a href="/products.php?category=Furniture">Furniture</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <h5 class="footer-title">Account</h5>
                        <ul class="footer-links">
                            <li><a href="/profile.php">My Profile</a></li>
                            <li><a href="/orders.php">Order History</a></li>
                            <li><a href="/cart.php">Shopping Cart</a></li>
                            <li><a href="/wishlist.php">Wishlist</a></li>
                            <li><a href="/settings.php">Settings</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <h5 class="footer-title">Support</h5>
                        <ul class="footer-links">
                            <li><a href="/help.php">Help Center</a></li>
                            <li><a href="/shipping.php">Shipping Info</a></li>
                            <li><a href="/returns.php">Returns</a></li>
                            <li><a href="/privacy.php">Privacy Policy</a></li>
                            <li><a href="/terms.php">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; <?php echo date('Y'); ?> CyberHunt. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="payment-methods">
                            <i class="bi bi-credit-card-2-front fs-4 me-2"></i>
                            <i class="bi bi-paypal fs-4 me-2"></i>
                            <i class="bi bi-wallet2 fs-4 me-2"></i>
                            <i class="bi bi-bank fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer-cyber {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffffff;
        }
        
        .footer-top {
            padding: 80px 0 50px;
        }
        
        .footer-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .footer-title {
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #ffffff;
            padding-left: 8px;
        }
        
        .social-links {
            display: flex;
            gap: 12px;
        }
        
        .social-link {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            background: var(--primary-gradient);
            transform: translateY(-3px);
            color: white;
        }
        
        .footer-bottom {
            background: rgba(0, 0, 0, 0.2);
            padding: 25px 0;
            font-size: 0.9rem;
        }
        
        .payment-methods {
            color: rgba(255, 255, 255, 0.7);
        }
    </style>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-cyber');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Auto-hide alerts
        document.querySelectorAll('.alert').forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
