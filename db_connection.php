<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "credit_card_system_v2";


$conn = new mysqli("localhost", "root", "", "credit_card_system_v2");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
