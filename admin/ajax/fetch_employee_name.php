<?php
include '../includes/conn.php';
session_start();
if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    $query = "SELECT employee_id, first_name, middle_name, last_name FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $full_name = trim($employee['first_name'] . ' ' . $employee['middle_name'] . ' ' . $employee['last_name']);

        echo json_encode([
            'full_name' => $full_name,
            'employee_id' => $employee['employee_id']
        ]);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'No employee ID provided']);
}
?>
