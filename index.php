<?php
session_start();
require 'db_connect.php'; // connection in $conn

$error = '';

if (isset($_POST['login'])) {
    // Grab and trim inputs
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter username and password.';
    } else {
        // Prepared statement to fetch user by username
        $sql = "SELECT admin_id, username, password FROM admin_account WHERE username = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $admin_id, $db_username, $db_hashed_password);
            if (mysqli_stmt_fetch($stmt)) {
                // Verify hashed password
                if (password_verify($password, $db_hashed_password)) {
                    // Successful login - store consistent session keys
                    $_SESSION['admin_id'] = $admin_id;
                    $_SESSION['username'] = $db_username;

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = 'Invalid username or password.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
            mysqli_stmt_close($stmt);
        } else {
            // statement prepare error - helpful for debugging
            $error = 'Database error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Komyu Admin Login</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #00c268;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      display: flex;
      align-items: center;
      justify-content: right;
      gap: 100px;
    }

    /* Divider */
    .divider {
      width: px;            /* thicker */
      height: 75%;           /* taller */
      background-color: #ffffff; /* solid white */
    }

    .logo-section {
      display: flex;
      flex-direction: column;
      align-items: center;
      color: #fff;
    }

    .welcome-text {
      font-size: 50px;
      margin-bottom: 10px;
      font-weight: bold;
      color: #0a1c2f ;

    }

    .logo-icon {
      font-size: 90px;
      margin-bottom: 10px;
    }

    .logo-text {
      font-size: 42px;
      font-weight: bold;
    }

    .login-card {
      background-color: #fff;
      border-radius: 24px;
      padding: 60px 50px;
      width: 450px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .login-card h2 {
      margin-bottom: 40px;
      font-size: 28px;
      color: #000;
    }

    .input-group {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 45px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 15px;
    }

    .input-group svg {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      fill: #00c268;
    }

    .forgot {
      display: block;
      text-align: right;
      font-size: 13px;
      margin-bottom: 25px;
      color: #333;
      text-decoration: none;
    }

    .forgot:hover {
      text-decoration: underline;
    }

    .btn {
      background-color: #0a1c2f;
      color: #fff;
      border: none;
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #122b4a;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        gap: 40px;
      }
      .divider {
        width: 80%;
        height: 2px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-section">
      <div class="welcome-text">Welcome To</div>
      <div class="logo-icon">
          <img src="komyu-logo.png" alt="Komyu Logo" />
     </div>
    </div>

   <!-- Login Card -->
<div class="login-card">
  <h2>Admin</h2>
  <!--  Added method and name -->
  <form method="POST">
    <div class="input-group">
      <!-- Username SVG stays -->
      <svg width="22" height="22" viewBox="0 0 24 24">
        <path d="M12 12c2.67 0 8 1.34 8 4v2H4v-2c0-2.66 
          5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z"/>
      </svg>
      <input type="text" placeholder="Username" name="username" required />
    </div>

    <div class="input-group">
      <!-- Password SVG stays -->
      <svg width="22" height="22" viewBox="0 0 24 24">
        <path d="M12 17a2 2 0 002-2v-2a2 2 0 
          00-4 0v2a2 2 0 002 2zm6-6h-1V9a5 
          5 0 00-10 0v2H6c-1.1 0-2 .9-2 
          2v8c0 1.1.9 2 2 2h12c1.1 
          0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm-3 
          0H9V9a3 3 0 016 0v2z"/>
      </svg>
      <input type="password" placeholder="Password" name="password" required />
    </div>

    <a href="#" class="forgot">Forgot password?</a>

    <!-- Added name="login" so PHP can detect it -->
    <button type="submit" class="btn" name="login">Sign In</button>
  </form>
</div>
</body>
</html>
updatedWebsite
