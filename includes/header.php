<?php
// includes/header.php
require_once 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cart items count calculate karne ka logic
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
} elseif (isset($_COOKIE['guest_cart'])) {
    $cookie_data = json_decode($_COOKIE['guest_cart'], true);
    $cart_count = is_array($cookie_data) ? count($cookie_data) : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        :root {
            --primary-dark: #1a1a1a;
            --accent-gold: #c4a47c;
            --light-gray: #f9f9f9;
            --border-color: #eee;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: var(--primary-dark); }

        .main-header {
            width: 100%;
            background: #fff;
            padding: 12px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Search Section - Matches Image 11 */
        .search-container {
            display: flex;
            align-items: center;
            background: var(--light-gray);
            padding: 8px 18px;
            border-radius: 25px;
            width: 220px;
        }
        .search-container i { color: #888; font-size: 13px; }
        .search-container input {
            border: none;
            background: transparent;
            margin-left: 10px;
            font-size: 13px;
            outline: none;
            width: 100%;
        }

        /* Logo Section */
        .logo a { text-decoration: none; color: var(--primary-dark); text-align: center; }
        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            letter-spacing: 4px;
            text-transform: uppercase;
            line-height: 1;
        }
        .logo span {
            font-size: 9px;
            letter-spacing: 6px;
            text-transform: uppercase;
            display: block;
            margin-top: 3px;
        }

        /* Navigation Links */
        .nav-links { display: flex; list-style: none; gap: 35px; }
        .nav-links a {
            text-decoration: none;
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .nav-links a:hover { color: var(--accent-gold); }

        /* Icons Section */
        .header-icons { display: flex; align-items: center; gap: 25px; }
        .header-icons a {
            color: var(--primary-dark);
            text-decoration: none;
            font-size: 18px;
            position: relative;
        }

        /* Profile Dropdown Logic */
        .user-menu { position: relative; display: inline-block; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 120px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 4px;
        }
        .dropdown-content a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 12px;
            border-bottom: 1px solid #f1f1f1;
        }
        .dropdown-content a:hover { background-color: var(--light-gray); color: var(--accent-gold); }
        .user-menu:hover .dropdown-content { display: block; }

        /* Cart Badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -10px;
            background: var(--accent-gold);
            color: white;
            font-size: 9px;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        @media (max-width: 992px) {
            .search-container, .nav-links { display: none; }
        }
    </style>
</head>
<body>

<header class="main-header">
    

    <div class="logo">
        <a href="index.php">
            <h1>Aura</h1>
            <span>Jewelry</span>
        </a>
    </div>

    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="collections.php">Collections</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>

    <div class="header-icons">
        <div class="user-menu">
            <a href="javascript:void(0)">
                <i class="fa-regular fa-user"></i>
            </a>
            <div class="dropdown-content">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="profile.php">My Profile</a>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <a href="admin/index.php">Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" style="color: #d9534f;">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="cart.php">
            <i class="fa-solid fa-bag-shopping"></i>
            <?php if($cart_count > 0): ?>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </a>
    </div>
</header>
<script src="js/main.js" defer></script>
</body>
</html>