<?php include 'includes/session.php'; ?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


if (isset($_GET['id'])) {
    $empid = $_GET['id'];

    // Query to fetch employee details along with allowances
    $sql = "SELECT 
                u.*, s.*, u.employee_id AS empid, addr.*, ed.*, d.*, p.*, 
                allowance.*,  allowances_employee.*,deductions_employees.*,deductions.*,deductions.amount as dedamount,
                d.department AS dep, p.position AS pos,allowance.amount as allowamount,
                CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS full_name,
                CONCAT(addr.street, ', ', addr.city, ', ', addr.province) AS full_address
            FROM employee u
            LEFT JOIN employee_details ed ON ed.employee_id = u.employee_id 
            LEFT JOIN department d ON d.depid = ed.departmentid 
            LEFT JOIN position p ON p.positionid = ed.positionid  
            LEFT JOIN schedules s ON s.scheduleid  = ed.scheduleid  
            LEFT JOIN address addr ON addr.addressid = u.employee_id 
            LEFT JOIN allowances_employee ON allowances_employee.employee_id = u.employee_id
            LEFT JOIN allowance ON allowance.allowid = allowances_employee.allowid
               LEFT JOIN deductions_employees ON deductions_employees.employee_id = u.employee_id
            LEFT JOIN deductions ON deductions.dedID = deductions_employees.deducid
            WHERE u.employee_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $empid);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if we have data
        if ($result->num_rows > 0) {
            $employee = [];
            $allowances = [];
            $deductions = [];
            while ($row = $result->fetch_assoc()) {
                // Store employee details once
                if (empty($employee)) {
                    $employee = $row;
                }
                // Collect allowances
                if ($row['allowid']) {
                    $allowances[] = [
                        'allowid' => $row['allowid'],
                        'allowance' => $row['allowance'] ?? 'N/A',
                        'allowamount' => $row['allowamount'] ?? '0'
                    ];
                }
                if ($row['dedID']) {
                    $deductions[] = [   // Use [] to append deductions
                        'dedID' => $row['dedID'],
                        'deduction' => $row['deduction'],
                        'dedamount' => $row['dedamount'] ?? '0',
                    ];
                }
            }
        } else {
            echo "No details found for this employee.";
            exit;
        }
    } else {
        echo "Error preparing the query.";
        exit;
    }
} else {
    echo "Employee ID is not provided.";
    exit;
}




?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>

<body>
    <!-- Page wrapper start -->
    <div class="page-wrapper">

        <!-- Main container start -->
        <div class="main-container">

            <!-- Sidebar wrapper start -->
            <nav id="sidebar" class="sidebar-wrapper">

                <!-- App brand starts -->
                <?php include 'includes/navbar.php'; ?>
                <!-- Sidebar profile ends -->

                <!-- Sidebar menu starts -->
                <?php include 'includes/menubar.php'; ?>
                <!-- Sidebar menu ends -->

            </nav>
            <!-- Sidebar wrapper end -->

            <!-- App container starts -->
            <div class="app-container">

                <!-- App header starts -->
                <div class="app-header d-flex align-items-center">

                    <!-- Toggle buttons start -->
                    <div class="d-flex">
                        <button class="btn btn-outline-dark me-2 toggle-sidebar" id="toggle-sidebar">
                            <i class="bi bi-chevron-left fs-5"></i>
                        </button>
                        <button class="btn btn-outline-dark me-2 pin-sidebar" id="pin-sidebar">
                            <i class="bi bi-chevron-left fs-5"></i>
                        </button>
                    </div>
                    <!-- Toggle buttons end -->

                    <!-- App brand sm start -->
                    <div class="app-brand-sm d-md-none d-sm-block">
                        <a href="index.html">
                            <img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">
                        </a>
                    </div>
                    <!-- App brand sm end -->

                    <!-- App header actions start -->
                    <?php include 'includes/navheader.php'; ?>
                    <!-- App header actions end -->

                </div>
                <!-- App header ends -->

                <!-- App hero header starts -->
                <div class="app-hero-header ">


                    <div>


                        <h3 class="fw-light">
                            <span>Home</span> / <span class="menu-text">Edit Personal Details </span>
                        </h3>

                    </div>
                    <!-- Page Title end -->

                    <!-- Header graphs start -->
                    <div class="ms-auto d-flex gap-2">
                        <a href="javascript:void(0)" onclick="history.back();"
                            class="btn bg-secondary position-relative" title="Go back to the previous page">
                            <i class="bi bi-arrow-left-circle-fill"></i> Back
                        </a>

                    </div>
                    <!-- Header graphs end -->

                </div>
                <!-- App Hero header ends -->

                <!-- App body starts -->
                <div class="app-body">
                    <?php include 'flash_messages.php'; ?>

                    <!-- Row start -->
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <form action="codes.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                                <input type="hidden" name="empid" value="<?php echo htmlspecialchars($employee['empid']); ?>">
                                <input type="hidden" name="empno" value="<?php echo htmlspecialchars($employee['employee_no']); ?>">
                                <input type="hidden" name="addressid" value="<?php echo htmlspecialchars($employee['addressid']); ?>">
                                <input type="hidden" name="employment_id" value="<?php echo htmlspecialchars($employee['employment_id']); ?>">

                                <input type="hidden" name="allowance" value="<?php echo htmlspecialchars($employee['allempid'] ?? ''); ?>">
                                <input type="hidden" name="deductions" value="<?php echo htmlspecialchars($employee['deductionid'] ?? ''); ?>">

                                <div class="card mb-4">
                                    <card class="card-header">
                                        <h5>Financial Details</h5>
                                    </card>
                                    <div class="card-body">
                            


                                        <div id="allowance-row">
                                            <?php if (!empty($allowances)): ?>
                                                <?php foreach ($allowances as $allowance): ?>

                                                    <div class="row mb-3 allowance-row">
                                                        <label for="text" class="col-sm-2 col-form-label">Allowance:</label>
                                                        <div class="col-sm-3">
                                                            <select class="form-control  ">
                                                                <option
                                                                    value="<?php echo htmlspecialchars($allowance['allowid']); ?>"
                                                                    selected>
                                                                    <?php echo htmlspecialchars($allowance['allowance']); ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="input-group">
                                                                <span class="input-group-text">₱</span>
                                                                <input type="number" min="0" class="form-control "
                                                                    value="<?php echo htmlspecialchars($allowance['allowamount']); ?>"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>


                                            <?php endif; ?>
                                        </div>
                                        <div id="allowance-container">
                                            <!-- First row will be here, initially loaded -->
                                            <div class="row mb-3 allowance-row">
                                                <label for="text" class="col-sm-2 col-form-label">Allowance:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control allowance-select" name="allowance[]">
                                                        <option value="">-Select</option>
                                                       
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" min="0"
                                                            class="form-control allowance-amount"
                                                            name="allowance_amount[]" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-light addallowance">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>




                                        <div id="deduction-containers">
                                            <?php if (!empty($deductions)): ?>
                                                <?php foreach ($deductions as $deduction): ?>
                                                    <div class="row mb-3 deduction-row">
                                                        <label for="deduction" class="col-sm-2 col-form-label">Deduction:</label>
                                                        <div class="col-sm-3">
                                                            <select class="form-control"  required>
                                                                <option value="" selected>- Select Deduction -</option>
                                                                <?php
                                                                // Fetch deductions available
                                                                $sql_deductions = "SELECT * FROM deductions";
                                                                $stmt = $conn->prepare($sql_deductions);
                                                                $stmt->execute();
                                                                $result_deductions = $stmt->get_result();
                                                                while ($row = $result_deductions->fetch_assoc()) {
                                                                    $selected = ($deduction['dedID'] == $row['dedID']) ? 'selected' : '';
                                                                    echo "<option value='" . $row['dedID'] . "' $selected>" . htmlspecialchars($row['deduction']) . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="input-group">
                                                                <span class="input-group-text">₱</span>
                                                                <input type="number" min="0" class="form-control" value="<?php echo htmlspecialchars($deduction['dedamount']); ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button type="button" class="btn btn-light adddeduction">
                                                                <i class="bi bi-plus-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>

                                            <?php endif; ?>
                                        </div>



                                        <div id="deduction-container">
                                         
                                            <div class="row mb-3 deduction-row">
                                                <label for="text" class="col-sm-2 col-form-label">Deduction:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control deduction-select" name="deduction[]">
                                                        <option value="">-Select</option>
                                                       
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" min="0"
                                                            class="form-control deduction-amount"
                                                            name="deduction_amount[]" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-light adddeduction">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>








                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <card class="card-header">
                                        <h5>Personal Details</h5>
                                    </card>
                                    <div class="card-body">



                                        <div class="row mb-3">
                                            <label for="firstName" class="col-sm-4 col-form-label">First Name<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" required
                                                    value="<?php echo $employee['first_name']; ?>">
                                                <small id="nameFeedback" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <!-- Middle Name -->
                                        <div class="row mb-3">
                                            <label for="middleName" class="col-sm-4 col-form-label">Middle Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="middle_name"
                                                    name="middle_name" value="<?php echo $employee['middle_name']; ?>">
                                            </div>
                                        </div>
                                        <!-- Last Name -->
                                        <div class="row mb-3">
                                            <label for="lastName" class="col-sm-4 col-form-label">Last Name<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="last_name" name="last_name"
                                                    required value="<?php echo $employee['last_name']; ?>">
                                                <small id="nameFeedback" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <!-- Name Extension -->
                                        <div class="row mb-3">
                                            <label for="nameExtension" class="col-sm-4 col-form-label">Name Extension
                                                (e.g., Jr., Sr.)</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control"
                                                    value="<?php echo $employee['name_extension']; ?>"
                                                    id="nameExtension" name="name_extension">
                                            </div>
                                        </div>

                                        <!-- Birthdate -->
                                        <div class="row mb-3">
                                            <label for="birthdate" class="col-sm-4 col-form-label">
                                                Birthdate<span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8 position-relative">

                                                <div class="input-group">
                                                    <input type="text" class="form-control datepickeradd"
                                                        name="birthdate" value="<?php echo $employee['birthdate']; ?>"
                                                        required="">
                                                    <span class="input-group-text"><i
                                                            class="bi bi-calendar4"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Gender -->
                                        <div class="row mb-3">
                                            <label for="gender" class="col-sm-4 col-form-label">Gender<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <select class="form-select" id="gender" name="gender"
                                                    value="<?php echo $employee['gender']; ?>" required>
                                                    <option value="Male" <?php echo (isset($employee['gender']) && $employee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo (isset($employee['gender']) && $employee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                    <option value="Other" <?php echo (isset($employee['gender']) && $employee['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Contact Number -->
                                        <div class="row mb-3">
                                            <label for="contactNumber" class="col-sm-4 col-form-label">Contact
                                                Number<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="number" min="0" class="form-control" id="contactNumber" value="<?php echo $employee['contact_number']; ?>"
                                                    name="contact_number">
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="row mb-3">
                                            <label for="streetAddress" class="col-sm-4 col-form-label">Street
                                                Address</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="streetAddress" value="<?php echo $employee['street']; ?>"
                                                    name="street_address">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="city" class="col-sm-4 col-form-label">City<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="city" name="city" value="<?php echo $employee['city']; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="state" class="col-sm-4 col-form-label">Province<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" min="0" class="form-control" id="province" name="province" value="<?php echo $employee['province']; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="postalCode" class="col-sm-4 col-form-label">Postal Code</label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control" id="postalCode" value="<?php echo $employee['postal_code']; ?>"
                                                    name="postal_code">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="postalCode" class="col-sm-4 col-form-label">Country</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="country" name="country" value="<?php echo $employee['country']; ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                        <label for="postalCode" class="col-sm-4 col-form-label">Update Photos</label>
                                        <div class="col-sm-3">

                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-toggle="tooltip"
                                            data-bs-custom-class="custom-tooltip-success"
                                            data-bs-title="This top tooltip is themed via CSS variables."
                                            data-bs-target="#cameraModal">
                                            <i class="icon-bi bi-camera"></i>

                                        </button>

                                        </div>
                                        </div>





                                    </div>

                                </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">

                            <div class="card mb-4">
                                <card class="card-header">
                                    <h5>Account login</h5>
                                </card>
                                <div class="card-body">
                                    <!-- Email -->
                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Email: <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" id="email" name="email" required value="<?php echo $employee['email']; ?>">
                                            <small id="emailFeedback" class="text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Password:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Confirm Password:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="cpassword" name="password">
                                            <div id="passwordError" style="color: red; display: none;">Passwords do not
                                                match!</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card mb-4">
                                <card class="card-header">
                                    <h5>Company Details</h5>
                                </card>
                                <div class="card-body">
                                    <!-- Email -->
                                    <div class="row mb-3">
                                        <label for="department" class="col-sm-4 col-form-label">Department:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="department" id="department" required>
                                                <option value="" selected>- Select -</option>
                                                <?php
                                                // Fetch departments from the database
                                                $sql = "SELECT * FROM department";
                                                $query = $conn->query($sql);
                                                while ($prow = $query->fetch_assoc()) {
                                                    // Check if the department is selected
                                                    $selected = (isset($employee['departmentid']) && $employee['departmentid'] == $prow['depid']) ? 'selected' : '';
                                                    echo "<option value='" . $prow['depid'] . "' $selected>" . $prow['department'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="position" class="col-sm-4 col-form-label">Position:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="position" id="position" value="<?php echo $employee['positionid']; ?>" required>
                                                <option value="" selected>- Select -</option>
                                                <?php
                                                // Fetch positions based on selected department (if any department is selected)
                                                if (isset($employee['departmentid']) && $employee['departmentid'] != '') {
                                                    $department_id = $employee['departmentid'];
                                                    $sql_positions = "SELECT * FROM position WHERE departmentid = ?";
                                                    $stmt = $conn->prepare($sql_positions);
                                                    $stmt->bind_param("i", $department_id);
                                                    $stmt->execute();
                                                    $positions = $stmt->get_result();
                                                    while ($prow_position = $positions->fetch_assoc()) {
                                                        $selected_position = (isset($employee['positionid']) && $employee['positionid'] == $prow_position['positionid']) ? 'selected' : '';
                                                        echo "<option value='" . $prow_position['positionid'] . "' $selected_position>" . $prow_position['position'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <label for="rate" class="col-sm-4 col-form-label">Rate:</label>
                                        <div class="col-sm-2 position-relative">

                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" min="0" id="rate" class="form-control " name="rate"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <small for="text" class="text-warning">Schedule not required for
                                            Pakyawan</small>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="schedule" class="col-sm-4 col-form-label">Schedule:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="schedule" id="schedule" >
                                                <option value="" disabled selected>- Select -</option>
                                                <?php
                                                // Fetch schedules from the database
                                                $sql = "SELECT * FROM schedules";
                                                $query = $conn->query($sql);
                                                if ($query) {
                                                    while ($prow = $query->fetch_assoc()) {
                                                        // Convert time_in and time_out to 12-hour AM/PM format
                                                        $time_in = date('g:i A', strtotime($prow['scheduled_start']));
                                                        $time_out = date('g:i A', strtotime($prow['scheduled_end']));

                                                        // Check if the schedule is the one selected by the employee
                                                        $selected = (isset($employee['scheduleid']) && $employee['scheduleid'] == $prow['scheduleid']) ? 'selected' : '';

                                                        echo "<option value='" . htmlspecialchars($prow['scheduleid']) . "' $selected>" . htmlspecialchars($time_in) . " - " . htmlspecialchars($time_out) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value='' disabled>No schedules available</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>




                                    <div class="row mb-2">
                                        <label for="email" class="col-sm-4 col-form-label">Hire Date:<span
                                                class="text-danger">*</span></label>

                                        <div class="col-sm-8 position-relative">
                                            <input type="text" class="form-control datepickeradd" name="datehire"
                                                id="datehire" required required value="<?php echo $employee['hire_date']; ?>">
                                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3">
                                                <i class="bi bi-calendar" aria-hidden="true"></i>
                                            </span>
                                        </div>


                                    </div>


                                    <div class="row mb-3">
                                        <label for="employment_type" class="col-sm-4 col-form-label">Employment Type:<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="employment_type" name="employment_type" required>
                                                <option value="">- Select -</option>
                                                <option value="full-time" <?php echo (isset($employee['employment_type']) && $employee['employment_type'] == 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                                                <option value="part-time" <?php echo (isset($employee['employment_type']) && $employee['employment_type'] == 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                                                <option value="contract" <?php echo (isset($employee['employment_type']) && $employee['employment_type'] == 'Contract') ? 'selected' : ''; ?>>Contract</option>
                                                <option value="temporary" <?php echo (isset($employee['employment_type']) && $employee['employment_type'] == 'Temporary') ? 'selected' : ''; ?>>Temporary</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>




                            </div>





                            <button type="submit" id="submitButton" name="updateEmployee"
                                class="btn btn-primary float-end">Update</button>

                        </div>
                        <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cameraModalLabel">Capture Attendance Photos</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <video id="video" width="100%" height="auto" autoplay></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                        <div class="mt-3 d-flex justify-content-between">
                                            <button type="button" class="btn btn-success" id="capture">Capture
                                                Photos</button>
                                        </div>
                                        <div id="capturedImages" class="mt-3 d-flex flex-wrap"></div>
                                        <input type="hidden" name="face_images" id="face_images">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        </form>


                    </div>
                    <!-- Row end -->



                </div>
                <!-- App body ends -->

                <!-- App footer start -->
                <?php include 'includes/footer.php'; ?>

                <!-- App footer end -->

            </div>
            <!-- App container ends -->

        </div>
        <!-- Main container end -->

    </div>
    <!-- Page wrapper end -->

    <?php include 'includes/scripts.php'; ?>



    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const capturedImages = document.getElementById('capturedImages');
        const faceImagesInput = document.getElementById('face_images');

        let capturedImageArray = [];
        let captureInterval;

        // Open camera when modal is shown
        document.getElementById('cameraModal').addEventListener('shown.bs.modal', () => {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => video.srcObject = stream)
                .catch(error => console.error("Error accessing the camera: ", error));
        });

        // Stop camera and clear interval when modal is hidden
        document.getElementById('cameraModal').addEventListener('hidden.bs.modal', () => {
            const stream = video.srcObject;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            video.srcObject = null;
            clearInterval(captureInterval);
        });

        // Start automatic capture
        captureButton.addEventListener('click', () => {
            startAutomaticCapture();
        });

        // Automatic capture for 5 photos
        function startAutomaticCapture() {
            let count = 0;
            capturedImageArray = []; // Clear previous images
            renderCapturedImages();
            updateHiddenInput();

            captureInterval = setInterval(() => {
                if (count < 5) {
                    capturePhoto();
                    count++;
                } else {
                    clearInterval(captureInterval);
                }
            }, 1000); // Capture every 1 second
        }

        // Capture a single photo
        function capturePhoto() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');
            capturedImageArray.push(imageData);

            renderCapturedImages();
            updateHiddenInput();
        }

        // Render captured images with remove button
        function renderCapturedImages() {
            capturedImages.innerHTML = ''; // Clear the container

            capturedImageArray.forEach((imageData, index) => {
                const imgContainer = document.createElement('div');
                imgContainer.style.margin = '5px';
                imgContainer.style.position = 'relative';
                imgContainer.style.display = 'inline-block';

                const imgElement = document.createElement('img');
                imgElement.src = imageData;
                imgElement.style.width = '100px';
                imgElement.style.marginBottom = '5px';

                // Remove Button
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Remove';
                removeBtn.className = 'btn btn-sm btn-danger';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '0';
                removeBtn.style.right = '0';
                removeBtn.addEventListener('click', () => removePhoto(index));

                imgContainer.appendChild(imgElement);
                imgContainer.appendChild(removeBtn);
                capturedImages.appendChild(imgContainer);
            });
        }

        // Remove a photo
        function removePhoto(index) {
            capturedImageArray.splice(index, 1); // Remove the selected image from the array
            renderCapturedImages();
            updateHiddenInput();
        }

        // Update the hidden input with the image array
        function updateHiddenInput() {
            faceImagesInput.value = JSON.stringify(capturedImageArray);
        }
        saveButton.addEventListener('click', () => {
            // Minimize modal using Bootstrap's modal backdrop handling
            const modal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
            modal.hide(); // Hide modal without clearing the data
            console.log('Photos saved:', capturedImageArray);
        });

    </script>
















    <script type="text/javascript">
        $(document).ready(function() {
            // When department is selected
            $('#department').change(function() {
                var departmentId = $(this).val();
                if (departmentId != "") {
                    // Make an AJAX request to get positions based on selected department
                    $.ajax({
                        url: 'ajax/get_positions.php', // PHP file to get positions
                        method: 'GET',
                        data: {
                            department_id: departmentId
                        }, // Send department ID
                        success: function(data) {
                            $('#position').html(data); // Update the position dropdown
                        }
                    });
                } else {
                    $('#position').html('<option value="">- Select -</option>'); 
                }
            });
            $('#position').change(function () {
                var positionId = $(this).val();
                if (positionId != "") {
                    // Fetch rate based on the selected position
                    $.ajax({
                        url: 'ajax/get_rate.php',
                        method: 'GET',
                        data: { position_id: positionId },
                        success: function (rate) {
                            $('#rate').val(rate); // Set the rate
                        }
                    });
                } else {
                    $('#rate').val(''); // Clear rate
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#email').on('keyup', function() {
                const email = $(this).val();

                if (email) {
                    $.ajax({
                        url: 'ajax/checkduplicates.php',
                        type: 'POST',
                        data: {
                            email: email
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.exists) {
                                $('#emailFeedback').text('Email already exists.');
                            } else {
                                $('#emailFeedback').text('');
                            }
                        },
                        error: function() {
                            $('#emailFeedback').text('Error checking email.');
                        }
                    });
                } else {
                    $('#emailFeedback').text('');
                }
            });

        });
    </script>




    <script>
        $(document).ready(function() {
            // Function to load allowance options into the select element
            function loadAllowanceOptions(selectElement) {
                $.ajax({
                    url: 'ajax/get_allowances.php', // Make sure to adjust the path if needed
                    type: 'GET',
                    success: function(response) {
                        let options = '<option value="">-Select</option>';
                        const allowances = JSON.parse(response);

                        // Get all the selected allowances in the form (from all rows)
                        const selectedAllowances = [];
                        $('.allowance-select').each(function() {
                            selectedAllowances.push($(this).val());
                        });

                        // Add options to the current select element
                        allowances.forEach(function(allowance) {
                            // If the allowance is already selected, disable it in the current select
                            const isDisabled = selectedAllowances.includes(allowance.allowid) ? 'disabled' : '';
                            options += `<option value="${allowance.allowid}" data-amount="${allowance.amount}" ${isDisabled}>${allowance.allowance}</option>`;
                        });

                        // Update the options in the current select element
                        $(selectElement).html(options);

                        // Set the selected value for this specific row (if any)
                        const selectedValue = $(selectElement).val();
                        $(selectElement).val(selectedValue).change(); // Ensure that the selected value is reflected
                    },
                    error: function() {
                        alert('Error loading allowances');
                    }
                });
            }

            // Load options for the first allowance row
            loadAllowanceOptions('.allowance-select');

            // Add a new allowance row when the addallowance button is clicked
            $(document).on('click', '.addallowance', function() {
                let newAllowanceRow = `
        <div class="row mb-3 allowance-row">
            <label for="text" class="col-sm-2 col-form-label">Allowance:</label>
            <div class="col-sm-3">
                <select class="form-control allowance-select" name="allowance[]">
                    <option value="">-Select</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" min="0" class="form-control allowance-amount" name="allowance_amount[]" readonly>
                </div>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger remove-btn">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;

                // Append the new row inside the form's container
                $('#allowance-container').append(newAllowanceRow);

                // Load options for the new select element
                loadAllowanceOptions($('#allowance-container .allowance-row:last-child .allowance-select'));
            });

            // Remove a dynamically added row
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.allowance-row').remove();
            });

            // Update the amount field when an allowance is selected
            $(document).on('change', '.allowance-select', function() {
                let selectedOption = $(this).find('option:selected');
                let allowanceValue = selectedOption.val();

                // Check if the selected allowance is already selected in another row
                let isDuplicate = false;
                $('.allowance-select').each(function() {
                    if ($(this).val() === allowanceValue && this !== $(this)[0]) {
                        isDuplicate = true;
                    }
                });

                if (isDuplicate) {
                    // Alert the user if the allowance is already selected
                    alert('This allowance has already been selected.');
                    // Clear the selection and set focus back to the select element
                    $(this).val('');
                } else {
                    // Update the amount field if the selection is valid
                    let amount = selectedOption.data('amount') || 0;
                    $(this).closest('.allowance-row').find('.allowance-amount').val(amount);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to load deduction options into the select element
            function loadDeductionOptions(selectElement) {
                $.ajax({
                    url: 'ajax/get_deduction.php', // Adjust this URL to match your data source
                    type: 'GET',
                    success: function(response) {
                        let options = '<option value="">-Select</option>';
                        const deductions = JSON.parse(response);

                        // Get all the selected deductions in the form (from all rows)
                        const selectedDeductions = [];
                        $('.deduction-select').each(function() {
                            selectedDeductions.push($(this).val());
                        });

                        // Add options to the current select element
                        deductions.forEach(function(deduction) {
                            // If the deduction is already selected, disable it in the current select
                            const isDisabled = selectedDeductions.includes(deduction.dedID) ? 'disabled' : '';
                            options += `<option value="${deduction.dedID}" data-amount="${deduction.amount}" ${isDisabled}>${deduction.deduction}</option>`;
                        });

                        // Update the options in the current select element
                        $(selectElement).html(options);

                        // Set the selected value for this specific row (if any)
                        const selectedValue = $(selectElement).val();
                        $(selectElement).val(selectedValue).change(); // Ensure that the selected value is reflected
                    },
                    error: function() {
                        alert('Error loading deductions');
                    }
                });
            }

            // Load options for the first deduction row
            loadDeductionOptions('.deduction-select');

            // Add a new deduction row when the adddeduction button is clicked
            $(document).on('click', '.adddeduction', function() {
                let newDeductionRow = `
        <div class="row mb-3 deduction-row">
            <label for="text" class="col-sm-2 col-form-label">Deduction:</label>
            <div class="col-sm-3">
                <select class="form-control deduction-select" name="deduction[]">
                    <option value="">-Select</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" min="0" class="form-control deduction-amount" name="deduction_amount[]" readonly>
                </div>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger remove-btn">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;

                // Append the new row inside the form's container
                $('#deduction-container').append(newDeductionRow);

                // Load options for the new select element
                loadDeductionOptions($('#deduction-container .deduction-row:last-child .deduction-select'));
            });

            // Remove a dynamically added row
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.deduction-row').remove();
            });

            // Update the amount field when a deduction is selected
            $(document).on('change', '.deduction-select', function() {
                let selectedOption = $(this).find('option:selected');
                let amount = selectedOption.data('amount') || 0;
                $(this).closest('.deduction-row').find('.deduction-amount').val(amount);

                // Check if the selected deduction is already selected in another row
                let deductionValue = selectedOption.val();
                let isDuplicate = false;

                $('.deduction-select').each(function() {
                    if ($(this).val() === deductionValue && this !== $(this)[0]) {
                        isDuplicate = true;
                    }
                });

                if (isDuplicate) {
                    // Alert the user if the deduction is already selected
                    alert('This deduction has already been selected.');
                    // Clear the selection and set focus back to the select element
                    $(this).val('');
                }
            });
        });
    </script>








    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('cpassword');
        const errorDiv = document.getElementById('passwordError');

        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    </script>




</body>


</html>