<?php
include_once 'conn.php';
header('Content-Type: application/json');


$data = json_decode(file_get_contents('php://input'), true);
$descriptors = $data['descriptors'];

// Load employee data from the database
$result = $conn->query("SELECT name, face_path FROM employees");
$employee_faces = [];

while ($row = $result->fetch_assoc()) {
    // Load each face image and compute the face descriptor
    $image_path = $row['face_path'];
    $name = $row['name'];
    
    // Here you would load the image and generate its descriptor
    // Note: You need to implement a solution to retrieve descriptors
    // E.g., you might save the descriptors in the database after a one-time processing
}

// This assumes you have a function to compare loaded face descriptors
$recognized_name = 'Unknown';
foreach ($employee_faces as $name => $face_descriptor) {
    
    if (compare_faces($descriptors, $face_descriptor)) { // Implement this function
        $recognized_name = $name;
        break;
    }
}

if ($recognized_name !== 'Unknown') {
    // Mark attendance in the database
    $stmt = $conn->prepare("INSERT INTO attendance (name, timestamp) VALUES (?, ?)");
    $stmt->bind_param("ss", $recognized_name, date("Y-m-d H:i:s"));
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(['status' => 'success', 'name' => $recognized_name]);
} else {
    echo json_encode(['status' => 'error']);
}

$conn->close();

// Placeholder function for face comparison
function compare_faces($recognition_descriptors, $db_descriptor) {
    // Implement your own face recognition logic, possibly using cosine similarity or another method
    return false; // Replace with actual logic
}
?>