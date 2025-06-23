<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}





$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$current_date = date('Y-m-d');

$sql = "SELECT t.employee_id, t.thirteenth_month_pay, u.employee_no,position.rate_per_hour as rate_per_day,
                CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS full_name ,
                TIMESTAMPDIFF(MONTH, d.hire_date, ? ) AS months_worked
        FROM `13th_month` t
        LEFT JOIN employee u ON t.employee_id = u.employee_id
        LEFT JOIN employee_details d ON u.employee_id = d.employee_id
        LEFT JOIN department  ON department.depid  = d.departmentid
        LEFT JOIN position  ON position.positionid   = d.positionid 
        WHERE t.year = ? 
        AND d.status ='Active' AND department !='pakyawan'";

$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $current_date, $year);
$stmt->execute();
$result = $stmt->get_result();

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
            <!-- <a href="index.html">
              <img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">
            </a> -->
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
              <span>Home</span> / <span class="menu-text">13th Month Pay Management</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>
        <!-- App Hero header ends -->

        <!-- App body starts -->
        <div class="app-body">

        <?php include 'flash_messages.php'; ?>


          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex align-items-center px-3 py-3">
                  <h5 class="m-0">13th Month Pay Management</h5>
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
                    <button type="submit" name="monthpay" class="btn btn-info mb-3">Process 13th Month Pay</button>
                    <div class="table-responsive">
                      <table id="13monthpay" class="table align-middle table-hover m-0">
                        <thead>
                          <tr>
                            <th>Employee No</th>
                            <th>Name</th>
                            <th>Rate per day</th>
                            <th>Months Worked</th>
                            <th>13th Month Pay</th>
                            
                        </thead>

                        <tbody>
                          <?php
                          if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                              // Store the months_worked in a variable
                              $months_worked = $row['months_worked'];

                              // Use min() on the variable
                              $months_worked = min($months_worked, 12);

                              echo "
                                        <tr>
                                            <td>{$row['employee_no']}</td>
                                            <td>{$row['full_name']}</td>
                                            <td>" . number_format($row['rate_per_day'], 2) . "</td>
                                            <td>{$months_worked}</td>
                                            <td>" . number_format($row['thirteenth_month_pay'], 2) . "</td>
                                             
                                        </tr>";
                            }
                          } else {

                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>





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