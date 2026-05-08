<?php
$conn = mysqli_connect("localhost", "root", "", "my_store_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>