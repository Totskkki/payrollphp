<?php
include 'conn.php';  
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$attendanceData = json_decode(file_get_contents("php://input"), true);
$current_date = date('Y-m-d');

if (isset($attendanceData['employee_no']) && isset($attendanceData['status'])) {
    $employee_no = $attendanceData['employee_no'];
    $status = $attendanceData['status'];

    // Check if the employee is clocking in
    if ($status == "present") {
        // Check if the employee already has a time_in record today
        $checkStmt = $conn->prepare("SELECT * FROM attendance WHERE employee_no = ? AND date = ? AND time_out IS NULL");
        $checkStmt->bind_param("ss", $employee_no, $current_date);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // If there's already a clock-in record today
            echo json_encode([
                "status" => "error",
                "message" => "Employee has already clocked in today."
            ]);
        } else {
            // Insert a new attendance record for clock-in with the correct time
            $stmt = $conn->prepare("INSERT INTO attendance (employee_no, date, time_in, status) VALUES (?, ?, ?, ?)");
            $current_time = date('Y-m-d H:i:s'); // Get the current time in Asia/Manila timezone
            $stmt->bind_param("ssss", $employee_no, $current_date, $current_time, $status);
            $stmt->execute();
            $stmt->close();

            echo json_encode([
                "status" => "success",
                "message" => "Employee clocked in successfully."
            ]);
        }

        // Close the check statement
        $checkStmt->close();
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required data."
    ]);
}
?>
