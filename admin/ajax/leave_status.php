<?php
include '../includes/conn.php';
session_start();
if (isset($_POST['status'])) {
    $leaveid = $_POST['leaveid'];
    $status = $_POST['status'];

    // Check the current status of the leave
    $checkStatusSql = "SELECT status FROM `leave` WHERE leaveid = ?";
    $checkStmt = $conn->prepare($checkStatusSql);
    $checkStmt->bind_param("i", $leaveid);
    $checkStmt->execute();
    $checkStmt->bind_result($currentStatus);
    $checkStmt->fetch();
    $checkStmt->close();


    if ($currentStatus == 2) {
        echo "Error: This leave is already approved and cannot be updated.";
        $conn->close();
        exit;
    } elseif ($currentStatus == 1) {
        echo "Error: This leave is already disapproved and cannot be updated.";
        $conn->close();
        exit;
    } else {
       
        $updateSql = "UPDATE `leave` SET status = ? WHERE leaveid = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $status, $leaveid);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
