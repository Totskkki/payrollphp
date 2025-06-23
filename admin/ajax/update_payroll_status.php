<?php
// update_payroll_status.php

include '../includes/conn.php';
session_start();
if (isset($_POST['payrollid'])) {
    $payrollid = $_POST['payrollid'];

    // Update the payroll status to 'active'
    $sql = "UPDATE payroll SET status = 'approve' WHERE payrollid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payrollid);

    if ($stmt->execute()) {
        echo "Status updated successfully!";
    } else {
        echo "Error updating status.";
    }

    $stmt->close();
    $conn->close();
}
?>
