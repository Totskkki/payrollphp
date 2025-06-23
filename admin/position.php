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
              <span>Home</span> / <span class="menu-text">Position List</span>
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

          <!-- Row start -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                  <h5>Position List</h5>
                  <!-- Add Employee Button -->


                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnew">
                    <i class="bi bi-plus-circle"></i> Add Position
                  </button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">

                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Department</th>
                          <th>Position</th>
                          <th>Rate per Hour</th>
                          <th>Fixed</th>
                          <th>Action</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $sql = "SELECT * FROM position
                                    LEFT JOIN department ON department.depid = position.departmentid
                                    ORDER BY positionid DESC";
                        $query = $conn->query($sql);

                        $counter = 1;

                        while ($row = $query->fetch_assoc()) {
                          echo "
                                <tr>
                                    <td>" . $counter++ . "</td> <!-- Auto-increment column -->
                                    <td>" . $row['department'] . "</td>
                                    <td>" . $row['position'] . "</td>
                                    <td>" . number_format($row['rate_per_hour'] ?? 0, 2) . "</td>
                                     <td>" . number_format($row['pakyawan_rate'] ?? 0, 2) . "</td>
                                    <td>
                                        <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['positionid'] . "'>
                                            <i class='bi bi-pencil'></i>
                                        </button>
                                        <button class='btn btn-danger btn-sm delete btn-flat' data-id='" . $row['positionid'] . "'>
                                            <i class='bi bi-trash'></i>
                                        </button>
                                    </td>
                                </tr>";
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
  <!-- Page wrapper end -->

  <?php include 'modals/position_modal.php'; ?>
  <?php include 'includes/scripts.php'; ?>


  <script>
    $(function () {
      // Open edit modal
      $('.edit').click(function (e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
        console.log(id);
      });

      // Open delete modal
      $('.delete').click(function (e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
        console.log(id);
      });
    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          posid: id
        },
        dataType: 'json',
        success: function (response) {
          console.log('Response:', response); // Log the response data
          console.log('Department from response:', response.department);

          // Fill the form fields with the fetched data
          $('#posid').val(response.positionid);
          $('#edit_val').val(response.departmentid).html(response.department); // set the department ID
          $('#edit_title').val(response.position);
          $('#edit_rate').val(response.rate_per_hour);
          $('#edit_pakyawan').val(response.pakyawan_rate);
          $('#del_posid').val(response.positionid);
          $('#del_position').html(response.position);

          // Call function to update visibility based on department
          updateRateVisibility(response.department);

        }
      });
    }

    function updateRateVisibility(department) {
      console.log('Department:', department);

      // Use classes instead of IDs
      const ratePerHourContainer = $('.ratePerHourContainer');
      const pakyawanRateContainer = $('.pakyawanRateContainer');
      const ratePerHourInput = $('#edit_rate');
      const pakyawanRateInput = $('#edit_pakyawan');

      // Use case-insensitive comparison for department
      if (department && department.toLowerCase() === 'pakyawan') {
        console.log('Hiding Rate per Hour and showing Pakyawan Rate');

        // Hide rate per hour and show pakyawan rate
        ratePerHourContainer.hide();
        pakyawanRateContainer.show();

        // Adjust the 'required' attribute accordingly
        ratePerHourInput.removeAttr('required');
        pakyawanRateInput.attr('required', 'true');
      } else {
        console.log('Showing Rate per Hour and hiding Pakyawan Rate');

        // Show rate per hour and hide pakyawan rate
        ratePerHourContainer.show();
        pakyawanRateContainer.hide();

        // Adjust the 'required' attribute accordingly
        ratePerHourInput.attr('required', 'true');
        pakyawanRateInput.removeAttr('required');
      }
    }

    // For department selection toggle in 'addnew' modal
    document.addEventListener('DOMContentLoaded', function () {
      const departmentSelect = document.getElementById('departid');
      const ratePerHourContainer = document.getElementById('ratePerHourContainer');
      const pakyawanRateContainer = document.getElementById('pakyawanRateContainer');
      const ratePerHourInput = document.getElementById('rate_per_hour');

      departmentSelect.addEventListener('change', function () {
        const selectedDepartment = departmentSelect.options[departmentSelect.selectedIndex].text;
        updateRateVisibility(selectedDepartment);
      });
    });
  </script>



</body>

</html>