<?php include 'includes/session.php';

?>



<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>
<style>


</style>

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
              <span>Home</span> / <span class="menu-text">Overtime Tracking</span>
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






          <!-- Overtime Statistics -->

          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5>Overtime tracking</h5>

                </div>


                <div class="card-body">
                  <form method="GET" action="">
                    <div class="row mb-3">
                      <!-- Status Filter -->
                      <div class="col-md-2">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status">
                          <option value="">All</option>
                          <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : ''; ?>>Pending</option>
                          <option value="2" <?php echo (isset($_GET['status']) && $_GET['status'] === '2') ? 'selected' : ''; ?>>Approved</option>
                          <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                      </div>

                      <!-- Month Filter -->
                      <div class="col-md-2">
                        <label for="date_completed">Filter by (Month-Year):</label>
                        <input type="month" class="form-control" id="date_completed" name="date_completed"
                          value="<?php echo isset($_GET['date_completed']) ? htmlspecialchars($_GET['date_completed']) : ''; ?>">
                      </div>

                      <!-- Filter Button -->
                      <div class="col-md-4">
                        <button type="submit" class="btn btn-info mt-4">Search</button>
                      </div>
                    </div>
                  </form>

                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Employee ID</th>
                        <th>Hours</th>
                        <th>Rate</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total Compensation</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Initialize the query
                      $sql = "SELECT overtime.*, employee.employee_no 
                      FROM overtime 
                      LEFT JOIN employee ON employee.employee_id = overtime.employee_id 
                      WHERE 1=1";

                      // Apply filters if set
                      $params = [];
                      $types = ""; // Parameter types string for bind_param

                      if (isset($_GET['status']) && $_GET['status'] !== "") {
                        $sql .= " AND overtime.status = ?";
                        $params[] = $_GET['status'];
                        $types .= "s"; // Add type string
                      }

                      if (!empty($_GET['date_completed'])) {
                        $sql .= " AND DATE_FORMAT(overtime.date_overtime, '%Y-%m') COLLATE utf8mb4_unicode_ci = ?";
                        $params[] = $_GET['date_completed'];
                        $types .= "s"; // Add type string
                      }

                      $stmt = $conn->prepare($sql);
                      if ($stmt) {
                        if ($params) {
                          $stmt->bind_param($types, ...$params);
                        }
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Display results
                        foreach ($result as $row): ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                            <td><?php echo number_format($row['hours'], 2); ?></td>

                            <td><?php echo htmlspecialchars($row['rate']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_overtime']); ?></td>
                            <td>
                              <?php
                              switch ($row['status']) {
                                case '0':
                                  echo '<span class="badge bg-warning">Pending</span>';
                                  break;
                                case '2':
                                  echo '<span class="badge bg-success">Approved</span>';
                                  break;
                                case '1':
                                  echo '<span class="badge bg-danger">Rejected</span>';
                                  break;
                                default:
                                  echo '<span class="badge bg-secondary">Unknown</span>';
                              }
                              ?>
                            </td>
                            <td>
                              <?php
                              $total_compensation = $row['hours'] * $row['rate'];
                              echo number_format($total_compensation, 2);
                              ?>
                            </td>
                          </tr>
                      <?php endforeach;
                      } else {
                        echo "<tr><td colspan='6'>Query failed: " . $conn->error . "</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>




                </div>
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
  <?php include 'includes/scripts.php'; ?>

 
</body>

</html>