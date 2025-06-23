<?php
include '../includes/conn.php';
session_start();
$employee_id = $_POST['id'];
$deductions = [];


if ($stmt = $conn->prepare("SELECT deductionid, description, amount, type FROM deductions_employees WHERE employee_id = ?")) {
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $deductions[] = $row;
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

foreach ($deductions as $deduction) {

  
    $type = '';
    switch ($deduction['type']) {
        case 1:
            $type = 'Monthly';
            break;
        case 2:
            $type = 'Weekly';
            break;
        case 3:
            $type = 'Once';
            break;
        default:
            $type = 'Unknown';
            break;
    }
    echo '<li class="list-group-item d-flex justify-content-between align-items-center dlist" data-id="' . $deduction['deductionid'] . '">
    <span>
    
        <p><small>' . ucwords($deduction['description']) . '</small></p>
        <p><small>Type: ' . $type . '</small></p>
        <p><small>Amount: ' . $deduction['amount'] . '</small></p>
    </span>
    <button class="badge bg-warning badge-pill btn remove_deduction" type="button" data-id="' . $deduction['deductionid'] . '" data-toggle="modal" data-target="#deleteDeductionModal"><i class="fa fa-trash"></i></button>
        <br/>
</li>';
}
?>
