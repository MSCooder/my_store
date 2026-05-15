<?php
// 1. Database aur Header include karein
require_once 'config/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Cart data handle karne ka logic (Session ya Cookie se)
$cart_items = [];
$subtotal = 0;

// Yeh sirf dummy data hai aapke screenshot ke mutabiq dikhane ke liye
// Asal mein yahan aapki session/cookie logic chalegi
$cart_items = [
    ['id' => 1, 'name' => 'Solstice Necklace', 'price' => 480, 'qty' => 1, 'img' => 'necklace.jpg'],
    ['id' => 2, 'name' => 'Luna Ring', 'price' => 250, 'qty' => 1, 'img' => 'ring.jpg']
];
?>

<style>
    .cart-wrapper {
        max-width: 1100px;
        margin: 50px auto;
        padding: 0 5%;
        font-family: 'Poppins', sans-serif;
    }
    .cart-title {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 40px;
    }
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }
    .cart-table th {
        text-align: left;
        padding: 15px;
        background: #f9f9f7;
        font-size: 13px;
        text-transform: uppercase;
        color: #666;
        border-bottom: 1px solid #eee;
    }
    .cart-table td {
        padding: 20px 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    .p-info { display: flex; align-items: center; gap: 20px; }
    .p-info img { width: 80px; height: 80px; object-fit: cover; background: #f9f9f9; }
    
    /* Quantity Selector */
    .qty-box {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        width: fit-content;
    }
    .qty-box button {
        background: none;
        border: none;
        padding: 5px 12px;
        cursor: pointer;
        font-size: 16px;
    }
    .qty-box input {
        width: 35px;
        text-align: center;
        border: none;
        font-weight: 600;
        outline: none;
    }

    /* Summary Section */
    .cart-summary {
        max-width: 350px;
        margin-left: auto;
        text-align: right;
    }
    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 15px;
    }
    .summary-total {
        border-top: 1px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
        font-weight: 700;
        font-size: 18px;
    }
    .checkout-btn {
        width: 100%;
        padding: 15px;
        background: #c4a47c;
        color: white;
        border: none;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
        margin-top: 25px;
        cursor: pointer;
        transition: 0.3s;
    }
    .checkout-btn:hover { background: #1a1a1a; }
    .remove-item { color: #999; cursor: pointer; font-size: 18px; transition: 0.3s; }
    .remove-item:hover { color: #000; }
</style>

<div class="cart-wrapper">
    <h2 class="cart-title">Your Shopping Bag</h2>

    <table class="cart-table">
        <thead>
            <tr>
                <th>Product Image & Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cart_items as $item): 
                $item_total = $item['price'] * $item['qty'];
                $subtotal += $item_total;
            ?>
            <tr>
                <td>
                    <div class="p-info">
                        <img src="https://via.placeholder.com/80?text=Jewelry" alt="<?= $item['name'] ?>">
                        <span><?= $item['name'] ?></span>
                    </div>
                </td>
                <td>$<?= $item['price'] ?></td>
                <td>
                    <div class="qty-box">
                        <button onclick="updateQty(<?= $item['id'] ?>, -1)">-</button>
                        <input type="text" value="<?= $item['qty'] ?>" readonly>
                        <button onclick="updateQty(<?= $item['id'] ?>, 1)">+</button>
                    </div>
                </td>
                <td>$<?= $item_total ?></td>
                <td><span class="remove-item">&times;</span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="cart-summary">
        <div class="summary-line">
            <span>Subtotal</span>
            <span>$<?= $subtotal ?></span>
        </div>
        <div class="summary-line">
            <span>Shipping</span>
            <span style="color: #c4a47c;">Free</span>
        </div>
        <div class="summary-line summary-total">
            <span>Total</span>
            <span>$<?= $subtotal ?></span>
        </div>
        <button class="checkout-btn">Proceed to Checkout</button>
    </div>
</div>

<script>
    function updateQty(id, change) {
        // Yahan aap AJAX call likhenge taake page refresh kiye baghair cart update ho
        console.log("Updating product " + id + " by " + change);
    }
</script>

<?php include 'includes/footer.php'; ?>