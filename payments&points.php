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

    /* Content */
    .content {
      padding: 20px;
      
    }

    .content h2 {
      margin-bottom: 15px;
    }
    .content h3{
        margin-bottom: 15px;
        font-size: 25px;
    }

    .search-filter {
        display: flex;
        justify-content: space-between; /* left on one side, right on the other */
        align-items: center;
        margin-bottom: 17px;
   }
   .left-controls {
        display: flex;
        align-items: center;
        gap: 10px; /* space between search and filter */
    }

    .left-controls input[type="text"] {
        width: 400px;   /* adjust search bar width */
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .left-controls select {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .right-controls {
        display: flex;
        gap: 10px; /* space between buttons */
    }

    .btn {
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    .delete {
        background-color: #e74c3c;
        color: white;
        border: 1px solid #000000;
    }

    .edit {
        background-color: #3498db;
        color: white;
        border: 1px solid #000000;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
      font-size: 14px;
    }

    th {
      background: #31EE7D;
    }

    table th:first-child,
    table td:first-child {
      width: 40px;
      text-align: center;
    }
    table tbody tr:hover {
      background-color: #e8f9f0; 
      cursor: pointer;            
      transition: background-color 0.3s ease; 
    }
    .status {
      padding: 6px 10px;
      border-radius: 5px;
      font-size: 13px;
      font-weight: bold;
      color: white;
    }
    input[type="checkbox"] {
      transform: scale(1.5);  /* Increase the size (1.5 = 150%) */
      margin: 5px;            /* Optional: add spacing */
      cursor: pointer;        /* Optional: show pointer on hover */
    }

    .pending { background: #007bff; }
    .approved { background: #28a745; }
    .rejected { background: #dc3545; }

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
        <a href="dashboard.php">
          <img src="gray-dashboard.png" alt="Dashboard" class="menu-icon"> Dashboard</a>
        <a href="commuters.php">
          <img src="gray-commuters.png" alt="Commuters" class="menu-icon"> Commuters</a>
        <a href="drivers&conductors.php">
          <img src="gray-drivers.png" alt="Drivers" class="menu-icon"> Drivers & Conductors</a>
        <a href="reports&ratings.php">
          <img src="gray-reports.png" alt="Reports" class="menu-icon"> Reports & Ratings</a>
        <a href="trips.php">
          <img src="gray-trips.png" alt="Trips" class="menu-icon"> Trips</a>
        <a href="payments&points.php" class="active">
          <img src="white-payments.png" alt="Payments" class="menu-icon"> Payment & Points</a>
          <a href="settings.php">
          <img src="gray-settings.png" alt="Acount Settings" class="menu-icon"> Account Settings</a>
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

  <!-- Content -->
  <div class="content">
    <h3>Payments & Points</h3>
</main>
</div>
</body>
</html>
