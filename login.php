<?php
// 1. Session start aur database include
require_once 'config/db.php';
require_once 'includes/functions.php'; // redirect function ke liye

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Agar user pehle se login hai toh home par bhej dein
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

// 2. Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Database se user check karein
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Session variables set karein
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role']; // admin ya user check karne ke liye

            // Agar admin hai toh admin panel bhej dein, warna home page
            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

include 'includes/header.php';
?>

<style>
    .login-container {
        display: flex;
        max-width: 1000px;
        margin: 60px auto;
        background: #fff;
        box-shadow: 0 15px 40px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
        min-height: 550px;
    }

    /* Left Image Section (As per Image 5.png) */
    .login-image {
        flex: 1.1;
        background: url('https://images.unsplash.com/photo-1584302179602-e4c3d3fd629d?q=80&w=2070&auto=format&fit=crop') center/cover;
        position: relative;
    }

    /* Right Form Section */
    .login-form-box {
        flex: 1;
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .login-form-box h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #1a1a1a;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 14px 15px;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
        outline: none;
        font-size: 14px;
        transition: 0.3s ease;
    }

    .form-group input:focus {
        border-color: #c4a47c;
        box-shadow: 0 0 5px rgba(196, 164, 124, 0.2);
    }

    /* Remember Me Checkbox */
    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        margin-bottom: 25px;
        color: #666;
        cursor: pointer;
    }

    .btn-signin {
        width: 100%;
        padding: 15px;
        background: #c4a47c; /* Premium Gold from Image 5 */
        color: white;
        border: none;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.4s;
        border-radius: 4px;
    }

    .btn-signin:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
    }

    .register-link {
        margin-top: 30px;
        font-size: 14px;
        text-align: center;
        color: #777;
    }

    .register-link a {
        color: #1a1a1a;
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1px solid #1a1a1a;
        padding-bottom: 2px;
    }

    @media (max-width: 850px) {
        .login-image { display: none; }
        .login-container { max-width: 90%; }
    }
</style>

<div class="login-container">
    <div class="login-image"></div>

    <div class="login-form-box">
        <h2>Welcome Back</h2>
        
        <?php if($error): ?>
            <div style="background: #fee2e2; color: #dc2626; padding: 12px; border-radius: 5px; font-size: 13px; margin-bottom: 20px; border: 1px solid #fecaca;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <label class="remember-me">
                <input type="checkbox" name="remember"> Remember Me
            </label>

            <button type="submit" class="btn-signin">Sign In</button>
        </form>

        <p class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>