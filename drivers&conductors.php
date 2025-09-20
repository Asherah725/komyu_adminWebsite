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

  #deleteBtn:hover {
    background: #b71c1c;
  }
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  .header h2 { color: #333; }

  .search-filter {
    display: flex;
    justify-content: space-between; /* pushes search left, button right */
    align-items: center;            /* vertical alignment */
    margin-bottom: 15px;
  }

  .search-inputs input {
    width: 300px;         /* Bigger search bar */
    font-size: 15px;      /* Slightly bigger text */
    padding: 10px 14px;   /* More padding for nicer look */
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  .search-inputs select {
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  .actions button {
    padding: 8px 16px; border: none; border-radius: 4px;
    background: #357edd; color: #fff; cursor: pointer;
  }
  .actions button:hover { 
    background: #285bb5; 
  }
    
  table td a {
    margin-left: 50px; /* adds space before View → */
    color: #357edd;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
  }

  table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 15px; 
  }
  table thead { 
    background: #1b8e2a; 
    color: #fff; 
  }
  table th, table td {
    padding: 10px; 
    text-align: left; 
    border-bottom: 1px solid #ddd; 
    font-size: 14px;
  }
  table td a {
    color: #357edd; 
    text-decoration: none; 
    font-weight: bold; 
    cursor: pointer;
  }

  .pagination { 
    margin-top: 15px; 
    display: flex; 
    justify-content: center; 
    align-items: center; 
    gap: 8px; 
  }
  .pagination button {
    padding: 5px 12px; 
    border: 1px solid #ccc; 
    background: #fff;
    cursor: pointer; 
    border-radius: 4px;
  }
  .pagination button.active { 
    background: #1b8e2a; 
    color: #fff; 
    border-color: #1b8e2a; 
  }
  .pagination button:hover { 
    background: #eee; 
  }

  .modal {
    display: none; 
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background: rgba(0,0,0,0.6); 
    justify-content: center; 
    align-items: center; 
    z-index: 1000;
  }
  .modal-content {
    background: #fff;
    padding: 20px; 
    border-radius: 6px; 
    width: 400px;
  }
  .modal-content h3 { 
    margin-bottom: 15px; 
  }
  .modal-content input, .modal-content select {
    width: 100%; 
    padding: 8px; 
    margin-bottom: 10px; 
    border: 1px solid #ccc; 
    border-radius: 4px;
  }
  .modal-content button { 
    padding: 8px 14px; 
    border: none; 
    border-radius: 4px; c
    ursor: pointer; 
  }
  .save-btn { 
    background: #1b8e2a; 
    color: #fff; 
    margin-right: 10px; 
  }
  .cancel-btn { 
    background: #ccc; 
  }
  .overlay {
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background: rgba(0,0,0,0.5); 
    display: none; 
    justify-content: center; 
    align-items: center;
    z-index: 2000;
  }
  .details-card {
    background: #fff;
    width: 500px; 
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 20px; 
    animation: fadeIn 0.3s ease-in-out;
  }
  @keyframes fadeIn { 
    from { transform: scale(0.9); 
    opacity: 0; 
    } to { 
    transform: scale(1); 
    opacity: 1; } 
    }
  .card-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
  }
  .card-header h2 { 
    font-size: 18px; 
    margin: 0; 
  }
  .close-btn { 
    cursor: pointer; 
    font-size: 20px; 
  }
  .profile { 
    text-align: center; 
    margin: 20px 0; 
  }
  .profile img { 
    width: 80px; 
    height: 80px; 
    border-radius: 50%; 
    margin-bottom: 10px; 
  }
  .profile h3 { 
    margin: 5px 0; 
  }
  .profile p { 
    color: #666; 
    margin: 0 0 10px 0; 
  }
  .status { 
    display: flex; 
    justify-content: center; 
    gap: 10px; 
  }
  .status span { 
    padding: 4px 10px; 
    border-radius: 6px; 
    font-size: 12px; 
    font-weight: bold;
  }
  .active { 
    background: #e0f8ea; 
    color: #0e9f6e; 
  }
  .role { 
    background: #f2f2f2; 
    color: #555; 
  }
  .section { 
    margin: 20px 0; 
  }
  .section h4 { 
    margin-bottom: 10px; 
    font-size: 14px; 
    color: #444; 
  }
  .info-grid { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 10px; 
    font-size: 14px; 
  }
  .info-item { 
    display: flex; 
    align-items: center; 
    gap: 6px; 
    color: #555;
  }
  hr { 
    border: none; 
    border-top: 1px solid #eee; 
    margin: 20px 0;
  }
  .stats { 
    display: flex; 
    justify-content: space-between; 
    text-align: center; 
  }
  .stat-box { 
    flex: 1; 
    padding: 10px;
    background: #f9f9f9;
    border-radius: 8px; 
    margin: 0 5px; 
  }
  .stat-box h2 { 
    margin: 0; 
    font-size: 20px; 
  }
  .stat-box p { 
    margin: 5px 0 0; 
    color: #666; 
    font-size: 13px; 
  }
  .content h2 {
    margin-bottom: 15px;
  }
  .content h3{
    margin-bottom: 15px;
    font-size: 25px;
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
        <a href="drivers&conductors.php" class="active">
          <img src="white-drivers.png" alt="Drivers" class="menu-icon"> Drivers & Conductors</a>
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

  <!-- Content -->
  <div class="content">
    <h3>Driver's & Conductors</h3>
    <!-- Search and filter moved below Drivers list -->
   <div class="search-filter">
  <div class="search-inputs">
    <input type="text" placeholder="Search...">
    <select>
      <option>Select Role</option>
      <option>Driver</option>
      <option>Conductor</option>
    </select>
  </div>
  <div class="actions">
    <button id="addEmployeeBtn">Add Employee</button>
    <button id="deleteBtn" style="display:none; background:#e63946; margin-left:8px;">
    <span class="iconify" data-icon="mdi:trash-can-outline" style="color:white; font-size:18px;"></span>
  </button>
  </div>
</div>

    <table id="employeeTable">
      <thead>
        <tr>
          <th></th>
          <th>Employee No.</th>
          <th>Username</th>
          <th>Email</th>
          <th>Fullname</th>
          <th>Role</th>
          <th>Assigned Bus</th>
          <th>License ID</th>
        </tr>
      </thead>
   <tbody>
  <tr>
    <td><input type="checkbox"></td>
    <td>123456</td>
    <td>pretty123</td>
    <td>nimm@gmail.com</td>
    <td>Nimrod Abayon</td>
    <td>Driver</td>
    <td>Bus 01</td>
    <td>DL-12345 <span style="margin-left:8px;"></span><a onclick="openDetails('123456','Nimrod Abayon','pretty123','nimm@gmail.com','Driver','Bus 01','DL-12345')"></a></td>
  </tr>
  <tr>
    <td><input type="checkbox"></td>
    <td>111112</td>
    <td>user112</td>
    <td>user112@gmail.com</td>
    <td>Asherah Amarado</td>
    <td>Conductor</td>
    <td>Bus 01</td>
    <td>DL-54321 <span style="margin-left:8px;"></span><a onclick="openDetails('111112','Asherah Amarado','user112','user112@gmail.com','Conductor','Bus 01','DL-54321')"></a></td>
  </tr>
  <tr>
    <td><input type="checkbox"></td>
    <td>222223</td>
    <td>driver223</td>
    <td>driver223@gmail.com</td>
    <td>Michael Santos</td>
    <td>Driver</td>
    <td>Bus 02</td>
    <td>DL-22345 <span style="margin-left:8px;"></span><a onclick="openDetails('222223','Michael Santos','driver223','driver223@gmail.com','Driver','Bus 02','DL-22345')"></a></td>
  </tr>
  <tr>
    <td><input type="checkbox"></td>
    <td>333334</td>
    <td>cond334</td>
    <td>cond334@gmail.com</td>
    <td>Angela Dizon</td>
    <td>Conductor</td>
    <td>Bus 03</td>
    <td>DL-33456 <span style="margin-left:8px;"></span><a onclick="openDetails('333334','Angela Dizon','cond334','cond334@gmail.com','Conductor','Bus 03','DL-33456')"></a></td>
  </tr>
  <tr>
    <td><input type="checkbox"></td>
    <td>444445</td>
    <td>driver445</td>
    <td>driver445@gmail.com</td>
    <td>Carlos Mendoza</td>
    <td>Driver</td>
    <td>Bus 04</td>
    <td>DL-44567 <span style="margin-left:8px;"></span><a onclick="openDetails('444445','Carlos Mendoza','driver445','driver445@gmail.com','Driver','Bus 04','DL-44567')"></a></td>
  </tr>
  <tr>
    <td><input type="checkbox"></td>
    <td>555556</td>
    <td>cond556</td>
    <td>cond556@gmail.com</td>
    <td>Jenny Reyes</td>
    <td>Conductor</td>
    <td>Bus 05</td>
    <td>DL-55678 <span style="margin-left:8px;"></span><a onclick="openDetails('555556','Jenny Reyes','cond556','cond556@gmail.com','Conductor','Bus 05','DL-55678')"></a></td>
  </tr>
  <tr>
    <td><input type="checkbox"></td>
    <td>666667</td>
    <td>driver667</td>
    <td>driver667@gmail.com</td>
    <td>Marco Villanueva</td>
    <td>Driver</td>
    <td>Bus 06</td>
    <td>DL-66789 <span style="margin-left:8px;"></span><a onclick="openDetails('666667','Marco Villanueva','driver667','driver667@gmail.com','Driver','Bus 06','DL-66789')"></a></td>
  </tr>
</tbody>

    </table>

    <div class="pagination">
      <button>Prev</button>
      <button class="active">1</button>
      <button>2</button>
      <button>3</button>
      <button>Next</button>
    </div>
  </div>

  <!-- Add Employee Modal -->
  <div class="modal" id="employeeModal">
    <div class="modal-content">
      <h3>Add New Employee</h3>
      <input type="text" id="empNo" placeholder="Employee No." required>
      <input type="text" id="username" placeholder="Username" required>
      <input type="email" id="email" placeholder="Email" required>
      <input type="text" id="fullname" placeholder="Fullname" required>
      <select id="role">
        <option value="Driver">Driver</option>
        <option value="Conductor">Conductor</option>
      </select>
      <input type="text" id="bus" placeholder="Assigned Bus">
      <input type="text" id="license" placeholder="License ID">
      <div>
        <button class="save-btn" onclick="saveEmployee()">Save</button>
        <button class="cancel-btn" onclick="closeModal()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- Driver Details Overlay -->
  <div class="overlay" id="detailsOverlay">
    <div class="details-card">
      <div class="card-header">
        <h2>Driver Details</h2>
        <span class="close-btn" onclick="closeDetails()">&times;</span>
      </div>

      <div class="profile">
        <img src="https://via.placeholder.com/80" alt="Driver">
        <h3 id="detailName">Name</h3>
        <p id="detailUsername">@username</p>
        <div class="status">
          <span class="active" id="detailStatus">Active</span>
          <span class="role" id="detailRole">Role</span>
        </div>
      </div>

      <div class="section">
        <h4>Basic Information</h4>
        <div class="info-grid">
          <div class="info-item"><span class="iconify" data-icon="mdi:card-account-details-outline"></span> <span id="detailEmpNo"></span></div>
          <div class="info-item"><span class="iconify" data-icon="mdi:email-outline"></span> <span id="detailEmail"></span></div>
          <div class="info-item"><span class="iconify" data-icon="mdi:phone-outline"></span> Phone: 09123456789</div>
          <div class="info-item"><span class="iconify" data-icon="mdi:calendar-blank-outline"></span> Date Joined: 2022-03-15</div>
          <div class="info-item"><span class="iconify" data-icon="mdi:map-marker-outline"></span> Address: Manila</div>
        </div>
      </div>

      <hr>

      <div class="section">
        <h4>License & Vehicle Information</h4>
        <div class="info-grid">
          <div class="info-item"><span class="iconify" data-icon="mdi:card-account-details"></span> <span id="detailLicense"></span></div>
          <div class="info-item"><span class="iconify" data-icon="mdi:calendar-alert"></span> Expiry: 2026-05-10</div>
          <div class="info-item"><span class="iconify" data-icon="mdi:bus"></span> <span id="detailBus"></span></div>
        </div>
      </div>

      <hr>

      <div class="section">
        <h4>Emergency Contact</h4>
        <div class="info-grid">
          <div class="info-item"><span class="iconify" data-icon="mdi:account-outline"></span> Contact Name: Juan Dela Cruz</div>
          <div class="info-item"><span class="iconify" data-icon="mdi:phone-outline"></span> Contact Phone: 09987654321</div>
        </div>
      </div>

      <hr>

      <div class="section">
        <h4>Performance Statistics</h4>
        <div class="stats">
          <div class="stat-box">
            <h2>142</h2>
            <p>Total Trips</p>
          </div>
          <div class="stat-box">
            <h2><span class="iconify" data-icon="mdi:star" style="color: gold;"></span> 4.8</h2>
            <p>Rating</p>
          </div>
          <div class="stat-box">
            <h2><span class="iconify" data-icon="mdi:clock-outline"></span> 2 hours ago</h2>
            <p>Last Trip</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
</div>

 <script>
   const modal = document.getElementById("employeeModal");
  const addBtn = document.getElementById("addEmployeeBtn");
  const tableBody = document.querySelector("#employeeTable tbody");
  const searchInput = document.querySelector(".search-inputs input");
  const roleSelect = document.querySelector(".search-inputs select");
  const pagination = document.querySelector(".pagination");
  const deleteBtn = document.getElementById("deleteBtn");

  let employees = []; 
  let currentPage = 1;
  const rowsPerPage = 5;

  // Load initial employees from table HTML
  function loadInitialEmployees() {
    const rows = Array.from(tableBody.querySelectorAll("tr"));
    employees = rows.map(row => {
      const cells = row.querySelectorAll("td");
      return {
        empNo: cells[1].innerText,
        username: cells[2].innerText,
        email: cells[3].innerText,
        fullname: cells[4].innerText,
        role: cells[5].innerText,
        bus: cells[6].innerText,
        license: cells[7].innerText.split("|")[0].trim()
      };
    });
    renderTable();
  }

  // ✅ Delete selected employees
  deleteBtn.addEventListener("click", () => {
    const checkboxes = document.querySelectorAll("#employeeTable tbody input[type='checkbox']");
    let toDelete = [];

    checkboxes.forEach((cb, index) => {
      if (cb.checked) {
        toDelete.push(tableBody.rows[index].cells[1].innerText); // collect empNo
      }
    });

    if (toDelete.length === 0) {
      alert("Please select at least one employee to delete.");
      return;
    }

    if (!confirm(`Are you sure you want to delete ${toDelete.length} employee(s)?`)) {
      return;
    }

    // filter out deleted employees
    employees = employees.filter(emp => !toDelete.includes(emp.empNo));
    renderTable();
  });

  // Show/hide delete button
  function updateDeleteButton() {
    const checkboxes = document.querySelectorAll("#employeeTable tbody input[type='checkbox']");
    let anyChecked = Array.from(checkboxes).some(cb => cb.checked);
    deleteBtn.style.display = anyChecked ? "inline-block" : "none";
  }

  function attachCheckboxListeners() {
    const checkboxes = document.querySelectorAll("#employeeTable tbody input[type='checkbox']");
    checkboxes.forEach(cb => {
      cb.addEventListener("change", updateDeleteButton);
    });
  }

  function renderTable() {
    let filtered = employees.filter(emp => {
      let matchesSearch =
        emp.fullname.toLowerCase().includes(searchInput.value.toLowerCase()) ||
        emp.username.toLowerCase().includes(searchInput.value.toLowerCase()) ||
        emp.email.toLowerCase().includes(searchInput.value.toLowerCase());
      let matchesRole =
        roleSelect.value === "Select Role" || emp.role === roleSelect.value;
      return matchesSearch && matchesRole;
    });

    let totalPages = Math.ceil(filtered.length / rowsPerPage);
    if (currentPage > totalPages) currentPage = totalPages || 1;

    let start = (currentPage - 1) * rowsPerPage;
    let end = start + rowsPerPage;
    let paginated = filtered.slice(start, end);

    tableBody.innerHTML = "";
    paginated.forEach(emp => {
      let row = document.createElement("tr");
      row.innerHTML = `
        <td><input type="checkbox"></td>
        <td>${emp.empNo}</td>
        <td>${emp.username}</td>
        <td>${emp.email}</td>
        <td>${emp.fullname}</td>
        <td>${emp.role}</td>
        <td>${emp.bus}</td>
        <td>${emp.license}  
          <a onclick="openDetails('${emp.empNo}','${emp.fullname}','${emp.username}','${emp.email}','${emp.role}','${emp.bus}','${emp.license}')">View →</a>
        </td>`;
      tableBody.appendChild(row);
    });

    renderPagination(totalPages);
    attachCheckboxListeners();
    updateDeleteButton();
  }

  // Pagination rendering stays the same
  function renderPagination(totalPages) {
    pagination.innerHTML = "";
    let prevBtn = document.createElement("button");
    prevBtn.textContent = "Prev";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => { currentPage--; renderTable(); };
    pagination.appendChild(prevBtn);

    for (let i = 1; i <= totalPages; i++) {
      let btn = document.createElement("button");
      btn.textContent = i;
      if (i === currentPage) btn.classList.add("active");
      btn.onclick = () => { currentPage = i; renderTable(); };
      pagination.appendChild(btn);
    }

    let nextBtn = document.createElement("button");
    nextBtn.textContent = "Next";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => { currentPage++; renderTable(); };
    pagination.appendChild(nextBtn);
  }

  // Search & Filter
  searchInput.addEventListener("input", () => { currentPage = 1; renderTable(); });
  roleSelect.addEventListener("change", () => { currentPage = 1; renderTable(); });

  // Add employee modal functions...
  addBtn.onclick = () => modal.style.display = "flex";
  function closeModal() { modal.style.display = "none"; }
  function saveEmployee() {
    let empNo = document.getElementById("empNo").value;
    let username = document.getElementById("username").value;
    let email = document.getElementById("email").value;
    let fullname = document.getElementById("fullname").value;
    let role = document.getElementById("role").value;
    let bus = document.getElementById("bus").value;
    let license = document.getElementById("license").value;

    if(empNo && username && email && fullname && role) {
      employees.push({ empNo, username, email, fullname, role, bus, license });
      renderTable();
      closeModal();
    } else {
      alert("Please fill all required fields.");
    }
  }
  window.onclick = (e) => { if(e.target == modal) closeModal(); }

  // Details overlay
  function openDetails(empNo, fullname, username, email, role, bus, license) {
    document.getElementById("detailEmpNo").innerText = "Employee No: " + empNo;
    document.getElementById("detailName").innerText = fullname;
    document.getElementById("detailUsername").innerText = "@" + username;
    document.getElementById("detailEmail").innerText = email;
    document.getElementById("detailRole").innerText = role;
    document.getElementById("detailBus").innerText = "Assigned Bus: " + bus;
    document.getElementById("detailLicense").innerText = "License ID: " + license;
    document.getElementById("detailsOverlay").style.display = "flex";
  }
  function closeDetails() { document.getElementById("detailsOverlay").style.display = "none"; }

  // Initialize
  loadInitialEmployees();
</script>
</body>
</html>
