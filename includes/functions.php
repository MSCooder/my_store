<?php
/**
 * includes/functions.php
 * Aura Jewelry - Global Utility Functions
 */

// 1. Database Connection check (Safety)
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/db.php';
}

/**
 * SQL Injection aur XSS attacks se bachne ke liye input ko sanitize karna
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Currency format karne ke liye (e.g., 1000 -> $1,000.00)
 */
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

/**
 * Cart ki total items count karne ke liye (Header mein use hota hai)
 * Yeh function Cookies aur Session dono ko handle karta hai
 */
function getCartCount() {
    $count = 0;
    
    // Agar user login hai toh Session check karein
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['cart'])) {
            // Cart session structure: [product_id => quantity]
            foreach ($_SESSION['cart'] as $qty) {
                $count += $qty;
            }
        }
    } 
    // Agar user guest hai toh Cookies check karein
    elseif (isset($_COOKIE['guest_cart'])) {
        $cookie_data = json_decode($_COOKIE['guest_cart'], true);
        if (is_array($cookie_data)) {
            foreach ($cookie_data as $qty) {
                $count += $qty;
            }
        }
    }
    
    return $count;
}

/**
 * Admin access check karne ke liye helper function
 */
function isAdmin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

/**
 * Redirect karne ke liye asan function
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Product ki detail nikalne ke liye function (Card ya Detail page ke liye)
 */
function getProductById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Cart ka Subtotal calculate karne ke liye
 */
function getCartTotal($pdo) {
    $total = 0;
    $items = [];

    // Data source select karein (Session ya Cookie)
    if (isset($_SESSION['user_id']) && isset($_SESSION['cart'])) {
        $items = $_SESSION['cart'];
    } elseif (isset($_COOKIE['guest_cart'])) {
        $items = json_decode($_COOKIE['guest_cart'], true);
    }

    if (!empty($items)) {
        foreach ($items as $id => $qty) {
            $product = getProductById($pdo, $id);
            if ($product) {
                $total += $product['price'] * $qty;
            }
        }
    }
    return $total;
}

/**
 * Check karein ke kya user logged in hai aur admin role rakhta hai
 */
// function isAdmin() {
//     return (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
// }

/**
 * Admin pages ko secure karne ke liye gatekeeper function
 */
function checkAdminAccess() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isAdmin()) {
        // Agar admin nahi hai toh direct shop page ya main index par bhej dein
        header("Location: ../shop.php");
        exit();
    }
}
?>


