<?php 
include '../includes/conn.php';

session_start();
$sql = "SELECT `dedID`, `deduction`, `deduction_type`, `amount`, `description`, `date_added` FROM `deductions`";
$query = $conn->query($sql);
$deductions = [];
while ($row = $query->fetch_assoc()) {
    $deductions[] = $row;
}

// Return the allowances as a JSON response
echo json_encode($deductions);
