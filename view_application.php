<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php'); 
    exit();
}

include('db_connection.php');

$query = "SELECT * FROM applications ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Applications | Admin</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f4f4f4;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #3498db;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    a {
      text-decoration: none;
      color: #3498db;
    }
  </style>
</head>
<body>
  <h2>Submitted Credit Card Applications</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>City</th>
      <th>State</th> 
      <th>Status</th>
      <th>Applied On</th>
      <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['fullname']; ?></td>
      <td><?php echo $row['email']; ?></td>
      <td><?php echo $row['phone']; ?></td>
      <td><?php echo $row['city']; ?></td>
      <td><?php echo $row['state']; ?></td>
      <td><?php echo ucfirst($row['status']); ?></td>
      <td><?php echo $row['created_at']; ?></td>
      <td>
        <a href="update-status.php?id=<?php echo $row['id']; ?>&status=approved">✔ Accept</a> |
        <a href="update-status.php?id=<?php echo $row['id']; ?>&status=rejected">✖ Reject</a>
      </td>
    </tr>
    <?php } ?>
  </table>
</body>
</html>

<?php $conn->close(); ?>
