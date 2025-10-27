<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT t.id, p.product_name, t.amount, t.status, t.created_at
        FROM transactions t 
        JOIN products p ON t.product_id = p.id 
        WHERE t.customer_id = ? 
        ORDER BY t.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Your Transaction History</h2>
    <p><a href="customer-dashboard.php">← Back to Dashboard</a></p>

    <table border="1" cellpadding="10">
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
            <td>₹<?php echo number_format($row['amount'], 2); ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

