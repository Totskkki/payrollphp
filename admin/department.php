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
              <span>Home</span> / <span class="menu-text">Department List</span>
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

          <!-- Row start -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                  <h5>Department List</h5>
                  <!-- Add Employee Button -->


                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnew">
                  <i class="bi bi-plus-circle"></i>    Add Department
                  </button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">
                      <?php
                      // Database query to fetch user data along with related information
                      $sql = "SELECT *  from position";

                      $query = $conn->query($sql);
                      ?>
                      <thead>
                        <tr>
                          <th>Department Title</th>
                          <th>Description</th>
                     
                          <th>Action</th>
                        </tr>
                      </thead>
                    
                      <tbody>
                        <?php
                        $sql = "SELECT * FROM department ORDER BY depid DESC";
                        $query = $conn->query($sql);
                        while ($row = $query->fetch_assoc()) {
                          echo "
                        <tr>
                        <td>" . $row['department'] . "</td>
                          <td>" . $row['description'] . "</td>
                    
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat  btn-flat' data-id='" . $row['depid'] . "'><i class='bi bi-pencil'></i> </button>
                            <button class='btn btn-danger btn-sm delete btn-flat btn-flat' data-id='" . $row['depid'] . "'><i class='bi bi-trash'></i> </button>
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
        <?php include 'modals/department_modal.php'; ?>
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
        console.log(id);
      });

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
          depid: id
        },
        dataType: 'json',
        success: function (response) {
          console.log(response);
          $('#depid').val(response.depid);
          $('#edit_dep').val(response.department);
          $('#edit_title').val(response.description);  
          $('#del_posid').val(response.depid);
          $('#del_position').html(response.description);


        }


      });
    }
  </script>

 


<script>
	document.getElementById('addPositionButton').addEventListener('click', function() {
		const positionContainer = document.getElementById('positionContainer');
		const newPositionGroup = document.createElement('div');
		newPositionGroup.classList.add('position-group');

		// Clone the first position group and reset its values
		const firstPositionGroup = positionContainer.querySelector('.position-group');
		const clonedPositionGroup = firstPositionGroup.cloneNode(true);
		clonedPositionGroup.querySelector('input[name="title[]"]').value = '';
		clonedPositionGroup.querySelector('input[name="rate[]"]').value = '';

		// Append the new cloned group to the container
		positionContainer.appendChild(clonedPositionGroup);
	});
</script>

</body>

</html>