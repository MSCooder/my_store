<?php
session_start();
require_once 'config/db.php';

// Agar user pehly sy logged in hai to home pr bhej dain
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validations
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check krain k email pehly sy to mojood nahi
        $check_email = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($result) > 0) {
            $error = "Email already exists!";
        } else {
            // Password ko encrypt krna
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $sql)) {
                $message = "Registration successful! <a href='login.php' style='color:#fff;'>Login here</a>";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | My Shop</title>
    <style>
        body {
            background-color: #0d0d0d;
            color: #00ff41;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 65, 0.2);
            width: 380px;
            border: 1px solid #00ff41;
        }
        h2 { text-align: center; color: #fff; margin-bottom: 20px; }
        label { font-size: 14px; display: block; margin-top: 10px; }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background: #262626;
            border: 1px solid #333;
            color: #fff;
            box-sizing: border-box;
        }
        input:focus { border-color: #00ff41; outline: none; }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #00ff41;
            border: none;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
        }
        button:hover { background: #00cc33; box-shadow: 0 0 10px #00ff41; }
        .error { color: #ff4d4d; background: rgba(255, 77, 77, 0.1); padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 10px; }
        .success { color: #00ff41; background: rgba(0, 255, 65, 0.1); padding: 10px; border-radius: 5px; text-align: center; margin-bottom: 10px; }
        a { color: #00ff41; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Create Account</h2>
    
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    <?php if ($message) echo "<div class='success'>$message</div>"; ?>

    <form method="POST" action="">
        <label>Username</label>
        <input type="text" name="username" required placeholder="Enter your full name">
        
        <label>Email Address</label>
        <input type="email" name="email" required placeholder="name@example.com">
        
        <label>Password</label>
        <input type="password" name="password" required placeholder="Create a strong password">
        
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required placeholder="Repeat password">
        
        <button type="submit">Sign Up</button>
    </form>
    
    <p style="text-align:center; margin-top:15px; color:#ccc; font-size: 14px;">
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>