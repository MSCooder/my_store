<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// URL se Order ID check karein
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = intval($_GET['id']);

try {
    // 1. Main Order details fetch karein
    $stmtOrder = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmtOrder->execute([$order_id]);
    $order = $stmtOrder->fetch();

    if (!$order) {
        header("Location: index.php");
        exit();
    }

    // 2. Us order ke items details fetch karein
    $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmtItems->execute([$order_id]);
    $order_items = $stmtItems->fetchAll();

} catch (PDOException $e) {
    echo "Error fetching order data: " . $e->getMessage();
    exit();
}
?>

<style>
    .success-wrapper {
        max-width: 700px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }

    .success-icon {
        font-size: 60px;
        color: #c4a47c;
        margin-bottom: 20px;
        line-height: 1;
    }

    .success-title {
        font-family: 'Playfair Display', serif;
        font-size: 30px;
        color: #1a1a1a;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .success-msg {
        color: #666;
        font-size: 15px;
        margin-bottom: 30px;
    }

    .order-meta-box {
        background: #f9f9f7;
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 30px;
        text-align: left;
    }

    .meta-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: #444;
    }
    .meta-line:last-child { margin-bottom: 0; }
    .meta-line strong { color: #1a1a1a; }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        text-align: left;
    }

    .invoice-table th {
        padding: 12px;
        border-bottom: 2px solid #eee;
        font-size: 12px;
        text-transform: uppercase;
        color: #888;
        letter-spacing: 0.5px;
    }

    .invoice-table td {
        padding: 15px 12px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #333;
    }

    .invoice-total-line {
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        font-size: 18px;
        color: #1a1a1a;
        border-top: 2px solid #eee;
        padding-top: 20px;
        margin-top: 10px;
    }

    .actions-row {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 35px;
    }

    .btn-success-flow {
        padding: 12px 30px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 4px;
        text-decoration: none;
        transition: 0.3s;
        display: inline-block;
    }

    .btn-gold-fill {
        background: #c4a47c;
        color: #fff;
        border: 1px solid #c4a47c;
    }
    .btn-gold-fill:hover {
        background: #1a1a1a;
        border-color: #1a1a1a;
    }

    .btn-outline-dark {
        background: transparent;
        color: #1a1a1a;
        border: 1px solid #1a1a1a;
    }
    .btn-outline-dark:hover {
        background: #1a1a1a;
        color: #fff;
    }
</style>

<div class="success-wrapper">
    <div class="success-icon">&#10004;</div>
    
    <h2 class="success-title">Order Confirmed</h2>
    <p class="success-msg">Thank you for shopping with Aura Jewelry. Your order has been successfully placed!</p>

    <div class="order-meta-box">
        <div class="meta-line">
            <span>Order ID:</span>
            <strong>#AURA-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></strong>
        </div>
        <div class="meta-line">
            <span>Payment Method:</span>
            <strong><?= htmlspecialchars($order['payment_method']) ?></strong>
        </div>
        <div class="meta-line">
            <span>Contact Phone:</span>
            <strong><?= htmlspecialchars($order['phone']) ?></strong>
        </div>
        <div class="meta-line">
            <span>Shipping Address:</span>
            <strong style="text-align: right; max-width: 60%;"><?= htmlspecialchars($order['shipping_address']) ?></strong>
        </div>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Product Description</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_items as $item): ?>
            <tr>
                <td>
                    <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                    <br>
                    <small style="color: #888; text-transform: uppercase; font-size: 11px;">Metal: <?= htmlspecialchars($item['metal']) ?></small>
                </td>
                <td style="text-align: center;"><?= $item['quantity'] ?></td>
                <td style="text-align: right;"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="invoice-total-line">
        <span>Amount Paid / Payable</span>
        <span style="color: #c4a47c;"><?= formatPrice($order['total_amount']) ?></span>
    </div>

    <div class="actions-row">
        <a href="index.php" class="btn-success-flow btn-gold-fill">Continue Shopping</a>
        <a href="shop.php" class="btn-success-flow btn-outline-dark">View Catalog</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>