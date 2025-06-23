<?php
include 'conn.php';  
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$attendanceData = json_decode(file_get_contents("php://input"), true);
$current_date = date('Y-m-d');

if (isset($attendanceData['employee_no']) && isset($attendanceData['status'])) {
    $employee_no = $attendanceData['employee_no'];
    $status = $attendanceData['status'];
    $time_out = null;  

    // Check if the employee is clocking in or clocking out
    if ($status == "present") {
        // Check if the employee has already clocked in for today
        $stmt = $conn->prepare("SELECT time_in FROM attendance WHERE employee_no = ? AND date = ? AND time_out IS NULL LIMIT 1");
        $stmt->bind_param("ss", $employee_no, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Employee has already clocked in, so update attendance (clock-out)
            $stmt = $conn->prepare("UPDATE attendance SET time_out = NOW() WHERE employee_no = ? AND date = ? AND time_out IS NULL");
            $stmt->bind_param("ss", $employee_no, $current_date);
            $stmt->execute();
            $stmt->close();

            echo json_encode([
                "status" => "success",
                "message" => "Employee clocked out successfully."
            ]);
        } else {
            // Employee has not clocked in yet, insert time_in
            $stmt = $conn->prepare("INSERT INTO attendance (employee_no, date, time_in, status) VALUES (?, ?, NOW(), ?)");
            $stmt->bind_param("sss", $employee_no, $current_date, $status);
            $stmt->execute();
            $stmt->close();

            echo json_encode([
                "status" => "success",
                "message" => "Employee clocked in successfully."
            ]);
        }
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required data."
    ]);
}
?>
