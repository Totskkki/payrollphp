<?php
include '../includes/conn.php';
session_start();
$timezone = 'Asia/Manila';
date_default_timezone_set($timezone);

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php_errors.log");

// Sanitize inputs
$department = $conn->real_escape_string($_POST['department']);
$year = $conn->real_escape_string($_POST['year']);
$month = $conn->real_escape_string($_POST['month']);

// Prepare SQL query
$sql = "SELECT employee.employee_id, employee.employee_no, employee.first_name, employee.last_name, attendance.*,
               attendance.date, YEAR(attendance.date) AS year, MONTH(attendance.date) AS month, 
               MONTHNAME(attendance.date) AS month_name, d.*, p.*
        FROM employee
        LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
        LEFT JOIN attendance ON employee.employee_no = attendance.employee_no
        LEFT JOIN position p ON employee_details.positionid = p.positionid
        LEFT JOIN department d ON p.departmentid = d.depid
        WHERE d.department LIKE '$department' 
        AND employee_details.status = 'Active' and employee.is_archived = 0
        AND YEAR(attendance.date) = $year
        AND MONTH(attendance.date) = $month";



$query = $conn->query($sql);

if (!$query) {
    error_log("SQL Error: " . $conn->error); // Log SQL errors
    die("Query failed: " . $conn->error);
}

$employees = array();

while ($row = $query->fetch_assoc()) {

    // Fetch attendance record for the employee
    $attendance_sql = "SELECT * FROM attendance WHERE employee_no = '{$row['employee_no']}' AND date = '{$row['date']}'";
    $attendance_query = $conn->query($attendance_sql);
    
    if (!$attendance_query) {
        error_log("Attendance Query Error: " . $conn->error); // Log errors if attendance query fails
    }

    $attendance_record = $attendance_query->fetch_assoc();

    $row['attendance_record'] = $attendance_record;

    if ($attendance_record) {
        $attendance_record['time_in'] = date('h:i A', strtotime($attendance_record['time_in']));
        $attendance_record['time_out'] = ($attendance_record['time_out'] && $attendance_record['time_out'] != '00:00:00') ? date('h:i A', strtotime($attendance_record['time_out'])) : 'N/A';
    }

    $employees[] = $row;
}



echo json_encode($employees);

