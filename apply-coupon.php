<?php
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['coupon_code'] ?? ''));
    
    if (empty($code)) {
        $_SESSION['error_message'] = 'Please enter a coupon code.';
        redirect('/cart.php');
    }
    
    $db = getDB();
    
    // SQL injection in coupon lookup
    $coupon = $db->query("SELECT * FROM coupons WHERE code = '$code' AND is_active = 1")->fetch(PDO::FETCH_ASSOC);
    
    if ($coupon) {
        // Check if expired
        if (strtotime($coupon['expiry_date']) < time()) {
            $_SESSION['error_message'] = 'This coupon has expired.';
        } elseif ($coupon['used_count'] >= $coupon['max_uses']) {
            $_SESSION['error_message'] = 'This coupon has reached its usage limit.';
        } else {
            // Apply discount
            $discount = 0;
            if ($coupon['discount_type'] === 'percentage') {
                // Get cart total
                $cartTotal = $db->query("SELECT SUM(p.sale_price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = " . $_SESSION['user_id'])->fetchColumn();
                $discount = $cartTotal * ($coupon['discount_value'] / 100);
            } else {
                $discount = $coupon['discount_value'];
            }
            
            $_SESSION['discount'] = $discount;
            $_SESSION['coupon_code'] = $code;
            $_SESSION['success_message'] = 'Coupon applied! You saved $' . number_format($discount, 2);
        }
    } else {
        $_SESSION['error_message'] = 'Invalid coupon code.';
    }
}

redirect('/cart.php');
?>
