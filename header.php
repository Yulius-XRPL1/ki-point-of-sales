<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Toko Sepatu</title>
</head>
<body>
    <div class="navbar">
        <h2>Toko Sepatu</h2>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="categories.php">Kategori</a></li>
            <li><a href="products.php">Produk</a></li>
            <li><a href="customers.php">Pelanggan</a></li>
            <li><a href="transactions.php">Transaksi</a></li>
            <li><a href="reports.php">Laporan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
