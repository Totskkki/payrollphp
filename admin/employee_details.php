<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}


if (isset($_GET['id'])) {
  $empid = $_GET['id'];

  // Query to fetch employee details
  $sql = "SELECT 
              u.*,s.*, u.employee_id as empid, addr.*, ed.*, d.*, p.*, 
              d.department as dep, p.position as pos,
              CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS full_name,
              CONCAT(addr.street, ', ', addr.city, ', ', addr.province) AS full_address
          FROM employee u
          LEFT JOIN employee_details ed ON ed.employee_id = u.employee_id 
          LEFT JOIN department d ON d.depid = ed.departmentid 
          LEFT JOIN position p ON p.positionid = ed.positionid  
          LEFT JOIN schedules s ON s.scheduleid  = ed.scheduleid  
          LEFT JOIN address addr ON addr.addressid = u.employee_id 
          WHERE u.employee_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $empid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if we have data
    if ($result->num_rows > 0) {
      $employee = $result->fetch_assoc();
    } else {
      echo "No details found for this employee.";
      exit;
    }
  } else {
    echo "Error preparing the query.";
    exit;
  }

  // Query to fetch allowances
  $allowances = [];
  $sql_allowances = "SELECT `allempid`, `employee_id`, allowances_employee.`allowid`, `allowance_amount`, `created_at`, `updated_at`, allowance.allowance
                     FROM `allowances_employee` 
                     JOIN allowance on allowance.allowid = allowances_employee.allowid
                     WHERE `allowances_employee`.`employee_id` = ?";

  if ($stmt_allowances = $conn->prepare($sql_allowances)) {
    $stmt_allowances->bind_param("i", $empid);
    $stmt_allowances->execute();
    $result_allowances = $stmt_allowances->get_result();

    while ($row = $result_allowances->fetch_assoc()) {
      $allowances[] = $row;
    }

    $stmt_allowances->close();
  } else {
    echo "Error preparing the allowances query.";
    exit;
  }
  $deductions = [];
  $sql_deductions = "SELECT `deductionid`, `deducid`, `deduc_amount`, `employee_id`, `created_on`, `updated_at` ,deductions.deduction
                      FROM `deductions_employees`
                     JOIN deductions on deductions.dedID = deductions_employees.deducid
                     WHERE `employee_id` = ?";
  if ($stmt_deductions = $conn->prepare($sql_deductions)) {
    $stmt_deductions->bind_param("i", $empid);
    $stmt_deductions->execute();
    $result_deductions = $stmt_deductions->get_result();

    while ($row = $result_deductions->fetch_assoc()) {
      $deductions[] = $row;
    }

    $stmt_deductions->close();
  } else {
    echo "Error preparing the allowances query.";
    exit;
  }
} else {
  echo "Employee ID is not provided.";
  exit;
}

// Now $employee contains the employee's details, and $allowances contains their allowances

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
        <div class="app-hero-header">

          <!-- Page Title start -->
          <div>


            <h3 class="fw-light">
              <span>Home</span> / <span class="menu-text">Profile</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>



        <div class="app-body">

          <!-- Row start -->
          <div class="row justify-content-center">
            <div class="col-sm-12">
              <div class="card mb-4 bg-primary">
                <div class="card-body">
                  <div class="d-flex align-items-center flex-row flex-wrap">
                    <img src="<?php echo (!empty($employee['photo'])) ? '../images/' . $employee['photo'] : '../images/profile.jpg'; ?>" class="img-5x rounded-circle" alt="Admin Dashboard">

                    <div class="ms-3 text-white">
                      <h5 class="mb-1"><?php echo htmlspecialchars($employee['full_name']); ?></h5>
                      <h6 class="m-0 fw-light"><?php echo htmlspecialchars($employee['pos']); ?></h6>
                    </div>

                    <div class="ms-4 text-white d-flex align-items-center ps-4 border-start">
                      <i class="bi bi-envelope-open fs-2 lh-1 me-2"></i>
                      <div>
                        <h6 class="mb-1">Email</h6>
                        <p class="m-0 fw-light small"><?php echo htmlspecialchars($employee['email']); ?></p>
                      </div>
                    </div>
                    <div class="ms-4 text-white d-flex align-items-center ps-4 border-start">
                      <i class="bi bi-telephone fs-2 lh-1 me-2"></i>
                      <div>
                        <h6 class="mb-1">Contact</h6>
                        <p class="m-0 fw-light small"><?php echo htmlspecialchars($employee['contact_number']); ?></p>
                      </div>
                    </div>
                    <div class="ms-4 text-white d-flex align-items-center ps-4 border-start">
                      <i class="bi bi-pin-angle fs-2 lh-1 me-2"></i>
                      <div>
                        <h6 class="mb-1">Location</h6>
                        <p class="m-0 fw-light small"><?php echo htmlspecialchars($employee['full_address']); ?></p>
                      </div>
                    </div>
                    <div class="ms-auto d-flex gap-2">
                      <a href="javascript:void(0)" onclick="history.back();" class="btn bg-danger position-relative" title="Go back to the previous page">
                        <i class="bi bi-arrow-left-circle-fill"></i> Back
                      </a>

                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-sm-6 col-12">
              <div class="card mb-4">
                <card class="card-header">
                  <h5>Personal Details</h5>
                </card>
                <div class="card-body">



                  <!-- Name Extension -->

                  <!-- Birthdate -->
                  <div class="row mb-3">
                    <label for="birthdate" class="col-sm-4 col-form-label">Birthdate</label>
                    <div class="col-sm-8">

                      <input type="text" class="form-control " value="<?php echo htmlspecialchars($employee['birthdate']); ?>"
                        readonly>
                    </div>
                  </div>
                  <!-- Gender -->
                  <div class="row mb-3">
                    <label for="gender" class="col-sm-4 col-form-label">Gender</label>
                    <div class="col-sm-8">
                      <input class="form-control" value="<?php echo htmlspecialchars($employee['gender']); ?>" readonly>


                    </div>
                  </div>



                  <div class="row mb-3">
                    <label for="postalCode" class="col-sm-4 col-form-label">Postal Code</label>
                    <div class="col-sm-8">

                      <input class="form-control" value="<?php echo htmlspecialchars($employee['postal_code']); ?>" readonly>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="postalCode" class="col-sm-4 col-form-label">Country</label>
                    <div class="col-sm-8">


                      <input class="form-control" value="<?php echo htmlspecialchars($employee['country']); ?>" readonly>
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
                    <label for="email" class="col-sm-4 col-form-label">Department:</label>
                    <div class="col-sm-8">
                      <input class="form-control" value="<?php echo htmlspecialchars($employee['dep']); ?>" readonly>



                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="email" class="col-sm-4 col-form-label">Position:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['pos']); ?>" readonly>
                    </div>
                  </div>
                  <div class="row mb-3">
                      <label for="email" class="col-sm-4 col-form-label">Schedule:</label>
                      <div class="col-sm-8">
                          <?php
                         
                          $time_in = isset($employee['scheduled_start']) ? date('g:i A', strtotime($employee['scheduled_start'])) : '';
                          $time_out = isset($employee['scheduled_end']) ? date('g:i A', strtotime($employee['scheduled_end'])) : '';
                          ?>
                          <input type="text" class="form-control" value="<?php echo htmlspecialchars($time_in . ' - ' . $time_out); ?>" readonly>
                      </div>
                  </div>

                  <div class="row mb-2">
                    <label for="email" class="col-sm-4 col-form-label">Hire Date:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control " value="<?php echo htmlspecialchars($employee['hire_date']); ?>"
                        readonly>
                      <div class="mb-3">


                      </div>

                    </div>


                  </div>
                  <div class="row mb-3">
                    <label for="email" class="col-sm-4 col-form-label">Employment Type:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control " value="<?php echo htmlspecialchars($employee['employment_type']); ?>"
                        readonly>

                    </div>
                  </div>
                </div>




              </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-12">

              <div class="card mb-4">
                <card class="card-header">
                  <h5>Financial Details</h5>
                </card>
                <div class="card-body">
                  <!-- Email -->
                  <div class="row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Rate per Day/ Pakyawan:</label>
                    <div class="col-sm-4">

                      <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="text" class="form-control"
                          value="<?php echo number_format($employee['rate_per_hour'], 2); ?> / <?php echo number_format($employee['pakyawan_rate'], 2); ?>" readonly>

                      </div>



                    </div>
                  </div>
                  <div>
                    <h4>Allowances</h3>
                      <?php if (!empty($allowances)): ?>
                        <ul>
                          <?php foreach ($allowances as $allowance): ?>
                            <li>
                              <?php echo htmlspecialchars($allowance['allowance']); ?>,
                              Amount: ₱<?php echo htmlspecialchars($allowance['allowance_amount']); ?>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      <?php else: ?>
                        <p>No allowances found for this employee.</p>
                      <?php endif; ?>
                  </div>

                  <h4>Deductions</h3>
                    <?php if (!empty($deductions)): ?>
                      <ul>
                        <?php foreach ($deductions as $deduction): ?>
                          <li>
                            <?php echo htmlspecialchars($deduction['deduction']); ?>,
                            Amount: ₱<?php echo htmlspecialchars($deduction['deduc_amount']); ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php else: ?>
                      <p>No Deductions found for this employee.</p>
                    <?php endif; ?>
                </div>








              </div>
            </div>









          </div>

        </div>




      </div>


      <?php include 'includes/footer.php'; ?>

    </div>
    <!-- App container ends -->

  </div>
  <!-- Main container end -->

  </div>
  <!-- Page wrapper end -->
  <?php include 'includes/scripts.php'; ?>






</body>

</html>