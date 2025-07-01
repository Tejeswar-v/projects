<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(["message" => "User not logged in."]);
    exit();
}

$username = $_SESSION['username'];
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$totalPrice = $_POST['totalPrice'];

try {
    // Begin a transaction
    $pdo->beginTransaction();

    // Step 1: Insert the order into the `orders` table
    $stmt = $pdo->prepare("INSERT INTO orders (username, email, phone, address, total_price, status, order_date) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->execute([$username, $email, $phone, $address, $totalPrice]);
    $orderId = $pdo->lastInsertId(); // Get the ID of the newly inserted order

    // Step 2: Fetch cart items for the user
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE username = ?");
    $stmt->execute([$username]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 3: Insert each cart item into the `order_items` table
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $item['product_id'], $item['count']]);
    }

    // Step 4: Clear the cart for the user
    $stmt = $pdo->prepare("DELETE FROM cart WHERE username = ?");
    $stmt->execute([$username]);

    // Commit the transaction
    $pdo->commit();

    // Redirect to acc.php after successful order placement
    header("Location: acc.php");
    exit();
} catch (PDOException $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();
    echo json_encode(["message" => "Error processing order.", "error" => $e->getMessage()]);
}
?>