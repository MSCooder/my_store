<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// 1. Get Active Filters & Search Queries
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 2. Build SQL Query Dynamically
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $sql .= " AND (title LIKE ? OR name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY id DESC"; // Newest pieces first

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<style>
    .shop-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 5%;
        font-family: 'Poppins', sans-serif;
    }

    /* Search & Filter Header Section */
    .shop-utility-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 40px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .category-tabs {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .tab-link {
        text-decoration: none;
        color: #666;
        font-size: 13px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        padding: 8px 16px;
        border: 1px solid #eee;
        border-radius: 4px;
        transition: 0.3s;
    }

    .tab-link:hover, .tab-link.active {
        background: #c4a47c;
        color: #fff;
        border-color: #c4a47c;
    }

    .search-box-form {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
        max-width: 300px;
        width: 100%;
    }

    .search-box-form input {
        border: none;
        padding: 10px 15px;
        outline: none;
        width: 100%;
        font-size: 14px;
    }

    .search-box-form button {
        background: #f9f9f9;
        border: none;
        border-left: 1px solid #ddd;
        padding: 10px 15px;
        cursor: pointer;
        color: #555;
        transition: 0.2s;
    }
    .search-box-form button:hover { background: #eee; }

    /* Shop Grid Layout */
    .shop-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 30px;
    }

    .product-card {
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .img-wrap {
        width: 100%;
        height: 300px;
        background: #f9f9f9;
        overflow: hidden;
    }

    .img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .img-wrap img {
        transform: scale(1.06);
    }

    .product-info {
        padding: 20px 10px;
        text-align: center;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-info h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        margin: 0 0 8px 0;
        color: #1a1a1a;
    }

    .product-price {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .btn-view-details {
        margin-top: auto;
        display: block;
        text-decoration: none;
        text-align: center;
        padding: 11px;
        background: #1a1a1a;
        color: #fff;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        border-radius: 4px;
        transition: 0.3s;
    }

    .btn-view-details:hover {
        background: #c4a47c;
    }

    .no-products {
        text-align: center;
        grid-column: 1 / -1;
        padding: 60px 0;
        color: #666;
    }
</style>

<div class="shop-container">
    
    <div class="shop-utility-bar">
        <div class="category-tabs">
            <a href="shop.php" class="tab-link <?= empty($category) ? 'active' : '' ?>">All Collection</a>
            <a href="shop.php?category=Rings" class="tab-link <?= $category === 'Rings' ? 'active' : '' ?>">Rings</a>
            <a href="shop.php?category=Necklaces" class="tab-link <?= $category === 'Necklaces' ? 'active' : '' ?>">Necklaces</a>
            <a href="shop.php?category=Bracelets" class="tab-link <?= $category === 'Bracelets' ? 'active' : '' ?>">Bracelets</a>
            <a href="shop.php?category=Earrings" class="tab-link <?= $category === 'Earrings' ? 'active' : '' ?>">Earrings</a>
        </div>

        <form action="shop.php" method="GET" class="search-box-form">
            <?php if(!empty($category)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
            <?php endif; ?>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search jewelry...">
            <button type="submit">&#x1F50D;</button>
        </form>
    </div>

    <div class="shop-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): 
                // Flexible keys checks to sync fallback mechanics safely
                $img_path = isset($product['image']) ? $product['image'] : (isset($product['image_url']) ? $product['image_url'] : '');
                $title_text = isset($product['name']) ? $product['name'] : (isset($product['title']) ? $product['title'] : 'Fine Jewelry');
            ?>
                <div class="product-card">
                    <div class="img-wrap">
                        <img src="<?= htmlspecialchars($img_path) ?>" onerror="this.src='https://via.placeholder.com/400x450?text=Aura+Jewelry'" alt="<?= htmlspecialchars($title_text) ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($title_text) ?></h3>
                        <div class="product-price"><?= formatPrice($product['price']) ?></div>
                        
                        <a href="product_details.php?id=<?= $product['id'] ?>" class="btn-view-details">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-products">
                <p style="font-size: 18px; margin-bottom: 10px;">No exquisite pieces found matching your criteria.</p>
                <a href="shop.php" style="color: #c4a47c; font-weight:600; text-decoration:none;">Clear Filters & Reset</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>