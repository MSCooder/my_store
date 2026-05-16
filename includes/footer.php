<?php
/**
 * includes/footer.php
 * Aura Jewelry - Premium Light Theme Footer
 */
?>
<style>
    /* Footer Main Styling */
    .main-footer {
        background-color: #f9f9f7; /* Soft premium light gray/cream background */
        padding: 80px 8% 30px;
        color: #1a1a1a;
        font-family: 'Poppins', sans-serif;
        border-top: 1px solid #eeeeee;
        padding-top: 60px;
    }

    .footer-wrapper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        max-width: 1300px;
        margin: 0 auto;
    }

    /* Column Headings */
    .footer-col h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 25px;
        text-transform: capitalize;
        letter-spacing: 0.5px;
    }

    /* Footer Links */
    .footer-col ul {
        list-style: none;
        padding: 0;
    }

    .footer-col ul li {
        margin-bottom: 12px;
    }

    .footer-col ul li a {
        text-decoration: none;
        color: #666666;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-col ul li a:hover {
        color: #c4a47c; /* Gold Accent */
        transform: translateX(5px);
    }

    /* Social Icons Styling */
    .social-links {
        display: flex;
        gap: 12px;
    }

    .social-links a {
        width: 38px;
        height: 38px;
        background-color: #1a1a1a;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 16px;
        transition: 0.3s ease;
    }

    .social-links a:hover {
        background-color: #c4a47c;
        transform: translateY(-5px);
    }

    /* Bottom Copyright Bar */
    .footer-bottom {
        max-width: 1300px;
        margin: 60px auto 0;
        padding-top: 25px;
        border-top: 1px solid #e5e5e5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #999999;
    }

    /* Mobile Responsiveness */
    @media (max-width: 992px) {
        .footer-wrapper { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 576px) {
        .footer-wrapper { grid-template-columns: 1fr; text-align: center; }
        .social-links { justify-content: center; }
        .footer-bottom { flex-direction: column; gap: 10px; }
    }
</style>

<footer class="main-footer">
    <div class="footer-wrapper">
        
        <div class="footer-col">
            <h4>Links</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Products</a></li>
                <li><a href="privacy.php">Privacy</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Category</h4>
            <ul>
                <li><a href="shop.php?cat=rings">Rings</a></li>
                <li><a href="shop.php?cat=necklaces">Collections</a></li>
                <li><a href="shop.php?cat=earrings">Silver</a></li>
                <li><a href="admin/index.php">Settings</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Links</h4>
            <ul>
                <li><a href="#">Units Guide</a></li>
                <li><a href="#">Store Locator</a></li>
                <li><a href="#">Terms</a></li>
                <li><a href="#">Returns</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Social Media</h4>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-pinterest"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Aura Jewelry. Designed with Elegance.</p>
        <p>Privacy Policy | Cookies Settings</p>
    </div>
</footer>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="assets/js/main.js"></script>
</body>
</html>