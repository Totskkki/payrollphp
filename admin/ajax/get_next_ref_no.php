<?php
include '../includes/conn.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$response = ['ref_no' => "PP-{$year}-01"];

$query = "SELECT ref_no FROM pay_periods WHERE YEAR(created_at) = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    preg_match('/PP-\d{4}-(\d+)/', $row['ref_no'], $matches);
    $nextNumber = isset($matches[1]) ? str_pad((int)$matches[1] + 1, 2, '0', STR_PAD_LEFT) : '01';
    $response['ref_no'] = "PP-{$year}-{$nextNumber}";
}

echo json_encode($response);
?>
