<?php
include '../includes/conn.php';
session_start();

$timezone = 'Asia/Manila';
date_default_timezone_set($timezone);



// Validate and sanitize inputs
$department = isset($_POST['department']) ? $conn->real_escape_string($_POST['department']) : ''; 
$date = isset($_POST['date']) ? $conn->real_escape_string($_POST['date']) : '';

if (!$date) {
    echo json_encode(['error' => 'Date is required']);
    exit;
}

// Fetch all employees or based on the selected department
$whereClause = !empty($department) ? "AND department.department = '$department'" : '';

$sql = "SELECT employee.*, s.scheduled_start as schedule_time_in, s.scheduled_end as schedule_time_out, p.*, department.department as department_name
        FROM employee 
        LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
        LEFT JOIN position p ON employee_details.positionid = p.positionid
        LEFT JOIN department ON department.depid = p.departmentid
        LEFT JOIN schedules s ON employee_details.scheduleid = s.scheduleid
        WHERE 1=1 $whereClause AND employee_details.status = 'Active' and employee.is_archived = 0";  // `WHERE 1=1` ensures all employees are selected if no department is chosen

$query = $conn->query($sql);

if (!$query) {
    error_log("SQL Error: " . $conn->error);
    echo json_encode(['error' => 'Failed to fetch employees']);
    exit;
}

$employees = array();

while ($row = $query->fetch_assoc()) {
    // Check if the employee has an attendance record for the selected date
    $attendance_sql = "SELECT * FROM attendance 
                       WHERE employee_no = '{$row['employee_no']}' 
                       AND date = '$date'";
    $attendance_query = $conn->query($attendance_sql);
    
    if (!$attendance_query) {
        error_log("SQL Error (Attendance): " . $conn->error);
        continue;
    }
    
    $attendance_record = $attendance_query->fetch_assoc();
    
    // Prepare the response data for this employee
    $row['attendance_record'] = $attendance_record;
    
    if ($attendance_record) {
        $scheduledTimeIn = new DateTime($row['schedule_time_in']);
        $scheduledTimeOut = new DateTime($row['schedule_time_out']);
        $actualTimeIn = new DateTime($attendance_record['time_in']);
        $actualTimeOut = new DateTime($attendance_record['time_out']);
        $isLate = $actualTimeIn > $scheduledTimeIn;
        $isUndertime = $actualTimeOut < $scheduledTimeOut;
        $row['attendance_record']['isLate'] = $isLate;
        $row['attendance_record']['isUndertime'] = $isUndertime;
    } else {
        $row['attendance_record'] = null;  // If no attendance record, set it to null
    }
  
    $employees[] = $row;
}

echo json_encode($employees);
