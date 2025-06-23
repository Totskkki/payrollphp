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

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />

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
                            <span>Home</span> / <span class="menu-text">Unit Tracking</span>
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
                    include 'flash_messages.php';
                    ?>


                    <!-- Row start -->
                    <!-- Row start -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                                    <h5>Daily Unit Tracking</h5>
                                    <div id="notification"
                                        style="text-align: center; margin-top: 10px; color: green; font-size: 18px;">
                                    </div>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addnew">
                                        <i class="bi bi-plus-circle"></i> New
                                    </button>
                                </div>

                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="row mb-3">

                                            <!-- Month Filter -->
                                            <div class="col-md-2">
                                                <label for="date_completed">Date Completed (Month-Year):</label>
                                                <input type="month" class="form-control" id="date_completed" name="date_completed"
                                                    value="<?php echo isset($_GET['date_completed']) ? $_GET['date_completed'] : ''; ?>">
                                            </div>

                                            <!-- Filter Button -->
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-info mt-4">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table id="example1" class="table align-middle table-hover m-0">

                                            <thead>
                                                <tr>
                                                    <th>EMPLOYEE ID</th>
                                                    <th>Employee Name</th>
                                                    <th>Unit Type</th>
                                                    <th>Total Units</th>
                                                    <th>Date Completed</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $bonusPeriodFilter = isset($_GET['date_completed']) ? $_GET['date_completed'] : '';

                                                // Prepare the SQL query
                                                $sql = "SELECT d.*, p.*, dep.*, e.*, CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS `full_name` 
                                                    FROM daily_units d
                                                    JOIN employee e ON e.employee_id = d.employee_id
                                                    JOIN employee_details de on de.employee_id = e.employee_id
                                                    JOIN department dep on dep.depid = de.departmentid
                                                    JOIN position p on p.positionid = de.positionid
                                                    WHERE dep.department = 'PAKYAWAN'";

                                                if ($bonusPeriodFilter) {
                                                    // Use the year and month part from the 'YYYY-MM' format input
                                                    $sql .= " AND DATE_FORMAT(d.date_completed, '%Y-%m') = '$bonusPeriodFilter'";
                                                }

                                                $sql .= " ORDER BY d.unitid DESC";
                                                $query = $conn->query($sql);

                                                if ($query->num_rows > 0) {
                                                    while ($row = $query->fetch_assoc()) {

                                                        echo "
                                                    <tr>
                                                       
                                                        <td>" . $row['employee_no'] . "</td>
                                                        <td>" . $row['full_name'] . "</td>
                                                         <td>" . $row['unit_type'] . "</td>
                                                        <td>" . $row['units_completed'] . "</td>
                                                        <td>" . $row['date_completed'] . "</td>
                                                       
                                                        <td>
                                                            <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['unitid'] . "'>
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
                    </div>

                    <!-- Employee Selection Dropdown inside the Modal -->
                    <div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="addnewLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addnewLabel">Add Units to Pakyawan Employees</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="codes.php" method="POST">

                                        <div class="mb-3" id="employee-selection-div">
                                            <label for="employee-selection" class="form-label">Select Employees <span class="text-danger">*</span></label>
                                            <select class="form-select" id="employee-selection" name="employee_ids[]"
                                                multiple style="width: 100%;">
                                                <option value="all" id="select-all-option">- Select All -</option>
                                                <?php
                                                $sql = "SELECT e.employee_id, CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS full_name
                                                FROM employee e
                                                JOIN employee_details de ON de.employee_id = e.employee_id
                                                JOIN department dep ON dep.depid = de.departmentid
                                                WHERE dep.department = 'PAKYAWAN'
                                                ORDER BY e.first_name, e.last_name";
                                                $query = $conn->query($sql);
                                                while ($prow = $query->fetch_assoc()) {
                                                    echo "<option value='" . $prow['employee_id'] . "'>" . $prow['full_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>



                                        <!-- Unit Type Dropdown -->
                                        <div class="mb-3">
                                            <label for="unit-type" class="form-label">Packing <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="unit-type"
                                                placeholder="Enter Packing">

                                        </div>

                                        <!-- Total Units Input -->
                                        <div class="mb-3">
                                            <label for="total-units" class="form-label">Total Units <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="total-units" min="0"
                                                placeholder="Enter Total Units">
                                        </div>

                                        <div class="mb-3">
                                            <label for="to_date" class="form-label">Date Completed <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepickeradd" name="to_date"
                                                    required>
                                                <span class="input-group-text">
                                                    <i class="bi bi-calendar4"></i>
                                                </span>

                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="save-units">Save</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="addnewLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="edit">Edit Units to Pakyawan Employees</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="codes.php" method="POST">


                                        <input type="hidden" id="unitid" name="unitid">
                                        <input type="hidden" id="empname" name="empname">
                                        <div class="mb-3">
                                            <label for="unit-type" class="form-label">Employee Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="fullname" id="fullname" required>

                                        </div>

                                        <!-- Unit Type Dropdown -->
                                        <div class="mb-3">
                                            <label for="unit-type" class="form-label">Packing <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="unit-type" id="unit-type"
                                                placeholder="Enter Packing" required>

                                        </div>

                                        <!-- Total Units Input -->
                                        <div class="mb-3">
                                            <label for="total-units" class="form-label">Total Units <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="total-units" id="total-units" min="0"
                                                placeholder="Enter Total Units" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="to_date" class="form-label">Date Completed <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control datepickeradd" name="to_date" id="to_date"
                                                    id="to_date" required>
                                                <span class="input-group-text">
                                                    <i class="bi bi-calendar4"></i>
                                                </span>

                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="edit-units">Update</button>
                                </div>
                                </form>
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




    <script>
        $(function() {
            $('.edit').click(function(e) {
                e.preventDefault();
                $('#edit').modal('show');
                var id = $(this).data('id');
                getRow(id);
                console.log(id);
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
                    unitid: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('#unitid').val(response.unitid);
                    $('#fullname').val(response.full_name);
                    $('#empname').val(response.employee_id);
                    $('#unit-type').val(response.unit_type);
                    $('#total-units').val(response.units_completed);
                    $('#to_date').val(response.date_completed);


                }


            });
        }
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 with modal parent
            $('#employee-selection').select2({
                placeholder: 'Select Employees', // Placeholder text
                allowClear: true, // Allow clearing selections
                closeOnSelect: false, // Prevent closing on each selection
                dropdownParent: $('#addnew'), // Attach dropdown to the modal

            });


            $('#employee-selection').on('change', function() {
                const selectedValues = $('#employee-selection').val();


                if (selectedValues.includes('all')) {

                    $('#employee-selection > option').prop('selected', true);
                    $('#employee-selection option[value="all"]').prop('selected', false);
                    $('#employee-selection').trigger('change.select2');
                }
            });
        });
    </script>


</body>


</html>