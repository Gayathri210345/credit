<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $fullname      = trim($_POST["fullname"]);
    $email         = trim($_POST["email"]);
    $phone         = trim($_POST["phone"]);
    $pan           = strtoupper(trim($_POST["pan_number"]));
    $fathername    = trim($_POST["fathername"]);
    $address       = trim($_POST["address"]);
    $state         = trim($_POST["state"]);
    $city          = trim($_POST["city"]);
    $pincode       = trim($_POST["pincode"]);
    $occupation    = trim($_POST["occupation"]);
    $annual_income = floatval($_POST["annual_income"]);

   
    if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan)) {
        echo "<script>alert('Invalid PAN format. Use format like ABCDE1234F'); history.back();</script>";
        exit;
    }

    
    $check = $conn->prepare("SELECT id FROM applications WHERE pan_number = ?");
    $check->bind_param("s", $pan);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('This PAN number ($pan) is already registered. Please use a different PAN.'); history.back();</script>";
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

   
    $sql = "INSERT INTO applications 
        (fullname, email, phone, pan_number, fathername, address, state, city, pincode, occupation, annual_income, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "<script>alert('Database prepare failed: " . addslashes($conn->error) . "'); history.back();</script>";
        exit;
    }

 
    $stmt->bind_param(
        "ssssssssssd",
        $fullname,
        $email,
        $phone,
        $pan,
        $fathername,
        $address,
        $state,
        $city,
        $pincode,
        $occupation,
        $annual_income
    );

    try {
        if ($stmt->execute()) {
            echo "<script>alert('Application submitted successfully for PAN: $pan'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Error submitting application: " . addslashes($stmt->error) . "'); history.back();</script>";
        }
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            echo "<script>alert('Duplicate PAN number ($pan). Application already exists.'); history.back();</script>";
        } else {
            echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "'); history.back();</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

