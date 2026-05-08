<?php
session_start();
require_once 'config/db.php';

// Agar cart khali hai to wapis bhej dain
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// User ID check (optional: login lazmi hai to redirect krain)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $grand_total = $_POST['grand_total'];

    // 1. Orders table mein entry insert krna
    $order_query = "INSERT INTO orders (user_id, user_name, phone, address, total_price) 
                    VALUES ($user_id, '$name', '$phone', '$address', '$grand_total')";
    
    if (mysqli_query($conn, $order_query)) {
        $order_id = mysqli_insert_id($conn); // Abhi jo order save hua uski ID mil jaye gi

        // 2. Order Items table mein cart k sary products save krna
        foreach ($_SESSION['cart'] as $p_id => $qty) {
            // Product ki current price nikalna
            $p_res = mysqli_query($conn, "SELECT price FROM products WHERE id = $p_id");
            $p_data = mysqli_fetch_assoc($p_res);
            $p_price = $p_data['price'];

            $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                           VALUES ($order_id, $p_id, $qty, '$p_price')";
            mysqli_query($conn, $item_query);
        }

        // 3. Order successfully save hony k baad cart khali kr dain
        unset($_SESSION['cart']);
        
        echo "<script>alert('Order Placed Successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Matrix Store</title>
    <style>
        body { background-color: #0d0d0d; color: #00ff41; font-family: 'Courier New', monospace; display: flex; justify-content: center; padding: 50px; }
        .checkout-box { background: #1a1a1a; padding: 30px; border: 1px solid #00ff41; width: 450px; border-radius: 8px; }
        h2 { text-align: center; text-transform: uppercase; margin-bottom: 25px; border-bottom: 1px solid #333; padding-bottom: 10px; }
        label { display: block; margin-bottom: 5px; font-size: 14px; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 15px; background: #262626; border: 1px solid #333; color: #fff; box-sizing: border-box; }
        input:focus { border-color: #00ff41; outline: none; }
        .total-pay { font-size: 18px; font-weight: bold; margin: 15px 0; color: #fff; }
        .btn-order { width: 100%; padding: 12px; background: #00ff41; color: #000; border: none; font-weight: bold; cursor: pointer; text-transform: uppercase; }
        .btn-order:hover { background: #00cc33; box-shadow: 0 0 15px #00ff41; }
    </style>
</head>
<body>

<div class="checkout-box">
    <h2>Checkout Details</h2>
    
    <form method="POST" action="">
        <label>Full Name</label>
        <input type="text" name="name" required placeholder="Enter delivery name">

        <label>Phone Number</label>
        <input type="text" name="phone" required placeholder="03xxxxxxxxx">

        <label>Delivery Address</label>
        <textarea name="address" rows="3" required placeholder="Enter your full address"></textarea>

        <?php
        // Calculate Total again for verification
        $g_total = 0;
        foreach ($_SESSION['cart'] as $id => $qty) {
            $res = mysqli_query($conn, "SELECT price FROM products WHERE id = $id");
            $row = mysqli_fetch_assoc($res);
            $g_total += ($row['price'] * $qty);
        }
        ?>
        
        <div class="total-pay">Amount to Pay: Rs. <?php echo $g_total; ?></div>
        <input type="hidden" name="grand_total" value="<?php echo $g_total; ?>">

        <button type="submit" class="btn-order">Confirm Order (COD)</button>
    </form>
</div>
<?php 
// 3. Aakhir mein footer file include karein
include 'includes/footer.php'; 
?>

</body>
</html>