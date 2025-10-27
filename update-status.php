<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

include('db_connection.php');

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    
    if (in_array($status, ['approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            header("Location: view_application.php"); 
            exit();
        } else {
            echo "Error updating status.";
        }

        $stmt->close();
    } else {
        echo "Invalid status!";
    }
} else {
    echo "Missing parameters!";
}

$conn->close();
?>

