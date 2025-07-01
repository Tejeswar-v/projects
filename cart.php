<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$username = $_SESSION['username'];

// Handle removing item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeFromCart'])) {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'] ?? 1;

    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("SELECT count FROM cart WHERE username = ? AND product_id = ?");
        $stmt->execute([$username, $productId]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cartItem) {
            throw new Exception('Item not found in cart.');
        }
        
        $quantity = $cartItem['count'];
        
        $stmt = $pdo->prepare("DELETE FROM cart WHERE username = ? AND product_id = ?");
        $stmt->execute([$username, $productId]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception('Failed to remove item from cart.');
        }
        
        $stmt = $pdo->prepare("UPDATE products SET count = count + ? WHERE id = ?");
        $stmt->execute([$quantity, $productId]);
        
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Item removed from cart and stock updated!']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit();
}

// Fetch cart items
try {
    $stmt = $pdo->prepare("SELECT cart.*, products.name, products.price, products.count as stock_count FROM cart JOIN products ON cart.product_id = products.id WHERE cart.username = ?");
    $stmt->execute([$username]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cart items: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeviceDen Cart</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        header {
            background-color: #2c3e50;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header-right button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        #cart-table {
            width: 80%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        #cart-table th, #cart-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        #cart-table th {
            background-color: #34495e;
            color: #fff;
        }
        .remove-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        #buy-now-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cart</h1>
        <div class="header-right">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='acc.php'">Account</button>
        </div>
    </header>

    <h1 style="text-align: center; margin-top: 20px;">Your Cart</h1>
    <table id="cart-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cartItems)): ?>
                <tr><td colspan="5" style="text-align:center;">Your cart is empty.</td></tr>
            <?php else: ?>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>₹<?= htmlspecialchars($item['price']) ?></td>
                        <td><?= htmlspecialchars($item['count']) ?></td>
                        <td>₹<?= htmlspecialchars($item['price'] * $item['count']) ?></td>
                        <td>
                            <button class="remove-btn" onclick="removeProduct('<?= $item['product_id'] ?>', <?= $item['count'] ?>)">
                                Remove
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td id="total-price">₹<?= array_sum(array_map(fn($item) => $item['price'] * $item['count'], $cartItems)) ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    
    <?php if (!empty($cartItems)): ?>
        <button id="buy-now-btn" onclick="showOrderForm()">Buy Now</button>
    <?php endif; ?>

    <!-- Order Form Modal -->
    <div class="modal" id="orderFormContainer">
        <div class="modal-content">
            <span style="float: right; cursor: pointer;" onclick="hideOrderForm()">✕</span>
            <h2>Order Information</h2>
            <form id="orderForm" method="POST" action="order.php">
                <input type="hidden" name="totalPrice" value="<?= array_sum(array_map(fn($item) => $item['price'] * $item['count'], $cartItems)) ?>">
                
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Shipping Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                
                <button type="submit" style="width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Place Order
                </button>
            </form>
        </div>
    </div>

    <script>
        function showOrderForm() {
            document.getElementById('orderFormContainer').style.display = 'flex';
        }

        function hideOrderForm() {
            document.getElementById('orderFormContainer').style.display = 'none';
        }

        async function removeProduct(productId, quantity) {
            if (confirm("Are you sure you want to remove this item?")) {
                try {
                    const response = await fetch('cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `removeFromCart=true&productId=${productId}&quantity=${quantity}`
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert(result.message);
                        location.reload();
                    } else {
                        alert(result.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while removing the item.');
                }
            }
        }
    </script>
</body>
</html>