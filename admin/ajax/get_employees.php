<?php
include '../includes/conn.php';
session_start();
$sql = "SELECT e.employee_id, CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS full_name
        FROM employee e
        JOIN employee_details de on de.employee_id = e.employee_id
        JOIN department dep on dep.depid = de.departmentid
        WHERE dep.department = 'PAKYAWAN'
        ORDER BY e.first_name, e.last_name";
$query = $conn->query($sql);

$employees = [];
while ($row = $query->fetch_assoc()) {
    $employees[] = $row;
}

echo json_encode($employees); // Return as JSON
?>
