<?php include 'includes/session.php';

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
              <span>Home</span> / <span class="menu-text">Overtime Computation</span>
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
                  <h5>Overtime</h5>

                </div>


                <div class="card-body">


                  <table id="example1" class="table table-bordered table-striped">
                    <thead>

                      <th>Employee ID</th>
                      <th>Employee Name</th>
                      <th>Hours</th>
                      <th>Rate</th>


                      <th>Calculations</th>

                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT *,overtime.status, overtime.overtimeid AS otid, employee.employee_id AS empid,employee.*,
                  ROUND(overtime.hours, 2) AS formatted_hours, position.rate_per_hour as rate_per_day
                FROM overtime
                LEFT JOIN employee ON employee.employee_id=overtime.employee_id 
                 LEFT JOIN employee_details  ON employee.employee_id = employee_details.employee_id
                LEFT JOIN position  ON position.positionid   = employee_details.positionid 
              
             
                ORDER BY overtime.date_overtime DESC";
                      $query = $conn->prepare($sql);

                      if ($query) {
                        $query->execute();
                        $result = $query->get_result();
                      } else {
                        die("Query failed: " . $conn->error);
                      }
                      ?>

                      <?php foreach ($result as $row): ?>
                        <tr>

                          <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                          <td><?php echo htmlspecialchars(ucwords($row['first_name'] . ' ' . $row['last_name'])); ?></td>
                          <td><?php echo number_format($row['hours'], 2); ?></td>
                          <td><?php echo htmlspecialchars($row['rate']); ?></td>


                          <td>
                            <?php
                            $hourly_rate = $row['rate_per_hour'] / 8; // Calculate hourly rate
                            $calculations = $row['hours'] * $hourly_rate; // Calculate hourly rate

                            echo number_format($hourly_rate, 2);
                            ?>
                            <small><i>(₱ <?php echo number_format($row['rate_per_hour'], 2); ?> / 8 * <?= $row['hours'] ?> ) = <?= $calculations ?></i></small> <!-- Label for Calculation -->
                          </td>
                        </tr>
                      <?php endforeach; ?>


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


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
      var ctx = document.getElementById('overtimeChart').getContext('2d');
      var overtimeChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($employeeNames); ?>,
          datasets: [{
            label: 'Total Overtime Hours',
            data: <?php echo json_encode($overtimeHours); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: true,
              position: 'top'
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
  </script> -->
</body>

</html>