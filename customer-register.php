<?php
session_start();
include 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

   
    $stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('You are already registered. Please log in.');</script>";
    } else {
        
        $stmt2 = $conn->prepare("INSERT INTO customers (fullname, email, phone, password) VALUES (?, ?, ?, ?)");
        if (!$stmt2) die("Prepare failed: " . $conn->error);
        $stmt2->bind_param("ssss", $fullname, $email, $phone, $password);

        if ($stmt2->execute()) {
            echo "<script>
                    alert('Registration successful! You can now log in.');
                    window.location.href='customer-login.php';
                  </script>";
        } else {
            echo "<script>alert('Error: Registration failed.');</script>";
        }

        $stmt2->close();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Customer Registration</h2>
    <form method="POST" action="customer-register.php">
        <label>Full Name:</label><br>
        <input type="text" name="fullname" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="customer-login.php">Login here</a>.</p>
</body>
</html>
