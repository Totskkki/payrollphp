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
                <!-- App brand -->
                <?php include 'includes/navbar.php'; ?>

                <!-- Sidebar menu -->
                <?php include 'includes/menubar.php'; ?>
            </nav>
            <!-- Sidebar wrapper end -->

            <!-- App container start -->
            <div class="app-container">

                <!-- App header start -->
                <header class="app-header d-flex align-items-center">
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
                        <a href="home.php" class="text-decoration-none">
                            <h5 class="text-white mb-0 py-2 px-3 rounded">Payroll System</h5>
                        </a>
                    </div>

                    <!-- App header actions -->
                    <?php include 'includes/navheader.php'; ?>
                </header>
                <!-- App header end -->

                <!-- Hero header -->
                <div class="app-hero-header">
                    <h3 class="fw-light">
                        <span>Home</span> / <span class="menu-text">Salary computation </span>
                    </h3>
                </div>
                <!-- Hero header end -->

                <!-- App body -->
                <div class="app-body">
                    <!-- Flash messages -->
                    <?php include 'flash_messages.php'; ?>

                    <!-- Allowance List -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Salary computation</h5>

                                </div>


                                <div class="card-body">
                                    <form method="POST" class="form-inline" id="payForm">
                                        <div class="row mb-3 ">
                                            <div class="col-md-2">
                                                <label for="payroll_period" class="me-1"></label>
                                                <div class="input-group me-2">

                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range">


                                                </div>
                                            </div>


                                            <div class="col-md-2">
                                                <label for="payroll_period" class="me-1">Payroll Period:</label>
                                                <select class="form-control" name="payroll_period" id="payroll_period">
                                                    <option value="" disabled selected>Select Payroll Period:
                                                    </option>
                                                    <option value="monthly">Monthly</option>
                                                    <option value="semi-monthly">Semi-Monthly</option>
                                                    <option value="weekly">Weekly</option>
                                                    <option value="custom">Custom</option>
                                                </select>

                                            </div>

                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <div class="input-group me-2">

                                                    <button type="button" class="btn btn-info btn-sm" id="payroll" disabled>
                                                        <i class="bi bi-printer"></i> Payroll
                                                    </button>
                                                    <input type="hidden" name="employee_id" id="employee_id" value="">
                                                    <input type="hidden" name="range_from" id="range_from" value="">
                                                    <input type="hidden" name="range_to" id="range_to" value="">
                                                </div>

                                            </div>
                                    </form>
                                </div>

                              
                                <hr>
                    <div class="mt-3">
                        <h5><strong>Salary Computation Formula:</strong></h5>
                        <p><strong>Lateminutes = </strong> (Total_Lateminutes / 60 ) x Rate per day </p>
                        <p><strong>Formula:</strong> (Gross Salary + Overtime + Allowances + Bonus incentive) - (Deductions + Cash Advance + Lateminutes + Undertime + Mandatory Deductions )</p>
                        
                    </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- App body end -->

        </div>
        <!-- App container end -->


        <!-- Main container end -->

        <!-- Page wrapper end -->


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

    <?php include 'includes/scripts.php'; ?>



    <!-- <script>
        $(function () {


            $(function () {
                
                $('#payroll').click(function (e) {
                    e.preventDefault();
                    $('#payForm').attr('action', 'payroll_generate.php');
                    $('#payForm').submit();

                    // $('#payroll').prop('disabled', !(dateRange && payrollPeriod));
                });

                // Handle payslip generation
                $('.generate-payslip').click(function (e) {
                    e.preventDefault();
                    var employeeId = $(this).data('id');
                    var dateRange = $('#reservation').val();
                    var rangeFrom = $('#range_from').val();
                    var rangeTo = $('#range_to').val();
                    window.location.href = 'payslip_view.php?id=' + employeeId + '&date_range=' + encodeURIComponent(dateRange) + '&from=' + encodeURIComponent(rangeFrom) + '&to=' + encodeURIComponent(rangeTo);
                });

                // Handle date range change
                $("#reservation").on('change', function () {
                    var range = encodeURI($(this).val());
                    var dates = range.split(' - ');
                    $('#range_from').val(dates[0]);
                    $('#range_to').val(dates[1]);
                    $('#payroll_period').val('custom'); // Set to custom to indicate manual date range selection
                });

                // Handle payroll period change
                $('#payroll_period').on('change', function () {
                    if ($(this).val() !== 'custom') {
                        updateDateRange();
                    }
                });

                // Function to update date range based on payroll period selection
                function updateDateRange() {
                    var payrollPeriod = $('#payroll_period').val();
                    var today = new Date();
                    var from, to;

                    switch (payrollPeriod) {
                        case 'monthly':
                            from = new Date(today.getFullYear(), today.getMonth(), 1);
                            to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                            break;
                        case 'semi-monthly':
                            var day = today.getDate();
                            if (day <= 15) {
                                from = new Date(today.getFullYear(), today.getMonth(), 1);
                                to = new Date(today.getFullYear(), today.getMonth(), 15);
                            } else {
                                from = new Date(today.getFullYear(), today.getMonth(), 16);
                                to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                            }
                            break;
                        case 'weekly':
                            from = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6);
                            to = today;
                            break;
                    }

                    var fromFormatted = formatDate(from);
                    var toFormatted = formatDate(to);

                    $('#reservation').val(fromFormatted + ' - ' + toFormatted);
                    $('#range_from').val(fromFormatted);
                    $('#range_to').val(toFormatted);
                }

                // Function to format date as YYYY-MM-DD
                function formatDate(date) {
                    var day = ("0" + date.getDate()).slice(-2);
                    var month = ("0" + (date.getMonth() + 1)).slice(-2);
                    var year = date.getFullYear();
                    return year + '-' + month + '-' + day;
                }

            
                if (!$('#reservation').val()) {
                    updateDateRange();
                }
            });


        });
    </script>  -->
    
    <!-- <script>
        $(function() {
            // Function to toggle the Payroll button
            function togglePayrollButton() {
                const dateRange = $('#reservation').val();
                const payrollPeriod = $('#payroll_period').val();

                // Enable Payroll button only if both Date Range and Payroll Period are selected
                $('#payroll').prop('disabled', !(dateRange && payrollPeriod));
            }

            // Function to update Date Range based on Payroll Period
            function updateDateRange() {
                const payrollPeriod = $('#payroll_period').val();
                const today = new Date();
                let from, to;

                // Calculate date range based on payroll period
                switch (payrollPeriod) {
                    case 'monthly':
                        from = new Date(today.getFullYear(), today.getMonth(), 1);
                        to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        break;
                    case 'semi-monthly':
                        const day = today.getDate();
                        if (day <= 15) {
                            from = new Date(today.getFullYear(), today.getMonth(), 1);
                            to = new Date(today.getFullYear(), today.getMonth(), 15);
                        } else {
                            from = new Date(today.getFullYear(), today.getMonth(), 16);
                            to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        }
                        break;
                    case 'weekly':
                        from = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6);
                        to = today;
                        break;
                    default:
                        from = to = null;
                }


                // Update Date Range input fields
                if (from && to) {
                    const fromFormatted = formatDate(from);
                    const toFormatted = formatDate(to);
                    $('#reservation').val(`${fromFormatted} - ${toFormatted}`);
                    $('#range_from').val(fromFormatted);
                    $('#range_to').val(toFormatted);
                }
            }

            // Function to format date as YYYY-MM-DD
            function formatDate(date) {
                const day = ("0" + date.getDate()).slice(-2);
                const month = ("0" + (date.getMonth() + 1)).slice(-2);
                const year = date.getFullYear();
                return `${year}-${month}-${day}`;
            }

            // Initialize toggle on page load
            togglePayrollButton();


            // Event listener for Date Range changes
            $('#reservation').on('change', togglePayrollButton);

            // Event listener for Payroll Period changes
            $('#payroll_period').on('change', function() {
                updateDateRange();
                togglePayrollButton();
            });
            $('#payroll_period').on('change', function() {
                if ($(this).val() !== 'custom') {
                    updateDateRange();
                    togglePayrollButton();
                }
            });

            // Payroll button click handler
            $('#payroll').click(function(e) {
                e.preventDefault();
                if (!$('#payroll').prop('disabled')) {
                    $('#payForm').attr('action', 'payroll_generate.php');
                    $('#payForm').submit();
                }
            });
        });
    </script> -->


<script>
    $(function() {
    // Function to toggle the Payroll button
    function togglePayrollButton() {
        const dateRange = $('#reservation').val();
        const payrollPeriod = $('#payroll_period').val();

        // Enable Payroll button only if both Date Range and Payroll Period are selected
        $('#payroll').prop('disabled', !(dateRange && payrollPeriod));
    }

    // Function to update Date Range based on Payroll Period
    function updateDateRange() {
        const payrollPeriod = $('#payroll_period').val();
        const today = new Date();
        let from, to;

        if (payrollPeriod === 'custom') {
            // Allow manual date selection for Custom
            $('#reservation').val(''); // Clear date range for manual input
            $('#range_from').val('');
            $('#range_to').val('');
            return;
        }

        // Calculate date range based on payroll period
        switch (payrollPeriod) {
            case 'monthly':
                from = new Date(today.getFullYear(), today.getMonth(), 1);
                to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'semi-monthly':
                const day = today.getDate();
                if (day <= 15) {
                    from = new Date(today.getFullYear(), today.getMonth(), 1);
                    to = new Date(today.getFullYear(), today.getMonth(), 15);
                } else {
                    from = new Date(today.getFullYear(), today.getMonth(), 16);
                    to = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                }
                break;
            case 'weekly':
                from = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6);
                to = today;
                break;
            default:
                from = to = null;
        }

        // Update Date Range input fields
        if (from && to) {
            const fromFormatted = formatDate(from);
            const toFormatted = formatDate(to);
            $('#reservation').val(`${fromFormatted} - ${toFormatted}`);
            $('#range_from').val(fromFormatted);
            $('#range_to').val(toFormatted);
        }
    }

    // Function to detect if Date Range is custom
    function isCustomDateRange() {
        const payrollPeriod = $('#payroll_period').val();
        const dateRange = $('#reservation').val();

        if (!dateRange) return false;

        const [from, to] = dateRange.split(' - ');
        if (!from || !to) return false;

        const fromDate = new Date(from);
        const toDate = new Date(to);
        const today = new Date();

        // Compare with known patterns
        const monthlyStart = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
        const monthlyEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];

        const weeklyStart = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 6).toISOString().split('T')[0];
        const weeklyEnd = today.toISOString().split('T')[0];

        if (
            (from === monthlyStart && to === monthlyEnd) ||
            (from === weeklyStart && to === weeklyEnd)
        ) {
            return false; // Matches known patterns
        }

        return true; // Custom range detected
    }

    // Function to format date as YYYY-MM-DD
    function formatDate(date) {
        const day = ("0" + date.getDate()).slice(-2);
        const month = ("0" + (date.getMonth() + 1)).slice(-2);
        const year = date.getFullYear();
        return `${year}-${month}-${day}`;
    }

    // Event listener for Date Range changes
    $('#reservation').on('change', function() {
        if (isCustomDateRange()) {
            $('#payroll_period').val('custom').trigger('change');
        }
        togglePayrollButton();
    });

    // Event listener for Payroll Period changes
    $('#payroll_period').on('change', function() {
        if ($(this).val() === 'custom') {
            // Enable manual date range input
            $('#reservation').prop('readonly', false);
        } else {
            $('#reservation').prop('readonly', true);
            updateDateRange();
        }
        togglePayrollButton();
    });

    // Payroll button click handler
    $('#payroll').click(function(e) {
        e.preventDefault();
        if (!$('#payroll').prop('disabled')) {
            $('#payForm').attr('action', 'payroll_generate.php');
            $('#payForm').submit();
        }
    });

    // Initialize on page load
    updateDateRange();
    togglePayrollButton();
});

</script>

</body>

</html>