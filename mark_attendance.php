
<?php
include 'conn.php';
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$userid = $data['userid'] ?? null;

if (!$userid) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

// Check if attendance is already marked for today
$date = date('Y-m-d');
$query = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $userid, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Attendance already marked for today']);
    exit;
}

// Mark attendance
$query = "INSERT INTO attendance (employee_id, date, time) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$time = date('H:i:s');
$stmt->bind_param('sss', $userid, $date, $time);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Attendance marked successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error marking attendance']);
}

$conn->close();

