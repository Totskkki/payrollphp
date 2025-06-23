<?php 
include '../includes/conn.php';


if (isset($_GET['position_id'])) {
    $position_id = $_GET['position_id'];

    // SQL query to fetch rate for the selected position
    $sql = "SELECT rate_per_hour, pakyawan_rate FROM position WHERE positionid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $position_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Fetch rate based on availability
        $rate = '';
        if (!empty($row['rate_per_hour'])) {
            $rate = $row['rate_per_hour']; // Prefer rate_per_hour
        } elseif (!empty($row['pakyawan_rate'])) {
            $rate = $row['pakyawan_rate'];
        } else {
            $rate = "Rate not available"; // Neither rate is available
        }

        echo $rate;
    } else {
        echo "Rate not found";
    }
} else {
    echo "Invalid request";
}