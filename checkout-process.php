<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'includes/functions.php';

// 1. Check karein ke cart khali toh nahi hai
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php?msg=empty_cart");
    exit();
}

// 2. AUTHENTICATION CHECK: Agar user login nahi hai, toh usey login page par bhejein
if (!isset($_SESSION['user_id'])) {
    // redirect_to parameter save kar rahe hain taake login ke baad user wapas isi flow mein aaye
    header("Location: login.php?redirect_to=checkout-process.php");
    exit();
}

// 3. Agar login hai, toh Order place karne ka logic (Database Processing)
$user_id = $_SESSION['user_id'];
$subtotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

$msg = "";
$msg_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form fields input sanitization
    $shipping_address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $payment_method = $_POST['payment_method']; // e.g., 'COD' ya 'CARD'

    if (!empty($shipping_address) && !empty($phone)) {
        try {
            $pdo->beginTransaction();

            // i. Main Orders table mein entry insert karein (Ab columns exact match hain!)
            $sqlOrder = "INSERT INTO orders (user_id, total_amount, shipping_address, phone, payment_method, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())";
            $stmt = $pdo->prepare($sqlOrder);
            $stmt->execute([$user_id, $subtotal, $shipping_address, $phone, $payment_method]);
            $order_id = $pdo->lastInsertId();

            // ii. Order items table mein entry insert karein (Sahi column names ke sath)
            $stmtItems = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, metal) VALUES (?, ?, ?, ?, ?, ?)");
            
            foreach ($_SESSION['cart'] as $item) {
                $stmtItems->execute([
                    $order_id, 
                    $item['id'], 
                    $item['name'], 
                    $item['price'], 
                    $item['qty'], // Form/Session se aane wali value
                    $item['metal']
                ]);
            }

            // DB transaction complete commit karein
            $pdo->commit();

            // Order successful hone par cart khali kar dein
            unset($_SESSION['cart']);

            // Success page par bhej dein order ID ke sath
            header("Location: order-success.php?id=" . $order_id);
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            $msg = "Order Processing Error: " . $e->getMessage();
            $msg_class = "error";
        }
    } else {
        $msg = "Please fill in all required fields.";
        $msg_class = "error";
    }
}

include 'includes/header.php';
?>

<style>
    .checkout-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 5%;
        display: flex;
        gap: 40px;
        font-family: 'Poppins', sans-serif;
    }
    .checkout-form-box { flex: 1.2; background: #fff; padding: 30px; border: 1px solid #eee; border-radius: 8px; }
    .checkout-summary-box { flex: 1; background: #f9f9f7; padding: 30px; border-radius: 8px; height: fit-content; }
    
    .checkout-title { font-family: 'Playfair Display', serif; font-size: 22px; text-transform: uppercase; margin-bottom: 25px; color: #1a1a1a; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; color: #333; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; outline: none; font-size: 14px; box-sizing: border-box; }
    .form-group input:focus, .form-group textarea:focus { border-color: #c4a47c; }

    .summary-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #555; }
    .btn-place-order { width: 100%; padding: 15px; background: #c4a47c; color: white; border: none; text-transform: uppercase; font-weight: 600; cursor: pointer; border-radius: 4px; transition: 0.3s; margin-top: 20px; letter-spacing: 1px; }
    .btn-place-order:hover { background: #1a1a1a; }
    
    .alert { padding: 12px; border-radius: 4px; font-size: 14px; margin-bottom: 20px; text-align: center; }
    .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    @media (max-width: 768px) {
        .checkout-container { flex-direction: column-reverse; }
    }
</style>

<div class="checkout-container">
    <div class="checkout-form-box">
        <h2 class="checkout-title">Shipping Details</h2>
        
        <?php if($msg): ?>
            <div class="alert alert-error"><?= $msg ?></div>
        <?php endif; ?>

        <form action="checkout-process.php" method="POST">
            <div class="form-group">
                <label>Contact Phone Number *</label>
                <input type="text" name="phone" placeholder="e.g., +923001234567" required>
            </div>

            <div class="form-group">
                <label>Full Delivery Address *</label>
                <textarea name="address" rows="4" placeholder="House#, Street, City, Country" required></textarea>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method">
                    <option value="COD">Cash on Delivery (COD)</option>
                    <option value="CARD">Credit/Debit Card</option>
                </select>
            </div>

            <button type="submit" class="btn-place-order">Place Order Now</button>
        </form>
    </div>

    <div class="checkout-summary-box">
        <h2 class="checkout-title">Order Review</h2>
        <?php foreach($_SESSION['cart'] as $item): ?>
            <div class="summary-item">
                <span><?= htmlspecialchars($item['name']) ?> <strong>x <?= $item['qty'] ?></strong> <small style="color:#888;">(<?= htmlspecialchars($item['metal']) ?>)</small></span>
                <span><?= formatPrice($item['price'] * $item['qty']) ?></span>
            </div>
        <?php endforeach; ?>
        
        <div class="summary-item" style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px; font-weight: 700; font-size: 16px; color: #000;">
            <span>Total Payable</span>
            <span><?= formatPrice($subtotal) ?></span>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>