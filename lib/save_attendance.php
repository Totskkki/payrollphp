<?php
include 'conn.php';  
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

date_default_timezone_set('Asia/Manila');


error_reporting(E_ALL);
ini_set('display_errors', 1);  // Show errors on the page
ini_set('log_errors', 1);      // Log errors
ini_set('error_log', 'php_errors.log');  // Log to a file


$attendanceData = json_decode(file_get_contents("php://input"), true);
error_log(print_r($attendanceData, true)); 


// Check if the data is valid
if (is_array($attendanceData) && count($attendanceData) > 0) {
$current_date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO attendance (employee_no,date,time_in, status) VALUES (?,?, NOW(), ?)");
    

    foreach ($attendanceData as $attendance) {
        // Prepare and bind parameters
        $employee_no = $attendance['employee_no'];
        $current_date = $attendance['date'];
        $time_in = $attendance['time_in'];
        $status = $attendance['status'];

        $stmt->bind_param("ssss", $employee_no, $current_date ,$time_in, $status);
        
        // Execute the query to insert the data
        if (!$stmt->execute()) {
            echo json_encode([
                "status" => "error", 
                "message" => "Error inserting attendance for employee ID: " . $employee_no
            ]);
            exit;
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Return success message
    echo json_encode([
        "status" => "success",
        "message" => "Attendance recorded successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid data."
    ]);
}


