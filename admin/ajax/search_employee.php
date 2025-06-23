<?php
include '../includes/conn.php';
session_start();
// Retrieve search term
$term = $_GET['term'];

// Fetch matching employees from the database
$sql = "SELECT employee.employee_id, CONCAT(employee.first_name, ' ',employee.middle_name, ' ', employee.last_name) AS full_name 
        FROM employee 
        LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
        WHERE CONCAT(employee.first_name, ' ', employee.last_name) LIKE '%$term%' 
        AND employee_details.status = 'Active' 
        LIMIT 10";
$result = $conn->query($sql);

$employees = [];

if ($result->num_rows > 0) {
    // Fetch matching employees and store in array
    while ($row = $result->fetch_assoc()) {
        $employees[] = [
            'label' => $row['full_name'], 
            'value' => $row['employee_id']
        ];
    }
}

// Return the employee list as a JSON response
echo json_encode($employees);

