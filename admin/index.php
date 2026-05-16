<?php 
include 'includes/header.php'; 

// Real-Time Analytics Counter Calculations
try {
    // 1. Total Orders count
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    
    // 2. Out of stock jewelry check
    $outOfStock = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= 0")->fetchColumn();
    
    // 3. Low stock warning array mapping (products having stock less than 5)
    $lowStockStmt = $pdo->query("SELECT id, name, stock_quantity FROM products WHERE stock_quantity > 0 AND stock_quantity <= 5");
    $lowStockItems = $lowStockStmt->fetchAll();
    
} catch (PDOException $e) {
    echo "Dashboard Error: " . $e->getMessage();
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family: 'Playfair Display', serif; font-weight: 700;">Operational Overview</h2>
        <span class="badge bg-dark p-2">Live Store Status</span>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card card-premium p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase small">Total Orders Logged</h6>
                        <h3 class="fw-bold m-0"><?= $totalOrders ?></h3>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-primary"><i class="fa-solid fa-bag-shopping fa-xl"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-premium p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase small">Out of Stock Pieces</h6>
                        <h3 class="fw-bold m-0 text-danger"><?= $outOfStock ?></h3>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-danger"><i class="fa-solid fa-triangle-exclamation fa-xl"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-premium p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase small">Active System Managers</h6>
                        <h3 class="fw-bold m-0 text-success">1</h3>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-success"><i class="fa-solid fa-user-shield fa-xl"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-premium p-4 bg-white">
                <h5 class="fw-bold mb-3 text-warning"><i class="fa-solid fa-warehouse me-2"></i> Critical Inventory Warning (Low Stock)</h5>
                
                <?php if (!empty($lowStockItems)): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product ID</th>
                                    <th>Jewelry Name</th>
                                    <th>Remaining Stock</th>
                                    <th>Status Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockItems as $item): ?>
                                    <tr>
                                        <td>#PROD-<?= $item['id'] ?></td>
                                        <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                                        <td><span class="badge badge-low-stock"><?= $item['stock_quantity'] ?> left</span></td>
                                        <td><a href="manage-products.php?edit=<?= $item['id'] ?>" class="btn btn-sm btn-dark">Refill Inventory</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-3 bg-light rounded text-muted text-center">
                        <i class="fa-solid fa-circle-check text-success me-2"></i> All jewelry item inventories are perfectly stocked above critical thresholds.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>