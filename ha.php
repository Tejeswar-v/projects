<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php'; // Include database connection

$category = $_GET['cat'] ?? 'home'; // Default to 'home' if no category is provided

try {
    // Fetch only non-deleted products (is_deleted = 0)
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
    <title>DeviceDen - <?= ucfirst($category) ?></title>
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
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='cart.php'">Cart</button>
            <button onclick="location.href='acc.php'">Account</button>
            
        </div>
    </header>

    <div class="products" id="products">
        <?php if (empty($products)): ?>
            <p style="text-align: center; width: 100%;">No products available in this category.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="product-details">
                        <h2><?= htmlspecialchars($product['name']) ?></h2>
                        <p>Description: <?= htmlspecialchars($product['description']) ?></p>
                        <p>Price: ₹<?= htmlspecialchars($product['price']) ?></p>
                    </div>
                    <?php if ($product['count'] > 0): ?>
                        <button class="add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                    <?php else: ?>
                        <p class="out-of-stock">Out of Stock</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async () => {
                const productId = button.getAttribute('data-product-id');
                const username = "<?= $_SESSION['username'] ?>";

                try {
                    const response = await fetch('api/addtocart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ username, productId }),
                    });

                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        window.location.href = `ha.php?cat=<?= $category ?>`;
                    } else {
                        alert(result.message);
                    }
                } catch (error) {
                    console.error("Error adding to cart:", error);
                    alert("An error occurred while adding the product to the cart.");
                }
            });
        });
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
            flex-direction: column; /* Make the product card a flex container */
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover; /* Ensures images maintain aspect ratio */
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
            flex: 1; /* Allow the details section to grow and push the button to the bottom */
        }

        .product .add-to-cart {
            display: block;
            width: calc(100% - 20px);
            margin: 10px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .product .add-to-cart:hover {
            background-color: #218838;
        }

        .product .out-of-stock {
            display: block;
            width: calc(100% - 20px);
            margin: 10px;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .products {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .products {
                grid-template-columns: 1fr;
            }
        }
    </style>