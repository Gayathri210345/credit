<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];


$sql = "SELECT p.*, m.businessname FROM products p JOIN merchants m ON p.merchant_id = m.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Available Products</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Product</th>
        <th>Merchant</th>
        <th>Price (₹)</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
        <td><?php echo htmlspecialchars($row['businessname']); ?></td>
        <td><?php echo $row['price']; ?></td>
        <td><?php echo htmlspecialchars($row['description']); ?></td>
        <td>
            <form action="make_payment.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="merchant_id" value="<?php echo $row['merchant_id']; ?>">
                <input type="hidden" name="amount" value="<?php echo $row['price']; ?>">
                <button type="submit">Buy Now</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
<p><a href="customer-dashboard.php">Back to Dashboard</a></p>
</body>
</html>
