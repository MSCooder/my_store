<?php include 'admin_header.php'; require_once '../config/db.php'; 

$stmt = $pdo->query("SELECT orders.*, users.name FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC");
$orders = $stmt->fetchAll();
?>

<h2>Customer Orders</h2>
<div class="card" style="margin-top:20px;">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $o): ?>
            <tr>
                <td>#ORD-<?= $o['id'] ?></td>
                <td><?= $o['name'] ?></td>
                <td>$<?= $o['total_amount'] ?></td>
                <td><?= date('d M, Y', strtotime($o['created_at'])) ?></td>
                <td><span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:12px;"><?= $o['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>