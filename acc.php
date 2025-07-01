<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Include database connection

$username = $_SESSION['username'];

try {
    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch orders with product details
    $stmt = $pdo->prepare("
        SELECT orders.*, order_items.product_id, order_items.quantity, products.name, products.price 
        FROM orders 
        JOIN order_items ON orders.id = order_items.order_id 
        JOIN products ON order_items.product_id = products.id 
        WHERE orders.username = ? 
        ORDER BY orders.order_date DESC
    ");
    $stmt->execute([$username]);
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
    die("Error fetching account details: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <style>
         body {
        font-family: Arial, sans-serif;
        background-image: url('https://img.freepik.com/free-vector/blue-gradient-background-limbo-studio-setup_107791-32108.jpg');
        background-size: cover;
        background-position: center;
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
        .user-info {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .user-info p {
            margin: 10px 0;
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
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>Cart</h1>
        </div>
        <div class="header-right">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='cart.php'">Cart</button>
            <button onclick="location.href='acc.php'">Account</button>
        </div>
    </header>

    <div class="container">
        <h1>User Account</h1>
        <div class="user-info">
            <p><strong>Username:</strong> <?= htmlspecialchars($userDetails['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($userDetails['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($userDetails['mobile']) ?></p>
        </div>

        <h2>Order History</h2>
        <div id="orders-container">
            <?php if (empty($groupedOrders)): ?>
                <p>No orders found.</p>
            <?php else: ?>
                <?php foreach ($groupedOrders as $order): ?>
                    <div class="order">
                        <h3>Order ID: <?= htmlspecialchars($order['id']) ?></h3>
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
                    </div>  
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>