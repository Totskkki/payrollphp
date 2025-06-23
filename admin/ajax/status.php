<?php
session_start();
	include '../includes/conn.php';
if (isset($_POST['empid']) && isset($_POST['status'])) {
    $empid = $_POST['empid'];
    $status = $_POST['status'];

    $sql = "UPDATE employee_details SET status = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $empid);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Status updated successfully.';       
        echo 'success';
    } else {
        $_SESSION['message'] = 'Failed to update status.';       
        echo 'error';
    }
} else {
    $_SESSION['message'] = 'Invalid request.';
    echo 'invalid';
}
?>
