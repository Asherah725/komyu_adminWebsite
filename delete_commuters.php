<?php
include 'db_connect.php';

if (isset($_POST['ids'])) {
    $ids = $_POST['ids']; // array of commuter IDs

    // Convert array to string for SQL
    $idsString = implode(",", array_map('intval', $ids));

    $sql = "DELETE FROM commuters WHERE commuter_id IN ($idsString)";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
}
$conn->close();
?>