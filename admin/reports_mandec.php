<?php include 'includes/session.php'; ?>

<?php
$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 days', strtotime($range_to)));


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
              <span>Home</span> / <span class="menu-text">Mandatory Reports</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>
        <!-- App Hero header ends -->

        <!-- App body starts -->
        <div class="app-body">

          <!-- Flash Messages -->
          <?php include 'flash_messages.php'; ?>

          <!-- Payroll Run Table -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5>Mandatory Reports</h5>

                </div>

                <div class="card-body">
                  <form method="GET" action="">
                    <div class="row mb-3">
                      <!-- Benefit Type Filter -->
                      <div class="col-md-2">
                        <label for="status">Benefit Type:</label>
                        <select class="form-control" id="status" name="status">
                          <option value="">All</option>
                          <?php
                          // Get all active benefit types
                          $sql = "SELECT * FROM mandatory_benefits WHERE status = 'active'";
                          $query = $conn->query($sql);
                          while ($prow = $query->fetch_assoc()) {
                            $selected = (isset($_GET['status']) && $_GET['status'] == $prow['benefit_type']) ? 'selected' : '';
                            echo "<option value='" . $prow['benefit_type'] . "' $selected>" . $prow['benefit_type'] . "</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <!-- Month Filter -->
                      <div class="col-md-2">
                        <label for="date_completed">Filter by (Month-Year):</label>
                        <input type="month" class="form-control" id="date_completed" name="date_completed"
                          value="<?php echo isset($_GET['date_completed']) ? htmlspecialchars($_GET['date_completed']) : ''; ?>">
                      </div>

                      <!-- Filter Button -->
                      <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-info">Filter</button>

                      </div>
                    </div>
                  </form>

                  <table id="13monthpay" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Employee No.</th>
                        <th>Employee Name</th>
                        <th>Benefit Type</th>
                        <th>Amount</th>
                        <th>Employee Share</th>
                        <th>Employer Share</th>
                        <th>Average Share</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                     $benefitTypeFilter = isset($_GET['status']) ? $_GET['status'] : '';
                     $bonusPeriodFilter = isset($_GET['date_completed']) ? $_GET['date_completed'] : '';
                     
                     $sql = "SELECT p.employee_id, e.employee_no, 
                             CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS full_name, 
                             p.mandatory_deductions 
                             FROM payroll p
                             JOIN employee e ON e.employee_id = p.employee_id";
                     
                     if ($bonusPeriodFilter) {
                         $sql .= " WHERE DATE_FORMAT(p.created_at, '%Y-%m') = '$bonusPeriodFilter'";
                     }
                     
                     $sql .= " ORDER BY p.payrollid ASC";
                     $query = $conn->query($sql);
                     
                     if ($query->num_rows > 0) {
                         while ($row = $query->fetch_assoc()) {
                             $mandatoryDeductions = json_decode($row['mandatory_deductions'], true);
                     
                             if (!empty($mandatoryDeductions)) {
                                 foreach ($mandatoryDeductions as $key => $deduction) {
                                     if (is_numeric($key)) {
                                         $benefitType = $deduction['benefit_type'] ?? 'Unknown';
                                         $amount = $deduction['amount'] ?? 0;
                                         $employeeShare = $deduction['employee_share'] ?? 0;
                                         $employerShare = $deduction['employer_share'] ?? 0;
                                     } elseif ($key === 'SSS_Employee' || $key === 'SSS_Employer' || $key === 'SSS_Total') {
                                         if ($key === 'SSS_Total') {
                                             $benefitType = 'SSS';
                                             $amount = $deduction;
                                             $employeeShare = $mandatoryDeductions['SSS_Employee'] ?? 0;
                                             $employerShare = $mandatoryDeductions['SSS_Employer'] ?? 0;
                                         } else {
                                             continue; // Skip individual SSS_Employee and SSS_Employer entries
                                         }
                                     } else {
                                         continue; // Skip unknown keys
                                     }
                     
                                     // Apply the Benefit Type filter
                                     if ($benefitTypeFilter && $benefitType !== $benefitTypeFilter) {
                                         continue;
                                     }
                     
                                     $averageShare = ($employeeShare + $employerShare) / 2;
                     
                                     // Output the row for each benefit type
                                     echo '<tr>';
                                     echo '<td>' . htmlspecialchars($row['employee_no']) . '</td>';
                                     echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                                     echo '<td>' . htmlspecialchars($benefitType) . '</td>';
                                     echo '<td>' . number_format($amount, 2) . '</td>';
                                     echo '<td>' . number_format($employeeShare, 2) . '</td>';
                                     echo '<td>' . number_format($employerShare, 2) . '</td>';
                                     echo '<td>' . number_format($averageShare, 2) . '</td>';
                                     echo '</tr>';
                                 }
                             }
                         }
                     }
                      ?>

                    </tbody>
                  </table>

                </div>
              </div>

            </div>
          </div>
        </div>
        <!-- App body end -->
        <?php include 'includes/footer.php'; ?>
      </div>
      <!-- App container end -->
    </div>
    <!-- Main container end -->

    <!-- Generate Payroll Modal -->


    <!-- View Details Modal -->


    <?php include 'includes/scripts.php'; ?>
  </div>
  <!-- Page wrapper end -->

  <!-- AJAX for View Details -->




  <script>
    $(document).ready(function () {
      // Initialize DataTable with buttons
      $("#13monthpay").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        dom:
          '<"row"<"col-sm-6"f><"col-sm-6 text-right"B>>' + // Search and buttons in the same row
          '<"row"<"col-sm-12"tr>>' +                      // Table
          '<"row"<"col-sm-5"i><"col-sm-7"p>>',           // Info and pagination
        buttons: ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#13monthpay_wrapper .col-sm-6:eq(1)');
    });
  </script>

</body>

</html>