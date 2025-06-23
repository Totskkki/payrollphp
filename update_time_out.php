<?php
include 'conn.php';
header('Content-Type: application/json');

// Get the raw POST data and decode it
$input = json_decode(file_get_contents("php://input"), true);

// Check if input is valid
if ($input === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data received.']);
    exit; // Exit if data is invalid
}

// Extract employee_no from the input
$employee_no = isset($input['employee_no']) ? $input['employee_no'] : null;

// Check if the required data is present
if ($employee_no === null) {
    echo json_encode(['status' => 'error', 'message' => 'Missing employee_no in the request.']);
    exit; // Exit if any required data is missing
}

// Query to get the latest time_in for today's attendance
$query = "SELECT date, time_in FROM attendance WHERE employee_no = ? AND DATE(time_in) = CURDATE() ORDER BY date DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $employee_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $attendance = $result->fetch_assoc();
    $time_in = $attendance['time_in']; // Get the latest time_in for today

    // Get current time (time_out) using NOW()
    $time_out = date('Y-m-d H:i:s'); // Current time in 'YYYY-MM-DD HH:MM:SS' format

    // Convert time_in and time_out to timestamps for calculation
    $time_in_timestamp = strtotime($time_in);
    $time_out_timestamp = strtotime($time_out);

    // Calculate the difference in minutes
    $total_minutes = ($time_out_timestamp - $time_in_timestamp) / 60;

    // If the total time worked is more than 4 hours, subtract 1 hour for break time
    if ($total_minutes > 240) {
        $total_minutes -= 60; // Deduct 1 hour break if total minutes exceed 4 hours
    }

    // Convert minutes back to hours
    $total_hours = round($total_minutes / 60, 2);

    // Update the time_out and total_hours in the database
    $update_query = "UPDATE attendance 
                     SET time_out = ?, num_hr = ? 
                     WHERE employee_no = ? AND DATE(time_in) = CURDATE() AND time_in = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssss", $time_out, $total_hours, $employee_no, $time_in);

    if ($update_stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update time out or total hours.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No attendance record found for this employee today.']);
}
?>
