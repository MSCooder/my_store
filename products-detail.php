<?php
// 1. Database aur Header/Functions include karein
require_once 'config/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// 2. URL se ID get karein
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Database prepared statement taake query safely execute ho
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    // Agar product nahi mila toh shop ya home page par bhej dein
    if (!$product) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<style>
    .detail-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 0 5%;
        display: flex;
        gap: 50px;
        background: #fff;
        font-family: 'Poppins', sans-serif;
    }

    /* Left Side: Images Section */
    .product-gallery { flex: 1.2; }
    .main-img-box {
        width: 100%;
        height: 500px;
        background: #f9f9f9;
        margin-bottom: 15px;
        overflow: hidden;
        border-radius: 4px;
    }
    .main-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .thumb-row {
        display: flex;
        gap: 10px;
    }
    .thumb {
        width: 80px;
        height: 80px;
        background: #f9f9f9;
        cursor: pointer;
        border: 1px solid #eee;
        border-radius: 2px;
        overflow: hidden;
    }
    .thumb img { width: 100%; height: 100%; object-fit: cover; }

    /* Right Side: Info Section */
    .product-details-info { flex: 1; }
    .product-details-info h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        margin-bottom: 10px;
        color: #1a1a1a;
    }
    .price-tag {
        font-size: 24px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 15px;
    }
    .rating { color: #c4a47c; margin-bottom: 20px; font-size: 14px; }
    
    .desc-title { font-weight: 600; margin-bottom: 5px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
    .desc-text { color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 25px; }

    /* Selection Options */
    .option-group { margin-bottom: 20px; }
    .option-label { font-weight: 600; font-size: 13px; text-transform: uppercase; margin-bottom: 10px; display: block; }
    .metal-options { display: flex; gap: 10px; }
    .metal-btn {
        padding: 8px 20px;
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        font-size: 13px;
        transition: 0.3s;
        border-radius: 4px;
    }
    .metal-btn.active { background: #c4a47c; color: #fff; border-color: #c4a47c; }

    /* Quantity & Button */
    .qty-selector {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        width: fit-content;
        margin-bottom: 30px;
        border-radius: 4px;
    }
    .qty-btn {
        padding: 8px 15px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
    }
    .qty-input {
        width: 40px;
        text-align: center;
        border: none;
        outline: none;
        font-weight: 600;
    }

    .add-to-cart-btn {
        width: 100%;
        padding: 15px;
        background: #c4a47c;
        color: #fff;
        border: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        border-radius: 4px;
    }
    .add-to-cart-btn:hover { background: #1a1a1a; }

    @media (max-width: 768px) {
        .detail-container { flex-direction: column; }
        .main-img-box { height: 350px; }
    }
</style>

<div class="detail-container">
    <div class="product-gallery">
        <div class="main-img-box">
            <?php 
                // Fallback architecture for keys
                $img_src = isset($product['image']) ? $product['image'] : (isset($product['image_url']) ? $product['image_url'] : '');
                $prod_title = isset($product['name']) ? $product['name'] : (isset($product['title']) ? $product['title'] : 'Fine Jewelry');
            ?>
            <img src="<?= htmlspecialchars($img_src) ?>" id="mainImage" onerror="this.src='https://via.placeholder.com/600x600?text=Jewelry'">
        </div>
        <div class="thumb-row">
            <div class="thumb"><img src="<?= htmlspecialchars($img_src) ?>" onclick="changeImg(this.src)"></div>
            <div class="thumb"><img src="https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&w=200" onclick="changeImg(this.src)"></div>
            <div class="thumb"><img src="https://images.unsplash.com/photo-1598560912015-f3776e033967?auto=format&fit=crop&w=200" onclick="changeImg(this.src)"></div>
        </div>
    </div>

    <div class="product-details-info">
        <h1><?= htmlspecialchars($prod_title) ?></h1>
        <div class="price-tag"><?= formatPrice($product['price']) ?></div>
        
        <div class="rating">
            <span style="color: #c4a47c;">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
        </div>

        <div class="desc-title">Description</div>
        <p class="desc-text">
            <?= !empty($product['description']) ? htmlspecialchars($product['description']) : "This exquisite piece is crafted with precision to bring elegance to your collection. Perfect for special occasions or daily luxury." ?>
        </p>

        <div class="option-group">
            <span class="option-label">Metal</span>
            <div class="metal-options">
                <button class="metal-btn active" type="button" onclick="selectMetal(this)">Gold</button>
                <button class="metal-btn" type="button" onclick="selectMetal(this)">Silver</button>
            </div>
        </div>

        <div class="option-group">
            <span class="option-label">Quantity</span>
            <div class="qty-selector">
                <button class="qty-btn" onclick="updateQty(-1)">-</button>
                <input type="text" value="1" id="qty" class="qty-input" readonly>
                <button class="qty-btn" onclick="updateQty(1)">+</button>
            </div>
        </div>

        <form action="cart.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="quantity" id="form-qty" value="1">
            <input type="hidden" name="metal" id="form-metal" value="Gold">
            <button type="submit" class="add-to-cart-btn"> <a href="index.php">Add to Cart</a> </button>
        </form>
    </div>
</div>

<script>
    // Image Switcher Logic
    function changeImg(src) {
        document.getElementById('mainImage').src = src;
    }

    // Metal Selection Toggler
    function selectMetal(element) {
        document.querySelectorAll('.metal-btn').forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');
        document.getElementById('form-metal').value = element.innerText;
    }

    // Quantity Counter Logic
    function updateQty(val) {
        let qtyInput = document.getElementById('qty');
        let formInput = document.getElementById('form-qty');
        let currentVal = parseInt(qtyInput.value);
        
        let newVal = currentVal + val;
        if (newVal < 1) newVal = 1;
        
        qtyInput.value = newVal;
        formInput.value = newVal;
    }
</script>

<?php include 'includes/footer.php'; ?>