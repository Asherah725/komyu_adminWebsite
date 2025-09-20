<?php
include 'db_connect.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';
$value  = isset($_GET['value']) ? trim($_GET['value']) : '';

$sql = "SELECT commuter_id, username, email, CONCAT(first_name, ' ', last_name) AS fullname, 
               categories AS role, account_created, type_of_id, id_status

        FROM commuters 
        WHERE 1=1";

// Search by text
if ($search !== '') {
  $safeSearch = mysqli_real_escape_string($conn, $search);
  $sql .= " AND (username LIKE '%$safeSearch%' 
             OR email LIKE '%$safeSearch%'
             OR CONCAT(first_name, ' ', last_name) LIKE '%$safeSearch%'
             OR categories LIKE '%$safeSearch%')";
}

// Dropdown filters
$orderBy = ''; // initialize

if ($filter !== '' && $value !== '') {
    $safeValue = mysqli_real_escape_string($conn, $value);

    switch ($filter) {
        case 'role':
            $sql .= " AND categories = '$safeValue'";
            break;

        case 'commuter_id':
            if (in_array(strtoupper($safeValue), ['ASC','DESC'])) {
                $orderBy = " ORDER BY commuter_id " . strtoupper($safeValue);
            } else {
                $sql .= " AND commuter_id LIKE '%$safeValue%'";
            }
            break;

        case 'account_created':
            if (in_array(strtoupper($safeValue), ['ASC','DESC'])) {
                $orderBy = " ORDER BY account_created " . strtoupper($safeValue);
            } else {
                $sql .= " AND account_created LIKE '%$safeValue%'";
            }
            break;

        case 'fullname':
            $sql .= " AND CONCAT(first_name, ' ', last_name) LIKE '%$safeValue%'";
            break;

        case 'id_status':
            $sql .= " AND id_status = '$safeValue'";
            break;
    }
}

// Append orderBy before executing query
$sql .= $orderBy;

$result = mysqli_query($conn, $sql);

$result = mysqli_query($conn, $sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusClass = strtolower($row['id_status']); // pending, approved, rejected
        echo "<tr>
                <td><input type='checkbox' class='commuterCheckbox' value='{$row['commuter_id']}'></td>
                <td>{$row['commuter_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['fullname']}</td>
                <td>{$row['role']}</td>
                <td>{$row['account_created']}</td>
                <td>{$row['type_of_id']}</td>
                <td><span class='status {$statusClass}'>{$row['id_status']}</span></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No commuters found</td></tr>";
}



$conn->close();
