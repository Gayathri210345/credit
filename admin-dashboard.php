<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit();
}

include('db_connection.php');

$newApplications = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE LOWER(status)='new'")->fetch_assoc()['total'];
$acceptedApplications = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE LOWER(status)='accepted'")->fetch_assoc()['total'];
$rejectedApplications = $conn->query("SELECT COUNT(*) AS total FROM applications WHERE LOWER(status)='rejected'")->fetch_assoc()['total'];
$subAdmins = $conn->query("SELECT COUNT(*) AS total FROM subadmins")->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      display: flex;
      height: 100vh;
      overflow-x: hidden;
    }

   
    .sidebar {
      background-color: #2c3e50;
      color: white;
      width: 250px;
      padding-top: 20px;
      height: 100%;
      position: fixed;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar ul li {
      padding: 15px;
      text-align: center;
      cursor: pointer;
      font-size: 18px;
      transition: background-color 0.3s;
    }

    .sidebar ul li:hover {
      background-color: #3498db;
    }

    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
    }

  
    .main {
      margin-left: 260px;
      padding: 40px;
      width: calc(100% - 260px);

      display: flex;
      flex-direction: row;
      justify-content: space-between;
      min-height: 100vh;
    }

    .main h1 {
      color: #2c3e50;
      margin-bottom: 40px;
      text-align: center;
    }

    .cards {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-bottom: 60px;
    }

    .card {
      padding: 30px;
      border-radius: 12px;
      width: 280px;
      text-align: center;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.15);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.25);
    }

    .card-green {
      background-color: #2ecc71;
    }

    .card-orange {
      background-color: #f39c12;
    }

    .card-red {
      background-color: #e60808ff;
    }

    .card-blue {
      background-color: #230cd1ff;
    }

    

    .card div:first-child {
      font-size: 20px;
      margin-bottom: 10px;
    }

    .card div:last-child {
      font-size: 36px;
      font-weight: bold;
    }

   
    .footer {
      text-align: center;
      color: #7f8c8d;
      font-size: 14px;
      margin-top: 40px;
    }

    .footer a {
      color: #3498db;
      text-decoration: none;
      font-weight: bold;
      margin-left: 10px;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  
  <div class="sidebar">
    <h2>CCAMS | Admin</h2>
    <ul>
      <li>📊 Dashboard</li>
      <li>👥 Sub-Admins</li>
      <li>📝 CC Application</li>
      <li>📁 Reports</li>
      <li>📄 Pages</li>
      <li>⚙️ Account Settings</li>
      <li><a href="view_application.php">📝 View Applications</a></li>
    </ul>
  </div>


  <div class="main">
    <h1>Welcome to the Admin Dashboard</h1>

    <div class="cards">
      <div class="card card-green" onclick="window.location.href='view_application.php?status=new'">
        <div>New Applications</div>
        <div><?php echo $newApplications; ?></div>
      </div>

      <div class="card card-orange" onclick="window.location.href='view_application.php?status=accepted'">
        <div>Accepted Applications</div>
        <div><?php echo $acceptedApplications; ?></div>
      </div>

      <div class="card card-red" onclick="window.location.href='view_application.php?status=rejected'">
        <div>Rejected Applications</div>
        <div><?php echo $rejectedApplications; ?></div>
      </div>

      <div class="card card-blue" onclick="window.location.href='view_application.php?status=rejected'">
        <div>Sub-Admins</div>
        <div><?php echo $subAdmins; ?></div>
      </div>
        
    </div>

    <div class="footer">
      <p>Copyright © 2025 CCAMS | Version 1.0
        <a href="logout.php">Log Out</a>
      </p>
    </div>
  </div>
</body>
</html>


