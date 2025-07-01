<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $count = $_POST['count'];
    $image_url = $_POST['image_url'];

    try {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, count = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $count, $image_url, $productId]);
        header("Location: aha.php?cat=home");
        exit();
    } catch (PDOException $e) {
        die("Error updating product: " . $e->getMessage());
    }
}
?>