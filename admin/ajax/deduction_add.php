<?php
include '../includes/conn.php';
session_start();
// if (isset($_POST['add'])) {
//     $firstname = $_POST['firstname'];
//     $middlename = $_POST['middlename'];
//     $lastname = $_POST['lastname'];
//     $name_extension = $_POST['name_extension'];
//     $birthdate = $_POST['birthdate'];
//     $contact = $_POST['contact'];
//     $gender = $_POST['gender'];
//     $position = $_POST['position'];
//     $schedule = $_POST['schedule'];

//     $brgy = $_POST['brgy'];
//     $purok = $_POST['purok'];
//     $city = $_POST['city'];
//     $province = $_POST['province'];

//     $filename = $_FILES['photo']['name'];

//     $generatedCode = $_POST['generatedCode'];

//     // Check if the filename is not empty
//     if (!empty($filename)) {
//         move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);
//     }

//     // // Generate employee ID
//     // $letters = '';
//     // $numbers = '';
//     // foreach (range('A', 'Z') as $char) {
//     //     $letters .= $char;
//     // }
//     // for ($i = 0; $i < 10; $i++) {
//     //     $numbers .= $i;
//     // }
//     // $employee_id = substr(str_shuffle($letters), 0, 3) . substr(str_shuffle($numbers), 0, 9);

   
//     $sql_address = "INSERT INTO address (brgy, purok, city, province) VALUES ('$brgy', '$purok', '$city', '$province')";
//     if ($conn->query($sql_address)) {
       
//         $address_id = $conn->insert_id;

	
//         $sql_employee_name = "INSERT INTO names (firstname, middlename, lastname, name_extension, birthdate, gender) VALUES ('$firstname', '$middlename', '$lastname', '$name_extension', '$birthdate', '$gender')";
//         if ($conn->query($sql_employee_name)) {
           
//             $employee_name_id = $conn->insert_id;

           
//             $sql_employee = "INSERT INTO users (QR_code, names_id, address_id, contact_info, position_id, schedule_id, photo, created_on) VALUES ('$generatedCode', '$employee_name_id', '$address_id', '$contact', '$position', '$schedule', '$filename', NOW())";
//             if ($conn->query($sql_employee)) {
//                 $_SESSION['success'] = 'Employee added successfully';
//             } else {
//                 $_SESSION['error'] = $conn->error;
//             }
//         } else {
//             $_SESSION['error'] = $conn->error;
//         }
//     } else {
//         $_SESSION['error'] = $conn->error;
//     }
// } else {
//     $_SESSION['error'] = 'Fill up add form first';
// }

// header('location: employee.php');

header('Content-Type: application/json');
$response = array('success' => false);

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    
//     $employee_id = $_POST["id"]; 
//     $description = $_POST["deduction_type"];
//     $type = $_POST["type"];
//     $amount = $_POST["deduction_amount"];
//     $edate = $_POST["date"]; 

//     // Escape user inputs to prevent SQL injection
//     $employee_id = $conn->real_escape_string($employee_id);
//     $description = $conn->real_escape_string($description);
//     $type = $conn->real_escape_string($type);
//     $amount = $conn->real_escape_string($amount);
//     $edate = $conn->real_escape_string($edate);

//     // Perform the database operation
//     $sql = "INSERT INTO deductions (description, amount, type, date, employee_id) VALUES ('$description', '$amount', '$type', '$edate', '$employee_id')";

//     if ($conn->query($sql)) {
//         echo json_encode(['success' => true]);
//     } else {
//         echo json_encode(['success' => false, 'error' => $conn->error]);
//     }
//     exit();
// } else {
//     echo json_encode(['success' => false, 'error' => 'Invalid request method']);
//     exit();
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $response = [];
//     $employee_id = $_POST["id"];
//     $descriptionArray = $_POST["deduction_type"];
//     $typeArray = $_POST["type"];
//     $amountArray = $_POST["deduction_amount"];
//     $dateArray = isset($_POST["date"]) ? $_POST["date"] : []; // Check if "date" key exists

//     if (
//         is_array($descriptionArray) &&
//         is_array($typeArray) &&
//         is_array($amountArray)
//     ) {
//         $success = true;
//         $conn->begin_transaction();
//         try {
//             for ($i = 0; $i < count($descriptionArray); $i++) {
//                 $description = $conn->real_escape_string($descriptionArray[$i]);
//                 $type = $conn->real_escape_string($typeArray[$i]);
//                 $amount = $conn->real_escape_string($amountArray[$i]);
//                 $date = isset($dateArray[$i]) && !empty($dateArray[$i]) ? "'" . $conn->real_escape_string($dateArray[$i]) . "'" : "NULL";

//                 $sql = "INSERT INTO deductions (description, amount, type, date, employee_id) VALUES ('$description', '$amount', '$type', $date, '$employee_id')";
//                 if (!$conn->query($sql)) {
//                     throw new Exception($conn->error);
//                 }
//             }
//             $conn->commit();
//             $response['success'] = true;
//         } catch (Exception $e) {
//             $conn->rollback();
//             $response['success'] = false;
//             $response['error'] = $e->getMessage();
//         }
//     } else {
//         $response['success'] = false;
//         $response['error'] = 'One or more POST parameters are not arrays.';
//     }

//     echo json_encode($response);
//     exit();
// } else {
//     echo json_encode(['success' => false, 'error' => 'Invalid request method']);
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = [];
    $employee_id = $_POST["id"];
    $descriptionArray = $_POST["deduction_type"];
    $typeArray = $_POST["type"];
    $amountArray = $_POST["deduction_amount"];
    $dateArray = isset($_POST["effective_date"]) ? $_POST["effective_date"] : []; // Check if "effective_date" key exists

    if (
        is_array($descriptionArray) &&
        is_array($typeArray) &&
        is_array($amountArray)
    ) {
        $success = true;
        $conn->begin_transaction();
        try {
            for ($i = 0; $i < count($descriptionArray); $i++) {
                $description = $conn->real_escape_string($descriptionArray[$i]);
                $type = $conn->real_escape_string($typeArray[$i]);
                $amount = $conn->real_escape_string($amountArray[$i]);
                $date = isset($dateArray[$i]) && !empty($dateArray[$i]) ? "'" . $conn->real_escape_string($dateArray[$i]) . "'" : "NULL";

                $sql = "INSERT INTO deductions_employees (description, amount, type, date, employee_id) VALUES ('$description', '$amount', '$type', $date, '$employee_id')";
                if (!$conn->query($sql)) {
                    throw new Exception($conn->error);
                }
                }
            
            $conn->commit();
            $response['success'] = true;
            $_SESSION['success'] = 'Deductions added successfully';

        } catch (Exception $e) {
            $conn->rollback();
            $response['success'] = false;
            $response['error'] = $e->getMessage();
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'One or more POST parameters are not arrays.';
    }

    echo json_encode($response);
    exit();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}



?>
