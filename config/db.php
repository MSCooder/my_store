<?php
// config/db.php

// Database Configuration Parameters
$host    = 'localhost';
$db_name = 'aura_jewellers_db';
$username = 'root';
$password = ''; // XAMPP me default password khali (empty) hota hai
$charset  = 'utf8mb4';

// Data Source Name (DSN) setup
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

// Options for secure and efficient database interaction
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Errors ko exception ki tarah throw karega
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Data ko associative array me return karega
    PDO::ATTR_EMULATE_PREPARES   => false,                  // SQL Injection se high-level security ke liye
];

try {
    // PDO Instance create karna
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Testing ke liye line (Jab website chal jaye, toh is line ko comment kar sakte hain)
    // echo "Database connected successfully!"; 
    
} catch (PDOException $e) {
    // Agar connection fail ho jaye toh error show kare aur script rok de
    die("Database connection failed: " . $e->getMessage());
}
?>