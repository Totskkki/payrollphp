<?php include 'includes/session.php'; ?>
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
            <a href="index.html">
              <img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">
            </a>
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
              <span>Home</span> / <span class="menu-text">User accounts</span>
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
        <h5>User List</h5>
        <!-- Add Employee Button -->
       
        
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adduser">
                    <i class="bi bi-plus-circle"></i>     Add User
										</button>
        </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">
                      <?php
                      // Database query to fetch user data along with related information
                  
                      ?>
                      <thead>
                        <tr>
                          <th>User Id</th>

                          <th>Photo</th>
                          <th>Name</th>
                          <th>Address</th>
                          <th>UserType</th>                 
                          <th>Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        


                          
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
        <?php include 'modals/employee_modal.php';?>
        <!-- App footer end -->

      </div>
      <!-- App container ends -->

    </div>
    <!-- Main container end -->

  </div>
  <!-- Page wrapper end -->

  <?php include 'modals/users_modal.php';?>
  <?php include 'includes/scripts.php'; ?>


  <script>
  $(document).ready(function () {
    $('.form-check-input').change(function () {
      var empId = $(this).data('id');
      var newStatus = $(this).is(':checked') ? 'active' : 'inactive';
      var statusBadge = $(this).siblings('.badge');

      $.ajax({
        url: 'ajax/status.php',
        type: 'POST',
        data: {
          empid: empId,
          status: newStatus
        },
        success: function (response) {
          if (response === 'success') {
            // Update badge class and text based on the new status
            statusBadge
              .removeClass('bg-success bg-danger')
              .addClass(newStatus === 'active' ? 'bg-success' : 'bg-danger')
              .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

            alert('Status updated successfully!');
          } else {
            alert('Failed to update status.');
            // Revert the checkbox to its previous state
            $(this).prop('checked', !$(this).is(':checked'));
          }
        }.bind(this),
        error: function () {
          alert('An error occurred while updating status.');
          // Revert the checkbox to its previous state
          $(this).prop('checked', !$(this).is(':checked'));
        }.bind(this)
      });
    });
  });
</script>


<script>

    $(function() {
      $('.view').click(function(e) {
        e.preventDefault();
        $('#view').modal('show');
        var id = $(this).data('id');
        getRow(id);
        getDeductions(id);     
      });
      $('.edit').click(function(e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $('.delete').click(function(e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $('.photo').click(function(e) {
        e.preventDefault();
        $('#edit_photo').modal('show');
        var id = $(this).data('id');
        getRow(id);
        
      });


    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          emp: id
        },
        dataType: 'json',
        success: function(response) {
          $('.empid').val(response.empid);
          $('.names_id').val(response.namesid);
            $('.address_id').val(response.addressid);
          $('.employee_id').html(response.employeeid);
          $('.del_employee_name').html(response.firstname + ' ' + response.lastname);
          $('#employee_name').html(response.firstname + ' ' + response.lastname);
          $('#edit_firstname').val(response.firstname);
          $('#edit_middlename').val(response.middlename);
          $('#edit_lastname').val(response.lastname);
          $('#edit_suffix').val(response.name_extension);
          $('#edit_purok').val(response.purok);
          $('#edit_brgy').val(response.brgy);
          $('#edit_city').val(response.city);
          $('#edit_province').val(response.province);
          $('#datepicker_edit').val(response.birthdate);
          $('#edit_contact').val(response.contact_info);
          $('#gender_val').val(response.gender).html(response.gender);
          $('#position_val').val(response.position_id).html(response.department);
          $('#schedule_val').val(response.schedule_id).html(response.time_in + ' - ' + response.time_out);
        }
      });
      
    }
  </script>
  





</script>
</body>


</html>