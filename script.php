<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $fathername = $_POST['fathername'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $occupation = $_POST['occupation'];
    $income = $_POST['income'];

    $sql = "INSERT INTO credit_card_applications (fullname, email, phone, fathername, address, state, city, pincode, occupation, income)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $fullname, $email, $phone, $fathername, $address, $state, $city, $pincode, $occupation, $income);

    if ($stmt->execute()) {
        echo "<script>alert('Application submitted successfully!'); window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('Error submitting application. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
