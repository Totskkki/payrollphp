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
              <span>Home</span> / <span class="menu-text">Payroll Reports</span>
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
                  <h5>Payroll Reports</h5>
                  
                </div>

                <div class="card-body">
                  <form method="GET" action="">
                    <div class="row mb-3">
                      <!-- Status Filter -->
                     

                      <!-- Month Filter -->
                      <div class="col-md-2">
                        <label for="date_completed">Filter by (Month-Year):</label>
                        <input type="month" class="form-control" id="date_completed" name="date_completed"
                          value="<?php echo isset($_GET['date_completed']) ? $_GET['date_completed'] : ''; ?>">
                      </div>

                      <!-- Filter Button -->
                      <div class="col-md-4">
                        <button type="submit" class="btn btn-info mt-4">Search</button>
                      </div>
                    </div>
                  </form>

                  <table id="13monthpay" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                    
                        <th>Payslip No.</th>
                        <th>Pay Period</th>
                       
                        <th>Gross Salary</th>
                        <th>Net Pay</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                
                      $bonusPeriodFilter = isset($_GET['date_completed']) ? $_GET['date_completed'] : '';

                      $sql = "SELECT de.*, pa.*, d.*, p.*, dep.*, e.*, CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS `full_name` 
                                                        FROM payroll d
                                                        JOIN employee e ON e.employee_id = d.employee_id
                                                        JOIN employee_details de ON de.employee_id = e.employee_id
                                                        JOIN department dep ON dep.depid = de.departmentid
                                                        JOIN position p ON p.positionid = de.positionid
                                                        JOIN pay_periods pa ON pa.payid = d.pay_period_id
                                                        WHERE d.status='paid'";

                      

                      // Apply month filter
                      if ($bonusPeriodFilter) {
                        $sql .= " AND DATE_FORMAT(d.created_at, '%Y-%m') = '$bonusPeriodFilter'";
                      }

                      $sql .= " ORDER BY d.payrollid DESC";
                      $query = $conn->query($sql);

                      if ($query->num_rows > 0) {
                        while ($row = $query->fetch_assoc()) {

                          $status = $row['status'];
                          $statusBadge = '';

                          if ($status == 'pending') {
                            $statusBadge = "<span class='badge bg-danger'>$status</span>";
                          } elseif ($status == 'approve') {
                            $statusBadge = "<span class='badge bg-success'>$status</span>";
                          } elseif ($status == 'paid') {
                            $statusBadge = "<span class='badge bg-primary'>$status</span>";
                          }


                          // Use PHP for dynamic content inside HTML, avoid echoing large strings
                          $full_name = $row['full_name'];
                          $from_date = date('Y, M j', strtotime($row['from_date']));
                          $to_date = date('Y, M j', strtotime($row['to_date']));
                          $basic_salary = $row['tot_deductions'];
                          $late = $row['late'];
                          $undertime = $row['undertime'];
                          $present = $row['present'];
                          $overtime = $row['overtime'];
                          $allowances = $row['allowances'];
                          $bonus = $row['bonus'];
                          $payslip_no = $row['payslip_no'];
                          $gross_salary = $row['gross_salary'];
                          $net_salary = $row['net_salary'];
                          $payrollid = $row['payrollid'];
                          ?>

                          <tr>
                           
                            <td><?= $payslip_no ?></td>
                            <td><?= $from_date . ' - ' . $to_date ?></td>                                                  
                            <td><?= $gross_salary ?></td>
                           
                            <td><?= $net_salary ?></td>
                            <td><?= $statusBadge ?></td>

                            <td>
                             
                             
                             
                                <!-- Generate Payslip -->
                                <a href="payslip_view.php?payrollid=<?= $payrollid ?>" class="btn btn-info btn-sm">
                                  <i class="bi bi-file-earmark-text"></i> View
                                </a>
                             
                               
                            </td>


                          </tr>

                          <?php
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