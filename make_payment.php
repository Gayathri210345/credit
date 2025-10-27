<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];
$message = "";

$limit_sql = "SELECT annual_income, email FROM applications 
              WHERE email = (SELECT email FROM customers WHERE id = ?) 
              AND status = 'approved'";
$limit_stmt = $conn->prepare($limit_sql);
$limit_stmt->bind_param("i", $customer_id);
$limit_stmt->execute();
$limit_stmt->bind_result($annual_income, $customer_email);
$limit_stmt->fetch();
$limit_stmt->close();

$annual_income = $annual_income ?: 0;
$credit_limit = $annual_income * 3;

$spent_sql = "SELECT SUM(amount) FROM transactions 
              WHERE customer_id = ? AND status = 'completed'";
$spent_stmt = $conn->prepare($spent_sql);
$spent_stmt->bind_param("i", $customer_id);
$spent_stmt->execute();
$spent_stmt->bind_result($total_spent);
$spent_stmt->fetch();
$spent_stmt->close();

$total_spent = $total_spent ?: 0;
$available_credit = $credit_limit - $total_spent;


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["make_payment"])) {
    $product_id = $_POST["product_id"];

    $product_sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $merchant_id = $product["merchant_id"];
        $amount = $product["price"];
        $product_name = $product["product_name"];

        if ($amount > $available_credit) {
            $message = "❌ Insufficient credit. You need ₹$amount but only ₹$available_credit is available.";
        } else {
            $insert_sql = "INSERT INTO transactions (customer_id, merchant_id, product_id, amount, status)
                           VALUES (?, ?, ?, ?, 'completed')";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iiid", $customer_id, $merchant_id, $product_id, $amount);

            if ($stmt->execute()) {
                $_SESSION['purchase_message'] = "✅ Payment successful for product: " . htmlspecialchars($product_name);
                $_SESSION['purchase_product'] = $product_name;
                $_SESSION['purchase_amount'] = $amount;
                $_SESSION['purchase_date'] = date("Y-m-d H:i:s");
                header("Location: purchase_receipt.php");
                exit;
            } else {
                $message = "❌ Failed to process payment.";
            }
        }
    } else {
        $message = "❌ Product not found.";
    }
}

$product_sql = "SELECT products.id, products.product_name, products.price, merchants.businessname AS merchant_name
                FROM products
                JOIN merchants ON products.merchant_id = merchants.id";
$products = $conn->query($product_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Make a Payment</h2>
    <p><strong>Available Credit: ₹<?php echo number_format($available_credit, 2); ?></strong></p>
    <a href="customer-dashboard.php">Back to Dashboard</a> | 
    <a href="logout.php">Logout</a>

    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Card Number</label><br>
        <input type="text" name="card_number" value="1234 5678 9012 3456" readonly><br><br>

        <label for="product_id">Choose a Product:</label><br>
        <select name="product_id" required>
            <option value="">-- Select --</option>
            <?php while ($row = $products->fetch_assoc()): ?>
                <option value="<?php echo $row["id"]; ?>">
                    <?php echo htmlspecialchars($row["product_name"]) . " - ₹" . $row["price"] . " (by " . $row["merchant_name"] . ")"; ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit" name="make_payment">Pay</button>
    </form>
</body>
</html>

