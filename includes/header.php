<?php
// Session yahan start krna behtar hai taqy har page pr mil saky
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --matrix-green: #00ff41;
            --dark-bg: #0d0d0d;
            --card-bg: #1a1a1a;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--matrix-green);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        header {
            background: rgba(13, 13, 13, 0.9);
            border-bottom: 2px solid var(--matrix-green);
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 0 15px rgba(0, 255, 65, 0.3);
        }

        .logo {
            font-size: 26px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #fff;
            text-shadow: 0 0 10px var(--matrix-green);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            color: var(--matrix-green);
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .nav-links a:hover {
            color: #fff;
            text-shadow: 0 0 8px var(--matrix-green);
        }

        .cart-badge {
            background: #fff;
            color: #000;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-left: 5px;
        }

        .user-welcome {
            color: #888;
            font-size: 13px;
            border-left: 1px solid #333;
            padding-left: 20px;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo">My Store</a>
    
    <nav class="nav-links">
        <a href="index.php">Products</a>
        
        <a href="cart.php">
            Cart 
            <span class="cart-badge">
                <?php 
                $count = 0;
                if(isset($_SESSION['cart'])) {
                    $count = count($_SESSION['cart']);
                } elseif(isset($_COOKIE['guest_cart'])) {
                    $cart_data = json_decode($_COOKIE['guest_cart'], true);
                    $count = count($cart_data);
                }
                echo $count;
                ?>
            </span>
        </a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="user-welcome">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" style="color: #ff4d4d;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="registration.php">Register</a>
        <?php endif; ?>
    </nav>
</header>