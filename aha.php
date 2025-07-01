<?php
session_start();

// Redirect if user is not logged in or is not admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Include database connection

// Handle soft deleting a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeProduct'])) {
    $productId = $_POST['productId'];

    try {
        // Mark the product as deleted (soft delete)
        $stmt = $pdo->prepare("UPDATE products SET is_deleted = 1 WHERE id = ?");
        $stmt->execute([$productId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Product marked as deleted successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error marking product as deleted: ' . $e->getMessage()]);
    }
    exit(); // Stop further execution after handling the AJAX request
}

// Fetch products based on category (excluding soft-deleted products)
$category = $_GET['cat'] ?? 'home'; // Default to 'home' if no category is provided

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE cat = ? AND is_deleted = 0");
    $stmt->execute([$category]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>DeviceDen - Product Count</title>
    <style>
        /* Your existing CSS styles */
    </style>
</head>
<body>
    <header>
        <div class="header-left">
            <h1>DeviceDen</h1>
        </div>
        <div class="header-right">
            <button onclick="location.href='ahome.php'">Home</button>
            <button onclick="location.href='aord.php'">Orders</button>
            <button onclick="location.href='users.php'">Account</button>
        </div>
    </header>

    <div class="products" id="products">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <p>Description: <?= htmlspecialchars($product['description']) ?></p>
                <p>Price: ₹<?= htmlspecialchars($product['price']) ?></p>
                <p>Count: <?= htmlspecialchars($product['count']) ?></p>
                <button class="edit-button" onclick="openEditModal('<?= $product['id'] ?>', '<?= htmlspecialchars($product['name']) ?>', '<?= htmlspecialchars($product['description']) ?>', <?= $product['price'] ?>, <?= $product['count'] ?>, '<?= htmlspecialchars($product['image_url']) ?>')">Edit</button>
                <button class="remove-button" onclick="removeProduct('<?= $product['id'] ?>')">Remove</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Product</h2>
            <form id="editForm" action="edit-product.php" method="POST">
                <input type="hidden" id="editProductId" name="productId">
                <label for="editName">Product Name:</label>
                <input type="text" id="editName" name="name" required>
                <label for="editDescription">Description:</label>
                <textarea id="editDescription" name="description" required></textarea>
                <label for="editPrice">Price:</label>
                <input type="number" id="editPrice" name="price" required>
                <label for="editCount">Count:</label>
                <input type="number" id="editCount" name="count" required>
                <label for="editImageUrl">Image URL:</label>
                <input type="text" id="editImageUrl" name="image_url" required>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        // Function to open the edit modal
        function openEditModal(productId, name, description, price, count, imageUrl) {
            document.getElementById('editProductId').value = productId;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editPrice').value = price;
            document.getElementById('editCount').value = count;
            document.getElementById('editImageUrl').value = imageUrl;
            document.getElementById('editModal').style.display = 'flex';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Function to soft delete a product
        async function removeProduct(productId) {
            if (confirm('Are you sure you want to mark this product as deleted?')) {
                try {
                    const response = await fetch('aha.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `removeProduct=true&productId=${productId}`,
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert(result.message);
                        location.reload(); // Refresh the page
                    } else {
                        alert(result.message || 'Failed to mark product as deleted.');
                    }
                } catch (error) {
                    console.error('Error marking product as deleted:', error);
                    alert('An error occurred while marking the product as deleted.');
                }
            }
        }
    </script>
</body>
</html>
<style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header-right button {
            padding: 10px;
            margin-left: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .header-right button:hover {
            background-color: #0056b3;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .product h2 {
            font-size: 18px;
            margin: 10px;
            color: #007bff;
        }

        .product p {
            margin: 10px;
            font-size: 14px;
            color: #555;
        }

        .product .product-details {
            flex: 1;
        }

        .product .edit-button,
        .product .remove-button {
            display: block;
            width: calc(100% - 20px);
            margin: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .product .edit-button {
            background-color: #28a745;
            color: white;
        }

        .product .edit-button:hover {
            background-color: #218838;
        }

        .product .remove-button {
            background-color: #dc3545;
            color: white;
        }

        .product .remove-button:hover {
            background-color: #c82333;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-content h2 {
            margin-top: 0;
        }

        .modal-content label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .modal-content input,
        .modal-content textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-content button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        .close {
            float: right;
            font-size: 24px;
            cursor: pointer;
        }
    </style>