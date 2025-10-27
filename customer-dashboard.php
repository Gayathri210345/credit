<?php
session_start();
include 'db_connection.php';


if (!isset($_SESSION["customer_id"])) {
    header("Location: customer-login.php");
    exit;
}

$customer_id = $_SESSION["customer_id"];
$customer_name = $_SESSION["customer_name"];

$app_sql = "SELECT a.*, s.status AS new_status
            FROM applications a
            LEFT JOIN status_updates s ON a.id = s.application_id
            WHERE a.email = (SELECT email FROM customers WHERE id = ?)
            ORDER BY s.updated_at DESC
            LIMIT 1";

$stmt = $conn->prepare($app_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$app_result = $stmt->get_result();
$app_data = $app_result->fetch_assoc();
$stmt->close();


$credit_limit = 0;
$total_spent = 0;
$available_credit = 0;
$credit_used_percent = 0;

$app_status = $app_data['new_status'] ?? $app_data['status'] ?? 'pending';

if ($app_data && $app_status === 'approved') {
    $annual_income = $app_data['annual_income'] ?? 0;
    $credit_limit = $annual_income * 3;


    $spent_sql = "SELECT SUM(amount) FROM transactions WHERE customer_id = ? AND status = 'completed'";
    $spent_stmt = $conn->prepare($spent_sql);
    $spent_stmt->bind_param("i", $customer_id);
    $spent_stmt->execute();
    $spent_stmt->bind_result($total_spent);
    $spent_stmt->fetch();
    $spent_stmt->close();

    $total_spent = $total_spent ?: 0;
    $available_credit = $credit_limit - $total_spent;
    $credit_used_percent = $credit_limit > 0 ? round(($total_spent / $credit_limit) * 100) : 0;
}


$chart_used = $total_spent > 0 ? $total_spent : 0.01;
$chart_available = $available_credit > 0 ? $available_credit : 0.01;

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f6f9; }
        h2, h3 { color: #2c3e50; }
        .progress-container { width: 100%; max-width: 400px; background-color: #eee; border-radius: 8px; margin: 15px auto; }
        .progress-bar { height: 24px; border-radius: 8px; background-color: #3498db; text-align: center; color: white; font-weight: bold; line-height: 24px; }
        .notification { padding: 15px; background-color: #27ae60; color: white; border-radius: 5px; font-weight: bold; margin: 20px auto; max-width: 500px; text-align: center; }
        .card { background: white; padding: 20px; border-radius: 10px; margin: 15px 0; max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        #creditChart { width: 300px !important; height: 300px !important; margin: 20px auto; display: block; }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($customer_name); ?>!</h2>

<?php if ($app_data && $app_status === 'approved'): ?>
    <div class="notification">
        🎉 Congratulations! Your credit card has been approved.
        <a href="credit_card_view.php" style="color: #fff; text-decoration: underline;">View Your Card</a>
    </div>
<?php endif; ?>

<div class="card">
    <h3>Credit Card Application Status</h3>
    <?php if ($app_data): ?>
        <p><strong>Application Status:</strong> <?php echo ucfirst($app_status); ?></p>
        <p><strong>Occupation:</strong> <?php echo $app_data['occupation'] ?? 'N/A'; ?></p>
        <p><strong>Annual Income:</strong> ₹<?php echo number_format($app_data['annual_income'] ?? 0, 2); ?></p>
        <p><strong>Credit Limit:</strong> ₹<?php echo number_format($credit_limit, 2); ?></p>

        <h3>Credit Usage</h3>
        <div class="progress-container">
            <div class="progress-bar" style="width: <?php echo $credit_used_percent; ?>%;">
                <?php echo $credit_used_percent; ?>%
            </div>
        </div>
        <p>Used ₹<?php echo number_format($total_spent, 2); ?> of ₹<?php echo number_format($credit_limit, 2); ?> available credit</p>

        <h3>Visual Credit Usage</h3>
        <canvas id="creditChart"></canvas>
        <script>
        window.onload = function() {
            const ctx = document.getElementById('creditChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Used Credit', 'Available Credit'],
                    datasets: [{
                        data: [<?php echo $chart_used; ?>, <?php echo $chart_available; ?>],
                        backgroundColor: ['#e74c3c', '#2ecc71'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ₹' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        };
        </script>

    <?php else: ?>
        <p>You have not applied for a credit card yet. <a href="submit_application.php">Apply now</a></p>
    <?php endif; ?>
</div>

<hr>
<p><a href="make_payment.php">💳 Make a Purchase</a></p>
<p><a href="repay.php">💰 Make a Repayment</a></p>
<p><a href="customer-transactions.php">📄 View Transaction History</a></p>
<p><a href="download_card.php">📥 Download Virtual Card</a></p>
<p><a href="logout.php">🚪 Logout</a></p>

</body>
</html>
