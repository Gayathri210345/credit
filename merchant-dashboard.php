<?php
session_start();
include 'db_connection.php';


if (!isset($_SESSION['merchant_id'])) {
    header("Location: merchant-login.php");
    exit;
}

$merchant_id = $_SESSION['merchant_id'];
$add_msg = "";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_product"])) {
    $product_name = trim($_POST["product_name"]);
    $price = floatval($_POST["price"]);
    $description = trim($_POST["description"]);
    $image_url = trim($_POST["image_url"]);

    $sql = "INSERT INTO products (merchant_id, product_name, price, description, image_url) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdss", $merchant_id, $product_name, $price, $description, $image_url);

    if ($stmt->execute()) {
        $add_msg = "✅ Product added successfully.";
    } else {
        $add_msg = "❌ Error adding product: " . $conn->error;
    }
}


$product_sql = "SELECT * FROM products WHERE merchant_id = ? ORDER BY created_at DESC";
$product_stmt = $conn->prepare($product_sql);
$product_stmt->bind_param("i", $merchant_id);
$product_stmt->execute();
$products = $product_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Merchant Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }
        h2, h3 {
            color: #333;
        }
        a {
            text-decoration: none;
            color: #3498db;
        }

        form input, form textarea {
            width: 100%;
            max-width: 500px;
            padding: 10px;
            margin-bottom: 10px;
        }

        form button {
            padding: 10px 20px;
            background: #27ae60;
            color: white;
            border: none;
            cursor: pointer;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-card h4 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #2c3e50;
        }

        .product-card .price {
            color: #27ae60;
            font-weight: bold;
        }

        .product-card .desc {
            font-size: 14px;
            color: #555;
            margin: 8px 0;
        }

        .product-card .timestamp {
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <h2>Welcome, Merchant</h2>
    <p><a href="logout.php">Logout</a></p>

    <h3>Add New Product</h3>
    <?php if (!empty($add_msg)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($add_msg); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="product_name" placeholder="Product Name" required><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <input type="text" name="image_url" placeholder="Image URL (e.g., https://...)" required><br>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h3>Your Products</h3>
    <div class="product-grid">
        <?php while ($row = $products->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($row['image_url'] ?? 'default.jpg'); ?>" alt="Product">
            <h4><?php echo htmlspecialchars($row['product_name']); ?></h4>
            <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>
            <div class="desc"><?php echo htmlspecialchars($row['description']); ?></div>
            <div class="timestamp"><?php echo $row['created_at']; ?></div>
        </div>
        <?php endwhile; ?>
    </div>

</body>
</html>

