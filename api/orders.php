




<?php
error_log("API accessed: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI']);
// api for diaplsying and updating the admin orders
session_start();

// Ensure only admin can access this API
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access denied']);
    exit();
}

require '../db.php'; // Include database connection

// Get the order ID from the URL
$orderId = $_GET['orderId'] ?? null;

if (!$orderId) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Order ID is required']);
    exit();
}

// Handle GET request (Fetch Order Details)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Fetch the order from the database
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Order not found']);
            exit();
        }

        // Return the order details
        echo json_encode($order);
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle PATCH request (Update Order Status)
elseif ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);
    $newStatus = $data['status'] ?? null;

    if (!$newStatus || !in_array($newStatus, ['pending', 'completed'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid status']);
        exit();
    }

    try {
        // Update the order status in the database
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Order not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle unsupported methods
else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}
?>