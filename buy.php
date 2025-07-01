<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(["message" => "User not logged in."]);
    exit();
}

$username = $_SESSION['username'];
$totalPrice = $_POST['totalPrice'];
$address = $_POST['address'];

try {
    // Fetch cart items
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE username = ?");
    $stmt->execute([$username]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create a new order
    $stmt = $pdo->prepare("INSERT INTO orders (username, email, phone, address, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $_SESSION['email'], $_SESSION['mobile'], $address, $totalPrice]);

    // Clear the cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE username = ?");
    $stmt->execute([$username]);

    header("Location: acc.php");
} catch (PDOException $e) {
    echo json_encode(["message" => "Error processing order.", "error" => $e->getMessage()]);
}
?>