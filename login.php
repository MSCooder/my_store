<?php


session_start();
require_once 'config/db.php';

// Agar user pehly sy logged in hai to home page pr bhej dain
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Password verify krna (Assuming you used password_hash during registration)
        if (password_verify($password, $user['password'])) {
            
            // Session create krna
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // --- COOKIE TO SESSION LOGIC ---
            // Agar cookies mein pehly sy cart ka data ha, to usay session mein dalain
            if (isset($_COOKIE['guest_cart'])) {
                $cookie_cart = json_decode($_COOKIE['guest_cart'], true);
                $_SESSION['cart'] = $cookie_cart;
                
                // Cookie delete kr dain login k baad
                setcookie("guest_cart", "", time() - 3600, "/");
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | My Shop</title>
    <style>
        /* Modern High-Contrast Theme */
        body {
            background-color: #0d0d0d;
            color: #00ff41; /* Matrix Green */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.2);
            width: 350px;
            border: 1px solid #00ff41;
        }
        h2 { text-align: center; color: #fff; }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #262626;
            border: 1px solid #333;
            color: #fff;
            box-sizing: border-box;
        }
        input:focus { border-color: #00ff41; outline: none; }
        button {
            width: 100%;
            padding: 10px;
            background: #00ff41;
            border: none;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #00cc33; }
        .error { color: #ff4d4d; font-size: 14px; text-align: center; }
        a { color: #00ff41; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    
    <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" required placeholder="Enter your email">
        
        <label>Password</label>
        <input type="password" name="password" required placeholder="Enter password">
        
        <button type="submit">Login Now</button>
    </form>
    
    <p style="text-align:center; margin-top:15px; color:#ccc;">
        Don't have an account? <a href="registration.php">Register Here</a>
    </p>
</div>

</body>
</html>