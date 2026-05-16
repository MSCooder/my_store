<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/functions.php';

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $key = $_POST['cart_key'];
    
    if ($_POST['action'] === 'update' && isset($_SESSION['cart'][$key])) {
        $qty = intval($_POST['qty']);
        if($qty < 1) $qty = 1;
        
        $_SESSION['cart'][$key]['qty'] = $qty;
        
        $item_total = $_SESSION['cart'][$key]['price'] * $qty;
        $response['item_total'] = formatPrice($item_total);
        $response['success'] = true;
    }
    
    if ($_POST['action'] === 'delete' && isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
        $response['success'] = true;
    }

    // Recalculate whole subtotal context
    $subtotal = 0;
    if(isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
    }
    
    $response['cart_subtotal'] = formatPrice($subtotal);
    $response['cart_empty'] = empty($_SESSION['cart']);
}

header('Content-Type: application/json');
echo json_encode($response);
exit();