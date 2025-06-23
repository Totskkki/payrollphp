<?php include 'includes/conn.php'; 

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    $employeeId = $input['employee_id'] ?? null;
    $allowanceId = $input['allowid'] ?? null;
    $allowanceAmount = $input['allowance_amount'] ?? null;

    if ($employeeId && $allowanceId && $allowanceAmount !== null) {
        try {
            // Create database connection
            $db = new Database();
            $conn = $db->getConnection();

            // Update the allowance record for the employee
            $sql = "UPDATE allowances_employee 
                    SET allowance_amount = :allowance_amount, updated_at = NOW() 
                    WHERE employee_id = :employee_id AND allowid = :allowid";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':allowance_amount', $allowanceAmount, PDO::PARAM_STR);
            $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
            $stmt->bindParam(':allowid', $allowanceId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Respond with success
                echo json_encode(['success' => true]);
            } else {
                // Respond with failure
                echo json_encode(['success' => false]);
            }
        } catch (Exception $e) {
            // Handle errors
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}