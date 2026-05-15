<?php
// Session aur Database connect karein
require_once 'config/db.php';
session_start();

// 1. Login Check: Agar user login nahi hai toh login page par bhej dein
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=please_login");
    exit();
}

// 2. Check karein ke Cart khali toh nahi (Session cart logic)
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php?msg=empty_cart");
    exit();
}

$user_id = $_SESSION['user_id'];
$total_amount = 0;

// Total calculate karein (Image 7 ke mutabiq)
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['qty'];
}

try {
    $pdo->beginTransaction();

    // 3. Main Order save karein
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status, created_at) VALUES (?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $total_amount]);
    $order_id = $pdo->lastInsertId();

    // 4. Order ki details (items) save karein
    $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $item_stmt->execute([
            $order_id, 
            $product_id, 
            $item['qty'], 
            $item['price']
        ]);
    }

    // 5. Order successful hone ke baad Cart khali kar dein
    unset($_SESSION['cart']);

    $pdo->commit();
    
    // Success page par bhej dein
    header("Location: order-success.php?order_id=" . $order_id);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Order failed: " . $e->getMessage();
}
?>