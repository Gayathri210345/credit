<?php
session_start();
include 'db_connection.php';

$login_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    $stmt = $conn->prepare("SELECT id, password FROM merchants WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $found = false;

    while ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['merchant_id'] = $row['id'];
            header("Location: merchant-dashboard.php");
            exit;
        }
    }

    $login_msg = "Merchant not found or password incorrect. Please check your email and password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Merchant Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Merchant Login</h2>
    <?php if ($login_msg): ?>
        <p style="color:red;"><?php echo $login_msg; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="merchant-register.php">Register here</a>.</p>
</body>
</html>
