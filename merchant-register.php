<?php
session_start();
include 'db_connection.php';

$businessname = $email = $address = $password = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $businessname = trim($_POST["businessname"]);
    $email        = trim($_POST["email"]);
    $address      = trim($_POST["address"]);
    $password_raw = $_POST["password"];
    $created_at   = date('Y-m-d H:i:s');

   
    if (empty($businessname) || empty($email) || empty($address) || empty($password_raw)) {
        $message = "All fields are required.";
    } else {
        
        $check_sql = "SELECT id FROM merchants WHERE email = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "Registration failed. Email already exists.";
        } else {
        
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            
            $sql = "INSERT INTO merchants (businessname, email, address, password, created_at) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $businessname, $email, $address, $password, $created_at);

            if ($stmt->execute()) {
                $message = "✅ Merchant registered successfully. You can now <a href='merchant-login.php'>login</a>.";
            } else {
                $message = "❌ Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $stmt_check->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Merchant Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Merchant Registration</h2>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Business Name:</label><br>
        <input type="text" name="businessname" value="<?php echo htmlspecialchars($businessname); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label>Address:</label><br>
        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already registered? <a href="merchant-login.php">Login here</a>.</p>
</body>
</html>