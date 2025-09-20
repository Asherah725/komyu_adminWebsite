<?php
session_start();

// Protect the page
if (!isset($_SESSION['admin_id'])) {
  header("Location: index.php");
  exit();
}

// Include database connection
include 'db_connect.php';

// Fetch commuters data
$sql = "SELECT commuter_id, username, email, 
               CONCAT(first_name, ' ', last_name) AS fullname, 
               categories AS role, account_created, type_of_id, id_status 
        FROM commuters";

$result = mysqli_query($conn, $sql);
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
        font-size: 30px;
        margin-top: 14px;
        margin-bottom: 25px;
    }

    /* Search container */
.search-container {
  position: relative;
  display: inline-block;
}

.search-container input[type="text"] {
  width: 350px;
  font-size: 15px;
  padding: 10px 40px 10px 14px; /* add space on the right for icon */
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Icon inside search bar */
.search-container .search-icon {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  width: 30px;
  height: 30px;
  opacity: 0.7;
  cursor: pointer;
}

/* Button icons */
.btn-icon {
  width: 25px;
  height: 25px;
  margin-right: 6px;
  vertical-align: middle;
}

    .search-filter {
        display: flex;
        justify-content: space-between; /* left on one side, right on the other */
        align-items: center;
        margin-bottom: 26px;
   }
   .left-controls {
        display: flex;
        align-items: center;
        gap: 10px; /* space between search and filter */
    }

    .left-controls input[type="text"] {
       width: 395px;   /* Bigger search bar */
      font-size: 15px; /* Slightly bigger text */
      padding: 15px;   /* More padding for nicer look */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .left-controls select {
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
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
    button.btn.delete:disabled {
      background-color: #ccc;
      color: #666;
      border: 1px solid #999;
      cursor: not-allowed;
    }
    
    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
    }

    th {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
      font-size: 16px;
    }
    td {
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

    /* Hover highlight */
    table tbody tr:hover {
      background-color: #e8f9f0;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    /* Persistent highlight for selected rows */
    tr.selected {
      background-color: #c6f6d5;   /* light green highlight */
    }

/* --- Dropdown Button --- */
#filterBtn {
  padding: 14px 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #fff;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
}

/* --- Dropdown Container --- */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  min-width: 190px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  padding: 6px 0;
  z-index: 20;
}

/* Show dropdown when active */
.dropdown-content.show {
  display: block;
}

/* --- Dropdown Items --- */
.filter-option,
.sub-option {
  padding: 10px 16px;
  font-size: 14px;
  cursor: pointer;
  white-space: nowrap;
  transition: background-color 0.2s ease;
}
.filter-option:hover,
.sub-option:hover {
  background-color: #f5f5f5;
}

/* --- Role option with submenu indicator --- */
.has-submenu {
  position: relative;
}
.has-submenu::after {
  position: absolute;
  right: 10px;
  font-size: 12px;
  color: #666;
}

/* --- Submenu --- */
.submenu {
  display: none;
  position: absolute;
  top: 0;
  left: 100%;
  margin-left: 2px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  min-width: 180px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  padding: 6px 0;
}
.has-submenu:hover .submenu {
  display: block;
}

.id-wrapper {
  position: relative;
  display: inline-block;
}

.id-wrapper {
  position: relative;
  display: inline-block;
}

.valid-id {
  width: 100px;
  height: auto;
  display: block;
  cursor: pointer;
  border-radius: 4px;
}

/* overlay effect */
.id-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6); /* transparent black */
  color: #fff;
  font-size: 14px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 4px;
}

/* show overlay on hover */
.id-wrapper:hover .id-overlay {
  opacity: 1;
}
/* Modal styles */
.modal {
  display: none; 
  position: fixed; 
  z-index: 100; 
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.7);
  justify-content: center; align-items: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  max-width: 500px;
  width: 90%;
  position: relative;
}

.modal-content img {
  width: 200px;
  margin: 10px;
  border-radius: 8px;
}

.modal-buttons {
  margin-top: 15px;
}

.modal-buttons button {
  padding: 8px 16px;
  margin: 0 10px;
  border: none;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
}

.approve-btn { background-color: #28a745; color: white; }
.reject-btn { background-color: #dc3545; color: white; }

.close-btn {
  position: absolute;
  top: 10px; right: 15px;
  font-size: 20px;
  cursor: pointer;
  font-weight: bold;
}
/* Modal buttons hover */
.approve-btn:hover {
    background-color: #218838; /* darker green */
    transform: scale(1.05);
    transition: all 0.2s ease;
}

.reject-btn:hover {
    background-color: #c82333; /* darker red */
    transform: scale(1.05);
    transition: all 0.2s ease;
}

/* Toast notification */
.toast {
    visibility: hidden;
    min-width: 250px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 12px;
    position: fixed;
    z-index: 9999;
    left: 50%;
    bottom: 30px;
    font-size: 16px;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.5s, bottom 0.5s;
}

.toast.show {
    visibility: visible;
    opacity: 1;
    bottom: 50px;
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
          <img src="gray-icons/gray-dashboard.png" alt="Dashboard" class="menu-icon"> Dashboard</a>
        <a href="commuters.php" class="active" >
          <img src="white-icons/white-commuters.png" alt="Commuters" class="menu-icon"> Commuters</a>
        <a href="drivers&conductors.php">
          <img src="gray-icons/gray-drivers.png" alt="Drivers" class="menu-icon"> Drivers & Conductors</a>
        <a href="reports&ratings.php">
          <img src="gray-icons/gray-reports.png" alt="Reports" class="menu-icon"> Reports & Ratings</a>
        <a href="trips.php">
          <img src="gray-icons/gray-trips.png" alt="Trips" class="menu-icon"> Trips</a>
        <a href="payments&points.php">
          <img src="gray-icons/gray-payments.png" alt="Payments" class="menu-icon"> Payment & Points</a>
          <a href="settings.php">
          <img src="gray-icons/gray-settings.png" alt="Acount Settings" class="menu-icon"> Account Settings</a>
      </div>
    </div>

    <div class="logout">
      <a href="index.php">
        <img src="icon-logout.png" alt="Logout" class="menu-icon"> Logout</a>
    </div>
  </nav>

  <!-- Main -->
  <div class="main">
    <div class="topbar">
      <span>Hello, Admin!</span>
      <img src="profile.jpg" alt="User Avatar" class="avatar">
    </div>

  <!-- Content -->
  <div class="content">
    <h3>Commuters list</h3>
    <div class="search-filter">
    <div class="left-controls">   
      <div class="search-container">
      <input type="text" id="searchBox" placeholder="Search...">
      <img src="gray-icons/gray-search.png" class="search-icon" alt="Search">
    </div>

    <div class="dropdown">
  <button id="filterBtn">Filter By ▾</button>
  <div class="dropdown-content">
  <div class="filter-option" data-filter="clear">Clear Filter</div>

  <!-- Commuter Number -->
  <div class="filter-option has-submenu">
    Commuter Number ▸
    <div class="submenu">
      <div class="sub-option" data-filter="commuter_id" data-order="ASC">Ascending Order</div>
      <div class="sub-option" data-filter="commuter_id" data-order="DESC">Descending Order</div>
    </div>
  </div>

  <!-- Role -->
  <div class="filter-option has-submenu">
    Role ▸
    <div class="submenu">
      <div class="sub-option" data-filter="role" data-role="Regular">Regular</div>
      <div class="sub-option" data-filter="role" data-role="Student">Student</div>
      <div class="sub-option" data-filter="role" data-role="PWD">PWD</div>
      <div class="sub-option" data-filter="role" data-role="Senior Citizen">Senior Citizen</div>
    </div>
  </div>

  <!-- Date Creation -->
  <div class="filter-option has-submenu">
    Date Creation ▸
    <div class="submenu">
      <div class="sub-option" data-filter="account_created" data-order="ASC">Oldest to Newest</div>
    <div class="sub-option" data-filter="account_created" data-order="DESC">Newest to Oldest</div>
    </div>
  </div>

  <!-- ID Status -->
<div class="filter-option has-submenu">
  ID Status ▸
  <div class="submenu">
    <div class="sub-option" data-filter="id_status" data-value="Pending">Pending</div>
    <div class="sub-option" data-filter="id_status" data-value="Approved">Approved</div>
    <div class="sub-option" data-filter="id_status" data-value="Rejected">Rejected</div>
  </div>
</div>
</div>
</div>
    </div>
    <div class="right-controls">
        <button class="btn delete" id="deleteBtn" disabled>
        <img src="gray-icons/gray-delete.png" class="btn-icon" alt="Delete"> Delete
    </button>
</div>
  </div>

   <!-- Table -->
    <table>
      <thead>
        <tr>
          <!-- Added Select All checkbox in the first column -->
          <th><input type="checkbox" id="selectAll"></th>
          <th>Commuter No.</th>
          <th>Username</th>
          <th>Email</th>
          <th>Fullname</th>
          <th>Role</th>
          <th>Date Creation</th>
          <th>Valid ID</th>
          <th>ID Status</th>
        </tr>
      </thead>
      <tbody id="commutersTable">

<?php
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
  $statusClass = strtolower($row['id_status']); // pending, approved, rejected

  echo "<tr>
    <td><input type='checkbox' class='commuterCheckbox' value='{$row['commuter_id']}'></td>
    <td>{$row['commuter_id']}</td>
    <td>{$row['username']}</td>
    <td>{$row['email']}</td>
    <td>{$row['fullname']}</td>
    <td>{$row['role']}</td>
    <td>{$row['account_created']}</td>
    <td>
  <div class='id-wrapper'>
    <img src='logo.png'
         alt='Front ID'
         class='valid-id'
         data-front='logo.png'
         data-back='logo.png'
         data-id='{$row['commuter_id']}'>
    <div class='id-overlay'>View Picture</div>
  </div>
</td>
    <td><span class='status {$statusClass}'>{$row['id_status']}</span></td>
  </tr>";
}

  }else {
  echo "<tr><td colspan='9'>No commuters found</td></tr>";
}
$conn->close();
?>
</tbody>
</table>


      <!-- Modal -->
<div class="modal" id="idModal">
  <div class="modal-content">
    <span class="close-btn" id="closeModal">&times;</span>
    <h3>Valid ID Preview</h3>
    <div>
      <img src="dummy-front.jpg" id="frontPreview" alt="Front ID">
      <img src="dummy-back.jpg" id="backPreview" alt="Back ID">
    </div>
    <div class="modal-buttons">
      <button class="approve-btn" id="approveBtn">Approve</button>
      <button class="reject-btn" id="rejectBtn">Reject</button>
    </div>
  </div>
</div>
<div id="toast" class="toast"></div>

<script>
document.getElementById("searchBox").addEventListener("keyup", function() {
    let query = this.value;  // get what the user typed
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search_commuters.php?q=" + query, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // replace the table rows with the new results
            document.getElementById("commutersTable").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
});
//Select All functionality
document.getElementById("selectAll").addEventListener("change", function() {
    let checkboxes = document.querySelectorAll("#commutersTable input[type='checkbox']");
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Define delete button first
const deleteBtn = document.getElementById("deleteBtn");

// Delete button functionality
deleteBtn.addEventListener("click", function() {
    let selected = [];
    document.querySelectorAll(".commuterCheckbox:checked").forEach(cb => {
        selected.push(cb.value);
    });

    if (selected.length === 0) {
        alert("Please select at least one commuter to delete.");
        return;
    }

    if (!confirm("Are you sure you want to delete the selected commuter(s)?")) {
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "delete_commuters.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200 && xhr.responseText === "success") {
            alert("Selected commuter(s) deleted successfully.");
            location.reload(); // reload page to refresh table
        } else {
            alert("Error deleting commuters.");
        }
    };
    xhr.send("ids[]=" + selected.join("&ids[]="));
});

// Function to toggle delete button state
function toggleDeleteButton() {
  const deleteBtn = document.getElementById("deleteBtn");
  const deleteIcon = deleteBtn.querySelector("img");
  const checked = document.querySelectorAll(".commuterCheckbox:checked").length > 0;

  if (checked) {
    deleteBtn.disabled = false;
    deleteIcon.src = "white-icons/white-delete.png"; // active icon
  } else {
    deleteBtn.disabled = true;
    deleteIcon.src = "gray-icons/gray-delete.png"; // disabled icon
  }
}


// Watch individual checkboxes
document.addEventListener("change", function(e) {
  if (e.target.classList.contains("commuterCheckbox") || e.target.id === "selectAll") {
    toggleDeleteButton();
  }
});


// --- Dropdown toggle ---
const filterBtn = document.getElementById('filterBtn');
const dropdownContent = document.querySelector('.dropdown-content');

// Toggle dropdown visibility
filterBtn.addEventListener('click', function (e) {
  e.stopPropagation();
  dropdownContent.classList.toggle('show');
});

// Close dropdown when clicking outside
document.addEventListener('click', function () {
  dropdownContent.classList.remove('show');
});

// Handle submenu clicks for all filters
document.querySelectorAll('.sub-option').forEach(opt => {
  opt.addEventListener('click', function (e) {
    e.stopPropagation();
    const filter = this.dataset.filter;     // commuter_id, role, account_created, id_status
    const order = this.dataset.order || ''; // ASC / DESC
    const value = this.dataset.value || this.dataset.role || ''; // Pending/Approved/Rejected or role

    let displayText = '';

    switch (filter) {
      case 'commuter_id':
        displayText = `Commuter Number (${order === 'ASC' ? 'Ascending' : 'Descending'})`;
        break;
      case 'role':
        displayText = `Role (${value})`;
        break;
      case 'account_created':
        displayText = `Date Creation (${order === 'ASC' ? 'Oldest to Newest' : 'Newest to Oldest'})`;
        break;
      case 'id_status':
        displayText = `ID Status (${value})`;
        break;
      default:
        displayText = 'Filter By ▾';
    }

    filterBtn.textContent = 'Filter By: ' + displayText;
    dropdownContent.classList.remove('show');

    // Send the filter and value to PHP
    applyFilter(filter, order || value);
  });
});

// Clear filter option
document.querySelectorAll('.filter-option[data-filter="clear"]').forEach(opt => {
  opt.addEventListener('click', function (e) {
    e.stopPropagation();
    filterBtn.textContent = 'Filter By ▾';
    location.reload();
  });
});

// AJAX function
function applyFilter(filter, value) {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', `search_commuters.php?filter=${filter}&value=${value}`, true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      document.getElementById('commutersTable').innerHTML = xhr.responseText;
    }
  };
  xhr.send();
}


function updateStatus(id, status) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "update_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function() {
    if (xhr.status === 200 && xhr.responseText === "success") {
      alert("Status updated successfully!");
      location.reload();
    }
  };
  xhr.send("id=" + id + "&status=" + status);
}
// Highlight entire row when checkbox is clicked
document.addEventListener('change', function(e) {
  if (e.target.classList.contains('commuterCheckbox')) {
    const row = e.target.closest('tr');
    if (e.target.checked) {
      row.classList.add('selected');
    } else {
      row.classList.remove('selected');
    }
  }
});

// Also handle "Select All" checkbox to highlight all rows
document.getElementById('selectAll').addEventListener('change', function() {
  const allRows = document.querySelectorAll('#commutersTable tr');
  allRows.forEach(row => {
    const checkbox = row.querySelector('.commuterCheckbox');
    if (checkbox) {
      row.classList.toggle('selected', this.checked);
    }
  });
});

// Handle approve/reject buttons
document.getElementById('approveBtn').addEventListener('click', () => {
  updateStatus(currentCommuterId, 'Approved');
  modal.style.display = 'none';
});

document.getElementById('rejectBtn').addEventListener('click', () => {
  updateStatus(currentCommuterId, 'Rejected');
  modal.style.display = 'none';
});

// --- Modal functionality ---
const modal = document.getElementById('idModal');
const closeModal = document.getElementById('closeModal');
let currentCommuterId = null;

// Show modal on image click
// Show modal on image or overlay click
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('valid-id') || e.target.classList.contains('id-overlay')) {
    const img = e.target.closest('.id-wrapper').querySelector('.valid-id');
    const front = img.dataset.front;
    const back = img.dataset.back;
    currentCommuterId = img.dataset.id;

    // Set images
    document.getElementById('frontPreview').src = front;
    document.getElementById('backPreview').src = back;

    modal.style.display = 'flex';
  }
});


// Close modal
closeModal.addEventListener('click', () => modal.style.display = 'none');

// Close modal if clicking outside content
window.addEventListener('click', (e) => {
  if (e.target === modal) modal.style.display = 'none';
});

// Handle approve/reject buttons
document.getElementById('approveBtn').addEventListener('click', () => {
  updateStatus(currentCommuterId, 'Approved');
  modal.style.display = 'none';
});

document.getElementById('rejectBtn').addEventListener('click', () => {
  updateStatus(currentCommuterId, 'Rejected');
  modal.style.display = 'none';
});

function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500); // Toast disappears after 2.5 seconds
}

function updateStatus(id, status) {
    fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            const row = document.querySelector("input[value='" + id + "']").closest("tr");
            row.querySelector("td:last-child span").textContent = status;
            row.querySelector("td:last-child span").className = 'status ' + status.toLowerCase();

            modal.style.display = 'none'; // close modal
            showToast(status + " successfully!"); // show toast
        } else {
            showToast("Failed to update status.");
        }
    })
    .catch(err => {
        console.error(err);
        showToast("Error updating status.");
    });
}

// Approve/Reject buttons
document.getElementById('approveBtn').addEventListener('click', () => {
    updateStatus(currentCommuterId, 'Approved');
});

document.getElementById('rejectBtn').addEventListener('click', () => {
    updateStatus(currentCommuterId, 'Rejected');
});


</script>
</body>
</html>
