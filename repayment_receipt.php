<?php
session_start();
if (!isset($_SESSION['repayment_success']) || !isset($_SESSION['repayment_amount'])) {
    header("Location: customer-dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Repayment Receipt</title>
  <style>
    .receipt {
      width: 400px;
      margin: 50px auto;
      padding: 20px;
      border: 1px solid #ccc;
      font-family: Arial;
    }
    h2 {
      text-align: center;
    }
    .download-btn {
      margin-top: 20px;
      display: block;
      width: 100%;
      padding: 10px;
      background: #27ae60;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="receipt">
  <h2>Repayment Receipt</h2>
  <p><strong>Amount Paid:</strong> ₹<?php echo number_format($_SESSION['repayment_amount'], 2); ?></p>
  <p><strong>Date:</strong> <?php echo date("Y-m-d H:i"); ?></p>
  <p><strong>Status:</strong> Success</p>

  <button class="download-btn" onclick="window.print()">Download Receipt</button>
  <p><a href="customer-dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>