
<?php
include '../includes/conn.php';
session_start();
if (isset($_POST['save-units'])) {
    if (isset($_POST['employee_ids']) && isset($_POST['unit_type']) && isset($_POST['total_units'])) {
        $employeeIds = explode(',', $_POST['employee_ids']);
        $unitType = $_POST['unit_type'];
        $totalUnits = $_POST['total_units'];
        $to_date = $_POST['to_date'];

        // Insert each employee's units into the database
        foreach ($employeeIds as $employeeId) {
            $sql = "INSERT INTO daily_units (employee_id, unit_type, units_completed,date_completed) VALUES (?, ?, ?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('issi', $employeeId, $unitType, $totalUnits,$to_date);
            if (!$stmt->execute()) {
                // Handle errors if any
                echo "Error saving data: " . $stmt->error;
                exit;
            }
        }

        echo "Units successfully saved!";
    } else {
        echo "Missing required fields!";
    }
}
?>
