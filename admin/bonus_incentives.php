<?php
include 'includes/session.php';




?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>
<style>
    .ui-autocomplete {
        z-index: 1051;
        /* Ensure dropdown is above the modal */
        max-height: 200px;
        overflow-y: auto;
        /* Allow scrolling if there are many results */
        overflow-x: hidden;
        background-color: #fff;
        /* Ensure dropdown is visible */
        border: 1px solid #ccc;
        position: absolute;
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
                            <span>Home</span> / <span class="menu-text">Bonus Incentives</span>
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

                    <!-- Payroll Run Table -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Bonus Incentives List</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addnew">
                                        <i class="bi bi-plus-circle"></i> New
                                    </button>
                                </div>




                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="row mb-3">
                                            <!-- Status Filter -->
                                            <div class="col-md-2">
                                                <label for="status">Status:</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">All</option>
                                                    <option value="Paid" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                                    <option value="Pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : ''; ?>>Pending
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Month Filter -->
                                            <div class="col-md-2">
                                                <label for="bonus_period">Bonus Period (Month-Year):</label>
                                                <input type="month" class="form-control" id="bonus_period"
                                                    name="bonus_period"
                                                    value="<?php echo isset($_GET['bonus_period']) ? $_GET['bonus_period'] : ''; ?>">
                                            </div>

                                            <!-- Filter Button -->
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-info mt-4">Filter</button>
                                            </div>
                                        </div>
                                    </form>

                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Bonus Type</th>
                                                <th>Amount</th>
                                                <th>Bonus Period</th>
                                                <th>Date Added</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Retrieve the filter values from GET
                                            $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
                                            $bonusPeriodFilter = isset($_GET['bonus_period']) ? $_GET['bonus_period'] : '';

                                            // Modify SQL query based on the filters
                                            $sql = "SELECT *, CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS `full_name`
                FROM bonus_incentives
                LEFT JOIN employee ON employee.employee_id = bonus_incentives.employee_id
                WHERE 1=1";

                                            // Filter by status
                                            if ($statusFilter) {
                                                $sql .= " AND bonus_incentives.status = '$statusFilter'";
                                            }

                                            // Filter by bonus period (month-year)
                                            if ($bonusPeriodFilter) {
                                                // Convert the month-year input (e.g., '2024-12') to 'December 2024' format
                                                $monthYear = date('F Y', strtotime($bonusPeriodFilter));  // Convert input to 'Month Year' format
                                                $sql .= " AND bonus_incentives.bonus_period = '$monthYear'";
                                            }

                                            $sql .= " ORDER BY bonusid DESC";

                                            $query = $conn->query($sql);
                                            $counter = 1;

                                            if ($query->num_rows > 0) {
                                                while ($row = $query->fetch_assoc()) {
                                                    // Status badge logic
                                                    $status = $row['status'];
                                                    $statusBadge = '';

                                                    if ($status == 'Paid') {
                                                        $statusBadge = "<span class='badge bg-success'>$status</span>";
                                                        $disabled = 'disabled'; // Disable buttons if status is 'Paid'
                                                    } elseif ($status == 'Pending') {
                                                        $statusBadge = "<span class='badge bg-warning'>$status</span>";
                                                        $disabled = ''; // Enable buttons if status is 'Pending'
                                                    } else {
                                                        $statusBadge = "<span class='badge bg-danger'>$status</span>";
                                                        $disabled = ''; // Enable buttons for other statuses
                                                    }

                                                    echo "
                                                        <tr>
                                                            <td>" . $counter++ . "</td>
                                                            <td>" . $row['full_name'] . "</td>
                                                            <td>" . $row['bonus_type'] . "</td>
                                                            <td>" . number_format($row['bonus_amount'] ?? 0, 2) . "</td>
                                                            <td>" . $row['bonus_period'] . "</td>
                                                            <td>" . $row['created_at'] . "</td>
                                                            <td>" . $row['bonus_description'] . "</td>
                                                            <td>" . $statusBadge . "</td>
                                                            <td>
                                                                <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['bonusid'] . "' $disabled>
                                                                    <i class='bi bi-pencil'></i>
                                                                </button>
                                                                <button class='btn btn-danger btn-sm delete btn-flat' data-id='" . $row['bonusid'] . "' $disabled>
                                                                    <i class='bi bi-trash'></i>
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

    <?php include 'modals/modal_bonus.php'; ?>
    <?php include 'includes/scripts.php'; ?>


    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function () {
            function initializeAutocomplete(selector, idSelector, modalSelector) {
                $(selector).autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "ajax/search_employee.php",
                            type: "GET",
                            data: {
                                term: request.term
                            },
                            success: function (data) {
                                console.log("Response Data:", data);
                                var parsedData = $.parseJSON(data);
                                response(parsedData); // Pass data to autocomplete
                            },
                            error: function (xhr, status, error) {
                                console.error("Error fetching autocomplete data:", error);
                            }
                        });
                    },
                    minLength: 2,
                    appendTo: modalSelector, // Attach to the modal content area
                    focus: function (event, ui) {
                        $(this).val(ui.item.label);
                        return false;
                    },
                    select: function (event, ui) {
                        $(this).val(ui.item.label);
                        $(idSelector).val(ui.item.value);
                        return false;
                    }
                });
            }

            // Initialize autocomplete for Add modal
            initializeAutocomplete("#add_employee_name", "#add_employee_id", "#addnew .modal-body");

            // Initialize autocomplete for Edit modal
            $('#edit').on('shown.bs.modal', function () {
                initializeAutocomplete("#edit_employee_name", "#edit_employee_id", "#edit .modal-body");
                // Clear the input value if needed

            });

            // Clear input fields on modal close
            $('#addnew, #edit').on('hidden.bs.modal', function () {
                $(this).find("input[type='text'], input[type='hidden']").val('');
            });




            // Fetch Bonus Details on Edit Button Click
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

            // Fetch Bonus Details
            function getRow(id) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_row.php',
                    data: {
                        bonusid: id
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.error) {
                            console.log(response);
                            console.error("Error:", response.error);
                        } else {
                            $('#bonusid').val(response.bonusid);
                            $('#bonus_amount').val(response.bonus_amount);
                            $('#bonus_type').val(response.bonus_type);
                            $('#bonus_description').val(response.bonus_description);
                            $('#bonus_periods').val(response.bonus_period);
                            $('.otid').val(response.bonusid);

                            // Set employee ID and name for the edit modal
                            $('#edit_employee_id').val(response.employee_id);
                            if (response.employee_id) {
                                getEmployeeName(response.employee_id); // Fetch employee name
                            }

                            const [month, year] = response.bonus_period.split(' ');
                            $('#bonus_period_select').val(month); // Select month in dropdown
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }

            // Fetch Employee Name for Editing
            function getEmployeeName(employee_id) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetch_employee_name.php',
                    data: {
                        employee_id: employee_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.error) {
                            console.error("Error:", data.error);
                            $('#edit_employee_name').val('Employee not found');
                            $('#edit_employee_id').val('');
                        } else {
                            $('#edit_employee_name').val(data.full_name);
                            $('#edit_employee_id').val(data.employee_id);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching employee name:", error);
                    }
                });
            }
        });
    </script>

    <script>
        document.getElementById('bonus_amount').addEventListener('input', function () {
            const errorMessage = document.getElementById('bonus_error');
            if (this.value < 0) {
                // Show error message
                errorMessage.style.display = 'block';
                this.value = ''; // Clear the input value
            } else {
                // Hide error message
                errorMessage.style.display = 'none';
            }
        });
    </script>

    <script>
        // Listen for changes on all .bonus-period-class dropdowns
        document.querySelectorAll('.bonus-period-class').forEach(function (selectElement) {
            selectElement.addEventListener('change', function () {
                var selectedMonth = this.value; // Get the selected month
                var currentYear = new Date().getFullYear(); // Get the current year
                var fullPeriod = selectedMonth + ' ' + currentYear; // Concatenate month and year

                // Find the corresponding input within the same form-group
                var formGroup = this.closest('.form-group');
                var bonusInput = formGroup.querySelector('.bonus-period-input');

                if (bonusInput) {
                    bonusInput.value = fullPeriod; // Set the value of the input field
                }
            });
        });
    </script>




    <!-- <script>
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

        // Fetch Bonus Details
        function getRow(id) {
            $.ajax({
                type: 'POST',
                url: 'fetch_row.php',
                data: {
                    bonusid: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Debug response
                    if (response.error) {
                        console.error("Error:", response.error);
                    } else {
                        $('#bonusid').val(response.bonusid);
                        $('#employee_id').val(response.employee_id);
                        $('#bonus_amount').val(response.bonus_amount);
                        $('#bonus_type').val(response.bonus_type);

                        $('#bonus_description').val(response.bonus_description);

                        if (response.bonus_period) {
                            const [month, year] = response.bonus_period.split(' ');
                            $('#bonus_period_select').val(month); // Select month in dropdown
                            $('#bonus_periods').val(response.bonus_period); // Store full period
                        }
                        // Fetch Employee Name
                        if (response.employee_id) {
                            getEmployeeName(response.employee_id);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        }

        // Fetch Employee Name
        function getEmployeeName(employee_id) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_employee_name.php',
                data: {
                    employee_id: employee_id
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data); // Debug response
                    if (data.error) {
                        console.error("Error:", data.error);
                        $('#employee_name').val('Employee not found');
                        $('#employee_ids').val('');
                    } else {
                        $('#employee_name').val(data.full_name);
                        $('#employee_ids').val(data.employee_id);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching employee name:", error);
                }
            });
        }


        // Fetch employee name based on the employee_id
        function getEmployeeName(employee_id) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_employee_name.php', // Path to the PHP handler for fetching employee name
                data: {
                    employee_id: employee_id
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    // Set the employee name in the input field
                    $('#employee_names').val(data.full_name);
                    $('#employee_id').val(data.employee_id);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching employee name:", error);
                }
            });
        }
    </script> -->




</body>

</html>