<?php

include '../includes/conn.php';
session_start();

$query = isset($_GET['query']) ? $_GET['query'] : '';


if (!empty($query)) {
    
    $sql = "SELECT u.userid, n.firstname, n.lastname
            FROM users u
            JOIN names n ON u.names_id = n.namesid
            WHERE n.firstname LIKE ? OR n.lastname LIKE ?";
    
    
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['userid']}' data-name='{$row['firstname']} {$row['lastname']}'>{$row['firstname']} {$row['lastname']}</option>";
        }
    } else {
        echo "<option value=''>No employees found</option>";
    }

    
    $stmt->close();
    $conn->close();
}

