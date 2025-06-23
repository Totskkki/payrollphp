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
              <span>Home</span> / <span class="menu-text">Employee records</span>
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
                  <h5>Employee List</h5>

                  <div id="notification" style="text-align: center; margin-top: 10px; color: green; font-size: 18px;">
                  </div>
                  <a href="employee_add.php" type="button" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Employee
                  </a>


                </div>





                <div class="card-body">
                  <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="filterDropdown"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      Filter Employees
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                      <li><a class="dropdown-item" href="?filter=active">Active Employees</a></li>
                      <li><a class="dropdown-item" href="?filter=archived">Archived Employees</a></li>
                    </ul>
                  </div>

                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">

                      <thead>
                        <tr>
                          <th>EMPLOYEE ID</th>
                          <th>Photo</th>
                          <th>Name</th>
                          <th>Department</th>
                          <th>Position</th>
                          <th>Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <?php
                      $filter = isset($_GET['filter']) ? $_GET['filter'] : 'active';
                      $is_archived = ($filter === 'archived') ? 1 : 0;

                      $sql = "SELECT u.*, u.employee_id AS empid, addr.*, ed.*, d.*, p.*, 
                      d.department AS dep, p.position AS pos,
                      CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS `full_name`,
                      CONCAT(addr.street, ', ', addr.city, ', ', addr.province) AS full_address        
                      FROM employee u
                      LEFT JOIN employee_details ed ON ed.employee_id = u.employee_id 
                      LEFT JOIN department d ON d.depid = ed.departmentid 
                      LEFT JOIN position p ON p.positionid = ed.positionid  
                      LEFT JOIN `address` addr ON addr.addressid = u.employee_id 
                      LEFT JOIN schedules ON schedules.scheduleid = ed.scheduleid
                      WHERE u.is_archived = ?
                      ORDER BY u.created_on DESC"; // Order by the created_at field for the latest employees
                      
                      $stmt = $conn->prepare($sql);
                      $stmt->bind_param("s", $is_archived);
                      $stmt->execute();
                      $query = $stmt->get_result();
                      ?>

                      <tbody>
                        <?php while ($row = $query->fetch_assoc()) { ?>
                          <tr>
                            <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                            <td>
                              <img
                                src="<?php echo (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/profile.jpg'; ?>"
                                width="30px" height="30px">
                              <a href="#edit_photo" data-toggle="modal" class="pull-right photo"
                                data-id="<?php echo $row['empid']; ?>">
                                <span class="fa fa-edit"></span>
                              </a>
                            </td>
                            <td><?php echo htmlspecialchars(ucwords($row['full_name'])); ?></td>
                            <td><?php echo htmlspecialchars($row['dep']); ?></td>
                            <td><?php echo htmlspecialchars($row['pos']); ?></td>
                            <td>
                              <span class="badge bg-<?php echo ($row['is_archived'] == 1) ? 'warning' : 'success'; ?>">
                                <?php echo ($row['is_archived'] == 1) ? 'Archived' : 'Active'; ?>
                              </span>
                            </td>
                            <td class="text-center">
                              <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                  data-bs-toggle="dropdown">
                                  <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                  <li>
                                    <a class="dropdown-item" href="employee_details.php?id=<?php echo $row['empid']; ?>">
                                      <i class="bi bi-eye"></i> View Details
                                    </a>
                                  </li>
                                  <?php if ($row['is_archived'] == 0) { ?>
                                    <li>
                                      <a class="dropdown-item" href="employee_edit.php?id=<?php echo $row['empid']; ?>">
                                        <i class="bi bi-pencil"></i> Edit
                                      </a>
                                    </li>
                                  <?php } else { ?>
                                    <li>
                                      <a class="dropdown-item disabled">
                                        <i class="bi bi-pencil"></i> Edit (Disabled)
                                      </a>
                                    </li>
                                  <?php } ?>
                                  <?php if ($row['is_archived'] == 0) { ?>
                                    <li>
                                      <button class="dropdown-item delete" data-id="<?php echo $row['empid']; ?>">
                                        <i class="bi bi-archive"></i> Archive
                                      </button>
                                    </li>
                                  <?php } else { ?>
                                    <li>
                                      <button class="dropdown-item unarchive" data-id="<?php echo $row['empid']; ?>">
                                        <i class="bi bi-arrow-up-circle"></i> Unarchive
                                      </button>
                                    </li>
                                  <?php } ?>
                                </ul>
                              </div>
                            </td>
                          </tr>
                        <?php } ?>
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
  <?php include 'modals/employee_modal.php'; ?>

  <?php include 'includes/scripts.php'; ?>

  <!-- 
  <script>
    function showNotification(message, color) {
      const notification = document.getElementById("notification");

      // Update notification text and style
      notification.textContent = message;
      notification.style.color = color;

      // Show the notification for a limited time
      notification.style.display = "block";
      setTimeout(() => {
        notification.style.display = "none";
      }, 5000); // Hide after 5 seconds
    }

    $(document).ready(function () {
      $('.form-check-input').change(function () {
        var empId = $(this).data('id');
        var newStatus = $(this).is(':checked') ? 'active' : 'inactive';
        var statusBadge = $(this).siblings('.badge');
        var checkbox = $(this); // Cache the checkbox for use in error/success handling

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

              showNotification(`Status updated to ${newStatus}!`, "green");
            } else {
              showNotification("Failed to update status. Please try again.", "red");
              // Revert the checkbox to its previous state
              checkbox.prop('checked', !checkbox.is(':checked'));
            }
          },
          error: function () {
            showNotification("An error occurred while updating status. Please try again later.", "red");
            // Revert the checkbox to its previous state
            checkbox.prop('checked', !checkbox.is(':checked'));
          }
        });
      });
    });
  </script> -->



  <script>
    $(function () {


      $(document).on('click', '.photo', function (e) {
        e.preventDefault();

        $('#edit_photo').modal('show');

        var id = $(this).data('id');
        getRow(id); // Fetch data using the employee ID
        console.log('Photo Edit Clicked for ID: ', id); // Debugging log
      });

      $(document).on('click', '.delete', function (e) {
        e.preventDefault();


        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
        console.log('Archieve Clicked for ID: ', id); // Debugging log
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
        success: function (response) {

          $('.empid').val(response.empid); // Changed to 'empid' for consistency
          $('.names_id').val(response.namesid);
          $('.address_id').val(response.addressid);
          $('.employee_id').html(response.empid);
          $('.del_employee_name').html(response.full_name);
        },
        error: function (xhr, status, error) {
          console.error('AJAX Error: ', status, error);
          alert('Failed to fetch employee details. Please try again.');
        }
      });
    }
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.unarchive').forEach(button => {
        button.addEventListener('click', function () {
          const employeeId = this.getAttribute('data-id');
          if (confirm('Are you sure you want to unarchive this employee?')) {
            fetch('ajax/unarchive_employee.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({ employee_id: employeeId })
            })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  alert('Employee successfully unarchived.');
                  location.reload();
                } else {
                  alert('Failed to unarchive employee.');
                }
              })
              .catch(error => console.error('Error:', error));
          }
        });
      });
    });
  </script>


</body>


</html>