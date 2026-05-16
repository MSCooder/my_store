<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

// Access secure check karein
checkAdminAccess();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Jewelry | Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #1a1a1a; color: #fff; width: 260px; position: fixed; }
        .sidebar .nav-link { color: #aaa; font-size: 14px; padding: 12px 20px; border-left: 3px solid transparent; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #242424; border-left-color: #c4a47c; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card-premium { border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .badge-low-stock { background-color: #ff3b30; color: white; }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar">
        <div class="p-4 text-center" style="border-bottom: 1px solid #2d2d2d;">
            <h4 style="font-family: 'Playfair Display', serif; color: #c4a47c; letter-spacing: 1px; margin: 0;">AURA ADMIN</h4>
            <small class="text-muted">Role: <?= strtoupper($_SESSION['user_role']) ?></small>
        </div>
        <div class="nav flex-column py-3">
            <a href="index.php" class="nav-link"><i class="fa-solid fa-chart-line me-2"></i> Overview</a>
            <a href="manage-products.php" class="nav-link"><i class="fa-solid fa-gem me-2"></i> Stock & Products</a>
            <a href="manage-orders.php" class="nav-link"><i class="fa-solid fa-cart-shopping me-2"></i> Orders Control</a>
            <a href="../index.php" class="nav-link mt-5 text-warning"><i class="fa-solid fa-arrow-left me-2"></i> View Website</a>
            <a href="../logout.php" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
        </div>
    </div>
    
    <div class="main-content w-100"></div>