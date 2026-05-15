<?php include 'admin_header.php'; require_once '../config/db.php'; ?>

<div class="card" style="max-width: 600px; margin: auto;">
    <h2 style="margin-bottom: 20px;">Upload New Design</h2>
    <form action="process_product.php" method="POST" enctype="multipart/form-data">
        <label>Design Title</label>
        <input type="text" name="title" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:5px;" required>
        
        <div style="display:flex; gap:10px;">
            <div style="flex:1">
                <label>Category</label>
                <select name="category" style="width:100%; padding:10px; margin:10px 0;">
                    <option>Rings</option>
                    <option>Necklaces</option>
                    <option>Earrings</option>
                </select>
            </div>
            <div style="flex:1">
                <label>Price ($)</label>
                <input type="number" name="price" style="width:100%; padding:10px; margin:10px 0;">
            </div>
        </div>

        <label>Product Image</label>
        <input type="file" name="product_image" style="margin:15px 0;" required>
        
        <button type="submit" class="btn btn-gold" style="width:100%; padding:12px; margin-top:10px;">Publish Design</button>
    </form>
</div>