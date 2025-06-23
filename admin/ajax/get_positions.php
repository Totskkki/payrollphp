<?php
include '../includes/conn.php';
session_start();
if (isset($_GET['department_id'])) {
    $department_id = $_GET['department_id'];
    
    // SQL query to get positions based on selected department
    $sql = "SELECT * FROM position WHERE departmentid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return positions as options for the select dropdown
    if ($result->num_rows > 0) {
        echo "<option value='' selected>- Select -</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['positionid'] . "'>" . $row['position'] . "</option>";
        }
    } else {
        echo "<option value=''>No positions available</option>";
    }
}
?>
