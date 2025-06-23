<?php 
	include 'includes/session.php';

	if(isset($_POST['overtime'])){
		$id = $_POST['overtime'];
		$sql = "SELECT *,n.*, overtime.overtimeid  AS otid FROM overtime 
		LEFT JOIN users on users.userid=overtime.employee_id 
        LEFT JOIN names n ON n.namesid = users.names_id 
        WHERE overtime.overtimeid='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}


	if (isset($_POST['emp'])) {
		$id = $_POST['emp'];
	
		$sql = "SELECT 
					u.*, u.employee_id as empid, addr.*, ed.*,		
					CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS full_name,
					CONCAT(addr.street, ', ', addr.city, ', ', addr.province) AS full_address
				FROM employee u
				LEFT JOIN employee_details ed ON ed.employee_id = u.employee_id 	
				LEFT JOIN address addr ON addr.addressid = u.employee_id 
				WHERE u.employee_id = ?";
	
		$stmt = $conn->prepare($sql);
		if ($stmt) {
			$stmt->bind_param("s", $id);
			$stmt->execute();
			$result = $stmt->get_result();
	
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				echo json_encode($row);
			} else {
				echo json_encode(['error' => 'No data found for the provided employee ID.']);
			}
		} else {
			echo json_encode(['error' => 'Failed to prepare the query.']);
		}
	}
	


    if(isset($_POST['attid'])){
		$id = $_POST['attid'];
		$sql = "SELECT attendance.*, employee.*,employee_details.*,attendance.status,schedules.*,
		CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name
		FROM `attendance`
		LEFT JOIN employee ON attendance.employee_no = employee.employee_no
		LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
		LEFT JOIN schedules on schedules.scheduleid = employee_details.scheduleid

		 WHERE attendance.attendanceid = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	

	if(isset($_POST['leave'])){
		$id = $_POST['leave'];
		$sql = "SELECT *,n.*, leave.leaveid as leave FROM leave 
		LEFT JOIN users ON users.userid=leave.employee_id
		LEFT JOIN names n ON users.names_id = n.namesid
		 WHERE leave.leaveid = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}



	if(isset($_POST['deducid'])){
		$id = $_POST['deducid'];
		$sql = "SELECT * FROM deductions WHERE dedID  = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}


	if(isset($_POST['depid'])){
		$id = $_POST['depid'];
		$sql = "SELECT * FROM department WHERE depid  = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	
	if(isset($_POST['posid'])){
		$id = $_POST['posid'];
		$sql = "SELECT * FROM position
		LEFT JOIN department on department.depid = position.departmentid
		 WHERE positionid  = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	if(isset($_POST['sched'])){
		$id = $_POST['sched'];
		$sql = "SELECT * FROM schedules WHERE scheduleid = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
	if(isset($_POST['allowid'])){
		$id = $_POST['allowid'];
		$sql = "SELECT * FROM allowance WHERE allowid = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	if(isset($_POST['cashid'])){
		$id = $_POST['cashid'];
		$sql = "SELECT *, cashadvance.cashid AS caid FROM cashadvance 
		LEFT JOIN employee on employee.employee_id=cashadvance.employee_id 
		WHERE cashadvance.cashid='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	
	if(isset($_POST['payid'])){
		$id = $_POST['payid'];
		$sql = "SELECT * FROM pay_periods 
		WHERE payid ='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

		
	if(isset($_POST['manid'])){
		$id = $_POST['manid'];
		$sql = "SELECT * FROM mandatory_benefits 
		WHERE mandateid  ='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	
	// if(isset($_POST['bonusid'])){
	// 	$id = $_POST['bonusid'];
	// 	$sql = "SELECT * FROM bonus_incentives 
	// 	WHERE bonusid   ='$id'";
	// 	$query = $conn->query($sql);
	// 	$row = $query->fetch_assoc();

	// 	echo json_encode($row);
	// }
	if (isset($_POST['bonusid'])) {
		$bonusid = $_POST['bonusid'];
		$stmt = $conn->prepare("SELECT * FROM bonus_incentives WHERE bonusid = ?");
		$stmt->bind_param("i", $bonusid);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
	
		if ($row) {
			echo json_encode([
				'bonusid' => $row['bonusid'],
				'bonus_amount' => $row['bonus_amount'],
				'bonus_type' => $row['bonus_type'],
				'bonus_period' => $row['bonus_period'],
				'bonus_description' => $row['bonus_description'],
				'employee_id' => $row['employee_id']
			]);
		} else {
			echo json_encode(['error' => 'Record not found']);
		}
		exit();
	}

	
	if(isset($_POST['unitid'])){
		$id = $_POST['unitid'];
		$sql = "SELECT *,
		CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name
		 FROM daily_units 
		LEFT JOIN employee on employee.employee_id = daily_units.employee_id
		WHERE unitid   ='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}

	if(isset($_POST['payroll'])){
		$id = $_POST['payroll'];
		$sql = "SELECT *,pay_periods.*,payroll.status,
		CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name
		 FROM payroll	 
		LEFT JOIN employee on employee.employee_id = payroll.employee_id
		LEFT JOIN pay_periods on pay_periods.payid   = payroll.pay_period_id 
		WHERE payrollid    ='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}






