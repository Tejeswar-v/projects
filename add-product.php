<?php
session_start();

// Redirect if user is not logged in or is not admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Include database connection

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $count = $_POST['count'] ?? '';
    $category = $_POST['category'] ?? '';
    $image_url = $_POST['image_url'] ?? '';

    // Validate inputs
    if (empty($name) || empty($description) || empty($price) || empty($count) || empty($category)) {
        $error = 'Please fill in all required fields';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number';
    } elseif (!is_numeric($count) || $count < 0) {
        $error = 'Count must be a non-negative number';
    } else {
        try {
            // Insert product into database
            $stmt = $pdo->prepare("
                INSERT INTO products (name, description, price, count, cat, image_url)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $description, $price, $count, $category, $image_url]);
            
            $success = 'Product added successfully!';
            // Clear form fields
            $_POST = [];
        } catch (PDOException $e) {
            $error = 'Error adding product: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
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
            max-width: 800px;
            margin: 0 auto;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .product-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
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
           
            <button onclick="location.href='logout.php'">Logout</button>
        </div>
    </header>

    <div class="container">
        <h1>Add New Product</h1>
        
        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="product-form">
            <form method="POST">
                <div class="form-group">
                    <label for="name">Product Name*</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description*</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (₹)*</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="count">Stock Count*</label>
                    <input type="number" id="count" name="count" min="0" value="<?= htmlspecialchars($_POST['count'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="category">Category*</label>
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="laptop" <?= ($_POST['category'] ?? '') === 'laptop' ? 'selected' : '' ?>>Laptops</option>
                        <option value="mobile" <?= ($_POST['category'] ?? '') === 'mobile' ? 'selected' : '' ?>>mobiles</option>
                        <option value="accessory" <?= ($_POST['category'] ?? '') === 'accessory' ? 'selected' : '' ?>>Accessories</option>
                        <option value="home" <?= ($_POST['category'] ?? '') === 'home' ? 'selected' : '' ?>>home</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image_url">Image URL</label>
                    <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($_POST['image_url'] ?? '') ?>" placeholder="https://example.com/image.jpg">
                </div>
                
                <button type="submit" class="submit-btn">Add Product</button>
            </form>
        </div>
    </div>
</body>
</html>