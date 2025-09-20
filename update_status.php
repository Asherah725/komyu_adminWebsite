<?php
include 'db_connect.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    // Allowed values should match your JS buttons
    $allowed = ['Pending', 'Approved', 'Rejected'];
    if (!in_array($status, $allowed)) {
        echo "invalid";
        exit();
    }

    // Use prepared statement for security
    $stmt = $conn->prepare("UPDATE commuters SET id_status = ? WHERE commuter_id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "invalid";
}
?>
