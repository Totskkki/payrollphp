<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';

$year = date('Y');
$today = date('Y-m-d');
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}


require '../vendor/autoload.php'; // Include the Composer autoloader
use PhpOffice\PhpSpreadsheet\IOFactory;




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

        <!-- App brand -->
        <?php include 'includes/navbar.php'; ?>

        <!-- Sidebar menu -->
        <?php include 'includes/menubar.php'; ?>

      </nav>
      <!-- Sidebar wrapper end -->

      <!-- App container -->
      <div class="app-container">

        <!-- App header -->
        <div class="app-header d-flex align-items-center">

          <!-- Toggle buttons -->
          <div class="d-flex">
            <button class="btn btn-outline-dark me-2 toggle-sidebar" id="toggle-sidebar">
              <i class="bi bi-chevron-left fs-5"></i>
            </button>
            <button class="btn btn-outline-dark me-2 pin-sidebar" id="pin-sidebar">
              <i class="bi bi-chevron-left fs-5"></i>
            </button>
          </div>

          <!-- App brand for small screens -->
          <div class="app-brand-sm d-md-none d-sm-block">
            <!-- <a href="index.html">
              <img src="assets/images/logo-dark.svg" class="logo" alt="Logo">
            </a> -->
          </div>

          <!-- App header actions -->
          <?php include 'includes/navheader.php'; ?>

        </div>
        <!-- App header ends -->

        <!-- App hero header -->
        <div class="app-hero-header">
          <!-- Page Title -->
          <div>
            <h3 class="fw-light">
              <span>Home</span> / <span class="menu-text">Attendance</span>
            </h3>
          </div>
        </div>
        <!-- App hero header ends -->

        <!-- App body -->
        <div class="app-body">

          <!-- PHP alert messages -->
          <?php include 'flash_messages.php'; ?>

          <!-- Main content area -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                  <!-- Title Section -->
                  <div>
                    <h5>Attendance List (<?php echo $today; ?>)</h5>
                  </div>
                  <!-- File Upload Form -->
                  <!-- <div class="d-flex justify-content-end align-items-center">
                    <form method="post" enctype="multipart/form-data" action="codes.php"
                      class="d-flex align-items-center">
                      <div class="md-2 mr-3">
                        <input type="file" class="form-control" name="attendance_file" id="attendance_file" required>
                      </div>
                      <div>
                        <button class="btn btn-primary" type="submit" name="attendance">Import Attendance</button>
                      </div>
                    </form>
                  </div>-->
                </div> 



                <div class="card-body">

                  <?php
                  // Get today's date
                  

                  // SQL Query for Today's Late Attendance
                  $sql = "SELECT attendance.*, employee.*,employee_details.*,attendance.status,schedules.*,
                        CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name
                  FROM `attendance`
                  LEFT JOIN employee ON attendance.employee_no = employee.employee_no
                  LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
                  LEFT JOIN schedules on schedules.scheduleid = employee_details.scheduleid
                     WHERE attendance.date = ? AND employee_details.status ='Active' AND employee.is_archived = 0
                  ORDER BY attendance.date DESC, attendance.time_in DESC";

                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param('s', $today);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  ?>
                  <table id="example1" class="table table-bordered">
                    <thead>
                      <tr>

                        <th>Date</th>
                        <th>Employee No.</th>
                        <th>Name</th>
                        <th>Scheduled Time In</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          // Determine Status
                          $status = (strtotime($row['time_in']) <= strtotime($row['scheduled_start']))
                            ? '<span class="badge bg-success">On Time</span>'
                            : '<span class="badge bg-danger">Late</span>';

                          echo "
                  <tr>
                    <td>{$row['date']}</td>
                    <td>{$row['employee_no']}</td>
                    <td>{$row['full_name']}</td>
                    <td>" . date('h:i A', strtotime($row['scheduled_start'])) . "</td>
                     <td>" . (empty($row['time_in']) ? 'Time In Not Available' : date('h:i A', strtotime($row['time_in']))) . "</td>
                    <td>" . (empty($row['time_out']) ? '' : date('h:i A', strtotime($row['time_out']))) . "</td>
                    <td>{$status}</td>
                    <td>
                      <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['attendanceid'] . "'>
                        <i class='bi bi-pencil'></i>
                      </button>
                     
                    </td>
                  </tr>";
                        }
                      }
                      ?>



                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Monthly Reports -->


        </div>
        <!-- App body ends -->

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>

      </div>
      <!-- App container ends -->

    </div>
    <!-- Main container ends -->

  </div>
  <!-- Page wrapper end -->

  <?php include 'modals/attendance_modal.php'; ?>

  <?php include 'includes/scripts.php'; ?>

</body>

</html>


<script>

  $(document).ready(function () {



    $(document).on('click', '.edit', function (e) {
      e.preventDefault();
      $('#edit').modal('show');
      var id = $(this).data('id');

      getRow(id);
    });

    $(document).on('click', '.delete', function (e) {
      e.preventDefault();
      $('#delete').modal('show');
      var id = $(this).data('id');

      getRow(id);
    });

    function getRow(id) {
      console.log("Sending ID:", id);
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          attid: id
        },
        dataType: 'json',
        success: function (response) {

          if (response) {
            $('#datepicker_edit').val(response.date);
            $('#edit_time_in').val(response.time_in);
            $('#edit_time_out').val(response.time_out);
            $('#attid').val(response.attendanceid);
            $('.employee_name').html(response.full_name);
            $('#del_attid').val(response.attid);
            $('#del_employee_name').html(response.firstname + ' ' + response.lastname);
          } else {
            console.error("Received null response");
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX error:", status, error);
        }
      });
    }
  });

</script>







</body>

</html>