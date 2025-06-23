<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');  // Default to current year

if (isset($_GET['year'])) {
    $year = $_GET['year'];  // Set the year from the query string if it exists
}

$current_date = date('Y-m-d');  // Get today's date for consistency

// Update the SQL query to get payroll data for the selected year
$sql = "SELECT *, position.*, department.*, 
                CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS full_name,
                SUM(p.gross_salary) AS total_gross_salary,
        SUM(p.overtime) AS total_overtime,
        SUM(p.bonus) AS total_bonus,
        SUM(p.allowances) AS total_allowances,
        SUM(p.tot_deductions) AS total_deductions,
        SUM(p.net_salary) AS total_net_salary
        FROM payroll p
        LEFT JOIN employee u ON p.employee_id = u.employee_id
        LEFT JOIN employee_details d ON u.employee_id = d.employee_id
        LEFT JOIN department ON department.depid = d.departmentid
        LEFT JOIN position ON position.positionid = d.positionid 
        WHERE YEAR(p.created_at) = ? 
        AND p.status= 'paid'
        GROUP BY p.employee_id";  

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $year);  // Bind the year parameter to the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>

<body>
  <div class="page-wrapper">

    <div class="main-container">

      <nav id="sidebar" class="sidebar-wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>
      </nav>

      <div class="app-container">

        <div class="app-header d-flex align-items-center">
          <div class="d-flex">
            <button class="btn btn-outline-dark me-2 toggle-sidebar" id="toggle-sidebar">
              <i class="bi bi-chevron-left fs-5"></i>
            </button>
            <button class="btn btn-outline-dark me-2 pin-sidebar" id="pin-sidebar">
              <i class="bi bi-chevron-left fs-5"></i>
            </button>
          </div>
          <?php include 'includes/navheader.php'; ?>
        </div>

        <div class="app-hero-header">
          <div>
            <h3 class="fw-light">
              <span>Home</span> / <span class="menu-text">Year End Summary Reports</span>
            </h3>
          </div>
        </div>

        <div class="app-body">
          <?php include 'flash_messages.php'; ?>

          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex align-items-center px-3 py-3">
                  <h5 class="m-0">Year End Summary Reports</h5>
                  <form method="GET" action="" class="d-flex align-items-center gap-2 ms-auto">
                    <label for="year" class="form-label m-0">Filter by Year:</label>
                    <select name="year" id="year" class="form-select w-auto">
                      <?php for ($i = 2023; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i; ?>" <?= $i == $year ? 'selected' : ''; ?>><?= $i; ?></option>
                      <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                  </form>
                </div>

                <div class="card-body">
                  <form method="POST" action="codes.php">
                    <div class="table-responsive">
                      <table id="13monthpay" class="table align-middle table-hover m-0">
                        <thead>
                          <tr>
                            <th>Employee Name</th>
                            <th>Position</th>
                            <th>Total Gross Salary</th>
                            <th>Total Overtime</th>
                            <th>Total Bonus</th>
                            <th>Total Allowances</th>
                            <th>Total Deductions</th>

                            <th>Total Net Salary</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                              <td><?= $row['full_name']; ?></td>
                              <td><?= $row['position']; ?></td> <!-- Adjust field name if necessary -->
                              <td><?= number_format($row['total_gross_salary'], 2); ?></td>
                              <td><?= number_format($row['total_overtime'], 2); ?></td>
                              <td><?= number_format($row['total_bonus'], 2); ?></td>
                              <td><?= number_format($row['total_allowances'], 2); ?></td>
                              <td><?= number_format($row['total_deductions'], 2); ?></td>
                             
                              <td><?= number_format($row['total_net_salary'], 2); ?></td>
                            </tr>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php include 'includes/footer.php'; ?>

      </div>

    </div>

  </div>

  <?php include 'includes/scripts.php'; ?>

  <script>
  $(document).ready(function () {
    // Initialize DataTable with buttons
    $("#13monthpay").DataTable({
      responsive: true,
      lengthChange: false,
      autoWidth: false,
      dom:
        '<"row"<"col-sm-6"f><"col-sm-6 text-right"B>>' +
        '<"row"<"col-sm-12"tr>>' +
        '<"row"<"col-sm-5"i><"col-sm-7"p>>',
      buttons: ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#13monthpay_wrapper .col-sm-6:eq(1)');
  });
  </script>

</body>

</html>
