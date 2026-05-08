<?php


session_start();
require_once 'config/db.php';

// Item remove karne ka logic
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// Quantity update karne ka logic
if (isset($_POST['update'])) {
    foreach ($_POST['qty'] as $id => $val) {
        if ($val <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $val;
        }
    }
    header("Location: cart.php");
    exit();
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Matrix Store</title>
    <style>
        body { background-color: #0d0d0d; color: #00ff41; font-family: 'Courier New', monospace; margin: 20px; }
        h2 { border-bottom: 2px solid #00ff41; padding-bottom: 10px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #1a1a1a; }
        th, td { padding: 15px; text-align: left; border: 1px solid #333; }
        th { background: #00ff41; color: #000; }
        input[type="number"] { width: 50px; background: #262626; color: #fff; border: 1px solid #00ff41; padding: 5px; }
        .btn { padding: 8px 15px; text-decoration: none; font-weight: bold; cursor: pointer; display: inline-block; }
        .btn-update { background: transparent; color: #00ff41; border: 1px solid #00ff41; }
        .btn-remove { color: #ff4d4d; font-size: 12px; }
        .btn-checkout { background: #00ff41; color: #000; float: right; margin-top: 20px; }
        .total-section { text-align: right; margin-top: 20px; font-size: 1.2rem; }
    </style>
</head>
<body>

    <h2>Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="cart.php">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $quantity):
                        $sql = "SELECT * FROM products WHERE id = $id";
                        $result = mysqli_query($conn, $sql);
                        $product = mysqli_fetch_assoc($result);
                        $subtotal = $product['price'] * $quantity;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $product['name']; ?></td>
                        <td>Rs. <?php echo $product['price']; ?></td>
                        <td>
                            <input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $quantity; ?>">
                        </td>
                        <td>Rs. <?php echo $subtotal; ?></td>
                        <td>
                            <a href="cart.php?remove=<?php echo $id; ?>" class="btn-remove">REMOVE</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <strong>Grand Total: Rs. <?php echo $total; ?></strong>
            </div>

            <button type="submit" name="update" class="btn btn-update">Update Cart</button>
            <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout</a>
        </form>
    <?php else: ?>
        <p>Your cart is empty. <a href="index.php" style="color: #fff;">Go shopping!</a></p>
    <?php endif; ?>
    <?php 
// 3. Aakhir mein footer file include karein
include 'includes/footer.php'; 
?>

</body>
</html>