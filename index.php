<?php 
require_once 'config/db.php';
require_once 'includes/functions.php'; 
include 'includes/header.php'; 

// Database se latest products fetch karein
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 12");
$featured = $stmt->fetchAll();
?>

<style>
    .hero-slider {
        height: 85vh;
        background: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.1)), 
                    url('https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=2070&auto=format&fit=crop') center/cover;
        display: flex;
        align-items: center;
        padding: 0 8%;
        color: white;
    }
    .hero-text h2 { font-family: 'Playfair Display', serif; font-size: 50px; letter-spacing: 2px; margin-bottom: 20px; text-transform: uppercase; }
    .btn-gold { background: #c4a47c; color: white; padding: 12px 30px; text-decoration: none; display: inline-block; font-size: 14px; letter-spacing: 1px; transition: 0.3s; }
    .btn-gold:hover { background: #1a1a1a; }

    .cat-section { padding: 80px 5%; text-align: center; background: #fff; }
    .cat-container { display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; margin-top: 40px; }
    .cat-item { text-decoration: none; color: #1a1a1a; }
    .cat-circle { width: 150px; height: 150px; border-radius: 20%; background: #f9f9f7; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; overflow: hidden; transition: 0.3s; border: 1px solid #eee; }
    .cat-circle img { width: 70%; transition: 0.3s; }
    .cat-item:hover .cat-circle { border-color: #c4a47c; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .cat-item p { font-weight: 500; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }

    .arrivals-section { padding: 60px 8%; background: #fdfdfb; text-align: center; }
    .section-title { font-family: 'Playfair Display', serif; font-size: 32px; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 2px; }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; }
    .p-card { background: white; padding: 15px; text-align: center; transition: 0.3s; border: 1px solid #f1f1f1; }
    .p-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
    .p-img { width: 100%; height: 280px; object-fit: cover; background: #f9f9f9; margin-bottom: 15px; }
    .p-card h4 { font-size: 16px; margin-bottom: 8px; color: #333; height: 40px; overflow: hidden; }
    .p-card .price { color: #1a1a1a; font-weight: 600; margin-bottom: 15px; display: block; }

    .about-section { padding: 100px 10%; display: flex; align-items: center; gap: 50px; background: #fff; }
    .about-img { flex: 1; height: 400px; background: url('https://images.unsplash.com/photo-1573408301185-9146fe634ad0?q=80&w=2000&auto=format&fit=crop') center/cover; }
    .about-text { flex: 1; }
    
    .contact-section { padding: 90px 10%; background: #f9f9f7; text-align: center; }
    .contact-container { max-width: 600px; margin: 50px auto 0; }
    .contact-container input, .contact-container textarea { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; outline: none; }
    .contact-container input:focus { border-color: #c4a47c; }

    @media (max-width: 768px) {
        .about-section { flex-direction: column; text-align: center; }
        .hero-text h2 { font-size: 32px; }
    }
</style>

<section class="hero-slider">
    <div class="hero-text">
        <h2>Elegance In<br>Every Detail</h2>
        <a href="shop.php" class="btn-gold">SHOP COLLECTIONS</a>
    </div>
</section>

<section class="cat-section">
    <div class="cat-container">
        <a href="shop.php?cat=rings" class="cat-item">
            <div class="cat-circle"><img src="asserts/images/image1.png" alt="Rings"></div>
            <p>Rings</p>
        </a>
        <a href="shop.php?cat=necklaces" class="cat-item">
            <div class="cat-circle"><img src="asserts/images/image2.png" alt="Necklaces"></div>
            <p>Necklaces</p>
        </a>
        <a href="shop.php?cat=earrings" class="cat-item">
            <div class="cat-circle"><img src="asserts/images/image3.png" alt="Earrings"></div>
            <p>Earrings</p>
        </a>
        <a href="shop.php?cat=bracelets" class="cat-item">
            <div class="cat-circle"><img src="asserts/images/image1.png" alt="Bracelets"></div>
            <p>Bracelets</p>
        </a>
    </div>
</section>

<section class="arrivals-section">
    <h3 class="section-title">New Arrivals</h3>
   <div class="product-grid">
        <?php if(!empty($featured)): ?>
            <?php foreach($featured as $p): ?>
            <div class="p-card">
                <?php 
                    // 1. Column Fallback Checks (Title vs Name mapping)
                    $prod_name = 'Luxury Jewelry';
                    if (isset($p['title'])) {
                        $prod_name = $p['title'];
                    } elseif (isset($p['name'])) {
                        $prod_name = $p['name'];
                    }

                    // 2. Image Path Alignment Framework
                    $db_image = isset($p['image']) ? $p['image'] : (isset($p['image_url']) ? $p['image_url'] : '');
                    
                    // Aapke folder ka naam 'asserts' hai (with single 'r'), isiliye direct prefix lagayein
                    $final_image_src = "asserts/images/" . $db_image;
                ?>
                
                <div style="width: 100%; height: 280px; overflow: hidden; background: #fafafa; margin-bottom: 15px;">
                    <img src="<?= htmlspecialchars($final_image_src) ?>" 
                         class="p-img" 
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/400x450?text=Aura+Jewelry'" 
                         alt="<?= htmlspecialchars($prod_name) ?>">
                </div>

                <h4><?= htmlspecialchars($prod_name) ?></h4>
                <span class="price"><?= isset($p['price']) ? formatPrice($p['price']) : '$0.00' ?></span>
                <a href="products-detail.php?id=<?= $p['id'] ?>" class="btn-gold" style="padding: 8px 20px;">View Details</a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; color: #666;">No products found. Please add from Admin Panel.</p>
        <?php endif; ?>
    </div>
</section>

<section class="about-section" id="about">
    <div class="about-img"></div>
    <div class="about-text">
        <h3 class="section-title" style="text-align: left; margin-bottom: 20px;">Our Heritage</h3>
        <p style="color: #666; line-height: 1.8;">Aura Jewelry stands for timeless beauty and craftsmanship. For decades, we have been crafting the finest gold and diamond pieces that celebrate life's most precious moments. Every design tells a story of luxury, purity, and passion.</p>
    </div>
</section>

<section class="contact-section" id="contact">
    <h3 class="section-title">Get In Touch</h3>
    <p style="color: #666;">Have a question? We'd love to hear from you.</p>
    <div class="contact-container">
        <form action="#">
            <input type="text" placeholder="Full Name" required>
            <input type="email" placeholder="Email Address" required>
            <textarea rows="5" placeholder="Your Message"></textarea>
            <button type="submit" class="btn-gold" style="border:none; cursor:pointer; width: 100%;">SEND MESSAGE</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>