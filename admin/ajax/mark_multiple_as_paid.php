<?php
include '../includes/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $payrollids = $data['payrollids'];

    if (!empty($payrollids)) {
        // Sanitize input
        $payrollids = array_map('intval', $payrollids);
        $payrollids_str = implode(',', $payrollids);

       
        $sql = "UPDATE payroll SET status = 'paid' WHERE payrollid IN ($payrollids_str)";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update payroll status']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No payrolls selected']);
    }
}
?>
