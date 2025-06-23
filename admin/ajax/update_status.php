<?php
include '../includes/conn.php';
session_start();
if (isset($_POST['payid']) && isset($_POST['status'])) {
    $payid = $_POST['payid'];
    $status = $_POST['status'];

    $query = "UPDATE pay_periods SET status = ? WHERE payid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $payid);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
    $conn->close();
} else {
    echo 'invalid';
}

