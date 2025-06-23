<?php
include 'includes/session.php';

if (isset($_POST['delete'])) {
	$id = $_POST['id'];
	$addID = $_POST['addID'];

	// Delete related records from foreign tables
	$sqlNames = "DELETE FROM employee WHERE employee_id  = ?";
	$stmtNames = $conn->prepare($sqlNames);
	$stmtNames->bind_param("i", $id);
	$stmtNames->execute();
	$stmtNames->close();

	$sql = "DELETE FROM employee_details WHERE employee_id  = ?";
	$stmtNames = $conn->prepare($sql);
	$stmtNames->bind_param("i", $id);
	$stmtNames->execute();
	$stmtNames->close();

	$sqlAddress = "DELETE FROM address WHERE addressid = ?";
	$stmtAddress = $conn->prepare($sqlAddress);
	$stmtAddress->bind_param("i", $addID);
	$stmtAddress->execute();
	$stmtAddress->close();





	$_SESSION['success'] = 'Employee deleted successfully';
} else {
	$_SESSION['error'] = 'Select item to delete first';
}

header('location: employee.php');
