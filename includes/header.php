<?php
// Includes mapping context setup safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check paths mapping variations safely
if (file_exists('includes/functions.php')) {
    require_once 'includes/functions.php';
} elseif (file_exists('functions.php')) {
    require_once 'functions.php';
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
    <title>Aura Jewelry | Luxury Collection</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        body { font-family: 'Poppins', sans-serif; color: var(--primary-dark); background-color: #fff; }

        .main-header {
            width: 100%;
            background: #fff;
            padding: 15px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Logo Section Style Balance */
        .logo a { text-decoration: none; color: var(--primary-dark); text-align: center; }
        .logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            letter-spacing: 4px;
            text-transform: uppercase;
            line-height: 1;
            font-weight: 600;
        }
        .logo span {
            font-size: 9px;
            letter-spacing: 6px;
            text-transform: uppercase;
            display: block;
            margin-top: 4px;
            color: #666;
        }

        /* Navigation Links Layout Grid */
        .nav-links { display: flex; list-style: none; gap: 30px; align-items: center; }
        .nav-links a {
            text-decoration: none;
            color: var(--primary-dark);
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s ease;
        }
        .nav-links a:hover { color: var(--accent-gold); }

        /* Icons & Admin Short Route Grouping */
        .header-icons { display: flex; align-items: center; gap: 22px; }
        .header-icons a {
            color: var(--primary-dark);
            text-decoration: none;
            font-size: 18px;
            position: relative;
            transition: 0.3s ease;
        }
        .header-icons a:hover { color: var(--accent-gold); }

        /* Dedicated Quick Admin Badge Button */
        .admin-direct-link {
            font-size: 12px !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: var(--primary-dark);
            color: #fff !important;
            padding: 6px 12px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .admin-direct-link:hover {
            background: var(--accent-gold);
            color: #fff !important;
        }

        /* Profile Dropdown Logic */
        .user-menu { position: relative; display: inline-block; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 140px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            z-index: 1050;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 15px;
            text-decoration: none;
            display: block;
            font-size: 12px;
            text-transform: capitalize;
            border-bottom: 1px solid #f9f9f9;
        }
        .dropdown-content a:hover { background-color: var(--light-gray); color: var(--accent-gold); }
        .user-menu:hover .dropdown-content { display: block; }

        /* Shopping Bag Badge Customization */
        .cart-badge {
            position: absolute;
            top: -7px;
            right: -9px;
            background: var(--accent-gold);
            color: white;
            font-size: 9px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }


        /* Responsive Mobile Layout Scaling */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            .admin-text { display: none; } /* Hide word label on small screens */
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
            <li><a href="about">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>

    <div class="header-icons">
        
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin/index.php" class="admin-direct-link" title="Go to Control Panel">
                <i class="fa-solid fa-sliders"></i>
                <span class="admin-text">Admin</span>
            </a>
        <?php endif; ?>

          <ul class="nav-links">
            <li class="admin"><a href="admin/index.php">Admin</a></li>
        </ul>

        <div class="user-menu">
            <a href="javascript:void(0)" aria-label="User Account">
                <i class="fa-regular fa-user"></i>
            </a>
            <div class="dropdown-content">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php"><i class="fa-regular fa-id-card" style="margin-right:8px;"></i>My Profile</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/index.php"><i class="fa-solid fa-chart-line" style="margin-right:8px;"></i>Admin Panel</a>
                    <?php endif; ?>
                    <a href="logout.php" style="color: #d9534f;"><i class="fa-solid fa-arrow-right-from-bracket" style="margin-right:8px;"></i>Logout</a>
                <?php else: ?>
                    <a href="login.php"><i class="fa-solid fa-arrow-right-to-bracket" style="margin-right:8px;"></i>Login</a>
                    <a href="register.php"><i class="fa-solid fa-user-plus" style="margin-right:8px;"></i>Register</a>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="cart.php" aria-label="Shopping Cart">
            <i class="fa-solid fa-bag-shopping"></i>
            <?php if ($cart_count > 0): ?>
                <span class="cart-badge"><?= $cart_count ?></span>
            <?php endif; ?>
        </a>
    </div>
</header>

<script src="js/main.js" defer></script>