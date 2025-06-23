<?php include 'includes/session.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);


?>


<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
  $year = $_GET['year'];
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
            <!--<a href="index.html">-->
            <!--  <img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">-->
            <!--</a>-->
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
              <span>Home</span> / <span class="menu-text">Schedule List</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>
        <!-- App Hero header ends -->

        <!-- App body starts -->
        <div class="app-body">

          <?php
          if (isset($_SESSION['error'])) {
            echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert' id='errorAlert'>
        <i class='fa fa-exclamation-circle me-2'></i>
        <strong>Error!</strong> {$_SESSION['error']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
            unset($_SESSION['error']);
          }
          if (isset($_SESSION['success'])) {
            echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert' id='successAlert'>
        <i class='fa fa-check-circle me-2'></i>
        <strong>Success!</strong> {$_SESSION['success']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
            unset($_SESSION['success']);
          }
          ?>



          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                  <h5>Schedule List</h5>
                  <!-- Add Employee Button -->


                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnew">
                  <i class="bi bi-plus-circle"></i>  Add Schedule
                  </button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">
                      <thead>
                      
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Action</th>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT * FROM schedules";
                        $query = $conn->query($sql);
                        while ($row = $query->fetch_assoc()) {
                          echo "
                        <tr>
                        
                          <td>" . date('h:i A', strtotime($row['scheduled_start'])) . "</td>
                          <td>" . date('h:i A', strtotime($row['scheduled_end'])) . "</td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['scheduleid'] . "'><i class='bi bi-pencil'></i> </button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='" . $row['scheduleid'] . "'><i class='bi bi-trash'></i> </button>
                          </td>
                        </tr>
                      ";
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
        <?php include 'modals/schedule_modal.php'; ?>
        <!-- App footer end -->

      </div>
      <!-- App container ends -->

    </div>
    <!-- Main container end -->

  </div>
  <!-- Page wrapper end -->


  <?php include 'includes/scripts.php'; ?>

  <script>
    $(function () {
      $('.edit').click(function (e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $('.delete').click(function (e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });
    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: { sched: id },
        dataType: 'json',
        success: function (response) {
          $('#timeid').val(response.scheduleid);
          $('#edit_time_in').val(response.scheduled_start);
          $('#edit_time_out').val(response.scheduled_end);
          $('#del_timeid').val(response.scheduleid);
          $('#del_schedule').html(response.time_in + ' - ' + response.time_out);
        }
      });
    }
  
  </script>
</body>

</html>