<?php include 'includes/session.php';





$payrollid = isset($_GET['payrollid']) ? $_GET['payrollid'] : 0;

if ($payrollid > 0) {
    // Query to fetch the payslip data
    $sql = "SELECT de.*,a.*, pa.*, d.*, p.*, dep.*, e.*, 
 
            CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS `full_name`,
            CONCAT(a.street, ' ', a.city, ' ', a.province) AS `address`,
                SUM(daily_units.units_completed) AS total_units
            FROM payroll d
            JOIN employee e ON e.employee_id = d.employee_id
            JOIN employee_details de ON de.employee_id = e.employee_id
            JOIN `address` a ON a.empid = d.employee_id
            JOIN department dep ON dep.depid = de.departmentid
            JOIN position p ON p.positionid = de.positionid
            JOIN pay_periods pa ON pa.payid = d.pay_period_id
           LEFT JOIN daily_units  ON daily_units.employee_id = d.employee_id
           
            WHERE d.payrollid = $payrollid";

    $query = $conn->query($sql);



    if ($query->num_rows > 0) {
        // Fetch the data
        $row = $query->fetch_assoc();

        $mandatory_deductions = json_decode($row['mandatory_deductions'], true);

        $total_units = isset($row['total_units']) ? $row['total_units'] : 0;

        $full_name = $row['full_name'];
        $from_date = date('Y, M j', strtotime($row['from_date']));
        $to_date = date('Y, M j', strtotime($row['to_date']));
        // $basic_salary = $row['basic_salary'];
      
        $gross_salary = $row['gross_salary'];
        $net_salary = $row['net_salary'];
        $status = $row['status'];
        $ref_no = $row['ref_no'];
        $address = $row['address'];
        $position = $row['position'];
        $department = $row['department'];
        $rate_per_hour = $row['rate_per_hour'];
        $pakyawan_rate = $row['pakyawan_rate'];
        $present = $row['present'];
        $overtime_pay = $row['overtime'];
        
        $cash_advance = $row['cash_advance'];
        $late = $row['late'];
        $allowances = $row['allowances'];
        $bonus = $row['bonus'];
        $undertime = $row['undertime'];
        $employee_id = $row['employee_no'];
        $payslip_no = 'PS-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        $totdeductions = $row['tot_deductions'];
        $deductions = $row['deductions'];

        $total_deductions=0;
        $employee_share_total = 0;
        $sss_employee_share = 0;
        $sss_employer_share = 0;
        
        // Calculate benefit shares
        if (!empty($mandatory_deductions)) {
            foreach ($mandatory_deductions as $key => $deduction) {
                if (strtoupper($key) == 'SSS_EMPLOYEE') {
                    $sss_employee_share = $deduction; // Explicitly handle SSS Employee Share
                } elseif (strtoupper($key) == 'SSS_EMPLOYER') {
                    $sss_employer_share = $deduction; // Explicitly handle SSS Employer Share
                } elseif (is_array($deduction) && isset($deduction['employee_share'])) {
                    $employee_share_total += $deduction['employee_share'];
                }
            }
        }
   
        
        // Final Total Deductions Calculation
        $total_deductions = $totdeductions  + $sss_employee_share + $late + $cash_advance + $undertime;
        
        // Format for Display
        $totdeductions_display = number_format($total_deductions, 2);


       


        if (!empty($row['payslip_no'])) {
            $payslip_no = $row['payslip_no'];
        } else {
            // Generate a new payslip number and save it in the database
            $payslip_no = 'PS-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $update_sql = "UPDATE payroll SET payslip_no = ? WHERE payrollid = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $payslip_no, $payrollid);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>


<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .text-left {
            text-align: left !important;
            ;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .m-b-20 {
            margin-bottom: 10px;
        }



        .table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 12px;
        }
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
                            <span>Home</span> / <span class="menu-text">Payslip</span>
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
                                    <a href="javascript:void(0)" onclick="history.back();"
                                        class="btn bg-secondary position-relative" title="Go back to the previous page">
                                        <i class="bi bi-arrow-left-circle-fill"></i> Back
                                    </a>

                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-white" onclick="printDiv('printableArea')"><i
                                                class="fa fa-print fa-lg"></i> Print</button>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-12">

                                    </div>




                                    <div class="row" id="printableArea">
                                        <div class="col-xs-12">
                                            <div class="box">
                                                <div class="box-header with-border">
                                                    <div class="text-center">


                                                        <h3 class="text-center text-uppercase ">Payslip for the Period
                                                            of <?= $from_date ?>
                                                            - <?= $to_date ?></h3>
                                                    </div>
                                                    <div>
                                                        <h5 class="text-end text-uppercase "> PAYSLIP #:
                                                            <?= $payslip_no ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <br>

                                            <div class="card-body">
                                                <h1 class="payslip-title text-center"></h1>
                                                <?php if (isset($no_data) && $no_data): ?>
                                                    <p class="text-center">None</p>
                                                <?php else: ?>
                                                    <div class="row">
                                                        <div class="col-sm-6 m-b-20">
                                                            <img src="<?php echo (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/profile.jpg'; ?>"
                                                                width="150px" height="150px"
                                                                style="border-radius: 50%; object-fit: cover;">
                                                            <ul class="list-unstyled mb-0">
                                                                <!-- Other content -->
                                                            </ul>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 m-b-20 text-left">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <h3 class="mb-0">
                                                                        <strong><?php echo ucwords($full_name); ?></strong>
                                                                    </h3>
                                                                </li>
                                                                <li><span><b>Designation -
                                                                            <?php echo $position; ?></b></span>
                                                                </li>



                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-4">
                                                        <div class="col-sm-6">
                                                            <div>
                                                                <h4 class="m-b-10"><strong>Earnings</strong></h4>

                                                                <table class="table table-bordered">
                                                                    <tbody>

                                                                        <tr>
                                                                            <td><strong>Rate per
                                                                                    day/Pakyawan/Packing:</strong></td>
                                                                            <td><span
                                                                                    class="float-right"><?php echo $rate_per_hour; ?>
                                                                                    / <?php echo $pakyawan_rate; ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Days Work:</strong>
                                                                            </td>
                                                                            <td><span
                                                                                    class="float-right"><?php echo $present; ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Total Units Finished:</strong></td>
                                                                            <td><span
                                                                                    class="float-right"><?php echo number_format($total_units); ?></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td><strong>Overtime Pay:</strong></td>
                                                                            <td><span
                                                                                    class="float-right"><?php echo number_format($overtime_pay, 2); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Allowance:</strong></td>
                                                                            <td><span
                                                                                    class="float-right"></span><?php echo number_format($allowances, 2); ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Bonus incentives:</strong></td>
                                                                            <td><span
                                                                                    class="float-right"></span><?php echo number_format($bonus, 2); ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><strong>Gross Pay:</strong>
                                                                            </td>
                                                                            <td><span
                                                                                    class="float-right"><strong><?php echo number_format($gross_salary, 2); ?></strong></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
    <div>
        <h4 class="m-b-10"><strong>Deductions</strong></h4>
        <table class="table table-bordered">
            <tbody>

                <!-- Loop through Employee Share Deductions -->
                <?php if (!empty($mandatory_deductions)): ?>
                    <?php foreach ($mandatory_deductions as $key => $deduction): ?>
                        <?php if (is_array($deduction) && isset($deduction['employee_share'])): ?>
                            <tr>
                                <td><strong><?php echo ucwords($deduction['benefit_type']); ?> (Employee Share):</strong></td>
                                <td><span class="float-right"><?php echo number_format($deduction['employee_share'], 2) / 2; ?></span></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Display SSS Employee and Employer Share -->
                <tr>
                    <td><strong>SSS Employee Share:</strong></td>
                    <td><span class="float-right"><?php echo number_format($sss_employee_share, 2); ?></span></td>
                </tr>
                <tr>
                    <td><strong>SSS Employer Share:</strong></td>
                    <td><span class="float-right"><?php echo number_format($sss_employer_share, 2); ?></span></td>
                </tr>

                <!-- Display Late, Undertime, and Cash Advance -->
                <tr>
                    <td><strong>Total Late (mins.):</strong></td>
                    <td><span class="float-right"><?php echo number_format($late, 2); ?></span></td>
                </tr>
                <tr>
                    <td><strong>Undertime:</strong></td>
                    <td><span class="float-right"><?php echo number_format($undertime, 2); ?></span></td>
                </tr>
                <tr>
                    <td><strong>Cash Advance:</strong></td>
                    <td><span class="float-right"><?php echo number_format($cash_advance, 2); ?></span></td>
                </tr>

                <tr>
                    <td><strong>Deductions:</strong></td>
                    <td><span class="float-right"><strong><?php echo $deductions; ?></strong></span></td>
                </tr>
                <!-- Display Total Deductions -->
                <tr>
                    <td><strong>Total Deductions:</strong></td>
                    <td><span class="float-right"><strong><?php echo $total_deductions; ?></strong></span></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>


                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h3><strong>Net Salary:
                                                                        <?php echo number_format($net_salary, 2); ?></strong>
                                                                </h3>
                                                            </div>
                                                        </div>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
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



            <?php include 'includes/scripts.php'; ?>
        </div>
        <!-- Page wrapper end -->


        <script>
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }
        </script>
</body>

</html>


<?php
// function convert_number_to_words($number)
// {
//     $hyphen = '-';
//     $conjunction = ' and ';
//     $separator = ', ';
//     $negative = 'negative ';
//     $decimal = ' point ';
//     $dictionary = array(
//         0 => 'zero',
//         1 => 'one',
//         2 => 'two',
//         3 => 'three',
//         4 => 'four',
//         5 => 'five',
//         6 => 'six',
//         7 => 'seven',
//         8 => 'eight',
//         9 => 'nine',
//         10 => 'ten',
//         11 => 'eleven',
//         12 => 'twelve',
//         13 => 'thirteen',
//         14 => 'fourteen',
//         15 => 'fifteen',
//         16 => 'sixteen',
//         17 => 'seventeen',
//         18 => 'eighteen',
//         19 => 'nineteen',
//         20 => 'twenty',
//         30 => 'thirty',
//         40 => 'forty',
//         50 => 'fifty',
//         60 => 'sixty',
//         70 => 'seventy',
//         80 => 'eighty',
//         90 => 'ninety',
//         100 => 'hundred',
//         1000 => 'thousand',
//         1000000 => 'million',
//         1000000000 => 'billion',
//         1000000000000 => 'trillion',
//         1000000000000000 => 'quadrillion',
//         1000000000000000000 => 'quintillion'
//     );

//     if (!is_numeric($number)) {
//         return false;
//     }

//     if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
//         // overflow
//         trigger_error(
//             'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
//             E_USER_WARNING
//         );
//         return false;
//     }

//     if ($number < 0) {
//         return $negative . convert_number_to_words(abs($number));
//     }

//     $string = $fraction = null;

//     if (strpos($number, '.') !== false) {
//         list($number, $fraction) = explode('.', $number);
//     }

//     switch (true) {
//         case $number < 21:
//             $string = $dictionary[$number];
//             break;
//         case $number < 100:
//             $tens = ((int) ($number / 10)) * 10;
//             $units = $number % 10;
//             $string = $dictionary[$tens];
//             if ($units) {
//                 $string .= $hyphen . $dictionary[$units];
//             }
//             break;
//         case $number < 1000:
//             $hundreds = $number / 100;
//             $remainder = $number % 100;
//             $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
//             if ($remainder) {
//                 $string .= $conjunction . convert_number_to_words($remainder);
//             }
//             break;
//         default:
//             $baseUnit = pow(1000, floor(log($number, 1000)));
//             $numBaseUnits = (int) ($number / $baseUnit);
//             $remainder = $number % $baseUnit;
//             $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
//             if ($remainder) {
//                 $string .= $remainder < 100 ? $conjunction : $separator;
//                 $string .= convert_number_to_words($remainder);
//             }
//             break;
//     }

//     if (null !== $fraction && is_numeric($fraction)) {
//         $string .= $decimal;
//         $words = array();
//         foreach (str_split((string) $fraction) as $number) {
//             $words[] = $dictionary[$number];
//         }
//         $string .= implode(' ', $words);
//     }

//     return $string;
// }
?>