<?php
session_start(); 


define('ADMIN_USERNAME', 'googleadmin');  
define('ADMIN_PASSWORD', 'Google@123');   


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin'] = $username; 
        header('Location: admin-dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login | Credid_card_processing</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        .login-box h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 24px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            font-size: 16px;
            background: #ecf0f1;
            transition: 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            background: #fff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            color: white;
            transition: 0.3s;
        }

        button:hover {
            background: #2980b9;
        }

        .links {
            margin-top: 10px;
            font-size: 14px;
        }

        .links a {
            color: #3498db;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <h2><strong>Admin</strong> | IOB</h2>
        <p>Sign in to start your session</p>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit" name="login">Sign In</button>
        </form>

        <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>

        <div class="links">
            <a href="#">Forgot your password?</a><br>
            <a href="index.html">Back to Home</a>
          </div>
        </div>
    
    </body>
    </html>
 

    






