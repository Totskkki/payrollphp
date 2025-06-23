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
              <span>Home</span> / <span class="menu-text">Overtime Approval</span>
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

        <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5>Overtime</h5>
                  
                </div>


                <div class="card-body">


                  <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <th class="hidden"></th>
                    <th>Date</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>No. of Hours</th>
                   
                    <th>Status</th>
                  
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT *,overtime.status, overtime.overtimeid AS otid, employee.employee_id AS empid,
                    ROUND(overtime.hours, 2) AS formatted_hours
                  FROM overtime
                  LEFT JOIN employee  on employee.employee_id=overtime.employee_id 
                 
                  WHERE overtime.status = 2 
                  ORDER BY date_overtime DESC";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                      $statusBadge = "";
                      if ($row['status'] == 2) {
                        $statusBadge = "<span class='badge bg-success'>Approved</span>";
                     
                      }
                      echo "
                  <tr>
                      <td class='hidden'></td>
                      <td>" . date('M d, Y', strtotime($row['date_overtime'])) . "</td>
                      <td>" . $row['employee_no'] . "</td>
                      <td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>
                      <td>" . $row['formatted_hours'] . "</td>
                   
                      <td>" . $statusBadge . "</td>
                   
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
  <?php include 'includes/scripts.php'; ?>
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
        success: function(response) {
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

  <!-- <script>
    $(document).ready(function() {
      $('.approve').click(function() {
        var leaveId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var approveButton = $(this);
        var disapproveButton = $(this).closest('tr').find('.disapprove');
        var statusBadge = $(this).closest('tr').find('td').eq(5).find('span');

        if (currentStatus == 2) {
          alert("This leave is already approved.");
          return;
        }

        if (confirm("Are you sure you want to approve this leave?")) {
          updateLeaveStatus(leaveId, 2, function() {
            // Update status badge
            statusBadge.removeClass('label-warning label-danger').addClass('label-success').text('Approved');

            approveButton.prop('disabled', true);
            disapproveButton.prop('disabled', true).data('status', 2);
          });
        }
      });

      $('.disapprove').click(function() {
        var leaveId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var approveButton = $(this).closest('tr').find('.approve');
        var disapproveButton = $(this);
        var statusBadge = $(this).closest('tr').find('td').eq(5).find('span');

        if (currentStatus == 2) {
          alert("This leave is already approved and cannot be disapproved.");
          return;
        }

        if (confirm("Are you sure you want to disapprove this leave?")) {
          updateLeaveStatus(leaveId, 1, function() {

            statusBadge.removeClass('label-warning label-warning').addClass('label-warning').text('Rejected');

            approveButton.prop('disabled', false);
            disapproveButton.prop('disabled', true).data('status', 1);
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
          success: function(response) {
            if (response === 'Success') {
              if (typeof callback === 'function') {
                callback();
              }
            } else {
              alert(response);
            }
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
          }
        });
      }
    });
  </script> -->


  <script>
    $(document).ready(function() {
      $('.approve').click(function() {
        var leaveId = $(this).data('id');
        var approveButton = $(this);
        var disapproveButton = $(this).closest('tr').find('.disapprove');
        var statusBadge = $(this).closest('tr').find('td').eq(6).find('span');

        if (confirm("Are you sure you want to approve this overtime?")) {
          updateLeaveStatus(leaveId, 2, function() {
            // Update status badge
            statusBadge.removeClass('label-warning label-danger').addClass('label-success').text('Approved');

            // Hide the buttons
            approveButton.hide();
            disapproveButton.hide();
          });
        }
      });

      $('.disapprove').click(function() {
        var leaveId = $(this).data('id');
        var disapproveButton = $(this);
        var approveButton = $(this).closest('tr').find('.approve');
        var statusBadge = $(this).closest('tr').find('td').eq(6).find('span');

        if (confirm("Are you sure you want to disapprove this overtime?")) {
          updateLeaveStatus(leaveId, 1, function() {
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
          success: function(response) {
            if (response === 'Success') {
              if (typeof callback === 'function') {
                callback();
              }
            } else {
              alert(response);
            }
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred. Please try again.");
          }
        });
      }
    });
  </script>


  <script>
    $(document).ready(function() {
      $('#Calculate').on('click', function() {
        var maxHours = parseFloat($('#maxhours').val());
        var hours = parseFloat($('#hours').val());
        var mins = parseFloat($('#mins').val());
        var rate = parseFloat($('#rate').val());


        if (isNaN(maxHours)) maxHours = 0;
      if (isNaN(hours)) hours = 0;
      if (isNaN(mins)) mins = 0;
      if (isNaN(rate)) rate = 0;

        // Ensure overtime hours do not exceed the maximum allowed
        var totalHours = hours + (mins / 60);
        if (totalHours > maxHours) {
          totalHours = maxHours;
        }

        // Calculate total compensation
        var totalCompensation = totalHours * rate;

        $('#total_compensation').val('₱' + totalCompensation.toFixed(2));
      });
    });
  </script>


  <!-- <script>
    $(function() {
      $.validator.setDefaults({
        submitHandler: function() {
          alert("Form successful submitted!");
        }
      });
      $('#overtime').validate({
        rules: {
          employee: {
            required: true,
            employee: true,
          },
          datepicker_add: {
            required: true,
            datepicker_add: true,
          },
          maxhours: {
            required: true,
            maxhours: true,
          },
          hours: {
            required: true,
            hours: true,
          },
          mins: {
            required: true,
            min: 0,
          },
          rate: {
            required: true,
            rate: true,
          },
        },
        messages: {
          employee: {
            required: "Please seelect employee",
            employee: "Please seelect employee"
          },
          datepicker_add: {
            required: "Please provide a date",
            datepicker_add: "Please provide a date"
          },
          maxhours: {
            required: "Please provide a maximum hours",
            maxhours: "Please provide a maximum hours"
          },
          hours: {
            required: "Please provide a hours",
            hours: "Please provide a hours"
          },
          mins: {
            required: "Please provide a mins",
            min: "Please provide a mins"
          },
          rate: {
            required: "Please provide a rate",
            rate: "Please provide a rate"
          },

        },
        errorElement: 'span',
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
    });
  </script> -->

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