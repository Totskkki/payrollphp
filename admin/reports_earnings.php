<?php include 'includes/session.php'; ?>



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
              <span>Home</span> / <span class="menu-text">Payroll Earnings Reports</span>
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
                  <h5>Payroll Earnings Reports</h5>
                </div>

                <?php
                // Database connection (ensure $conn is properly initialized)
                if (!$conn) {
                  die("Database connection failed: " . mysqli_connect_error());
                }

                $pay_period_id = $_GET['pay_period_id'] ?? null;

                $query = "SELECT de.*, pa.*, d.*, p.*, dep.*, e.*, 
                             CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS `full_name` 
                      FROM payroll d
                      JOIN employee e ON e.employee_id = d.employee_id
                      JOIN employee_details de ON de.employee_id = e.employee_id
                      JOIN department dep ON dep.depid = de.departmentid
                      JOIN position p ON p.positionid = de.positionid
                      JOIN pay_periods pa ON pa.payid = d.pay_period_id
                      WHERE d.status = 'paid'";

                // Add filter condition if pay_period_id is set
                if ($pay_period_id) {
                  $query .= " AND d.pay_period_id = ?";
                }

                $stmt = $conn->prepare($query);

                if ($pay_period_id) {
                  $stmt->bind_param("s", $pay_period_id);
                }

                $stmt->execute();
                $result = $stmt->get_result();

                $payrolls = $result->fetch_all(MYSQLI_ASSOC);
                ?>

                <div class="card-body">
                  <form method="GET" action="">
                    <div class="row mb-3">
                      <!-- Pay Period Filter -->
                      <div class="col-md-4">
                        <label for="pay_period_id">Select Pay Period:</label>
                        <select class="form-control" id="pay_period_id" name="pay_period_id">
                          <option value="">-- Select Pay Period --</option>
                          <?php
                          // Fetch pay periods from the database
                          $query = "SELECT payid, ref_no, year, from_date, to_date FROM pay_periods WHERE status IN('open', 'closed', 'locked') ORDER BY created_at DESC";
                          $result = $conn->query($query);

                          if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                              $selected = (isset($_GET['pay_period_id']) && $_GET['pay_period_id'] == $row['payid']) ? 'selected' : '';
                              ?>
                              <option value="<?= htmlspecialchars($row['payid']) ?>" <?= $selected ?>>
                                <?= htmlspecialchars($row['ref_no'] . " (" . $row['from_date'] . " - " . $row['to_date'] . ")") ?>
                              </option>
                              <?php
                            endwhile;
                          endif;
                          ?>
                        </select>
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
                        <th>#</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Gross Salary</th>
                        <th>Total Deductions</th>
                        <th>Allowances</th>
                        <th>Cash Advance</th>
                        <th>Bonus</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if ($payrolls):
                        $counter = 1; ?>

                        <?php foreach ($payrolls as $payroll):

                          $status = $payroll['status'];
                          $statusBadge = '';

                          if ($status == 'pending') {
                            $statusBadge = "<span class='badge bg-danger'>$status</span>";
                          } elseif ($status == 'approve') {
                            $statusBadge = "<span class='badge bg-success'>$status</span>";
                          } elseif ($status == 'paid') {
                            $statusBadge = "<span class='badge bg-primary'>$status</span>";
                          }
                          ?>
                          <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($payroll['employee_no']) ?></td>
                            <td><?= htmlspecialchars($payroll['full_name']) ?></td>
                            <td><?= number_format($payroll['gross_salary'], 2) ?></td>
                            <td><?= number_format($payroll['tot_deductions'], 2) ?></td>
                            <td><?= number_format($payroll['allowances'], 2) ?></td>
                            <td><?= number_format($payroll['cash_advance'], 2) ?></td>
                            <td><?= number_format($payroll['bonus'], 2) ?></td>
                            <td><?= number_format($payroll['net_salary'], 2) ?></td>
                            <td><?= $statusBadge ?></td>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($payroll['created_at']))) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="11" class="text-center">No records found for the selected period.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
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