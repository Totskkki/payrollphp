<?php
include 'conn.php'; 
header('Content-Type: application/json');

// Query to fetch only active and non-archived employees
$query = "SELECT `employee_no`, `first_name`, `last_name` 
          FROM `employee`
          JOIN employee_details ON employee_details.employee_id = employee.employee_id
          WHERE employee_details.status = 'Active' AND employee.is_archived = 0"; 

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $employeeNumbers = []; // Array for employee_no values
    $employees = []; // Optional, if you want to keep full employee details without face_path

    while ($row = $result->fetch_assoc()) {
        $employeeNumbers[] = $row['employee_no']; // Collect employee_no into an array

        // Optional: collect additional employee details if needed
        $employees[] = [
            "employee_no" => $row['employee_no'],
            "name" => $row['first_name'] . ' ' . $row['last_name']
        ];
    }

    // Output only the employee_no array if that's all you need
    echo json_encode(['status' => 'success', 'employee_numbers' => $employeeNumbers]);

    // Uncomment the line below to include optional details
    // echo json_encode(['status' => 'success', 'employee_numbers' => $employeeNumbers, 'employees' => $employees]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No active employees found.']);
}
?>
