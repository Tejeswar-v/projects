<?php
session_start();
require '../db.php'; // Adjust the path to db.php based on your directory structure

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(["message" => "User not logged in."]);
    exit();
}

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['productId'])) {
    header('Content-Type: application/json');
    echo json_encode(["message" => "Invalid request."]);
    exit();
}

$username = $_SESSION['username'];
$productId = $data['productId'];

try {
    // Check if the product exists
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Content-Type: application/json');
        echo json_encode(["message" => "Product not found"]);
        exit();
    }

    if ($product['count'] <= 0) {
        header('Content-Type: application/json');
        echo json_encode(["message" => "Product is out of stock"]);
        exit();
    }

    // Check if the product is already in the cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE username = ? AND product_id = ?");
    $stmt->execute([$username, $productId]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cartItem) {
        // Increment quantity
        $stmt = $pdo->prepare("UPDATE cart SET count = count + 1 WHERE id = ?");
        $stmt->execute([$cartItem['id']]);
    } else {
        // Add new item to cart
        $stmt = $pdo->prepare("INSERT INTO cart (username, product_id, count) VALUES (?, ?, 1)");
        $stmt->execute([$username, $productId]);
    }

    // Decrement product count
    $stmt = $pdo->prepare("UPDATE products SET count = count - 1 WHERE id = ?");
    $stmt->execute([$productId]);

    header('Content-Type: application/json');
    echo json_encode(["message" => "Product added to cart successfully"]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(["message" => "Server error", "error" => $e->getMessage()]);
}
?>