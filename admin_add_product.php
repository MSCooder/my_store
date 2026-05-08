<?php 
// 3. Aakhir mein footer file include karein
include 'includes/header.php'; 
?>

<?php
require_once 'config/db.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    // Image Upload Logic
    $image_name = $_FILES['product_image']['name'];
    $tmp_name = $_FILES['product_image']['tmp_name'];
    $folder = "assets/images/" . $image_name;

    // Database me record save krna
    $sql = "INSERT INTO products (name, price, image, description) VALUES ('$name', '$price', '$image_name', '$desc')";
    
    if (mysqli_query($conn, $sql)) {
        // Picture ko folder me transfer krna
        if (move_uploaded_file($tmp_name, $folder)) {
            echo "<script>alert('Product Added Successfully!');</script>";
        } else {
            echo "Image upload failed.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Add Product</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .form-container { max-width: 500px; margin: 50px auto; background: #1a1a1a; padding: 30px; border: 1px solid #00ff41; border-radius: 10px; }
        h2 { text-align: center; color: #fff; margin-bottom: 20px; text-transform: uppercase; }
        input, textarea { margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Product</h2>
    <!-- Note: enctype="multipart/form-data" file upload k liye lazmi ha -->
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" required placeholder="e.g. Mechanical Keyboard">
        </div>

        <div class="form-group">
            <label>Price (Rs.)</label>
            <input type="number" name="price" required placeholder="e.g. 5000">
        </div>

        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="product_image" accept="image/*" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Brief details about product..."></textarea>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Upload Product</button>
    </form>
</div>
<?php 
// 3. Aakhir mein footer file include karein
include 'includes/footer.php'; 
?>
</body>
</html>