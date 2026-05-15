<?php
// 1. Database connection aur functions include karein
require_once 'config/db.php';
require_once 'includes/functions.php';

// Agar user pehle se login hai toh home par bhej dein
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

// 2. Registration Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check karein ke email pehle se toh nahi
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email is already registered.";
        } else {
            // Password Hash karein (Security ke liye)
            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->execute([$full_name, $email, $hashed_pw]);
                $success = "Registration successful! You can now login.";
            } catch (PDOException $e) {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

include 'includes/header.php';
?>

<style>
    .auth-wrapper {
        display: flex;
        max-width: 1100px;
        margin: 60px auto;
        background: #fff;
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
        min-height: 600px;
    }

    /* Left Image Section (Matches Image 6.png) */
    .auth-side-img {
        flex: 1.2;
        background: url('https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?q=80&w=2070&auto=format&fit=crop') center/cover;
        position: relative;
    }

    /* Right Form Section */
    .auth-form-container {
        flex: 1;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .auth-form-container h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 35px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #1a1a1a;
    }

    .form-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 14px 15px;
        border: 1px solid #e8e8e8;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
        outline: none;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #c4a47c;
        background-color: #fdfbf8;
    }

    .btn-register {
        width: 100%;
        padding: 16px;
        background: #c4a47c; /* Gold theme from Image 6 */
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.4s ease;
        margin-top: 10px;
    }

    .btn-register:hover {
        background: #1a1a1a;
        transform: translateY(-2px);
    }

    .auth-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 14px;
        color: #666;
    }

    .auth-footer a {
        color: #1a1a1a;
        font-weight: 700;
        text-decoration: none;
        border-bottom: 2px solid #c4a47c;
    }

    /* Alerts */
    .alert { padding: 12px; border-radius: 5px; font-size: 13px; margin-bottom: 20px; }
    .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }

    @media (max-width: 900px) {
        .auth-side-img { display: none; }
        .auth-wrapper { max-width: 90%; }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-side-img"></div>

    <div class="auth-form-container">
        <h2>Create Account</h2>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <span class="form-label">Full Name</span>
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>

            <span class="form-label">Email Address</span>
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>

            <span class="form-label">Password</span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>

            <span class="form-label">Confirm Password</span>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>

            <button type="submit" class="btn-register">Register</button>
        </form>

        <p class="auth-footer">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>