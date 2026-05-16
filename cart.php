<?php
// 1. Session start karein taake dynamic cart data read/write ho sakay
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// 2. Incoming 'Add to Cart' POST Request ko Handle karein
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $product_id = intval($_POST['product_id']);
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $metal = isset($_POST['metal']) ? $_POST['metal'] : 'Gold';

    if ($qty < 1) $qty = 1;

    // Fetch product details directly from database to prevent price tampering
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $cart_key = $product_id . '_' . $metal; // Uniquely handle combinations

        if (isset($_SESSION['cart'][$cart_key])) {
            $_SESSION['cart'][$cart_key]['qty'] += $qty;
        } else {
            // Extract the right image property safely
            $img_path = isset($product['image']) ? $product['image'] : (isset($product['image_url']) ? $product['image_url'] : '');
            $prod_name = isset($product['name']) ? $product['name'] : (isset($product['title']) ? $product['title'] : 'Jewelry');

            $_SESSION['cart'][$cart_key] = [
                'id' => $product['id'],
                'name' => $prod_name,
                'price' => floatval($product['price']),
                'qty' => $qty,
                'image' => $img_path,
                'metal' => $metal
            ];
        }
    }
}

// 3. Sync local variables with active session array
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
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
    .p-info img { width: 80px; height: 80px; object-fit: cover; background: #f9f9f9; border-radius: 4px; }
    .p-meta { font-size: 11px; color: #888; display: block; text-transform: uppercase; margin-top: 3px; }
    
    /* Quantity Box Setup */
    .qty-box {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        width: fit-content;
        border-radius: 4px;
        overflow: hidden;
    }
    .qty-box button {
        background: #f9f9f9;
        border: none;
        padding: 5px 12px;
        cursor: pointer;
        font-size: 16px;
        transition: 0.2s;
    }
    .qty-box button:hover { background: #eee; }
    .qty-box input {
        width: 35px;
        text-align: center;
        border: none;
        font-weight: 600;
        outline: none;
    }

    /* Summary Block */
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
        border-radius: 4px;
        display: inline-block;
        text-decoration: none;
        text-align: center;
    }
    .checkout-btn:hover { background: #1a1a1a; }
    .remove-item { color: #999; cursor: pointer; font-size: 20px; transition: 0.3s; }
    .remove-item:hover { color: #d9534f; }
    
    .empty-cart-msg { text-align: center; padding: 40px 0; color: #666; }

    /* ULTRA MOBILE-RESPONSIVE MEDIA QUERY */
    @media (max-width: 768px) {
        .cart-title { font-size: 22px; margin-bottom: 25px; }
        
        /* Hide traditional table structures */
        .cart-table, .cart-table society, .cart-table tr, .cart-table td, .cart-table th {
            display: block;
            width: 100%;
        }
        .cart-table thead { display: none; } /* Hide headers */

        .cart-table tr {
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 10px 15px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            position: relative;
        }

        .cart-table td {
            text-align: right;
            padding: 12px 10px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-table td:last-child { border-bottom: none; }

        /* Dynamic headers conversion */
        .cart-table td::before {
            content: attr(data-label);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            color: #888;
            letter-spacing: 0.5px;
        }

        /* Adjustments for info area */
        .cart-table td[data-label="Product Details"] {
            display: block;
            text-align: left;
            padding-top: 15px;
        }
        .cart-table td[data-label="Product Details"]::before {
            display: block;
            margin-bottom: 10px;
        }
        .p-info { width: 100%; }

        .qty-box { margin-left: auto; }
        .cart-summary { max-width: 100%; margin-top: 30px; }
    }
</style>

<div class="cart-wrapper">
    <h2 class="cart-title">Your Shopping Bag</h2>

    <?php if (!empty($cart_items)): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product Details</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cart_items as $key => $item): 
                $item_total = $item['price'] * $item['qty'];
                $subtotal += $item_total;
            ?>
            <tr id="row-<?= $key ?>">
                <td data-label="Product Details">
                    <div class="p-info">
                        <img src="asserts/images/<?= htmlspecialchars($item['image']) ?>" onerror="this.src='https://via.placeholder.com/80?text=Jewelry'" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div>
                            <strong><?= htmlspecialchars($item['name']) ?></strong>
                            <span class="p-meta">Metal: <?= htmlspecialchars($item['metal']) ?></span>
                        </div>
                    </div>
                </td>
                <td data-label="Price"><?= formatPrice($item['price']) ?></td>
                <td data-label="Quantity">
                    <div class="qty-box">
                        <button type="button" onclick="changeQty('<?= $key ?>', -1)">-</button>
                        <input type="text" id="input-<?= $key ?>" value="<?= $item['qty'] ?>" readonly>
                        <button type="button" onclick="changeQty('<?= $key ?>', 1)">+</button>
                    </div>
                </td>
                <td data-label="Total" id="total-<?= $key ?>"><?= formatPrice($item_total) ?></td>
                <td data-label="Remove"><span class="remove-item" onclick="removeItem('<?= $key ?>')">&times;</span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="cart-summary">
        <div class="summary-line">
            <span>Subtotal</span>
            <span id="summary-subtotal"><?= formatPrice($subtotal) ?></span>
        </div>
        <div class="summary-line">
            <span>Shipping</span>
            <span style="color: #c4a47c;">Free</span>
        </div>
        <div class="summary-line summary-total">
            <span>Total</span>
            <span id="summary-total"><?= formatPrice($subtotal) ?></span>
        </div>
        
        <a href="checkout-process.php" class="checkout-btn">Proceed to Checkout</a>
    </div>
    <?php else: ?>
        <div class="empty-cart-msg">
            <p>Your shopping bag is empty.</p>
            <a href="shop.php" class="btn-gold" style="margin-top: 20px;">Go To Shop</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function updateCartBackend(cartKey, newQty, remove = false) {
        let formData = new FormData();
        formData.append('cart_key', cartKey);
        formData.append('qty', newQty);
        if(remove) {
            formData.append('action', 'delete');
        } else {
            formData.append('action', 'update');
        }

        fetch('cart-process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                if(remove || newQty <= 0) {
                    document.getElementById('row-' + cartKey).remove();
                } else {
                    document.getElementById('total-' + cartKey).innerText = data.item_total;
                }
                
                document.getElementById('summary-subtotal').innerText = data.cart_subtotal;
                document.getElementById('summary-total').innerText = data.cart_subtotal;
                
                if(data.cart_empty) {
                    location.reload();
                }
            }
        })
        .catch(err => console.error("Error managing cart:", err));
    }

    function changeQty(key, change) {
        let inputEl = document.getElementById('input-' + key);
        let currentVal = parseInt(inputEl.value);
        let newVal = currentVal + change;
        
        if (newVal >= 1) {
            inputEl.value = newVal;
            updateCartBackend(key, newVal);
        }
    }

    function removeItem(key) {
        if(confirm("Are you sure you want to remove this item?")) {
            updateCartBackend(key, 0, true);
        }
    }
</script>

<?php include 'includes/footer.php'; ?>