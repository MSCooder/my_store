<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// functions.php ko formatPrice helper ke liye include karein agar zaroorat ho
require_once 'includes/functions.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $cart_key = $_POST['cart_key'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$cart_key])) {
        // 1. Quantity Update Logic
        if ($action === 'update' && isset($_POST['qty'])) {
            $new_qty = intval($_POST['qty']);
            if ($new_qty >= 1) {
                $_SESSION['cart'][$cart_key]['qty'] = $new_qty;
                $response['success'] = true;
                
                // Individual Item Total calculate karein
                $item_total_raw = $_SESSION['cart'][$cart_key]['price'] * $new_qty;
                $response['item_total'] = formatPrice($item_total_raw);
            }
        } 
        // 2. Item Remove Logic
        elseif ($action === 'delete') {
            unset($_SESSION['cart'][$cart_key]);
            $response['success'] = true;
        }

        // 3. Poore Cart ka Subtotal Dubara Calculate karein
        $new_subtotal = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $new_subtotal += $item['price'] * $item['qty'];
            }
            $response['cart_subtotal'] = formatPrice($new_subtotal);
            $response['cart_empty'] = false;
        } else {
            $response['cart_subtotal'] = formatPrice(0);
            $response['cart_empty'] = true;
        }
    }
}

// JSON Formatted Response Header taake JavaScript fetch accept kar sakay
header('Content-Type: application/json');
echo json_encode($response);
exit();