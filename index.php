<?php
session_start();
require_once 'config/db.php';

// --- ADD TO CART LOGIC ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1;

    if (isset($_SESSION['user_id'])) {
        // 1. Agar user login hai to direct SESSION mein save krain
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    } else {
        // 2. Agar user login NAHI hai to COOKIES mein save krain
        $cart_data = isset($_COOKIE['guest_cart']) ? json_decode($_COOKIE['guest_cart'], true) : [];
        
        if (isset($cart_data[$product_id])) {
            $cart_data[$product_id] += $quantity;
        } else {
            $cart_data[$product_id] = $quantity;
        }
        
        // Cookie set krain (30 din k liye)
        setcookie('guest_cart', json_encode($cart_data), time() + (86400 * 30), "/");
        
        echo "<script>alert('Product added to cookie cart! Please login to checkout.');</script>";
    }
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Matrix Store | Home</title>
    <style>
        body { background-color: #0d0d0d; color: #00ff41; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 10px 50px; border-bottom: 2px solid #00ff41; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #fff; }
        .nav-links a { color: #00ff41; text-decoration: none; margin-left: 20px; font-weight: bold; }
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 0 50px; }
        .product-card { background: #1a1a1a; border: 1px solid #333; padding: 15px; border-radius: 8px; text-align: center; transition: 0.3s; }
        .product-card:hover { border-color: #00ff41; box-shadow: 0 0 15px rgba(0, 255, 65, 0.2); }
        .product-card img { max-width: 100%; height: 200px; object-fit: cover; border-radius: 5px; }
        .product-card h3 { color: #fff; margin: 15px 0 10px 0; }
        .product-card p { color: #888; font-size: 14px; height: 40px; overflow: hidden; }
        .price { font-size: 20px; font-weight: bold; color: #00ff41; margin: 10px 0; }
        
        .add-btn { background: #00ff41; color: #000; border: none; padding: 10px 20px; font-weight: bold; cursor: pointer; width: 100%; border-radius: 4px; }
        .add-btn:hover { background: #00cc33; }
    </style>
</head>
<body>

<!-- <nav>
    <div class="logo">MATRIX STORE</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</nav> -->

<h2 style="text-align: center; text-transform: uppercase; letter-spacing: 2px;">Available Products</h2>

<div class="product-grid">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM products");
    while ($product = mysqli_fetch_assoc($result)):
    ?>
    <div class="product-card">
        <img src="assets/images/<?php echo $product['image']; ?>" alt="Product Image">
        <h3><?php echo $product['name']; ?></h3>
        <p><?php echo $product['description']; ?></p>
        <div class="price">Rs. <?php echo $product['price']; ?></div>
        
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" name="add_to_cart" class="add-btn">ADD TO CART</button>
        </form>
    </div>
    <?php endwhile; ?>
</div>
<?php 
// 3. Aakhir mein footer file include karein
include 'includes/footer.php'; 
?>
</body>
</html>