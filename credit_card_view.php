<?php
session_start();
if (!isset($_SESSION["customer_id"])) {
    header("Location: customer-login.php");
    exit;
}
$customer_name = $_SESSION["customer_name"];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Virtual Credit Card</title>
  <style>
    body {
      font-family: Arial;
      text-align: center;
      margin-top: 60px;
      background-color: #f4f4f4;
    }
    .card {
      background: linear-gradient(135deg, #2c3e50, #3498db);
      width: 350px;
      margin: auto;
      border-radius: 15px;
      color: white;
      padding: 20px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      position: relative;
    }
    .card-number {
      font-size: 22px;
      margin: 20px 0;
      letter-spacing: 2px;
    }
    .card-name, .expiry {
      font-size: 16px;
    }
    .buttons {
      margin-top: 30px;
    }
    .button {
      background: #27ae60;
      color: white;
      padding: 10px 20px;
      border: none;
      text-decoration: none;
      border-radius: 5px;
      margin: 5px;
      cursor: pointer;
      display: inline-block;
    }
    .button:hover {
      background: #219150;
    }
  </style>
</head>
<body>

<div class="card" id="virtualCard">
  <h2>Virtual Credit Card</h2>
  <div class="card-number">1234 5678 9012 3456</div>
  <div class="card-name">Name: <?php echo htmlspecialchars($customer_name); ?></div>
  <div class="expiry">Valid Thru: 12/30</div>
</div>

<div class="buttons">
  <button class="button" onclick="downloadCard()">Download Card</button>
  <a class="button" href="customer-dashboard.php">Proceed to Dashboard</a>
</div>

<script>
  function downloadCard() {
    const card = document.getElementById("virtualCard");
    const cardClone = card.cloneNode(true);
    const style = document.createElement("style");
    style.innerHTML = `
      body { margin: 0; padding: 0; font-family: Arial; }
      .card {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        width: 350px;
        margin: 100px auto;
        border-radius: 15px;
        color: white;
        padding: 20px;
        text-align: center;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      }
      .card-number {
        font-size: 22px;
        margin: 20px 0;
        letter-spacing: 2px;
      }
      .card-name, .expiry {
        font-size: 16px;
      }
    `;
    const win = window.open('', '_blank');
    win.document.write('<html><head><title>Download Card</title></head><body></body></html>');
    win.document.head.appendChild(style);
    win.document.body.appendChild(cardClone);
    setTimeout(() => {
      win.print();
      win.close();
    }, 500);
  }
</script>

</body>
</html>

