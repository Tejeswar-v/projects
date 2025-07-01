<?php
session_start();

// Redirect if user is not logged in or is not admin
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Include database connection

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "Order status updated successfully!";
        } else {
            $_SESSION['error'] = "No changes made or order not found";
        }
        header("Location: aord.php");
        exit();
    } catch (PDOException $e) {
        die("Error updating order status: " . $e->getMessage());
    }
}

try {
    // Fetch all orders with product details
    $stmt = $pdo->query("
        SELECT orders.*, order_items.product_id, order_items.quantity, products.name, products.price 
        FROM orders 
        JOIN order_items ON orders.id = order_items.order_id 
        JOIN products ON order_items.product_id = products.id 
        ORDER BY orders.order_date DESC
    ");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group orders by order ID
    $groupedOrders = [];
    foreach ($orders as $order) {
        $orderId = $order['id'];
        if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = [
                'id' => $order['id'],
                'username' => $order['username'],
                'email' => $order['email'],
                'phone' => $order['phone'],
                'address' => $order['address'],
                'total_price' => $order['total_price'],
                'status' => $order['status'],
                'order_date' => $order['order_date'],
                'products' => []
            ];
        }
        $groupedOrders[$orderId]['products'][] = [
            'name' => $order['name'],
            'quantity' => $order['quantity'],
            'price' => $order['price']
        ];
    }
} catch (PDOException $e) {
    die("Error fetching orders: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header-right button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }
        .header-right button:hover {
            background-color: #2980b9;
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .order {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .order h3 {
            margin-top: 0;
        }
        .order table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order table th, .order table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order table th {
            background-color: #f1f1f1;
        }
        .status-form {
            margin-top: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-form select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .status-form button {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .status-form button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>DeviceDen - Admin</h1>
        </div>
        <div class="header-right">
            <button onclick="location.href='ahome.php'">Home</button>
            <button onclick="location.href='aord.php'">Orders</button>
            <button onclick="location.href='users.php'">Users</button>
            <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div class="container">
        <h1>All Orders</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div id="orders-container">
            <?php if (empty($groupedOrders)): ?>
                <p>No orders found.</p>
            <?php else: ?>
                <?php foreach ($groupedOrders as $order): ?>
                    <div class="order">
                        <h3>Order ID: <?= htmlspecialchars($order['id']) ?></h3>
                        <p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?></p>
                        <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
                        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                        <p><strong>Total Price:</strong> ₹<?= htmlspecialchars($order['total_price']) ?></p>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['products'] as $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['quantity']) ?></td>
                                        <td>₹<?= htmlspecialchars($product['price']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <form class="status-form" method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status">
                                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status">Update Status</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>