<?php
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['coupon_code'] ?? ''));
    
    if (empty($code)) {
        $_SESSION['error_message'] = 'Please enter a coupon code.';
        header('Location: /cart.php');
        exit;
    }
    
    // Valid coupons - INTENTIONALLY VULNERABLE to business logic flaws
    $validCoupons = [
        'WELCOME10' => ['discount' => 10, 'type' => 'percentage', 'description' => 'Welcome Discount'],
        'GRAND20' => ['discount' => 20, 'type' => 'percentage', 'description' => 'Grand Sale Offer'],
        'FLAT50' => ['discount' => 50, 'type' => 'fixed', 'description' => 'Flat $50 Off']
    ];
    
    if (isset($validCoupons[$code])) {
        $coupon = $validCoupons[$code];
        
        // BUSINESS LOGIC VULNERABILITY: Coupon stacking
        // When applying a DIFFERENT coupon than the previous one, 
        // the discount ADDS to the existing discount instead of replacing it.
        // This allows infinite discount stacking by alternating between coupons.
        
        $db = getDB();
        $cartTotal = 0;
        
        if (isset($_SESSION['user_id'])) {
            $result = $db->query("SELECT SUM(p.sale_price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = " . $_SESSION['user_id']);
            $cartTotal = $result->fetchColumn() ?: 0;
        }
        
        if ($cartTotal <= 0) {
            $cartTotal = 100; // Demo fallback
        }
        
        // Calculate discount for current coupon
        $currentDiscount = 0;
        if ($coupon['type'] === 'percentage') {
            $currentDiscount = $cartTotal * ($coupon['discount'] / 100);
        } else {
            $currentDiscount = $coupon['discount'];
        }
        
        // VULNERABILITY: Check previous coupon
        $previousCoupon = $_SESSION['coupon_code'] ?? '';
        $previousDiscount = $_SESSION['discount'] ?? 0;
        
        // If different coupon, ADD discounts (vulnerable!)
        // This allows: WELCOME10 -> GRAND20 -> WELCOME10 -> GRAND20... infinitely
        if ($previousCoupon !== '' && $previousCoupon !== $code) {
            $totalDiscount = $previousDiscount + $currentDiscount;
            $_SESSION['success_message'] = "🎉 Coupon '{$code}' applied! Combined discount: $" . number_format($totalDiscount, 2);
        } else {
            $totalDiscount = $currentDiscount;
            $_SESSION['success_message'] = "✓ Coupon '{$code}' applied! You save: $" . number_format($totalDiscount, 2);
        }
        
        $_SESSION['discount'] = $totalDiscount;
        $_SESSION['coupon_code'] = $code;
        
    } else {
        $_SESSION['error_message'] = 'Invalid coupon code. Try: WELCOME10, GRAND20, or FLAT50';
    }
    
    header('Location: /cart.php');
    exit;
}

// GET request - redirect to cart
header('Location: /cart.php');
exit;
?>
