<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["customer_id"])) {
    header("Location: customer-login.php");
    exit;
}

$customer_id = $_SESSION["customer_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = floatval($_POST["amount"]);

    if ($amount > 0) {
        $sql = "INSERT INTO repayments (customer_id, amount) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $customer_id, $amount);
        if ($stmt->execute()) {
            $_SESSION['repayment_amount'] = $amount;
            $_SESSION['repayment_success'] = true;
            header("Location: repayment_receipt.php");
exit;
        } else {
            $message = "Failed to process repayment.";
        }
    } else {
        $message = "Please enter a valid amount.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Repay Credit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Repay Credit</h2>

    <?php if ($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Enter Repayment Amount (₹):</label>
        <input type="number" name="amount" step="0.01" required>
        <button type="submit">Submit Payment</button>
    </form>

    <p><a href="customer-dashboard.php">Back to Dashboard</a></p>
</body>
</html>
