<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(["message" => "User not logged in."]);
    exit();
}

$username = $_SESSION['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$totalPrice = $_POST['totalPrice'];

try {
    // Fetch cart items
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE username = ?");
    $stmt->execute([$username]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create a new order
    $stmt = $pdo->prepare("INSERT INTO orders (username, email, phone, address, total_price, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->execute([$username, $email, $phone, $address, $totalPrice]);

    // Clear the cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE username = ?");
    $stmt->execute([$username]);

    header("Location: acc.php");
} catch (PDOException $e) {
    echo json_encode(["message" => "Error submitting order.", "error" => $e->getMessage()]);
}
?>