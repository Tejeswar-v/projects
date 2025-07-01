<?php
session_start();
require 'db.php';

// Handle CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json"); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["message" => "Method not allowed. Use DELETE."]);
    exit();
}

if (!isset($_SESSION['username'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["message" => "User not logged in."]);
    exit();
}

$username = $_SESSION['username'];
$productId = $_GET['productId'];

// Debugging: Log the username and productId
error_log("Username: $username, Product ID: $productId");

try {
    // Remove item from cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE username = ? AND product_id = ?");
    $stmt->execute([$username, $productId]);

    // Debugging: Check if the row was deleted
    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "Product removed from cart successfully"]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Product not found in cart."]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    error_log("Error removing product: " . $e->getMessage()); // Log the error
    echo json_encode(["message" => "Error removing product.", "error" => $e->getMessage()]);
}
?>