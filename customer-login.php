<?php
session_start();
include 'db_connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $query = "SELECT id, fullname, password FROM customers WHERE email = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        $customer_id = $user["id"];
        $name = $user["fullname"];
        $hashed_password = $user["password"];

        if (password_verify($password, $hashed_password)) {
            $_SESSION["customer_id"] = $customer_id;
            $_SESSION["customer_name"] = $name;
            header("Location: credit_card_view.php"); 
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Customer not found or not registered!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Customer Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="customer-login.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p>New user? <a href="customer-register.php">Register here</a>.</p>
</body>
</html>
