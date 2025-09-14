<?php
session_start();

// Protect the page
if (!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Komyu Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #f8f9fa;
      border-right: 1px solid #ddd;
      display: flex;
      flex-direction: column;
      justify-content: space-between; /* keeps logout at bottom */
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
    }

    .sidebar-header {
      background-color: #0DD85F;
      padding: 20px;
      display: flex;
      align-items: center;
      height: 100px;
      justify-content: center;
      flex-direction: column;
    }

    .sidebar-header img {
      width: 200px;
      height: auto;
      display: block;
      margin-bottom: 10px;
    }

    .menu {
      display: flex;
      flex-direction: column;
      padding: 20px 0;
      flex-grow: 1;
    }

    .menu a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: #222;
      text-decoration: none;
      transition: background 0.2s, color 0.2s, transform 0.2s;
      border-radius: 6px;
      margin: 10px;
      font-size: 15px;
    }

    .menu a:hover, .menu a.active {
      background: #0DD85F;
      color: white;
      font-weight: bold;
    }

    .menu a:hover {
      transform: scale(1.1);
    }

    .menu-icon {
      width: 25px;
      height: 25px;
      margin-right: 10px;
    }

    .logout {
      padding: 15px 20px;
      border-top: 1px solid #ddd;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logout a {
      text-decoration: none;
      color: #555;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s, color 0.2s;
      width: 100%;
      padding: 10px;
      border-radius: 6px;
    }

    .logout a:hover {
      background-color: #ff4d4d;
      color: white;
    }

    /* Main Content */
    .main {
      margin-left: 250px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* Top bar */
    .topbar {
      background-color: #1dd65f;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 10px 20px;
    }

    .topbar span {
      color: white;
      font-weight: bold;
      margin-right: 10px;
    }

    .avatar {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }

    .content {
      padding: 20px;
      flex-grow: 1;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <div>
      <div class="sidebar-header">
        <img src="komyu-logo.png" alt="Komyu Logo">
      </div>
      <div class="menu">
        <a href="dashboard.php" class="active">
          <img src="icon-dashboard.png" alt="Dashboard" class="menu-icon"> Dashboard</a>
        <a href="commuters.php">
          <img src="gray-commuters.png" alt="Commuters" class="menu-icon"> Commuters</a>
        <a href="drivers.php">
          <img src="gray-drivers.png" alt="Drivers" class="menu-icon"> Drivers & Conductors</a>
        <a href="reports.php">
          <img src="gray-reports.png" alt="Reports" class="menu-icon"> Reports & Ratings</a>
        <a href="trips.php">
          <img src="gray-trips.png" alt="Trips" class="menu-icon"> Trips</a>
        <a href="payments.php">
          <img src="gray-payments.png" alt="Payments" class="menu-icon"> Payment & Points</a>
      </div>
    </div>

    <div class="logout">
      <a href="index.php">
        <img src="icon-logout.png" alt="Logout" class="menu-icon"> Logout
      </a>
    </div>
  </nav>

  <!-- Main -->
  <div class="main">
    <div class="topbar">
      <span>Hello, Gigachad!</span>
      <img src="profile.jpg" alt="User Avatar" class="avatar">
    </div>
    <main class="content">
      <h2>Dashboard Content</h2>
    </main>
  </div>
</body>
</html>
