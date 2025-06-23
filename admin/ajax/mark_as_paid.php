<?php
include '../includes/conn.php';

session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['payrollid'])) {
    $payrollid = intval($data['payrollid']);

    // Update status to 'paid'
    $sql = "UPDATE payroll SET status = 'paid' WHERE payrollid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payrollid);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
$conn->close();
?>
