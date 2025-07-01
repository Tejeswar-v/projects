<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all products
    try {
        $stmt = $pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error fetching products.", "error" => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update a product
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $name = $data['name'];
    $price = $data['price'];
    $count = $data['count'];
    $description = $data['description'];
    $image_url = $data['image_url'];

    try {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, count = ?, description = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$name, $price, $count, $description, $image_url, $id]);
        echo json_encode(["message" => "Product updated successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error updating product.", "error" => $e->getMessage()]);
    }
}
?>