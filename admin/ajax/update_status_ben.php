<?php
include '../includes/conn.php';
session_start();

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

   
    $sql = "UPDATE mandatory_benefits SET status = ? WHERE mandateid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo 'success';  // Return success if the update is successful
    } else {
        echo 'error';  // Return error if something goes wrong
    }
    $stmt->close();
}
?>
