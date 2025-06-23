<?php include 'includes/session.php'; ?>

<?php
$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 days', strtotime($range_to)));


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>

<style>
  .stats-info {
    padding: 20px;
  }

  .stats-info h6 {
    font-size: 14px;
    text-transform: uppercase;
    margin-bottom: 10px;
  }

  .stats-info h4 {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
  }

  .stats-info span {
    font-size: 12px;
    font-weight: normal;
  }

  .card {
    border: none;
    border-radius: 8px;
  }

  
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
              <span>Home</span> / <span class="menu-text">Overtime Request</span>
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
          <?php

          $overtimeEmployees = 0;
          $totalOvertimeHours = 0;
          $formattedOvertimeHours = 0;
          $pendingRequests = 0;
          $rejectedRequests = 0;


          $sql = "SELECT overtimeid, employee_id, hours, rate, date_overtime, status FROM overtime";

          $query = $conn->query($sql);

          if ($query === false) {
            echo "Error: " . $conn->error;
            exit;
          }
          $uniqueEmployeeIds = [];

          while ($row = $query->fetch_assoc()) {

            if (!in_array($row['employee_id'], $uniqueEmployeeIds)) {
              $uniqueEmployeeIds[] = $row['employee_id'];
            }


            $totalOvertimeHours += $row['hours'];
            $formattedOvertimeHours = number_format($totalOvertimeHours, 1);


            if ($row['status'] == 0) {
              $pendingRequests++;
            }

            if ($row['status'] == 1) {
              $rejectedRequests++;
            }
          }


          $overtimeEmployees = count($uniqueEmployeeIds);

          ?>
          <!-- Overtime Statistics -->
          <div class="row d-flex justify-content-around flex-nowrap overflow-auto">
            <!-- Overtime Employees -->
            <div class="col-12 col-md-3 mb-3">
              <div class="card text-white bg-primary shadow-sm">
                <div class="card-body stats-info text-center">
                  <i class="bi bi-people fs-2 mb-2"></i>
                  <h6 class="fw-bold">Overtime Employees</h6>
                  <h4><?php echo $overtimeEmployees; ?> <span class="fs-6">this month</span></h4>
                </div>
              </div>
            </div>

            <!-- Overtime Hours -->
            <div class="col-12 col-md-3 mb-3">
              <div class="card text-white bg-success shadow-sm">
                <div class="card-body stats-info text-center">
                  <i class="bi bi-clock fs-2 mb-2"></i>
                  <h6 class="fw-bold">Overtime Hours</h6>
                  <h4><?php echo $formattedOvertimeHours; ?> <span class="fs-6">this month</span></h4>
                </div>
              </div>
            </div>

            <!-- Pending Requests -->
            <div class="col-12 col-md-3 mb-3">
              <div class="card text-white bg-warning shadow-sm">
                <div class="card-body stats-info text-center">
                  <i class="bi bi-hourglass-split fs-2 mb-2"></i>
                  <h6 class="fw-bold">Pending Requests</h6>
                  <h4><?php echo $pendingRequests; ?></h4>
                </div>
              </div>
            </div>

            <!-- Rejected Requests -->
            <div class="col-12 col-md-3 mb-3">
              <div class="card text-white bg-danger shadow-sm">
                <div class="card-body stats-info text-center">
                  <i class="bi bi-x-circle fs-2 mb-2"></i>
                  <h6 class="fw-bold">Rejected</h6>
                  <h4><?php echo $rejectedRequests; ?></h4>
                </div>
              </div>
            </div>
          </div>
       



          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5>Overtime</h5>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnew">
                    <i class="bi bi-plus-circle"></i> Add
                  </button>
                </div>


                <div class="card-body">


                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    
                      <th>Date</th>
                      <th>Employee ID</th>
                      <th>Name</th>
                      <th>No. of Hours</th>
                      <th>Total</th>
                      <th>Status</th>
                  
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT *,overtime.status, overtime.overtimeid AS otid, employee.employee_id AS empid,employee.*,
                    ROUND(overtime.hours, 2) AS formatted_hours
                  FROM overtime
                  LEFT JOIN employee ON employee.employee_id=overtime.employee_id 
                
                  where overtime.status = 2
                  ORDER BY date_overtime DESC";
                      $query = $conn->query($sql);
                      while ($row = $query->fetch_assoc()) {
                        $statusBadge = "";
                        if ($row['status'] == 2) {
                          $statusBadge = "<span class='badge bg-success'>Approved</span>";

                        }
                        echo "
                  <tr>
                     
                      <td>" . date('M d, Y', strtotime($row['date_overtime'])) . "</td>
                      <td>" . $row['employee_no'] . "</td>
                      <td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>
                      <td>" . $row['formatted_hours'] . "</td>
                      <td>" . $row['total_compensation'] . "</td>
                      <td>" . $statusBadge . "</td>
                      ";
                        
                        
                        echo "
                     
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

      <!-- App footer end -->

    </div>
    <!-- App container ends -->

  </div>
  <!-- Main container end -->

  </div>
  <!-- Page wrapper end -->



  <?php include 'modals/overtime_modal.php'; ?>

  <?php include 'includes/scripts.php'; ?>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


 
 
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

        // Fetch the employee's rate per day from the database
        $.ajax({
          url: "ajax/get_employee_rate.php", // Path to your PHP file
          type: "GET",
          data: { employee_id: ui.item.value },
          success: function (data) {
            // Assuming the response contains a JSON object with the rate_per_day
            var employeeData = $.parseJSON(data);
            var ratePerDay = employeeData.rate_per_hour;

            // Calculate hourly rate (rate per day / 8 hours)
            var hourlyRate = ratePerDay / 8;

            // Set the rate in the input field
            $('#rate').val(hourlyRate.toFixed(2)); // Display the hourly rate
          },
          error: function (xhr, status, error) {
            console.error("Error fetching employee rate:", error);
          }
        });

        return false;
      }
    });

    // Clear modal input when modal is hidden
    $('#addnew').on('hidden.bs.modal', function () {
      $("#employee_name").val('');
      $("#employee_id").val(''); // Clear the hidden input when modal is closed
      $("#rate").val(''); // Clear the rate field
    });

    // Calculate total compensation when Calculate button is clicked
   
  });
</script>
 
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
        console.log(id);
      });


    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          overtime: id
        },
        dataType: 'json',
        success: function (response) {
          var time = response.hours;
          var split = time.split('.');
          var hour = split[0];
          var min = '.' + split[1];
          min = min * 60;
          console.log(min);
          $('.employee_name').html(response.firstname + ' ' + response.lastname);
          $('.otid').val(response.otid);
          $('#datepicker_edit').val(response.date_overtime);
          $('#overtime_date').html(response.date_overtime);
          $('#hours_edit').val(hour);
          $('#mins_edit').val(min);
          $('#rate_edit').val(response.rate);
        }
      });
    }
  </script>



  <script>
    $(document).ready(function () {
      $('.approve').click(function () {
        var leaveId = $(this).data('id');
        var approveButton = $(this);
        var disapproveButton = $(this).closest('tr').find('.disapprove');
        var statusBadge = $(this).closest('tr').find('td').eq(6).find('span');

        if (confirm("Are you sure you want to approve this overtime?")) {
          updateLeaveStatus(leaveId, 2, function () {
            // Update status badge
            statusBadge.removeClass('label-warning label-danger').addClass('label-success').text('Approved');

            // Hide the buttons
            approveButton.hide();
            disapproveButton.hide();
          });
        }
      });

      $('.disapprove').click(function () {
        var leaveId = $(this).data('id');
        var disapproveButton = $(this);
        var approveButton = $(this).closest('tr').find('.approve');
        var statusBadge = $(this).closest('tr').find('td').eq(6).find('span');

        if (confirm("Are you sure you want to disapprove this overtime?")) {
          updateLeaveStatus(leaveId, 1, function () {
            // Update status badge
            statusBadge.removeClass('label-warning label-success').addClass('label-danger').text('Rejected');

            // Hide the buttons
            approveButton.hide();
            disapproveButton.hide();
          });
        }
      });

      function updateLeaveStatus(leaveId, status, callback) {
        $.ajax({
          url: 'ajax/overtime_status.php',
          type: 'POST',
          data: {
            leaveid: leaveId,
            status: status
          },
          success: function (response) {
            if (response === 'Success') {
              if (typeof callback === 'function') {
                callback();
              }
            } else {
              alert(response);
            }
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
          }
        });
      }
    });
  </script>


  <script>
    $(document).ready(function () {
      $('#Calculate').on('click', function () {
        var maxHours = parseFloat($('#maxhours').val()); // Maximum allowed overtime hours
        var hours = parseFloat($('#hours').val()); // Overtime hours entered
        var mins = parseFloat($('#mins').val()); // Minutes entered
        var rate = parseFloat($('#rate').val()); // Rate per hour

        // Handle NaN values
        if (isNaN(maxHours)) maxHours = 0;
        if (isNaN(hours)) hours = 0;
        if (isNaN(mins)) mins = 0;
        if (isNaN(rate)) rate = 0;

        // Convert minutes to hours
        var minsToHours = mins / 60;

        // Calculate total hours worked
        var totalHours = hours + minsToHours;

        // Cap total hours to maxHours
        if (totalHours > maxHours) {
            totalHours = maxHours;
        }

        // Calculate total compensation based on capped hours
        var totalCompensation = totalHours * rate;

        // Display total compensation
        $('#total_compensation').val('₱' + totalCompensation.toFixed(2));
    });
    });
  </script>

  <script>
    $(function () {


      $('#overtime').validate({
        rules: {
          employee: {
            required: true,
          },
          date: {
            required: true,
          },
          maxhours: {
            required: true,
          },
          hours: {
            required: true,
          },
          mins: {
            required: true,
            min: 0,
          },
          rate: {
            required: true,
          },
        },
        messages: {
          employee: {
            required: "Please select an employee",
          },
          date: {
            required: "Please provide a date",
          },
          maxhours: {
            required: "Please provide maximum hours",
          },
          hours: {
            required: "Please provide hours",
          },
          mins: {
            required: "Please provide minutes",
            min: "Please provide valid minutes",
          },
          rate: {
            required: "Please provide a rate",
          },
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });

      // Calculate total compensation

    });
  </script>

</body>

</html>