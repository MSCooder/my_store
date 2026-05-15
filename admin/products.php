<?php include 'admin_header.php'; require_once '../config/db.php'; 

// Fetch Products
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2>Product Management</h2>
    <a href="add-product.php" class="btn btn-gold">+ ADD NEW DESIGN</a>
</div>

<div style="display: flex; gap: 15px; margin-bottom: 30px; overflow-x: auto;">
    <?php foreach(array_slice($products, 0, 5) as $p): ?>
        <img src="../assets/images/products/<?= $p['image_url'] ?>" style="width:120px; height:120px; border-radius:10px; object-fit:cover; border: 2px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <?php endforeach; ?>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $row): ?>
            <tr>
                <td><img src="../assets/images/products/<?= $row['image_url'] ?>" class="prod-img"></td>
                <td><strong><?= $row['title'] ?></strong></td>
                <td><?= $row['category'] ?></td>
                <td>$<?= $row['price'] ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <button class="btn" style="background:#e2e8f0;">Edit</button>
                    <button class="btn btn-red">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body></html>