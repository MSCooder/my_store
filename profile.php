<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Agar user logged in nahi hai, toh usey login page par redirect kar dein
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // 1. User ki account details fetch karein (users table se)
    $stmtUser = $pdo->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch();

    if (!$user) {
        // Fallback agar user record na mile
        session_destroy();
        header("Location: login.php");
        exit();
    }

    // 2. User ki complete Order History fetch karein (orders table se)
    $stmtOrders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmtOrders->execute([$user_id]);
    $orders = $stmtOrders->fetchAll();

} catch (PDOException $e) {
    echo "Profile Loading Error: " . $e->getMessage();
    exit();
}
?>

<style>
    .profile-container {
        max-width: 1100px;
        margin: 50px auto;
        padding: 0 5%;
        font-family: 'Poppins', sans-serif;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 40px;
        align-items: start;
    }

    /* Left Sidebar: User Info Card */
    .profile-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        background: #c4a47c;
        color: #fff;
        font-size: 32px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 20px auto;
        text-transform: uppercase;
    }

    .profile-card h2 {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        color: #1a1a1a;
        margin: 0 0 5px 0;
    }

    .profile-card .user-email {
        font-size: 14px;
        color: #777;
        margin-bottom: 25px;
    }

    .profile-meta-info {
        border-top: 1px solid #eee;
        padding-top: 20px;
        text-align: left;
        font-size: 13px;
        color: #555;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .btn-logout {
        display: block;
        margin-top: 25px;
        padding: 10px;
        background: transparent;
        color: #d9534f;
        border: 1px solid #d9534f;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }
    .btn-logout:hover {
        background: #d9534f;
        color: #fff;
    }

    /* Right Section: Order History */
    .orders-section h3 {
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        color: #1a1a1a;
        margin: 0 0 25px 0;
        letter-spacing: 0.5px;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 6px;
        overflow: hidden;
    }

    .orders-table th {
        background: #f9f9f7;
        padding: 15px;
        font-size: 12px;
        text-transform: uppercase;
        color: #666;
        letter-spacing: 0.5px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .orders-table td {
        padding: 18px 15px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #eee;
    }

    /* Status Badge Styling */
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }
    .status-pending { background: #fef9e7; color: #f39c12; }
    .status-completed { background: #eaf2f8; color: #2980b9; }
    .status-delivered { background: #e8f8f5; color: #2ecc71; }
    .status-cancelled { background: #ffdac1; color: #ff3b30; }

    .btn-view-invoice {
        color: #c4a47c;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s;
    }
    .btn-view-invoice:hover {
        color: #1a1a1a;
        text-decoration: underline;
    }

    .no-orders-box {
        border: 1px dashed #ddd;
        padding: 40px;
        text-align: center;
        border-radius: 6px;
        color: #777;
    }

    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
        .orders-table, .orders-table tbody, .orders-table tr, .orders-table td, .orders-table th {
            display: block;
            width: 100%;
        }
        .orders-table tr { margin-bottom: 15px; border: 1px solid #eee; }
        .orders-table td { text-align: right; padding-left: 50%; position: relative; }
        .orders-table td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            color: #888;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-grid">
        
        <div class="profile-card">
            <div class="avatar-circle">
                <?= substr(htmlspecialchars($user['name']), 0, 1) ?>
            </div>
            
            <h2><?= htmlspecialchars($user['name']) ?></h2>
            <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
            
            <div class="profile-meta-info">
                <div class="meta-row">
                    <span>Account Type:</span>
                    <strong>Customer</strong>
                </div>
                <div class="meta-row">
                    <span>Joined Since:</span>
                    <strong><?= date('M d, Y', strtotime($user['created_at'])) ?></strong>
                </div>
            </div>
            
            <a href="logout.php" class="btn-logout">Logout Account</a>
        </div>

        <div class="orders-section">
            <h3>Your Order History</h3>
            
            <?php if (!empty($orders)): ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $row): 
                            // Status text filter mapping safely
                            $status_class = 'status-pending';
                            $current_status = strtolower($row['status']);
                            if ($current_status === 'completed') $status_class = 'status-completed';
                            if ($current_status === 'delivered') $status_class = 'status-delivered';
                            if ($current_status === 'cancelled') $status_class = 'status-cancelled';
                        ?>
                            <tr>
                                <td data-label="Order ID"><strong>#AURA-<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                                <td data-label="Date"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                <td data-label="Total Amount"><?= formatPrice($row['total_amount']) ?></td>
                                <td data-label="Status">
                                    <span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($row['status']) ?></span>
                                </td>
                                <td data-label="Invoice">
                                    <a href="order-success.php?id=<?= $row['id'] ?>" class="btn-view-invoice">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-orders-box">
                    <p style="font-size: 16px; margin-bottom: 15px;">You haven't placed any exquisite orders yet.</p>
                    <a href="shop.php" style="color: #c4a47c; font-weight: 600; text-decoration: none; text-transform: uppercase; font-size: 13px; letter-spacing: 1px;">Discover Collection &rarr;</a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>