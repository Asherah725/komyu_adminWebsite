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
      cursor: pointer;
    }

    .content {
      padding: 20px;
      
    }
  /* === Dashboard Cards === */
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 15px;
  margin: 20px 0;
  margin-left: 30px;
}

.card {
  background: #D2FFE4;
  border-radius: 12px;
  padding: 20px;
  width: 250px;
  height: 120px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  justify-content: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  transform: translateY(-5px) scale(1.05);
  box-shadow: 0 8px 15px rgba(0,0,0,0.25);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center; /* keeps them aligned vertically */
}

.card-text {
  display: flex;
  flex-direction: column;
}

.card-title {
  font-weight: bold;
  font-size: 16px;
  margin-bottom: 10px;
  color: #000;
}

.card-value {
  font-size: 32px;
  font-weight: bold;
  color: #000;
  display: flex;
  align-items: center; /* aligns with icon */
}

.card-icon img {
  width: 32px;
  height: 32px;
}
.card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.linechart-container{
  flex: 1;
  width: 555px;
  height: 350px;
  margin-left: 30px;
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}


  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
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
        <a href="drivers&conductors.php">
          <img src="gray-drivers.png" alt="Drivers" class="menu-icon"> Drivers & Conductors</a>
        <a href="reports&ratings.php">
          <img src="gray-reports.png" alt="Reports" class="menu-icon"> Reports & Ratings</a>
        <a href="trips.php">
          <img src="gray-trips.png" alt="Trips" class="menu-icon"> Trips</a>
        <a href="payments&points.php">
          <img src="gray-payments.png" alt="Payments" class="menu-icon"> Payment & Points</a>
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
    <main class="content">
  <h2>Dashboard</h2>

  <!-- === DASHBOARD CARDS START === -->
  <div class="cards">
    <div class="card">
  <div class="card-text">
    <span class="card-title">Total Commuters</span>
    <div class="card-footer">
      <div class="card-value">150</div>
      <div class="card-icon">
        <img src="black-commuters.png" alt="Total Commuters" class="menu-icon">
      </div>
    </div>
  </div>
</div>
    <div class="card">
  <div class="card-text">
    <span class="card-title">Active Drivers</span>
    <div class="card-footer">
      <div class="card-value">10</div>
      <div class="card-icon">
        <img src="black-drivers.png" alt="Active Drivers" class="menu-icon">
      </div>
    </div>
  </div>
</div>
    <div class="card">
  <div class="card-text">
    <span class="card-title">Ongoing Trips</span>
    <div class="card-footer">
      <div class="card-value">10</div>
      <div class="card-icon">
        <img src="black-trips.png" alt="Ongoing Trips" class="menu-icon">
      </div>
    </div>
  </div>
</div>
   <div class="card">
  <div class="card-text">
    <span class="card-title">Pending Reports</span>
    <div class="card-footer">
      <div class="card-value">50</div>
      <div class="card-icon">
        <img src="black-reports.png" alt="Pending Reports" class="menu-icon">
      </div>
    </div>
  </div>
</div>
  </div>

  <!-- === Line Graph === -->
  <div class="charts-row">
  <div class="linechart-container">
    <canvas id="myChart1"></canvas>
  </div>
   </div>
    </main>
</body>
<script>
  let courses=["Sun","Mon","Tue","Wed","Thu","Fri","Sat"]// x ax
  let students=[90,100,30,40,35,100,150,200] //y ax
  
new Chart("myChart1",{
            type:'line',
            data:{
                labels: courses,
                datasets: [{
                  label: "Commuters",
                  data: students,
                  borderColor: "green",               // line color
                  backgroundColor: "rgba(101, 101, 255, 0.2)", // fill color
                  pointBackgroundColor: "green",      // point (dot) color
                         // border around points
                  fill: true,                         // fill area under line
                  tension: 0.3                        // smooth curve
      }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              legend: { display: true },
              title: {
                display: true,
                text: 'Passenger Trends'
           
                   }
                  }
        });
        </script>
</html>
