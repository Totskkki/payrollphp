<?php
include 'conn.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['employee_no'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Employee number is required.'
        ]);
        exit;
    }

    $employee_no = $data['employee_no'];
    $current_date = date('Y-m-d'); // Current date in YYYY-MM-DD format

    // Query to check the most recent clock-in date and time for the employee
    $query = "SELECT * FROM attendance WHERE employee_no = ? ORDER BY date DESC, time_in DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $employee_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Get the most recent attendance record
        $attendance = $result->fetch_assoc();
        $last_clock_in_date = $attendance['date']; // Use the `date` field from your table
   
        $last_time_in = date('c', strtotime($attendance['time_in'])); // Convert time_in to ISO 8601 format

        // Check if the employee has clocked in today
        if ($last_clock_in_date === $current_date) {
            // Employee has clocked in today
            echo json_encode([
                'status' => 'already_clocked_in',
                'last_time_in' => $last_time_in,
                'last_clock_in_date' => $last_clock_in_date
                
            ]);
        } else {
            // Employee has not clocked in today, clock them in
            echo json_encode([
                'status' => 'not_clocked_in',
                'last_time_in' => null,
                'last_clock_in_date' => null
            ]);
        }
    } else {
        // Employee has not clocked in at all
        echo json_encode([
            'status' => 'not_clocked_in',
            'last_time_in' => null,
            'last_clock_in_date' => null
        ]);
    }
} catch (Exception $e) {
    // Handle any errors
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while processing the request.',
        'error' => $e->getMessage()
    ]);
}

