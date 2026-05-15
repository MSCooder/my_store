<?php include 'admin_header.php'; require_once '../config/db.php'; ?>

<h1 style="margin-bottom: 20px;">Admin Dashboard</h1>
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
    <div class="card"><h3>Total Orders</h3><p style="font-size: 24px;">24</p></div>
    <div class="card"><h3>Active Designs</h3><p style="font-size: 24px;">12</p></div>
    <div class="card"><h3>Total Revenue</h3><p style="font-size: 24px;">$12,450</p></div>
</div>

<?php include '../includes/footer.php'; ?>