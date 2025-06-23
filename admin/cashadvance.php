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
              <span>Home</span> / <span class="menu-text">Cash advance monitoring</span>
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
                  <h5>Cash advance list</h5>
                  <!-- Add Employee Button -->

                  <!--         
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle"></i>     Add Employee
										</button> -->


                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnew">
                    <i class="bi bi-plus-circle"></i> Add
                  </button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">
                      <thead>
                        <th class="hidden"></th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Date</th>
                      
                     
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>Action</th>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT 
                            cashadvance.cashid AS caid, 
                            employee.employee_no AS empid, 
                            employee.first_name, 
                            employee.last_name, 
                            cashadvance.advance_date, 
                            cashadvance.advance_amount ,
                            cashadvance.remarks
                        FROM cashadvance 
                        LEFT JOIN employee ON employee.employee_id = cashadvance.employee_id 
                      
                        ORDER BY cashadvance.advance_date DESC";
                        $query = $conn->query($sql);
                        if ($query) {
                          while ($row = $query->fetch_assoc()) {
                            echo "
                          <tr>
                              <td class='hidden'></td>
                                <td>" . $row['empid'] . "</td>
                                         <td>" . ucwords($row['first_name']) . ' ' . ucwords($row['last_name']) . "</td>
                              <td>" . date('M d, Y', strtotime($row['advance_date'])) . "</td>
                            
                     
                              <td>" . number_format($row['advance_amount'], 2) . "</td>
                               <td>" . $row['remarks'] . "</td>

                            
                                     <td class='text-center'>
                              <div class='dropdown'>
                                <button class='btn btn-light btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
                                  <i class='bi bi-three-dots-vertical'></i>
                                </button>
                                <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                 
                                  <li>
                                    <button class='dropdown-item  edit ' data-id='" . $row['caid'] . "'><i class='fa fa-edit'></i> Edit</button>
                                  </li>
                                  <li>
                                    
                                    <button class='dropdown-item delete ' data-id='" . $row['caid'] . "'><i class='fa fa-trash'></i> Delete</button>
                                  </li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                      ";
                          }
                        } else {
                          echo "Error: " . $conn->error;
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

        < <!-- App body ends -->

          <!-- App footer start -->
          <?php include 'includes/footer.php'; ?>

          <!-- App footer end -->

      </div>
      <!-- App container ends -->

    </div>
    <!-- Main container end -->

  </div>
  <!-- Page wrapper end -->
  <?php include 'modals/cashadvance_modal.php'; ?>

  <?php include 'includes/scripts.php'; ?>
  
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <!-- <script>
        $(document).ready(function () {
            $('.select2').select2(); // Initialize Select2
        });
    </script> -->

    <script>
        $(document).ready(function () {
            // Autocomplete setup
            $("#employee_name").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "ajax/search_employee.php", // Path to your PHP handler
                        type: "GET",
                        data: { term: request.term },
                        success: function (data) {
                            console.log(data);
                            var parsedData = $.parseJSON(data);
                            response(parsedData);
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching autocomplete data:", error);
                        }
                    });
                },
                appendTo: "#addnew", // Attach dropdown to the modal to avoid z-index issues
                minLength: 2,
                focus: function (event, ui) {
                    $("#employee_name").val(ui.item.label); // Set focused value
                    return false;
                },
                select: function (event, ui) {
                    $("#employee_name").val(ui.item.label); // Set the name input value
                    $("#employee_id").val(ui.item.value); // Set the hidden input value to employee_id
                    console.log("Selected Employee ID:", ui.item.value); // Log the employee_id
                    return false;
                }
            });

            // Clear modal input when modal is hidden
            $('#addnew').on('hidden.bs.modal', function () {
                $("#employee_name").val('');
                $("#employee_id").val(''); // Clear the hidden input when modal is closed
            });
        });
    </script>
  <script>
    $(function() {
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
    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          cashid: id
        },
        dataType: 'json',
        success: function(response) {
          console.log(response);
          $('.date').html(response.date_advance);
          $('.employee_name').html(
    response.first_name.charAt(0).toUpperCase() + response.first_name.slice(1).toLowerCase() + ' ' + 
    response.last_name.charAt(0).toUpperCase() + response.last_name.slice(1).toLowerCase()
);

          $('.caid').val(response.caid);
          $('#edit_amount').val(response.advance_amount);
          $('#edit_remarks').val(response.remarks);
        }
      });
    }
  </script>


</body>

</html>