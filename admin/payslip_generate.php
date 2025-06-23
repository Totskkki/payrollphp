<?php
include 'includes/session.php';


// Fetch the payrollid from the URL
$payrollid = isset($_GET['payrollid']) ? $_GET['payrollid'] : 0;

if ($payrollid > 0) {
    // Query to fetch the payslip data
    $sql = "SELECT de.*, pa.*, d.*, p.*, dep.*, e.*, CONCAT(e.first_name, ' ', e.middle_name, ' ', e.last_name, ' ', e.name_extension) AS `full_name` 
            FROM payroll d
            JOIN employee e ON e.employee_id = d.employee_id
            JOIN employee_details de ON de.employee_id = e.employee_id
            JOIN department dep ON dep.depid = de.departmentid
            JOIN position p ON p.positionid = de.positionid
            JOIN pay_periods pa ON pa.payid = d.pay_period_id
            WHERE d.payrollid = $payrollid
              AND de.status = 'Active' AND e.is_archived = 0";  

    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        // Fetch the data
        $row = $query->fetch_assoc();

        // Extract values from the fetched data
        $full_name = $row['full_name'];
        $from_date = date('Y, M j', strtotime($row['from_date']));
        $to_date = date('Y, M j', strtotime($row['to_date']));
        $basic_salary = $row['basic_salary'];
        $totdeductions = $row['tot_deductions'];
        $gross_salary = $row['gross_salary'];
        $net_salary = $row['net_salary'];
        $status = $row['status'];
        // Add other details needed for the payslip generation
        
        // Example of displaying the payslip data in the HTML
        ?>
        <div class="payslip">
            <h2>Payslip for <?= $full_name ?></h2>
            <p>Period: <?= $from_date ?> - <?= $to_date ?></p>
            <p>Basic Salary: <?= number_format($basic_salary, 2) ?></p>
            <p>Total Deductions: <?= number_format($totdeductions, 2) ?></p>
            <p>Gross Salary: <?= number_format($gross_salary, 2) ?></p>
            <p>Net Salary: <?= number_format($net_salary, 2) ?></p>
            <p>Status: <?= ucfirst($status) ?></p>

            <!-- Add additional details like deductions, bonuses, etc. -->

            <!-- Optionally, you can include a button or link to download the payslip -->
            <a href="download_payslip.php?payrollid=<?= $payrollid ?>" class="btn btn-primary">Download Payslip</a>
        </div>
        <?php
    } else {
        echo "<p>No data found for this payroll.</p>";
    }
} else {
    echo "<p>Invalid payroll ID.</p>";
}
?>
