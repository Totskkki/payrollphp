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
              <span>Home</span> / <span class="menu-text">Employee attendance report </span>
            </h3>
          </div>
        </div>
        <!-- App hero header ends -->

        <!-- App body -->
        <div class="app-body">

          <!-- PHP alert messages -->

          <?php include 'flash_messages.php'; ?>

          <!-- Main content area -->
          <div class="row ">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">

                  <!-- Add Employee Button -->
                </div>

                <div class="card-body">
                  <!-- Filters -->
                  <div class="row mb-3">
                    <div class="col-md-2">
                      <label for="department">Employees By Department</label>
                      <select name="department" id="department" class="form-control">
                        <option value="" selected>- All employees -</option>
                        <?php
                        $sql = "SELECT *,department.* FROM position
                              LEFT JOIN department on position.departmentid = department.depid                 
                        
                        ";
                        $query = $conn->query($sql);
                        while ($prow = $query->fetch_assoc()) {
                          echo "<option value='" . $prow['department'] . "'>" . $prow['department'] . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="attendance_date">Date</label>
                      <input type="text" class="form-control datepickeradd " id="attendance_date" name="attendance_date">
                    </div>
                    <div class="col-md-2">
                      <label>&nbsp;</label>
                      <button type="button" class="btn btn-primary w-100" id="fetch_attendance">Attendance List</button>
                    </div>
                    <div class="col-md-2">
                      <label for="year">Year</label>
                      <select id="year" class="form-control">
                        <?php
                        $currentYear = date("Y");
                        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                          echo "<option value=\"$i\">$i</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="month">Month</label>
                      <select id="month" class="form-control">
                        <?php
                        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        foreach ($months as $key => $month) {
                          echo "<option value=\"" . ($key + 1) . "\">$month</option>";
                        }
                        ?>
                      </select>
                    </div>

                    <div class="col-md-2">
                      <label>&nbsp;</label>
                      <button type="button" id="fetch_reports" class="btn btn-primary w-100">Show Reports</button>
                    </div>

                    <div class="box-header with-border mt-3">
                      <div class="pull-right">
                        <div class="col-auto float-right ml-auto">
                          <div class="btn-group btn-group-sm">
                            <button class="btn btn-primary" id="exportCSV">Export CSV</button>



                          </div>
                          <div class="btn-group btn-group-sm">
                            <button class="btn btn-secondary" id="print"><i class="bi bi-printer"></i> Print</button>



                          </div>

                        </div>
                      </div>

                    </div>



                  </div>
                  <!-- Export button -->

                  <!-- Attendance Table -->
                  <table id="example1" class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="hidden"></th>
                        <th>Date</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="attendance_table_body"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>



          <!-- Monthly Reports -->
          <div class="row">
            <div class="col-xs-12">
              
                
                  <h3><b>MONTHLY REPORTS</b></h3>
                </div>
                <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">

                  <!-- Add Employee Button -->
                </div>

                <div class="card-body">



                  <div class="row mb-3">
                  

                    <div class="box-header with-border mt-3">
                      <div class="pull-right">
                        <div class="col-auto float-right ml-auto">
                          <div class="btn-group btn-group-sm">
                            <button class="btn btn-primary" id="export_csv">Export CSV</button>



                          </div>
                          <div class="btn-group btn-group-sm">
                            <button class="btn btn-secondary" id="printmonthly"><i class="bi bi-printer"></i> Print</button>



                          </div>

                        </div>
                      </div>

                    </div>



                  </div>
               
                  <table id="example2" class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="hidden"></th>
                        <th>Date</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id="reports"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- App body ends -->

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
        <?php include 'modals/attendance_modal.php'; ?>

      </div>
      <!-- App container ends -->

    </div>
    <!-- Main container ends -->

  </div>
  <!-- Page wrapper end -->

  <!-- Employee modal -->
  <?php include 'modals/employee_modal.php'; ?>
  <?php include 'includes/scripts.php'; ?>

</body>

</html>


<script>
  $(document).ready(function() {


    // Function to fetch attendance data
    function fetchAttendance() {
      var department = $('#department').val();
      var date = $('#attendance_date').val();

      // if (!date) {
      //   alert('Please select a date');
      //   return;
      // }

      $.ajax({
        url: 'ajax/fetch_attendance.php',
        type: 'POST',
        data: {
          department: department,
          date: date
        },
        dataType: 'json',
        success: function(response) {
          var tbody = $('#attendance_table_body');
          tbody.empty(); // Clear previous data

          if (response.error) {
            alert(response.error);
            return;
          }

          if (response.length > 0) {
            $.each(response, function(index, row) {
              var status = '';
              var lateness = '';
              var time_in = 'N/A';
              var time_out = 'N/A';
              if (row.attendance_record) {
                status = '<span class="label label-success">Present</span>';

                time_in = formatTime(row.attendance_record.time_in);

                if (row.attendance_record.time_out && row.attendance_record.time_out != '00:00:00') {
                  time_out = formatTime(row.attendance_record.time_out);
                  if (row.attendance_record.isUndertime) {
                    time_out += ' (Undertime)';
                  }
                } else {
                  time_out = 'N/A';
                }
                lateness = row.attendance_record.isLate ? '<span class="label label-warning">Late</span>' : '';
              } else if (row.leave_record) {
                status = '<span class="label label-info">On Leave</span>';
                var time_in = 'N/A';
                var time_out = 'N/A';
                lateness = '';

              } else {
                status = '<span class="label label-danger">Absent</span>';
                lateness = '<span class="label label-warning">Late</span>';
                var time_in = 'N/A';
                var time_out = 'N/A';
                lateness = '';
              }

              tbody.append(`
                            <tr>
                                <td class="hidden"></td>
                                <td>${formatDate(date)}</td>
                                <td>${row.employee_no}</td>
                                <td>${row.first_name} ${row.last_name}</td>
                                <td>${time_in}</td>
                                <td>${time_out}</td>
                                <td>${status}${lateness}</td>
                               
                                <td>
                               
                            </tr>
                        `);
            });
          } else {
            tbody.append('<tr><td colspan="8" class="text-center">No records found</td></tr>');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX error:", xhr.responseText);
          alert("Failed to fetch data. Please try again.");
        }
      });
    }

    // Event listener for department selection change
    $('#department').change(function() {
      fetchAttendance(); // Call the function to fetch attendance
    });

    // Event listener for fetch attendance button click
    $('#fetch_attendance').click(function() {
      fetchAttendance(); // Call the function to fetch attendance
    });

    $(document).on('click', '.edit', function(e) {
      e.preventDefault();
      $('#edit').modal('show');
      var id = $(this).data('id');
      console.log("Edit ID:", id); // Debugging line
      getRow(id);
    });

    $(document).on('click', '.delete', function(e) {
      e.preventDefault();
      $('#delete').modal('show');
      var id = $(this).data('id');
      console.log("Delete ID:", id); // Debugging line
      getRow(id);
    });

    function getRow(id) {
      console.log("Sending ID:", id); // Debugging line
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          id: id
        },
        dataType: 'json',
        success: function(response) {
          console.log("Response:", response);
          if (response) {
            $('#datepicker_edit').val(response.date);
            $('#edit_time_in').val(response.time_in);
            $('#edit_time_out').val(response.time_out);
            $('#attid').val(response.attid);
            $('#employee_name').html(response.firstname + ' ' + response.lastname);
            $('#del_attid').val(response.attid);
            $('#del_employee_name').html(response.firstname + ' ' + response.lastname);
          } else {
            console.error("Received null response");
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX error:", status, error);
        }
      });
    }
  });

  $('#print').click(function() {
    // Select the table element
    var printContents = document.getElementById('attendance_table_body').outerHTML; // Ensure this matches the table's ID
    var originalContents = document.body.innerHTML;

    // Prepare the printable HTML
    document.body.innerHTML = `
    <html>
      <head>
        <title>Attendance Report</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
          }
          table, th, td {
            border: 1px solid black;
          }
          th, td {
            padding: 8px;
            text-align: left;
          }
          th {
            background-color: #f2f2f2;
          }
          h2 {
            text-align: center;
            margin-bottom: 20px;
          }
        </style>
      </head>
      <body>
        <h2>Attendance Report</h2>
        <table>
          ${printContents}
        </table>
      </body>
    </html>
  `;

    // Trigger the print dialog
    window.print();

    // Restore the original content and reload the page to reinitialize events
    document.body.innerHTML = originalContents;
    location.reload();
  });

  $('#exportCSV').click(function() {
    var csv = [];
    var rows = document.querySelectorAll('#attendance_table_body tr');

    var selectedDate = $('#attendance_date').val();




    csv.push([selectedDate + ' Attendace Reports'].join(','));

    var headers = ['Employee ID', 'Name', 'Time In', 'Time Out', 'Status'];
    csv.push(headers.join(','));

    for (var i = 0; i < rows.length; i++) {
      var row = [],
        cols = rows[i].querySelectorAll('td, th');


      for (var j = 2; j < cols.length; j++) {
        row.push(cols[j].innerText);
      }

      csv.push(row.join(','));
    }

    // Download CSV
    var csvFile = new Blob([csv.join('\n')], {
      type: 'text/csv'
    });
    var downloadLink = document.createElement('a');
    downloadLink.download = 'daily_attendance_reports.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  });



  $('#fetch_reports').click(function() {
    var department = $('#department').val();
    var year = $('#year').val();
    var month = $('#month').val();

    $.ajax({
      url: 'ajax/fetch_reports.php',
      type: 'POST',
      data: {
        department: department,
        year: year,
        month: month
      },
      dataType: 'json',
      success: function(response) {
        console.log(response);
        var tbody = $('#reports');
        tbody.empty(); // Clear previous data

        if (response.length > 0) {
          $.each(response, function(index, row) {
            var status = '';
            var time_in = 'N/A';
            var time_out = 'N/A';

            if (row.attendance_record) {
              status = '<span class="label label-success">Present</span>';
              time_in = formatTime(row.attendance_record.time_in);
              time_out = row.attendance_record.time_out && row.attendance_record.time_out != '00:00:00' ? formatTime(row.attendance_record.time_out) : 'N/A';
            } else if (row.leave_record) {
              status = '<span class="label label-info">On Leave</span>';
            } else {
              status = '<span class="label label-danger">Absent</span>';
            }

            tbody.append(`
              <tr>
                <td class="hidden"></td>
                <td>${formatDate(row.date)}</td>
                <td>${row.employee_no}</td>
                <td>${row.first_name} ${row.last_name}</td>
                <td>${time_in}</td>
                <td>${time_out}</td>
                <td>${status}</td>
              </tr>
            `);
          });
        } else {
          tbody.append('<tr><td colspan="9" class="text-center">No records found</td></tr>');
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX error:", xhr.responseText);
        alert("Failed to fetch reports. Please try again.");
      }
    });
  });

  $('#printmonthly').click(function() {
    // Select the table element
    var printContents = document.getElementById('reports').outerHTML; // Ensure this matches the table's ID
    var originalContents = document.body.innerHTML;

    // Prepare the printable HTML
    document.body.innerHTML = `
    <html>
      <head>
        <title>Attendance Report</title>
        <style>
          body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
          }
          table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
          }
          table, th, td {
            border: 1px solid black;
          }
          th, td {
            padding: 8px;
            text-align: left;
          }
          th {
            background-color: #f2f2f2;
          }
          h2 {
            text-align: center;
            margin-bottom: 20px;
          }
        </style>
      </head>
      <body>
        <h2>Attendance Report</h2>
        <table>
          ${printContents}
        </table>
      </body>
    </html>
  `;

    // Trigger the print dialog
    window.print();

    // Restore the original content and reload the page to reinitialize events
    document.body.innerHTML = originalContents;
    location.reload();
  });

  $('#export_csv').click(function() {
    var csv = [];
    var rows = document.querySelectorAll('#reports tr');

    var month = $('#month option:selected').text();


    csv.push([month + ' Monthly Reports'].join(','));


    var headers = ['Date', 'Year', 'Employee ID', 'Name', 'Time In', 'Time Out', 'Status'];
    csv.push(headers.join(','));

    for (var i = 0; i < rows.length; i++) {
      var row = [],
        cols = rows[i].querySelectorAll('td, th');


      for (var j = 1; j < cols.length; j++) {
        row.push(cols[j].innerText);
      }

      csv.push(row.join(','));
    }

    // Download CSV
    var csvFile = new Blob([csv.join('\n')], {
      type: 'text/csv'
    });
    var downloadLink = document.createElement('a');
    downloadLink.download = 'Montly_attendance_reports.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  });



  // function formatDate(dateString) {
  //   var date = new Date(dateString);
  //   var day = date.getDate();
  //   var month = date.toLocaleString('en-us', { month: 'short' }); // Get short month name
  //   var year = date.getFullYear();

  //   return day + '-' + month + ' ' + year;
  // }
  function formatDate(date) {
    const options = {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    };
    return new Date(date).toLocaleDateString(undefined, options);
  }

  function formatTime(time) {

    if (time && time !== '00:00:00') {
      var timeComponents = time.split(':');
      var hours = parseInt(timeComponents[0]);
      var minutes = parseInt(timeComponents[1]);
      var meridiem = hours >= 12 ? 'PM' : 'AM';

      hours = hours % 12;
      hours = hours ? hours : 12;

      var formattedTime = hours + ':' + (minutes < 10 ? '0' : '') + minutes + ' ' + meridiem;
      return formattedTime;
    } else {
      return 'N/A';
    }
  }
</script>







</body>

</html>