<?php include 'includes/session.php'; ?>

<?php
$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 days', strtotime($range_to)));


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
                            <span>Home</span> / <span class="menu-text">Payroll Runs</span>
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
                                    <h5>Payroll Run</h5>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#generatePayrollModal">Generate Payroll</button>
                                </div>

                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="row mb-3">
                                            <!-- Status Filter -->
                                            <div class="col-md-2">
                                                <label for="status">Status:</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">All</option>
                                                    <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>Pending
                                                    </option>
                                                    <option value="approve" <?php echo (isset($_GET['status']) && $_GET['status'] == 'approve') ? 'selected' : ''; ?>>Approve
                                                    </option>
                                                    <option value="paid" <?php echo (isset($_GET['status']) && $_GET['status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                                                </select>
                                            </div>

                                            <!-- Month Filter -->
                                            <div class="col-md-2">
                                                <label for="date_completed">Filter by (Month-Year):</label>
                                                <input type="month" class="form-control" id="date_completed"
                                                    name="date_completed"
                                                    value="<?php echo isset($_GET['date_completed']) ? $_GET['date_completed'] : ''; ?>">
                                            </div>

                                            <!-- Filter Button -->
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-info mt-4">Search</button>
                                            </div>
                                        </div>
                                    </form>

                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="input-control" id="selectAll"
                                                        onclick="toggleSelectAll()"></th> <!-- Select All Checkbox -->
                                                <th>Employee Name</th>
                                                <th>Pay Period</th>
                                                <th>Rate per day / Pakyawan</th>
                                                <th>Gross Salary</th>
                                                <th>Deductions</th>
                                                <th>Days of work</th>
                                                <th>Overtime</th>
                                                <th>Allowance</th>
                                                <th>Cash Advance</th>
                                                <th>Bonus</th>
                                                <th>Net Pay</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
                                            $bonusPeriodFilter = isset($_GET['date_completed']) ? $_GET['date_completed'] : '';

                                            $sql = "SELECT de.*, pa.*, d.*, p.*, dep.*, e.*, CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS `full_name` 
                                                        FROM payroll d
                                                        JOIN employee e ON e.employee_id = d.employee_id
                                                        JOIN employee_details de ON de.employee_id = e.employee_id
                                                        JOIN department dep ON dep.depid = de.departmentid
                                                        JOIN position p ON p.positionid = de.positionid
                                                        JOIN pay_periods pa ON pa.payid = d.pay_period_id
                                                        WHERE 1=1";

                                            // Apply status filter
                                            if ($statusFilter) {
                                                $sql .= " AND d.status = '$statusFilter'";
                                            }

                                            // Apply month filter
                                            if ($bonusPeriodFilter) {
                                                $sql .= " AND DATE_FORMAT(d.created_at, '%Y-%m') = '$bonusPeriodFilter'";
                                            }

                                            $sql .= " ORDER BY e.first_name, e.last_name ASC";
                                            $query = $conn->query($sql);

                                            if ($query->num_rows > 0) {
                                                while ($row = $query->fetch_assoc()) {

                                                    $status = $row['status'];
                                                    $statusBadge = '';

                                                    if ($status == 'pending') {
                                                        $statusBadge = "<span class='badge bg-danger'>$status</span>";
                                                    } elseif ($status == 'approve') {
                                                        $statusBadge = "<span class='badge bg-success'>$status</span>";
                                                    } elseif ($status == 'paid') {
                                                        $statusBadge = "<span class='badge bg-primary'>$status</span>";
                                                    }


                                                    // Use PHP for dynamic content inside HTML, avoid echoing large strings
                                                    $full_name = $row['full_name'];
                                                    $from_date = date('Y, M j', strtotime($row['from_date']));
                                                    $to_date = date('Y, M j', strtotime($row['to_date']));
                                                    $basic_salary = $row['rate_per_hour'];
                                                    $pakyawan_rate = $row['pakyawan_rate'];
                                                    $gross_salary = $row['gross_salary'];

                                                    $deductions = $row['tot_deductions'];
                                                    $present = $row['present'];
                                                    $overtime = $row['overtime'];
                                                    $allowances = $row['allowances'];
                                                    $bonus = $row['bonus'];
                                                    $net_salary = $row['net_salary'];
                                                    $payrollid = $row['payrollid'];
                                                    $cash_advance = $row['cash_advance'];
                                                    ?>

                                                    <tr>
                                                        <td><input type="checkbox" class="payrollCheckbox"
                                                                data-payrollid="<?= $payrollid ?>"></td>
                                                        <td><?= $full_name ?></td>
                                                        <td><?= $from_date . ' - ' . $to_date ?></td>
                                                        <td><?= $basic_salary ?> / <?= $pakyawan_rate ?> </td>
                                                        <td><?= $gross_salary ?></td>
                                                        <td><?= $deductions ?></td>
                                                        <td><?= $present ?></td>
                                                        <td><?= $overtime ?></td>
                                                        <td><?= $allowances ?></td>
                                                        <td><?= $cash_advance ?></td>
                                                        <td><?= $bonus ?></td>
                                                        <td><?= $net_salary ?></td>
                                                        <td><?= $statusBadge ?></td>

                                                        <td>
                                                            <?php if ($status == 'pending') { ?>
                                                                <!-- Approve Button -->
                                                                <button onclick="approvePayroll(<?= $payrollid ?>)"
                                                                    class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                                                    data-bs-placement="bottom" data-bs-title="Approve">
                                                                    <i class="bi bi-check2-circle"></i> 
                                                                </button>
                                                            <?php } elseif ($status == 'approve') { ?>
                                                                <!-- Generate Payslip -->
                                                                <a href="payslip_view.php?payrollid=<?= $payrollid ?>"
                                                                    class="btn btn-info btn-sm" data-bs-toggle="tooltip"
                                                                    data-bs-placement="bottom" data-bs-title="Generate Payslip">
                                                                    <i class="bi bi-file-earmark-text"></i>
                                                                </a>
                                                                <!-- Mark as Paid -->
                                                                <button onclick="markAsPaid(<?= $payrollid ?>)"
                                                                    class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                                                    data-bs-placement="bottom" data-bs-title="Mark as Paid">
                                                                    <i class="bi bi-cash"></i>
                                                                </button>
                                                            <?php } elseif ($status == 'paid') { ?>
                                                                <!-- Generate Payslip (Always Visible) -->
                                                                <a href="payslip_view.php?payrollid=<?= $payrollid ?>"
                                                                    class="btn btn-info btn-sm" data-bs-toggle="tooltip"
                                                                    data-bs-placement="bottom" data-bs-title="Generate Payslip">
                                                                    <i class="bi bi-file-earmark-text"></i>
                                                                </a>


                                                            <?php } ?>


                                                            <button class="btn btn-success btn-sm edit"
                                                                data-id="<?= $row['payrollid']; ?>" data-bs-toggle="tooltip"
                                                                data-bs-placement="bottom" data-bs-title="Edit">
                                                                <i class='bi bi-pencil'></i>
                                                            </button>
                                                        </td>


                                                    </tr>

                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                    <button class="btn btn-primary" onclick="markSelectedAsPaid()">Mark Selected as
                                        Paid</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- App body end -->
                <?php include 'includes/footer.php'; ?>
            </div>
            <!-- App container end -->
        </div>
        <!-- Main container end -->

        <!-- Generate Payroll Modal -->
        <div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-labelledby="generatePayrollModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generatePayrollModalLabel">Generate Payroll</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="generate_payroll.php" method="POST">
                        <div class="modal-body">
                            <label>Select Pay Period:</label>
                            <select name="pay_period" class="form-control" required>

                                <option value="" disabled selected>-Select Pay Period-
                                </option>
                                <?php
                                $sql = "SELECT `payid`, `ref_no`, `year`, `from_date`, `to_date`, `status`, `created_at`, `updated_at` FROM `pay_periods`
                                    where status ='open'";
                                $query = $conn->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='{$row['payid']}'>{$row['from_date']} - {$row['to_date']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Generate</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- View Details Modal -->
        <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDetailsModalLabel">Payroll Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div id="payrollDetailsContent"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'includes/scripts.php'; ?>
        <?php include 'modals/payroll_modal.php'; ?>
    </div>




    <script>
        $(document).ready(function () {
            // Edit button handler
            $(document).on('click', '.edit', function (e) {
                e.preventDefault();
                $('#edit').modal('show');
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
                    payroll: id
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                 
                    $('.name').html(response.full_name);
                    $('#payrollid').val(response.payrollid);
                    $('#payslip_no').val(response.payslip_no);
                    $('#gross_salary').val(response.gross_salary);
                    $('#pay_period_id').val(response.from_date + ' to ' + response.to_date || '');
                    $('#tot_deductions').val(response.tot_deductions);
                    $('#deductions').val(response.deductions);
                    $('#late').val(response.late);
                    $('#undertime').val(response.undertime);
                    $('#present').val(response.present);
                    $('#overtime').val(response.overtime);
                    $('#allowances').val(response.allowances);
                    $('#cash_advance').val(response.cash_advance);
                    $('#bonus').val(response.bonus);
                    $('#net_salary').val(response.net_salary);              
                    $('#status_val').val(response.status).html(response.status);
                    
                    


                }


            });
        }
    </script>




    <!-- AJAX for View Details -->
    <script>
        $(document).on('click', '.viewDetails', function () {
            var employeeId = $(this).data('id');
            $.ajax({
                url: 'ajax/fetch_payroll_details.php',
                method: 'POST',
                data: {
                    id: employeeId
                },
                success: function (response) {
                    $('#payrollDetailsContent').html(response);
                }
            });
        });
    </script>

    <script>

        function approvePayroll(payrollid) {
            if (confirm('Are you sure you want to approve this payroll?')) {
                fetch('ajax/approve_payroll.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `payrollid=${encodeURIComponent(payrollid)}`
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.text();
                    })
                    .then(data => {
                        if (data.trim() === 'Success') {
                            alert('Payroll Approved and Email Sent Successfully!');
                            location.reload();
                        } else {
                            alert('Error approving payroll: ' + data);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('An error occurred while processing the request. Please try again.');
                    });
            }
        }


        function markAsPaid(payrollid) {
            if (confirm('Are you sure you want to mark this payroll as paid?')) {
                fetch('ajax/mark_as_paid.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        payrollid: payrollid
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Payroll Approved!', 'success');
                            location.reload();
                        } else {
                            showNotification('Error approving payroll: ' + data, 'error');

                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while marking the payroll as paid.');
                    });
            }
        }
    </script>

    <script>
        // Function to toggle 'Select All' checkbox
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.payrollCheckbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }

        // Function to mark selected payrolls as paid
        function markSelectedAsPaid() {
            const selectedPayrolls = [];
            document.querySelectorAll('.payrollCheckbox:checked').forEach(checkbox => {
                selectedPayrolls.push(checkbox.getAttribute('data-payrollid'));
            });

            if (selectedPayrolls.length > 0) {
                if (confirm('Are you sure you want to mark the selected payrolls as paid?')) {
                    fetch('ajax/mark_multiple_as_paid.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            payrollids: selectedPayrolls
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {

                                alert('Selected payrolls marked as paid successfully!');
                                location.reload();
                            } else {
                                showNotification('Error: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while marking payrolls as paid.');
                            console.error('Error:', error);

                        });
                }
            } else {

                alert('Please select payrolls to mark as paid.');
            }
        }
    </script>



</body>

</html>