<?php
include 'includes/session.php';

require '../vendor/autoload.php'; // Include the Composer autoloader
use PhpOffice\PhpSpreadsheet\IOFactory;


if (isset($_POST['attendance'])) {
    $file = $_FILES['attendance_file']['tmp_name']; // Get the uploaded file
  
    if (is_uploaded_file($file)) {
      $spreadsheet = IOFactory::load($file); // Load the Excel file
      $sheet = $spreadsheet->getActiveSheet(); // Get the active sheet
      $rows = $sheet->toArray(); // Convert sheet data to an array
  
      $success_count = 0; // To count successful imports
      $error_rows = [];  // To track rows with errors
  
      foreach ($rows as $index => $row) {
        // Skip the header row (e.g., the first row)
        if ($index === 0)
          continue;
  
        // Map Excel columns to table fields
        $employee_no = $row[0]; // Employee number
        $raw_date = $row[1]; // Date in "20/01/2025" format
        $time_in = $row[2]; // Time In
        $time_out = $row[3]; // Time Out
        $status = $row[4] ?? 'Present'; // Status
  
        // Parse the date format (convert to YYYY-MM-DD)
        $date = \DateTime::createFromFormat('d/m/Y', $raw_date);
        if ($date) {
          $date = $date->format('Y-m-d'); // Convert to MySQL date format
        } else {
          $error_rows[] = $index + 1; // Record row with invalid date
          continue; // Skip invalid rows
        }
  
        // Check for duplicate entries
        $check_sql = "SELECT * FROM attendance WHERE employee_no = ? AND date = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $employee_no, $date);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
  
        if ($result->num_rows > 0) {
          $error_rows[] = $index + 1; // Record duplicate row
          continue; // Skip duplicate rows
        }
  
        // Insert into the `attendance` table
        $insert_sql = "INSERT INTO attendance (employee_no, date, time_in, time_out, status) 
                       VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssss", $employee_no, $date, $time_in, $time_out, $status);
        if ($insert_stmt->execute()) {
          $success_count++; // Increment success count
        } else {
          $error_rows[] = $index + 1; // Record row with insertion error
        }
      }
  
      // Set success or error messages
      if ($success_count > 0) {
        $_SESSION['success'] = "$success_count rows were successfully imported.";
      }
  
      if (!empty($error_rows)) {
        $_SESSION['error'] = "Errors occurred on rows: " . implode(', ', $error_rows);
      }
  
      // Redirect to the attendance page
      header('location: attendance.php');
    } else {
      $_SESSION['error'] = 'Please upload a valid Excel file.';
      header('location: attendance.php');
    }
  }

  



if (isset($_POST['saveEmployee'])) {
    // Sanitize and assign form data
    // var_dump($_POST);

  
    $first_name = ucfirst(strtolower(trim($_POST['first_name'])));
    $middle_name = isset($_POST['middle_name']) ? ucfirst(strtolower(trim($_POST['middle_name']))) : null;
    $last_name = ucfirst(strtolower(trim($_POST['last_name'])));
    $name_extension = ucfirst(strtolower(trim($_POST['name_extension'])));

    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'] ?? null;
    $street_address = $_POST['street_address'] ?? null;
    $city = $_POST['city'] ?? null;
    $province = $_POST['province'] ?? null;
    $postal_code = $_POST['postal_code'] ?? null;
    $country = $_POST['country'] ?? null;
    $schdule = $_POST['schdule'] ?? null;

    $face_images = json_decode($_POST['face_images'], true);



    // Upload photo
    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../images/";
        $photo = $target_dir . basename($_FILES['photo']['name']);
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            echo "Error uploading photo.";
            exit;
        }
    }

    // Account Login
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);


    // Company Details
    $department = $_POST['department'];
    $position = $_POST['position'];
    $hire_date = $_POST['datehire'];
    $employment_type = $_POST['employment_type'];
    $status = 'Active';

    // Financial Details
    // $basic_salary = $_POST['basic_salary'];


    $sql_last_id = "SELECT employee_no FROM employee ORDER BY employee_no DESC LIMIT 1";
    $result = $conn->query($sql_last_id);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $last_employee_no = $row['employee_no'];

        preg_match('/(\d+)$/', $last_employee_no, $matches);
        $numeric_part = (int) $matches[0];


        $new_numeric_part = $numeric_part + 1;
    } else {

        $new_numeric_part = 101;
    }


    $employee_no = 'JV-' . $new_numeric_part;




    $face_paths = [];

    if (!empty($_POST['face_images'])) {
        $face_images = json_decode($_POST['face_images'], true);
        $image_dir = "../assets/images/faces/{$employee_no}/";
        if (!file_exists($image_dir)) {
            mkdir($image_dir, 0777, true);
        }

        foreach ($face_images as $index => $image_data) {
            $decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
            $file_name = $employee_no . "_face_" . ($index + 1) . ".png";
            $file_path = $image_dir . $file_name;

            if (!file_put_contents($file_path, $decoded_image)) {
                throw new Exception('Failed to save face image.');
            }

            $face_paths[] = $file_name;
        }
    }

    // Convert the array to a JSON string
    $face_paths_json = json_encode($face_paths);


    $conn->begin_transaction();


    // Insert employee data
    $sql = "INSERT INTO employee
        (employee_no,first_name, middle_name, last_name, name_extension, birthdate, gender, contact_number, email,`password`, photo, userid,face_path)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssssis', $employee_no, $first_name, $middle_name, $last_name, $name_extension, $birthdate, $gender, $contact_number, $email, $password, $photo, $user, $face_paths_json);
    $stmt->execute();
    $employee_id = $conn->insert_id;


    $allowance_ids = $_POST['allowance'] ?? [];
    $allowance_amounts = $_POST['allowance_amount'] ?? [];

    if (!empty($allowance_ids) && !empty($allowance_amounts)) {
        for ($i = 0; $i < count($allowance_ids); $i++) {
            $allowance_id = $allowance_ids[$i];
            $amount = $allowance_amounts[$i];

            if (!empty($allowance_id) && !empty($amount)) {
                $stmt = $conn->prepare("INSERT INTO allowances_employee (employee_id, allowid, allowance_amount) VALUES (?, ?, ?)");
                $stmt->bind_param("isd", $employee_id, $allowance_id, $amount);

                if (!$stmt->execute()) {
                    echo "Error inserting allowance: " . $stmt->error;
                }
            }
        }
    } else {
        echo "No allowances posted.";
    }


    $deduction_ids = $_POST['deduction'] ?? [];
    $deduction_amounts = $_POST['deduction_amount'] ?? [];

    if (!empty($deduction_ids) && !empty($deduction_amounts)) {
        for ($i = 0; $i < count($deduction_ids); $i++) {
            $deduction_id = $deduction_ids[$i];
            $damount = $deduction_amounts[$i];

            if (!empty($deduction_id) && !empty($damount)) {
                $stmt = $conn->prepare("INSERT INTO deductions_employees (`deducid`, `deduc_amount`, `employee_id`) VALUES (?, ?, ?)");
                $stmt->bind_param("sdi", $deduction_id, $damount, $employee_id);

                if (!$stmt->execute()) {
                    echo "Error inserting deduction: " . $stmt->error;
                }
            }
        }
    } else {
        echo "No deduction posted.";
    }




    // Insert employee details
    $sql_details = "INSERT INTO employee_details 
                (employee_id, positionid, departmentid,scheduleid, hire_date, employment_type, `status`)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_details);
    $stmt->bind_param('issssss', $employee_id, $position, $department, $schdule, $hire_date, $employment_type, $status);
    $stmt->execute();


    // Insert address
    $sql_address = "INSERT INTO address 
        (street, city, province, postal_code,country, empid) 
        VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql_address);
    $stmt->bind_param('sssssi', $street_address, $city, $province, $postal_code, $country, $employee_id);
    $stmt->execute();




    // Commit transaction
    $conn->commit();


    $_SESSION['success'] = 'Employee added successfully';
    header('location: employee.php');
}


if (isset($_POST['updateEmployee'])) {
   

    $employee_id = $_POST['empid'];
    $employee_no = $_POST['empno'];
    $addressid = $_POST['addressid'];
    $employment_id = $_POST['employment_id'];


    $user = $_SESSION['admin'];

    $first_name = ucfirst(strtolower(trim($_POST['first_name'])));
    $middle_name = isset($_POST['middle_name']) ? ucfirst(strtolower(trim($_POST['middle_name']))) : null;
    $last_name = ucfirst(strtolower(trim($_POST['last_name'])));
    $name_extension = ucfirst(strtolower(trim($_POST['name_extension'])));

    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'] ?? null;
    $street_address = $_POST['street_address'] ?? null;
    $city = $_POST['city'] ?? null;
    $province = $_POST['province'] ?? null;
    $postal_code = $_POST['postal_code'] ?? null;
    $country = $_POST['country'] ?? null;
    $schdule = $_POST['schedule'] ?? null;

    // Account Login
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Company Details
    $department = $_POST['department'];
    $position = $_POST['position'];
    $hire_date = $_POST['datehire'];
    $employment_type = $_POST['employment_type'];

   


   

    $face_paths = [];
    $image_dir = "../assets/images/faces/{$employee_no}/";

    // ✅ Step 1: Delete Existing Photos
    if (file_exists($image_dir)) {
        $files = glob($image_dir . '*'); // Get all files in the directory
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Delete each file
            }
        }
    } else {
        mkdir($image_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // ✅ Step 2: Save New Photos
    if (!empty($_POST['face_images'])) {
        $face_images = json_decode($_POST['face_images'], true);

        foreach ($face_images as $index => $image_data) {
            $decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
            $file_name = $employee_no . "_face_" . ($index + 1) . ".png";
            $file_path = $image_dir . $file_name;

            if (!file_put_contents($file_path, $decoded_image)) {
                throw new Exception('Failed to save face image.');
            }

            $face_paths[] = $file_name;
        }
    }

    // Convert the array to a JSON string
    $face_paths_json = json_encode($face_paths);

    // Start transaction
    $conn->begin_transaction();

    // Update employee data
    $sql = "UPDATE employee
        SET first_name = ?, middle_name = ?, last_name = ?, name_extension = ?, birthdate = ?, gender = ?, contact_number = ?, email = ?, `password` = ?, userid = ?, face_path = ?
        WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssssi', $first_name, $middle_name, $last_name, $name_extension, $birthdate, $gender, $contact_number, $email, $password, $user, $face_paths_json, $employee_id);
    $stmt->execute();


        
    $allempid = $_POST['allowance'];
    $deductionid = $_POST['deductions'];

    // Update or Insert Allowances
    $allowance_ids = $_POST['allowance'] ?? [];
    $allowance_amounts = $_POST['allowance_amount'] ?? [];

    if (!empty($allowance_ids) && !empty($allowance_amounts)) {
        for ($i = 0; $i < count($allowance_ids); $i++) {
            $allowance_id = $allowance_ids[$i];
            $amount = $allowance_amounts[$i];

            if (!empty($allowance_id) && !empty($amount)) {
                // Check if allowance already exists for this employee
                $checkAllowance = $conn->prepare("SELECT * FROM allowances_employee WHERE employee_id = ? AND allowid = ?");
                $checkAllowance->bind_param("ii", $employee_id, $allowance_id);
                $checkAllowance->execute();
                $result = $checkAllowance->get_result();

                if ($result->num_rows > 0) {
                    // Update the existing allowance
                    $stmt = $conn->prepare("UPDATE allowances_employee SET allowance_amount = ? WHERE employee_id = ? AND allempid  = ?");
                    $stmt->bind_param("dii", $amount, $employee_id, $allempid);
                } else {
                    // Insert new allowance
                    $stmt = $conn->prepare("INSERT INTO allowances_employee (employee_id, allowid, allowance_amount) VALUES (?, ?, ?)");
                    $stmt->bind_param("isd", $employee_id, $allowance_id, $amount);
                }

                if (!$stmt->execute()) {
                    echo "Error inserting or updating allowance: " . $stmt->error;
                }
            }
        }
    }

    // Update or Insert Deductions
    $deduction_ids = $_POST['deduction'] ?? [];
    $deduction_amounts = $_POST['deduction_amount'] ?? [];

    if (!empty($deduction_ids) && !empty($deduction_amounts)) {
        for ($i = 0; $i < count($deduction_ids); $i++) {
            $deduction_id = $deduction_ids[$i];
            $damount = $deduction_amounts[$i];

            if (!empty($deduction_id) && !empty($damount)) {
                // Check if deduction already exists for this employee
                $checkDeduction = $conn->prepare("SELECT * FROM deductions_employees WHERE employee_id = ? AND deducid = ?");
                $checkDeduction->bind_param("ii", $employee_id, $deduction_id);
                $checkDeduction->execute();
                $result = $checkDeduction->get_result();

                if ($result->num_rows > 0) {
                    // Update the existing deduction
                    $stmt = $conn->prepare("UPDATE deductions_employees SET deduc_amount = ? WHERE employee_id = ? AND deductionid  = ?");
                    $stmt->bind_param("dii", $damount, $employee_id, $deductionid);
                } else {
                    // Insert new deduction
                    $stmt = $conn->prepare("INSERT INTO deductions_employees (deducid, deduc_amount, employee_id) VALUES (?, ?, ?)");
                    $stmt->bind_param("sdi", $deduction_id, $damount, $employee_id);
                }

                if (!$stmt->execute()) {
                    echo "Error inserting or updating deduction: " . $stmt->error;
                }
            }
        }
    }

    // Update employee details
    $sql_details = "UPDATE employee_details SET positionid = ?, departmentid = ?, scheduleid = ?, hire_date = ?, employment_type = ? WHERE employment_id  = ?";
    $stmt = $conn->prepare($sql_details);
    $stmt->bind_param('ssssss', $position, $department, $schdule, $hire_date, $employment_type,  $employment_id);
    $stmt->execute();

    // Update address
    $sql_address = "UPDATE address SET street = ?, city = ?, province = ?, postal_code = ?, country = ? WHERE empid = ?";
    $stmt = $conn->prepare($sql_address);
    $stmt->bind_param('sssssi', $street_address, $city, $province, $postal_code, $country, $employee_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    $_SESSION['success'] = 'Employee updated successfully';
    header('location: employee.php');
}



if (isset($_POST['archive'])) {
    $id = $_POST['id'];


    $sql = "UPDATE `employee` SET `is_archived`='1'
     WHERE employee_id  = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Employee archive successfully';

        header('location:employee.php');
    } else {
        $_SESSION['error'] = $conn->error;

        header('location:employee.php');
    }
}

// if (isset($_POST['add'])) {
//     // Get form data and sanitize
//     $firstname = trim($_POST['firstname']);
//     $middlename = trim($_POST['middlename']);
//     $lastname = trim($_POST['lastname']);
//     $name_extension = trim($_POST['name_extension']);
//     $birthdate = $_POST['birthdate'];
//     $contact = trim($_POST['contact']);
//     $gender = trim($_POST['gender']);
//     $position = (int) $_POST['position'];
//     $schedule = (int) $_POST['schedule'];
//     $brgy = trim($_POST['brgy']);
//     $purok = trim($_POST['purok']);
//     $city = trim($_POST['city']);
//     $province = trim($_POST['province']);
//     $filename = $_FILES['photo']['name'];
//     $generatedCode = trim($_POST['generatedCode']);
//     $face_images = json_decode($_POST['face_images'], true);

//     // Define the directory for storing face images
//     $image_dir = 'uploads/faces/';

//     // Check for duplicate names
//     $stmt = $conn->prepare("SELECT * FROM names WHERE firstname = ? AND lastname = ? AND middlename = ?");
//     $stmt->bind_param("sss", $firstname, $lastname, $middlename);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $_SESSION['error'] = 'Employee already exists';
//         header('location: employee.php');
//         exit;
//     }

//     $conn->begin_transaction();
//     try {
//         // Insert address
//         $sql_address = "INSERT INTO address (brgy, purok, city, province) VALUES (?, ?, ?, ?)";
//         $stmt = $conn->prepare($sql_address);
//         $stmt->bind_param("ssss", $brgy, $purok, $city, $province);
//         $stmt->execute();
//         $address_id = $stmt->insert_id;

//         // Insert employee name
//         $sql_employee_name = "INSERT INTO names (firstname, middlename, lastname, name_extension, birthdate, gender) VALUES (?, ?, ?, ?, ?, ?)";
//         $stmt = $conn->prepare($sql_employee_name);
//         $stmt->bind_param("ssssss", $firstname, $middlename, $lastname, $name_extension, $birthdate, $gender);
//         $stmt->execute();
//         $employee_name_id = $stmt->insert_id;

//         // Handle face images and save file paths
//         $face_paths = [];
//         foreach ($face_images as $index => $image_data) {
//             $decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
//             $file_name = $image_dir . $employee_name_id . "_face_" . $index . ".png";
//             if (!file_put_contents($file_name, $decoded_image)) {
//                 throw new Exception('Failed to save face image.');
//             }
//             $face_paths[] = $file_name; // Save the file path
//         }

//         // Convert the array of file paths to a string (e.g., JSON format)
//         $face_paths_string = implode(',', $face_paths);

//         // Set status
//         $status = 'active'; // Ensure this is non-null

//         // Debugging output
//         var_dump($status);

//         // Insert employee details
//         $sql_employee = "INSERT INTO users (QR_code, names_id, address_id, contact_info, position_id, schedule_id, photo, status, face_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
//         $stmt = $conn->prepare($sql_employee);
//         $stmt->bind_param("siiisssss", $generatedCode, $employee_name_id, $address_id, $contact, $position, $schedule, $filename, $status, $face_paths_string);
//         $stmt->execute();

//         $conn->commit();
//         $_SESSION['success'] = 'Employee added successfully';
//     } catch (Exception $e) {
//         $conn->rollback();
//         $_SESSION['error'] = $e->getMessage();
//     }

//     header('location: employee.php');
//     exit;
// }


// if (isset($_POST['saveEmployee'])) {
//     // Start transaction
//     $conn->begin_transaction();

//     try {
//         // Sanitize and validate inputs
//         $user = $_SESSION['admin'];

//         // Validate required fields
//         $required_fields = ['first_name', 'last_name', 'birthdate', 'gender', 'email', 'password', 'department', 'position', 'datehire', 'employment_type', 'basic_salary'];
//         foreach ($required_fields as $field) {
//             if (empty($_POST[$field])) {
//                 throw new Exception("Field $field is required");
//             }
//         }

//         // Sanitize inputs
//         $first_name = htmlspecialchars(trim($_POST['first_name']));
//         $middle_name = !empty($_POST['middle_name']) ? htmlspecialchars(trim($_POST['middle_name'])) : null;
//         $last_name = htmlspecialchars(trim($_POST['last_name']));
//         $name_extension = !empty($_POST['name_extension']) ? htmlspecialchars(trim($_POST['name_extension'])) : null;
//         $birthdate = $_POST['birthdate'];
//         $gender = $_POST['gender'];
//         $contact_number = !empty($_POST['contact_number']) ? htmlspecialchars(trim($_POST['contact_number'])) : null;

//         // Address details
//         $street_address = !empty($_POST['street_address']) ? htmlspecialchars(trim($_POST['street_address'])) : null;
//         $city = !empty($_POST['city']) ? htmlspecialchars(trim($_POST['city'])) : null;
//         $province = !empty($_POST['province']) ? htmlspecialchars(trim($_POST['province'])) : null;
//         $postal_code = !empty($_POST['postal_code']) ? htmlspecialchars(trim($_POST['postal_code'])) : null;

//         // Photo upload
//         $photo = null;
//         if (!empty($_FILES['photo']['name'])) {
//             $target_dir = "../images/";
//             $photo_filename = uniqid() . '_' . basename($_FILES['photo']['name']);
//             $photo = $target_dir . $photo_filename;

//             if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
//                 throw new Exception("Error uploading photo");
//             }
//         }

//         // Face images processing
//         $face_paths = [];
//         if (!empty($_POST['face_images'])) {
//             $face_images = json_decode($_POST['face_images'], true);
//             $image_dir = 'uploads/faces/';

//             foreach ($face_images as $index => $image_data) {
//                 $decoded_image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_data));
//                 $file_name = $image_dir . uniqid() . "_face_" . $index . ".png";

//                 if (!file_put_contents($file_name, $decoded_image)) {
//                     throw new Exception('Failed to save face image.');
//                 }
//                 $face_paths[] = $file_name;
//             }
//         }
//         $face_paths_string = implode(',', $face_paths);

//         // Account details
//         $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
//         if (!$email) {
//             throw new Exception("Invalid email format");
//         }
//         $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

//         // Employment details
//         $department = $_POST['department'];
//         $position = $_POST['position'];
//         $hire_date = $_POST['datehire'];
//         $employment_type = $_POST['employment_type'];
//         $basic_salary = floatval($_POST['basic_salary']);
//         $status = 'Active';

//         // Insert Employee
//         $sql_employee = "INSERT INTO employee 
//             (first_name, middle_name, last_name, name_extension, birthdate, gender, 
//             contact_number, email, photo, userid, face_path)
//             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//         $stmt_employee = $conn->prepare($sql_employee);
//         $stmt_employee->bind_param(
//             'sssssssssis', 
//             $first_name, $middle_name, $last_name, $name_extension, 
//             $birthdate, $gender, $contact_number, $email, $photo, 
//             $user, $face_paths_string
//         );
//         $stmt_employee->execute();
//         $employee_id = $conn->insert_id;

//         // Insert Employee Details
//         $sql_details = "INSERT INTO employee_details 
//             (employee_id, position, department, hire_date, employment_type, `status`, basic_salary)
//             VALUES (?, ?, ?, ?, ?, ?, ?)";
//         $stmt_details = $conn->prepare($sql_details);
//         $stmt_details->bind_param(
//             'isssssd', 
//             $employee_id, $position, $department, $hire_date, 
//             $employment_type, $status, $basic_salary
//         );
//         $stmt_details->execute();

//         // Insert Address
//         $sql_address = "INSERT INTO address 
//             (street, city, province, postal_code, empid) 
//             VALUES (?, ?, ?, ?, ?)";
//         $stmt_address = $conn->prepare($sql_address);
//         $stmt_address->bind_param(
//             'ssssi', 
//             $street_address, $city, $province, $postal_code, $employee_id
//         );
//         $stmt_address->execute();

//         // Process Allowances
//         if (!empty($_POST['Allowance']) && !empty($_POST['allowance_amount'])) {
//             $allowances = $_POST['Allowance'];
//             $allowance_amounts = $_POST['allowance_amount'];

//             $sql_allowances = "INSERT INTO allowances_employee 
//                 (employee_id, allowid, allowance_amount) 
//                 VALUES (?, ?, ?)";
//             $stmt_allowances = $conn->prepare($sql_allowances);

//             foreach ($allowances as $index => $allowance_id) {
//                 if (empty($allowance_id)) continue;

//                 $allowance_amount = $allowance_amounts[$index] ?? 0;
//                 $stmt_allowances->bind_param('iid', $employee_id, $allowance_id, $allowance_amount);
//                 $stmt_allowances->execute();
//             }
//         }

//         // Process Deductions
//         if (!empty($_POST['deductions']) && !empty($_POST['deductions_amount'])) {
//             $deductions = $_POST['deductions'];
//             $deduction_amounts = $_POST['deductions_amount'];

//             $sql_deductions = "INSERT INTO deductions_employees 
//                 (deducid, deduc_amount, employee_id) 
//                 VALUES (?, ?, ?)";
//             $stmt_deductions = $conn->prepare($sql_deductions);

//             foreach ($deductions as $index => $deduction_id) {
//                 if (empty($deduction_id)) continue;

//                 $deduction_amount = $deduction_amounts[$index] ?? 0;
//                 $stmt_deductions->bind_param('isi', $deduction_id, $deduction_amount, $employee_id);
//                 $stmt_deductions->execute();
//             }
//         }

//         // Commit transaction
//         $conn->commit();

//         // Set success message
//         $_SESSION['success'] = 'Employee added successfully';
//         header('location: employee.php');
//         exit();

//     } catch (Exception $e) {
//         // Rollback transaction
//         $conn->rollback();

//         // Log the error
//         error_log("Employee Registration Error: " . $e->getMessage());

//         // Set error message
//         $_SESSION['error'] = $e->getMessage();
//         header('location: employee.php');
//         exit();
//     }
// }

// if (isset($_POST['edit_employee'])) {
//     $empid = $_POST['emp_edit'];
//     $names_id = $_POST['names_id'];
//     $address_id = $_POST['address_id'];


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

//     $sql_employee = "UPDATE users SET contact_info = '$contact', position_id = '$position', schedule_id = '$schedule' WHERE userid = '$empid'";
//     if ($conn->query($sql_employee)) {
//         $sql = "UPDATE names SET firstname = '$firstname',middlename = '$middlename', lastname = '$lastname', name_extension= '$name_extension', birthdate = '$birthdate', gender = '$gender' WHERE namesid  = '$names_id'";
//         if ($conn->query($sql)) {

//             $sql_address = "UPDATE address  set purok='$purok', brgy='$brgy', city='$city', province= '$province' WHERE addressid = '$address_id'";
//             if ($conn->query($sql_address)) {
//                 $_SESSION['success'] = 'Employee updated successfully';
//                 header('location: employee.php');
//             } else {
//                 $_SESSION['error'] = $conn->error;
//             }
//         } else {
//             $_SESSION['error'] = 'Select employee to edit first';
//             header('location: employee.php');
//         }
//     }
// }
if (isset($_POST['upload'])) {
    $empid = $_POST['id'];
    $filename = $_FILES['photo']['name'];

    if (!empty($filename)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/' . $filename);

        // Escape the filename and user ID to handle special characters
        $filename = $conn->real_escape_string($filename);
        $empid = $conn->real_escape_string($empid);

        // Prepare the SQL statement
        $sql = "UPDATE employee SET photo = '$filename' WHERE employee_id = '$empid'";

        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Employee photo updated successfully';
            header('location: employee.php');
        } else {
            $_SESSION['error'] = 'Error: ' . $conn->error;
            header('location: employee.php');
        }
    } else {
        $_SESSION['error'] = 'No file selected.';
        header('location: employee.php');
    }
}





if (isset($_POST['add_department'])) {
    $dep = $_POST['dep'];
    $title = $_POST['Description'];



    $check_sql = "SELECT * FROM department WHERE department = '$dep'";
    $check_query = $conn->query($check_sql);

    if ($check_query->num_rows > 0) {

        $_SESSION['error'] = 'Department already exists';
        header('location: department.php');
    } else {

        $sql = "INSERT INTO department (department, description) VALUES ('$dep', '$title')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Department added successfully';
            header('location: department.php');
        } else {
            $_SESSION['error'] = $conn->error;
            header('location: department.php');
        }
    }
}

if (isset($_POST['edit_dep'])) {
    $id = $_POST['id'];
    $dep = $_POST['dep'];
    $title = $_POST['title'];


    $sql = "UPDATE department SET department ='$dep', description = '$title' WHERE depid = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'department updated successfully';
        header('location:department.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location:department.php');
    }
}


if (isset($_POST['delete_department'])) {
    $id = $_POST['position_id'];

    $sql = "DELETE FROM department WHERE depid  = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Department deleted successfully';
        header('location: department.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: department.php');
    }
}




// =====================position================================================


if (isset($_POST['add_position'])) {
    $dep = $_POST['departid'];
    $title = $_POST['title'];
    $rate = $_POST['rate_per_hour'];
    $pakyawan_rate = $_POST['pakyawan_rate'];

    $sql = "INSERT INTO position (`departmentid`, `position`, `rate_per_hour`,`pakyawan_rate`) VALUES ('$dep','$title', '$rate','$pakyawan_rate')";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Position added successfully';
        header('location: position.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: position.php');
    }
}



if (isset($_POST['edit_position'])) {
    $id = $_POST['id'];
    $dep = $_POST['edit_dep'];
    $title = $_POST['title'];
    $rate = $_POST['rate'];
    $pakyawan_rate = $_POST['pakyawan_rate'];

    $sql = "UPDATE position SET departmentid ='$dep',position  = '$title', rate_per_hour = '$rate',pakyawan_rate = '$pakyawan_rate' WHERE positionid = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Position updated successfully';
        header('location:position.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location:position.php');
    }
}
if (isset($_POST['delete_position'])) {
    $id = $_POST['position_id'];

    $sql = "DELETE FROM position WHERE positionid = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Position deleted successfully';
        header('location: position.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: position.php');
    }
}




// else{
//     // $_SESSION['error'] = 'Fill up edit form first';
// }

// =====================position================================================

if (isset($_POST['edit_attendance'])) {
    $id = $_POST['id'];
    $date = $_POST['edit_date'];
    $time_in = $_POST['edit_time_in'];
    $time_in = date('H:i:s', strtotime($time_in));  // Convert time_in to the correct format
    $time_out = $_POST['edit_time_out'];
    $time_out = date('H:i:s', strtotime($time_out));  // Convert time_out to the correct format

    // Using a prepared statement to update the attendance record
    $sql = "UPDATE attendance SET date = ?, time_in = ?, time_out = ? WHERE attendanceid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $date, $time_in, $time_out, $id);  // Bind the parameters to prevent SQL injection

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Attendance updated successfully';

        // Retrieve the employee number and other details from the updated attendance record
        $sql = "SELECT * FROM attendance WHERE attendanceid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);  // Bind the attendance ID
        $stmt->execute();
        $query = $stmt->get_result();
        $row = $query->fetch_assoc();
        $emp = $row['employee_no'];

        // Fetch the employee schedule details
        $sql = "SELECT * FROM employee  
                LEFT JOIN attendance on attendance.employee_no = employee.employee_no
                LEFT JOIN employee_details on employee.employee_id = employee_details.employee_id
                LEFT JOIN schedules ON schedules.scheduleid = employee_details.scheduleid 
                WHERE employee.employee_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $emp);
        $stmt->execute();
        $query = $stmt->get_result();
        $srow = $query->fetch_assoc();


        $logstatus = (strtotime($time_in) > strtotime($srow['scheduled_start'])) ? 0 : 1;


        if (strtotime($time_in) < strtotime($srow['scheduled_start'])) {
            $time_in = $srow['scheduled_start'];
        }

        if (strtotime($time_out) > strtotime($srow['scheduled_end'])) {
            $time_out = $srow['scheduled_end'];
        }

        // Calculate the difference in hours and minutes
        $time_in = new DateTime($time_in);
        $time_out = new DateTime($time_out);
        $interval = $time_in->diff($time_out);
        $hrs = $interval->format('%h');
        $mins = $interval->format('%i');
        $mins = $mins / 60;
        $int = $hrs + $mins;


        if ($int > 4) {
            $int = $int - 1;
        }

        // Update the attendance record with the calculated hours worked
        $sql = "UPDATE attendance SET num_hr = ?, status = 'present' WHERE attendanceid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $int, $id);  // Bind the number of hours and attendance ID
        $stmt->execute();

        $_SESSION['success'] = 'Attendance updated successfully';
        header('location:attendance.php');
    } else {
        $_SESSION['error'] = $stmt->error;
        header('location:attendance.php');
    }
}



if (isset($_POST['add_overtime'])) {
    $employee = $_POST['employee_id'];
    $date = $_POST['date'];
    $hours = $_POST['hours'];
    $mins = $_POST['mins'];
    $rate = $_POST['rate'];
    $maxhours = $_POST['maxhours'];

    $total_hours = floatval($maxhours) + (floatval($mins) / 60);

    // Ensure overtime hours do not exceed the maximum allowed
    if ($total_hours > floatval($hours)) {
        $total_hours = floatval($hours);
    }

    $total_compensation = $total_hours * floatval($rate);

    // ✅ Check if the employee exists
    $sql = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employee);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        $_SESSION['error'] = 'Employee not found';
        header('location: overtimerequest.php');
        exit();
    }

    $row = $result->fetch_assoc();
    $employee_id = $row['employee_id'];

    // ✅ Check if overtime already exists for this date
    $sql = "SELECT * FROM overtime WHERE employee_id = ? AND date_overtime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $employee_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Overtime for this date already exists.';
        header('location: overtimerequest.php');
        exit();
    }

    // ✅ Insert overtime data
    $sql = "INSERT INTO overtime (employee_id, date_overtime, hours, rate, total_compensation, status) 
            VALUES (?, ?, ?, ?, ?, '2')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isddd', $employee_id, $date, $total_hours, $rate, $total_compensation);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Overtime added successfully';
    } else {
        $_SESSION['error'] = $stmt->error;
    }

    header('location: overtimerequest.php');
    exit();
}

if (isset($_POST['deduction_id'])) {
    $deduction_id = $_POST['deduction_id'];
    $sql = "DELETE FROM deductions_employees WHERE deductionid  = '$deduction_id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Deduction deleted successfully';
        header('location: employee.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: employee.php');
    }
}

if (isset($_POST['add_leave'])) {
    $employee = $_POST['employee'];
    $leave_dates = $_POST['leave_dates'];
    $leave_type = $_POST['leave_type'];
    $reason = $_POST['leave_message'];


    $sql_fetch_leave_dates = "SELECT date_leave FROM `leave` WHERE employee_id = '$employee'";
    $result_fetch_leave_dates = $conn->query($sql_fetch_leave_dates);
    $existing_dates = [];
    if ($result_fetch_leave_dates->num_rows > 0) {
        while ($row = $result_fetch_leave_dates->fetch_assoc()) {
            $dates_in_row = explode(',', $row['date_leave']);
            $existing_dates = array_merge($existing_dates, $dates_in_row);
        }
    }


    $new_dates = explode(',', $leave_dates);
    $conflicting_dates = array_intersect($existing_dates, $new_dates);


    if (!empty($conflicting_dates)) {
        $conflicting_dates_str = implode(', ', $conflicting_dates);

        $_SESSION['error'] = 'You have already applied for leave on one or more of the selected dates:';
        header('location: leaves.php');
    } else {

        $sql_insert_leave = "INSERT INTO `leave` (employee_id, date_leave, type, reason, status) 
                             VALUES ('$employee', '$leave_dates', '$leave_type', '$reason', '0')";
        if ($conn->query($sql_insert_leave)) {
            $_SESSION['success'] = 'Leave added successfully';
            header('location: leaves.php');
        } else {
            $_SESSION['error'] = $conn->error;
            header('location: leaves.php');
        }
    }
}






if (isset($_POST['addschedules'])) {
    $time_in = $_POST['time_in'];
    $time_in = date('H:i:s', strtotime($time_in));
    $time_out = $_POST['time_out'];
    $time_out = date('H:i:s', strtotime($time_out));


    $sql_check = "SELECT COUNT(*) AS schedule_count FROM schedules WHERE scheduled_start = '$time_in' AND scheduled_end = '$time_out'";
    $result_check = $conn->query($sql_check);

    if ($result_check) {
        $row = $result_check->fetch_assoc();
        if ($row['schedule_count'] > 0) {

            $_SESSION['error'] = 'This schedule already exists.';
            header('location: schedule.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Error checking for existing schedule: ' . $conn->error;
        header('location: schedule.php');
        exit();
    }


    $sql_insert = "INSERT INTO schedules (scheduled_start, scheduled_end) VALUES ('$time_in', '$time_out')";
    if ($conn->query($sql_insert)) {
        $_SESSION['success'] = 'Schedule added successfully';
        header('location: schedule.php');
    } else {
        $_SESSION['error'] = 'Error inserting schedule: ' . $conn->error;
        header('location: schedule.php');
    }
}

if (isset($_POST['editschedules'])) {
    $id = $_POST['id'];
    $time_in = $_POST['time_in'];
    $time_in = date('H:i:s', strtotime($time_in));
    $time_out = $_POST['time_out'];
    $time_out = date('H:i:s', strtotime($time_out));

    $sql = "UPDATE schedules SET scheduled_start = '$time_in', scheduled_end = '$time_out' WHERE scheduleid  = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Schedule updated successfully';

        header('location:schedule.php');
    } else {
        $_SESSION['error'] = $conn->error;

        header('location:schedule.php');
    }
}


if (isset($_POST['deleteschedules'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM schedules WHERE scheduleid = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Schedule deleted successfully';

        header('location: schedule.php');
    } else {
        $_SESSION['error'] = $conn->error;

        header('location: schedule.php');
    }
}


//====================Start Allowance==================================

if (isset($_POST['add_allowance'])) {
    $allow = $_POST['allow'];
    $amount = $_POST['amount'];
    $frequency = $_POST['frequency'];
    $description = $_POST['description'];




    $check_sql = "SELECT * FROM allowance WHERE allowance = '$allow'";
    $check_query = $conn->query($check_sql);

    if ($check_query->num_rows > 0) {

        $_SESSION['error'] = 'Allowance already exists';
        header('location: allowance.php');
    } else {

        $sql = "INSERT INTO `allowance`( `allowance`, `allowance_type`, `amount`,`description`)
          VALUES ('$allow','$frequency','$amount','$description')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Allowance  added successfully';
            header('location: allowance.php');
        } else {
            $_SESSION['error'] = $conn->error;
            header('location: allowance.php');
        }
    }
}
if (isset($_POST['edit_allowance'])) {
    $allid = $_POST['allid'];
    $allow = $_POST['allow'];
    $amount = $_POST['amount'];
    $frequency = $_POST['frequency'];
    $description = $_POST['description'];



    $sql = "UPDATE `allowance` SET `allowance`=' $allow',`allowance_type`='$frequency',`amount`='$amount',`description`='$description'
     WHERE allowid = '$allid'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Allowance updated successfully';

        header('location:allowance.php');
    } else {
        $_SESSION['error'] = $conn->error;

        header('location:allowance.php');
    }
}


if (isset($_POST['delete_allow'])) {

    $id = $_POST['allowid'];
    $sql = "DELETE FROM allowance WHERE allowid  = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Deduction deleted successfully';
        header('location: allowance.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: allowance.php');
    }
}



//====================End Allowance==================================



if (isset($_POST['add_deduction'])) {
    $deduc = $_POST['Deduction'];
    $frequency = $_POST['frequency'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];



    $check_sql = "SELECT deduction FROM deductions WHERE deduction  = '$deduc'";
    $check_query = $conn->query($check_sql);

    if ($check_query->num_rows > 0) {

        $_SESSION['error'] = 'Deduction already exists';
        header('location: deduction.php');
    } else {

        $sql = "INSERT INTO `deductions`(`deduction`, `deduction_type`, `amount`, `description`)
          VALUES ('$deduc','$frequency','$amount','$description')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'deduction  added successfully';
            header('location: deduction.php');
        } else {
            $_SESSION['error'] = $conn->error;
            header('location: deduction.php');
        }
    }
}
if (isset($_POST['edit_deduction'])) {
    $allid = $_POST['id'];
    $allow = $_POST['Deduction'];
    $frequency = $_POST['frequency'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];



    $sql = "UPDATE `deductions` SET `deduction`=' $allow',`deduction_type`='$frequency',`amount`='$amount',`description`='$description'
     WHERE dedID  = '$allid'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Deduction updated successfully';

        header('location:deduction.php');
    } else {
        $_SESSION['error'] = $conn->error;

        header('location:deduction.php');
    }
}

if (isset($_POST['delete_deduction'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM deductions WHERE dedID  = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Deduction deleted successfully';
        header('location: deduction.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: deduction.php');
    }
}





//=======================================================================

// if (isset($_POST['monthpay'])) {
//     $employee_ids = $_POST['employee_id'];
//     $basic_salaries = $_POST['basic_salary'];
//     $months_worked_arr = $_POST['months_worked'];
//     $thirteenth_month_arr = $_POST['thirteenth_month'];
//     $year = date('Y');

//     try {
//         $conn->begin_transaction();

//         $sql = "INSERT INTO 13th_month (employee_id, thirteenth_month_pay, year) VALUES (?, ?, ?)
//                 ON DUPLICATE KEY UPDATE thirteenth_month_pay = VALUES(thirteenth_month_pay)";
//         $stmt = $conn->prepare($sql);

//         foreach ($employee_ids as $index => $employee_id) {
//             $basic_salary = $basic_salaries[$index];
//             $months_worked = $months_worked_arr[$index];
//             $thirteenth_month = $thirteenth_month_arr[$index];

//             $stmt->bind_param('sds', $employee_id, $thirteenth_month, $year);
//             $stmt->execute();
//         }

//         $conn->commit();
//         $_SESSION['success'] = '13th Month Pay records saved successfully.';
//         header('Location: 13th_month.php');
//     } catch (Exception $e) {
//         $conn->rollback();
//         $_SESSION['error'] = 'Error saving records: ' . $e->getMessage();
//         header('Location: 13th_month.php');
//     }



// }
if (isset($_POST['monthpay'])) {
    $year = date('Y'); // Current year
    $today = date('Y-m-d');

    // Clear old records for the current year
    $conn->query("DELETE FROM `13th_month` WHERE `year` = '$year'");

    // Query to calculate 13th-month pay
    $sql = "SELECT u.employee_id, 
                   position.rate_per_hour AS rate_per_day,
                   TIMESTAMPDIFF(MONTH, employee_details.hire_date, ?) AS months_worked
            FROM employee u
            LEFT JOIN employee_details 
                ON employee_details.employee_id = u.employee_id
            LEFT JOIN department 
                ON department.depid = employee_details.departmentid
            LEFT JOIN position 
                ON position.positionid = employee_details.positionid
            WHERE YEAR(employee_details.hire_date) <= ? 
              AND employee_details.status = 'Active' 
              AND u.is_archived = 0
              AND department != 'pakyawan'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $today, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    // Process each employee's 13th-month pay
    while ($row = $result->fetch_assoc()) {
        $months_worked = min($row['months_worked'], 12); // Cap months at 12
        $rate_per_day = $row['rate_per_day'];

        // Calculate Total Salary Earned Based on Fixed 30 Days per Month
        $total_salary_earned = $rate_per_day * 30 * $months_worked;

        // Calculate 13th Month Pay
        $thirteenth_month = $total_salary_earned / 12;

        // Insert into the 13th_month table
        $insert_sql = "INSERT INTO `13th_month` 
                      (`employee_id`, `year`, `thirteenth_month_pay`, `created_at`) 
                      VALUES (?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param('iis', $row['employee_id'], $year, $thirteenth_month);
        $insert_stmt->execute();
    }

    // Check if the last insertion succeeded
    if ($insert_stmt->affected_rows > 0) {
        $_SESSION['success'] = '13th Month Pay successfully processed';
    } else {
        $_SESSION['error'] = $conn->error;
    }

    header('location: 13th_month.php');
    exit;
}


//=======================================================================

if (isset($_POST['addcash'])) {
    $employee = $_POST['employee'];
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];

    $sql = "SELECT * FROM employee
    left join employee_details on employee_details.employee_id = employee.employee_id
     WHERE employee.employee_id = '$employee' and employee_details.status ='Active' ";
    $query = $conn->query($sql);
    if ($query->num_rows < 1) {
        $_SESSION['error'] = 'Employee not found';
        header('location: cashadvance.php');
    } else {
        $row = $query->fetch_assoc();
        $employee_id = $row['employee_id'];
        $sql = "INSERT INTO cashadvance (employee_id, advance_amount,advance_date, remarks) VALUES ('$employee_id', '$amount',NOW(), '$remarks')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Cash Advance added successfully';
            header('location: cashadvance.php');
        } else {
            $_SESSION['error'] = $conn->error;
            header('location: cashadvance.php');
        }
    }
}

if (isset($_POST['editcash'])) {
    $id = $_POST['id'];
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];


    $sql = "UPDATE cashadvance SET advance_amount = '$amount', remarks = '$remarks' WHERE cashid  = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Cash advance updated successfully';
        header('location: cashadvance.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: cashadvance.php');
    }
}

if (isset($_POST['cashdelete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM cashadvance WHERE cashid = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Cash advance deleted successfully';

        header('location: cashadvance.php');
    } else {
        $_SESSION['error'] = $conn->error;

        header('location: cashadvance.php');
    }
}


if (isset($_POST['add_payperiod'])) {

    // Get form data
    $ref_no = $_POST['ref_no'];
    $year = $_POST['year'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];


    $checkQuery = "SELECT * FROM pay_periods WHERE (from_date <= ? AND to_date >= ?) OR (from_date <= ? AND to_date >= ?)";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ssss", $to_date, $from_date, $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'The selected pay period overlaps with an existing pay period.';
        header('location: pay_periods.php');
        exit();
    }


    $insertQuery = "INSERT INTO pay_periods (ref_no, year, from_date, to_date, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("siss", $ref_no, $year, $from_date, $to_date);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Pay period added successfully';
        header('location: pay_periods.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: pay_periods.php');
    }
}
if (isset($_POST['edit_payperiod'])) {

    $payid = $_POST['payid'];
    $year = $_POST['year'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    try {

        $updateQuery = "UPDATE pay_periods SET year = ?, from_date = ?, to_date = ? WHERE payid = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('issi', $year, $from_date, $to_date, $payid);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Pay period updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update pay period';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }

    header('location: pay_periods.php');
    exit();
}


if (isset($_POST['delete_payid'])) {
    $delpayid = $_POST['delpayid'];
    $sql = "DELETE FROM pay_periods WHERE payid  = '$delpayid'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Pay period deleted successfully';
        header('location: pay_periods.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: pay_periods.php');
    }
}



if (isset($_POST['add_bonus'])) {

    $employee_name = $_POST['add_employee_id'];
    $bonus_amount = $_POST['bonus_amount'];
    $bonus_type = $_POST['bonus_type'];
    $bonus_period = $_POST['bonus_period'];
    $bonus_description = $_POST['bonus_description'];


    $check_sql = "SELECT * FROM bonus_incentives WHERE employee_id = '$employee_name' AND bonus_type = '$bonus_type' AND bonus_period = '$bonus_period'
                    and status = 'Paid'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {


        $_SESSION['error'] = 'This bonus incentive already exists for this employee in the selected period..';
        header('location: bonus_incentives.php');
        exit();
    }

    $sql = "INSERT INTO bonus_incentives (employee_id, bonus_amount, bonus_type, bonus_period, bonus_description)
            VALUES ('$employee_name', '$bonus_amount', '$bonus_type', '$bonus_period', '$bonus_description')";

    if ($conn->query($sql) === TRUE) {


        $_SESSION['success'] = 'New bonus incentive added successfully!';
        header('location: bonus_incentives.php');
    } else {

        $_SESSION['error'] = $conn->error;
        header('location: bonus_incentives.php');
    }
}

if (isset($_POST['edit_bonus'])) {
    $bonusid = $_POST['id'];
    $employee_id = $_POST['edit_employee_id'];
    $bonus_amount = $_POST['bonus_amount'];
    $bonus_type = $_POST['bonus_type'];
    $bonus_period = $_POST['bonus_period'];
    $bonus_description = $_POST['bonus_description'];

    // Fetch the current bonus period if none is provided
    if (empty($bonus_period)) {
        $stmt = $conn->prepare("SELECT bonus_period FROM bonus_incentives WHERE bonusid = ?");
        $stmt->bind_param("i", $bonusid);
        $stmt->execute();
        $stmt->bind_result($current_bonus_period);
        $stmt->fetch();
        $stmt->close();

        $bonus_period = $current_bonus_period;
    }

    $stmt = $conn->prepare("UPDATE bonus_incentives SET employee_id = ?, bonus_amount = ?, bonus_type = ?, bonus_period = ?, bonus_description = ? WHERE bonusid = ?");
    $stmt->bind_param("idsssi", $employee_id, $bonus_amount, $bonus_type, $bonus_period, $bonus_description, $bonusid);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Bonus updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update bonus.";
    }

    header('Location: bonus_incentives.php');
    exit();
}


if (isset($_POST['delete_bonus'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM bonus_incentives WHERE bonusid   = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Bonus incentive deleted successfully';
        header('location: bonus_incentives.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: bonus_incentives.php');
    }
}



//=========================Generate payroll==================================================


//===========================================================================


if (isset($_POST['save-units'])) {
    $employeeIds = $_POST['employee_ids'];
    $unitType = $_POST['unit-type'];
    $totalUnits = $_POST['total-units'];
    $to_date = $_POST['to_date'];


    $to_date = date('Y-m-d', strtotime($to_date));


    if (in_array('all', $employeeIds)) {

        $employeeIds = [];
        $sql = "SELECT e.employee_id FROM employee e
                JOIN employee_details de ON de.employee_id = e.employee_id
                JOIN department dep ON dep.depid = de.departmentid
                WHERE dep.department = 'PAKYAWAN'";
        $query = $conn->query($sql);
        while ($row = $query->fetch_assoc()) {
            $employeeIds[] = $row['employee_id'];
        }
    }

    foreach ($employeeIds as $employeeId) {

        $checkSql = "SELECT COUNT(*) AS count FROM daily_units WHERE employee_id = ? AND date_completed = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param('is', $employeeId, $to_date);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $row = $checkResult->fetch_assoc();

        if ($row['count'] > 0) {
            $_SESSION['error'] = "Duplicate entry found for employees  on $to_date.";
            header('location: unit_tracking.php');
            exit;
        }

        // Insert record
        $sql = "INSERT INTO daily_units (employee_id, unit_type, units_completed, date_completed) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $employeeId, $unitType, $totalUnits, $to_date);
        if (!$stmt->execute()) {
            $_SESSION['error'] = $conn->error;
            header('location: unit_tracking.php');
            exit;
        }
    }

    $_SESSION['success'] = 'Unit successfully saved!';
    header('location: unit_tracking.php');
    exit;
}

if (isset($_POST['edit-units'])) {
    $unitid = $_POST['unitid'];
    $empname = $_POST['empname'];
    $unitType = $_POST['unit-type'];
    $totalUnits = $_POST['total-units'];
    $to_date = $_POST['to_date'];

    // Convert date to proper format
    $to_date = date('Y-m-d', strtotime($to_date));

    // Prepare the SQL query
    $sql = "UPDATE daily_units 
            SET employee_id = ?, unit_type = ?, units_completed = ?, date_completed = ? 
            WHERE unitid = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bind_param("ssisi", $empname, $unitType, $totalUnits, $to_date, $unitid);

    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        $_SESSION['error'] = $stmt->error;
        header('location: unit_tracking.php');
        exit;
    }

    $_SESSION['success'] = 'Unit updated successfully!';
    header('location: unit_tracking.php');
    exit;
}







if (isset($_POST['add_mandbenifits'])) {
    $Type = $_POST['Type'];
    $amount = $_POST['Amount'];


    $check_sql = "SELECT * FROM mandatory_benefits WHERE benefit_type = '$Type'";
    $check_query = $conn->query($check_sql);

    if ($check_query->num_rows > 0) {

        $_SESSION['error'] = 'Type of benifits already exists';
        header('location: mandatorybenefits.php');
    } else {

        $sql = "INSERT INTO `mandatory_benefits`(`benefit_type`, `amount`)
          VALUES ('$Type','$amount')";
        if ($conn->query($sql)) {
            $_SESSION['success'] = 'Mndatory benefits  added successfully';
            header('location: mandatorybenefits.php');
        } else {
            $_SESSION['error'] = $conn->error;
            header('location: mandatorybenefits.php');
        }
    }
}

if (isset($_POST['edit_mandbenifits'])) {
    $mandateid = $_POST['mandateid'];
    $Type = $_POST['Type'];
    $amount = $_POST['Amount'];



    $sql = "UPDATE mandatory_benefits SET benefit_type = '$Type', amount = '$amount' WHERE mandateid   = '$mandateid'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Mndatory benefits  updated successfully';
        header('location: mandatorybenefits.php');
    } else {
        $_SESSION['error'] = $conn->error;
        header('location: mandatorybenefits.php');
    }
}


if (isset($_POST['adduser'])) { 
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $cPassword = $_POST['cPassword'];
    $status = 'active';

    // Validate password and confirm password
    if ($Password !== $cPassword) {
        $_SESSION['error'] = 'Passwords do not match!';
        header('location: useraccounts.php');
        exit;
    }

    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../images/";
        $photo = $target_dir . basename($_FILES['photo']['name']);
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
            $_SESSION['error'] = "Error uploading photo.";
            header('location: useraccounts.php');
            exit;
        }
    }

    // Check if user already exists
    $check_sql = "SELECT * FROM users WHERE fname = ? AND lname = ? AND username = ? AND email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ssss", $firstname, $lastname, $Username, $email);
    $stmt->execute();
    $check_query = $stmt->get_result();

    if ($check_query->num_rows > 0) {
        $_SESSION['error'] = 'User already exists';
        header('location: useraccounts.php');
    } else {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users` (`fname`, `mname`, `lname`, `contact`, `photo`, `status`, `username`, `email`, `password`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $firstname, $middlename, $lastname, $contact, $photo, $status, $Username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'User account added successfully';
            header('location: useraccounts.php');
        } else {
            $_SESSION['error'] = $stmt->error;
            header('location: useraccounts.php');
        }
    }
}



if (isset($_POST['edit_payroll'])) { 
    $payrollid = $_POST['payrollid'];
    $gross_salary = $_POST['gross_salary'];
    $tot_deductions = $_POST['tot_deductions'];
    $deductions = $_POST['deductions'];
    $late = $_POST['late'];
    $undertime = $_POST['undertime'];
    $present = $_POST['present'];
    $overtime = $_POST['overtime'];
    $allowances = $_POST['allowances'];
    $cash_advance = $_POST['cash_advance'];
    $bonus = $_POST['bonus'];
    $net_salary = $_POST['net_salary'];
    $status = $_POST['status'];
    $admin_id = $_SESSION['admin'];

    $conn->begin_transaction();

    try {
        
        $employee_query = "
        SELECT CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name
        FROM payroll
        LEFT JOIN employee ON employee.employee_id = payroll.employee_id
        WHERE payroll.payrollid = ?
    ";
    
    $emp_stmt = $conn->prepare($employee_query);
    $emp_stmt->bind_param('i', $payrollid);
    $emp_stmt->execute();
    $emp_stmt->bind_result($full_name);
    $emp_stmt->fetch();
    $emp_stmt->close();
    


        $update_query = "
            UPDATE `payroll` 
            SET 
                `gross_salary` = ?, 
                `tot_deductions` = ?, 
                `deductions` = ?, 
                `late` = ?, 
                `undertime` = ?, 
                `present` = ?, 
                `overtime` = ?, 
                `allowances` = ?, 
                `cash_advance` = ?, 
                `bonus` = ?, 
                `net_salary` = ?, 
                `status` = ?
            WHERE `payrollid` = ?
        ";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param(
            'dddddddddssss',
            $gross_salary,
            $tot_deductions,
            $deductions,
            $late,
            $undertime,
            $present,
            $overtime,
            $allowances,
            $cash_advance,
            $bonus,
            $net_salary,
            $status,
            $payrollid
        );
        $stmt->execute();

        // Insert into the audit log
        $action = "Edit Payroll";
        $description = "Payroll for Employee '$full_name' was updated.";
        $audit_query = "
            INSERT INTO `audit_logs` (`user_id`, `action`, `description`, `timestamp`) 
            VALUES (?, ?, ?, NOW())
        ";
        $audit_stmt = $conn->prepare($audit_query);
        $audit_stmt->bind_param('iss', $admin_id, $action, $description);
        $audit_stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Success response
        $_SESSION['success'] = 'Payroll updated successfully!';
        header('location: payroll_runs.php');
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();

        // Error response
        $_SESSION['error'] = 'Error updating payroll!';
        header('location: payroll_runs.php');
    }
}

    
