<?php
// 1. Database connection include karein
require_once 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check: Sirf authenticated Admin hi is page ko access kar sake
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?msg=admin_only");
    exit();
}

$msg = "";
$msg_class = "";

// 2. Form Submit hone par Product Add karne ka Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $p_name = trim($_POST['product_name']);
    $p_price = trim($_POST['product_price']);
    $p_desc = trim($_POST['product_description']);
    
    // IMAGE UPLOAD PATH FIX: Aapke folder structure ke mutabiq 'asserts/images/' use hoga
    $target_dir = "asserts/images/"; 
    $image_name = basename($_FILES["product_image"]["name"]);
    
    // Extension check karne ke liye
    $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    
    // File ka unique naam banayein taake same naam ki images overwrite na hon
    $new_image_name = time() . "_" . uniqid() . "." . $imageFileType;
    $target_file = $target_dir . $new_image_name;
    
    $uploadOk = 1;

    // Check karein ke file real image hai ya nahi
    if (!empty($_FILES["product_image"]["tmp_name"])) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if($check === false) {
            $msg = "File is not a valid image.";
            $msg_class = "error";
            $uploadOk = 0;
        }
    } else {
        $msg = "Please select an image.";
        $msg_class = "error";
        $uploadOk = 0;
    }

    // Sirf specific formats allow karein (jpg, jpeg, png, webp)
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp" ) {
        $msg = "Only JPG, JPEG, PNG & WEBP formats are allowed.";
        $msg_class = "error";
        $uploadOk = 0;
    }

    // Agar sab valid hai toh file move karein aur DB mein save karein
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            try {
                // Database query (Hone page par display karne ke liye target_file path save hoga)
                $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
                $stmt->execute([$p_name, $p_price, $p_desc, $target_file]);
                
                $msg = "Product added successfully! Check your Home Page.";
                $msg_class = "success";
            } catch (PDOException $e) {
                $msg = "Database Error: " . $e->getMessage();
                $msg_class = "error";
            }
        } else {
            $msg = "Error uploading your file. Check folder permissions.";
            $msg_class = "error";
        }
    }
}

// Header include karein
include 'includes/header.php';
?>

<style>
    .admin-container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
    }

    .admin-container h2 {
        font-family: 'Playfair Display', serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 30px;
        color: #1a1a1a;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        outline: none;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    .form-group input:focus, .form-group textarea:focus {
        border-color: #c4a47c;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: #c4a47c; /* Gold theme */
        color: white;
        border: none;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        cursor: pointer;
        border-radius: 4px;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: #1a1a1a;
    }

    /* Alerts */
    .alert { padding: 12px; border-radius: 4px; font-size: 14px; margin-bottom: 20px; text-align: center; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
</style>

<div class="admin-container">
    <h2>Add New Product</h2>

    <?php if($msg): ?>
        <div class="alert alert-<?= $msg_class ?>"><?= $msg ?></div>
    <?php endif; ?>

    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="product_name" placeholder="e.g., Solstice Gold Ring" required>
        </div>

        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" name="product_price" placeholder="e.g., 350" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="product_description" rows="4" placeholder="Enter jewelry details..." required></textarea>
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="product_image" accept="image/*" required>
        </div>

        <button type="submit" class="btn-submit">Upload Product</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>