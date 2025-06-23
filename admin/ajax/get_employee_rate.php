<?php
include '../includes/conn.php';

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    $sql = "SELECT rate_per_hour FROM position 
            JOIN employee_details ON position.positionid = employee_details.positionid
            WHERE employee_details.employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['rate_per_hour' => $row['rate_per_hour']]);
    } else {
        echo json_encode(['rate_per_hour' => 0]);
    }

    $stmt->close();
    $conn->close();
}

