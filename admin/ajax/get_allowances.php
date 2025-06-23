<?php 
include '../includes/conn.php';
session_start();

$sql = "SELECT `allowid`, `allowance`, `allowance_type`, `amount`, `description`, `created_on` FROM `allowance`";
$query = $conn->query($sql);
$allowances = [];
while ($row = $query->fetch_assoc()) {
    $allowances[] = $row;
}

// Return the allowances as a JSON response
echo json_encode($allowances);
