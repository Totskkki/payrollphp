<?php
include '../includes/conn.php';
session_start();
if (isset($_POST['first_name'], $_POST['last_name'], $_POST['middle_name'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM employee WHERE first_name = ? AND last_name = ? AND middle_name = ?");
    $stmt->bind_param("sss", $first_name, $last_name, $middle_name);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(['exists' => $count > 0]);
}

