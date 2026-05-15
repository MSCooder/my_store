<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aura Jewelry Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --sidebar-bg: #1e293b; --accent: #c4a47c; --bg: #f8fafc; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; background: var(--bg); }
        
        .sidebar { width: 260px; height: 100vh; background: var(--sidebar-bg); color: white; padding: 20px; position: fixed; }
        .sidebar h2 { font-size: 22px; margin-bottom: 40px; text-align: center; color: var(--accent); letter-spacing: 2px; }
        .sidebar a { display: block; color: #94a3b8; text-decoration: none; padding: 12px; margin-bottom: 5px; border-radius: 8px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #334155; color: white; }
        
        .main-content { flex: 1; margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .btn { padding: 8px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; border: none; }
        .btn-gold { background: var(--accent); color: white; }
        .btn-red { background: #ef4444; color: white; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th { text-align: left; padding: 12px; border-bottom: 2px solid #f1f5f9; color: #64748b; font-size: 13px; text-transform: uppercase; }
        table td { padding: 15px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .prod-img { width: 45px; height: 45px; border-radius: 8px; object-fit: cover; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>AURA JEWELRY</h2>
        <a href="admin.php">Dashboard</a>
        <a href="products.php" class="active">Products</a>
        <a href="orders.php">Orders</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="main-content"></div>