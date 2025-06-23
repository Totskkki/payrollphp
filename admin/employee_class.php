<?php
include 'includes/session.php';

class Employee
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function saveEmployee($postData, $fileData)
    {
        try {
            $this->conn->begin_transaction();

            // Sanitize and assign form data
            $first_name = ucfirst(strtolower(trim($postData['first_name'])));
            $middle_name = isset($postData['middle_name']) ? ucfirst(strtolower(trim($postData['middle_name']))) : null;
            $last_name = ucfirst(strtolower(trim($postData['last_name'])));
            $name_extension = ucfirst(strtolower(trim($postData['name_extension'])));
            $birthdate = $postData['birthdate'];
            $gender = $postData['gender'];
            $contact_number = $postData['contact_number'] ?? null;
            $street_address = $postData['street_address'] ?? null;
            $city = $postData['city'] ?? null;
            $province = $postData['province'] ?? null;
            $postal_code = $postData['postal_code'] ?? null;
            $country = $postData['country'] ?? null;
            $schedule = $postData['schedule'] ?? null;
            $email = $postData['email'];
            $password = password_hash($postData['password'], PASSWORD_BCRYPT);
            $department = $postData['department'];
            $position = $postData['position'];
            $hire_date = $postData['datehire'];
            $employment_type = $postData['employment_type'];
            $status = 'Active';

            // Upload photo
            $photo = null;
            if (!empty($fileData['photo']['name'])) {
                $target_dir = "../images/";
                $photo = $target_dir . basename($fileData['photo']['name']);
                if (!move_uploaded_file($fileData['photo']['tmp_name'], $photo)) {
                    throw new Exception("Error uploading photo.");
                }
            }

            // Generate Employee Number
            $sql_last_id = "SELECT employee_no FROM employee ORDER BY employee_no DESC LIMIT 1";
            $result = $this->conn->query($sql_last_id);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                preg_match('/(\d+)$/', $row['employee_no'], $matches);
                $numeric_part = (int)$matches[0] + 1;
            } else {
                $numeric_part = 101;
            }
            $employee_no = 'JV-' . $numeric_part;

            // Process face images
            $face_paths = [];
            if (!empty($postData['face_images'])) {
                $face_images = json_decode($postData['face_images'], true);
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
            $face_paths_json = json_encode($face_paths);

            // Insert employee data
            $sql = "INSERT INTO employee (employee_no, first_name, middle_name, last_name, name_extension, birthdate, gender, contact_number, email, password, photo, face_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssssssssssss', $employee_no, $first_name, $middle_name, $last_name, $name_extension, $birthdate, $gender, $contact_number, $email, $password, $photo, $face_paths_json);
            $stmt->execute();
            $employee_id = $this->conn->insert_id;

            // Insert employee details
            $sql_details = "INSERT INTO employee_details (employee_id, positionid, departmentid, scheduleid, hire_date, employment_type, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql_details);
            $stmt->bind_param('issssss', $employee_id, $position, $department, $schedule, $hire_date, $employment_type, $status);
            $stmt->execute();

            // Insert address
            $sql_address = "INSERT INTO address (street, city, province, postal_code, country, empid) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql_address);
            $stmt->bind_param('sssssi', $street_address, $city, $province, $postal_code, $country, $employee_id);
            $stmt->execute();

            // Commit transaction
            $this->conn->commit();

            $_SESSION['success'] = 'Employee added successfully';
            header('location: employee.php');
        } catch (Exception $e) {
            $this->conn->rollback();
            die("Error saving employee: " . $e->getMessage());
        }
    }
    
}
?>
