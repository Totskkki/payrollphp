<?php
include 'includes/session.php';

// Retrieve form inputs
$from = $_POST['range_from'];
$to = $_POST['range_to'];



// function calculateAllowances($employee_id, $from, $to, $payroll_period, $conn)
// {
//     $sql = "SELECT allowance_type, allowance_amount, allowance_date 
//             FROM allowances_employee 
//             WHERE employee_id = '$employee_id' AND allowance_date BETWEEN '$from' AND '$to'";
//     $result = $conn->query($sql);

//     $total_allowances = 0;

//     while ($row = $result->fetch_assoc()) {
//         switch ($row['allowance_type']) {
//             case 'once':
//                 // One-time allowance, add if within the date range
//                 $total_allowances += $row['allowance_amount'];
//                 break;
//             case 'weekly':
//                 // Weekly allowance, add if applicable for this payroll period
//                 if ($payroll_period == 'weekly') {
//                     $total_allowances += $row['allowance_amount'];
//                 }
//                 break;
//             case 'quarterly':
//                 // Quarterly allowance, add if the date is within the current quarter
//                 if (isWithinQuarter($row['allowance_date'], $from, $to)) {
//                     $total_allowances += $row['allowance_amount'];
//                 }
//                 break;
//             case 'monthly':
//                 // Monthly allowance, add if applicable for this payroll period
//                 if ($payroll_period == 'monthly') {
//                     $total_allowances += $row['allowance_amount'];
//                 }
//                 break;
//         }
//     }

//     return $total_allowances;
// }

// function calculateDeductions($employee_id, $from, $to, $payroll_period, $conn)
// {
//     $sql = "SELECT deduc_type, deduc_amount, created_on 
//             FROM deductions_employees 
//             WHERE employee_id = '$employee_id' AND created_on BETWEEN '$from' AND '$to'";
//     $result = $conn->query($sql);

//     $total_deductions = 0;

//     while ($row = $result->fetch_assoc()) {
//         switch ($row['deduc_type']) {
//             case 'once':
//                 // One-time deduction, add if within the date range
//                 $total_deductions += $row['deduc_amount'];
//                 break;
//             case 'weekly':
//                 // Weekly deduction, add if applicable for this payroll period
//                 if ($payroll_period == 'weekly') {
//                     $total_deductions += $row['deduc_amount'];
//                 }
//                 break;
//             case 'monthly':
//                 // Monthly deduction, add if applicable for this payroll period
//                 if ($payroll_period == 'monthly') {
//                     $total_deductions += $row['deduc_amount'];
//                 }
//                 break;
//         }
//     }

//     return $total_deductions;
// }




function isWithinQuarter($date, $from, $to)
{
    // Check if a date falls within the current quarter defined by $from and $to
    $date_timestamp = strtotime($date);
    $from_timestamp = strtotime($from);
    $to_timestamp = strtotime($to);

    return ($date_timestamp >= $from_timestamp && $date_timestamp <= $to_timestamp);
}

function generateRow($from, $to, $payroll_period, $conn)
{
    $contents = '';
    $sql = "SELECT 
                employee.employee_id, 
                CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name, 
                department.department AS department_name,
                position.position AS position, 
                position.rate_per_hour AS rate_perday,
                position.pakyawan_rate AS pakyawan,
                -- SUM(attendance.num_hr) AS total_hours,
                  COUNT(DISTINCT attendance.date) AS total_days_work,
                IFNULL(SUM(overtime.total_compensation), 0) AS overtime_pay
            FROM attendance 
            LEFT JOIN employee ON employee.employee_no = attendance.employee_no
            LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
            LEFT JOIN position ON position.positionid = employee_details.positionid       
            LEFT JOIN department ON department.depid = employee_details.departmentid
            LEFT JOIN overtime ON overtime.employee_id = employee.employee_id 
            WHERE 
                attendance.date BETWEEN '$from' AND '$to' 
                AND employee_details.status ='Active'
            GROUP BY employee.employee_id";

    $query = $conn->query($sql);
    $totalNetSalary = 0;

    while ($row = $query->fetch_assoc()) {

        $gross_salary = $row['pakyawan'] > 0 ? $row['pakyawan'] : ($row['total_days_work'] * $row['rate_perday']) + $row['overtime_pay'];
        // $deductions = calculateDeductions($row['employee_id'], $from, $to, $payroll_period, $conn);
        // $allowances = calculateAllowances($row['employee_id'], $from, $to, $payroll_period, $conn);
        $cash_advance = calculateCashAdvance($row['employee_id'], $from, $to, $conn);
        $bonus = calculateBonus($row['employee_id'], $from, $to, $conn);

        $net_salary = $gross_salary +  - (+ $cash_advance) + $bonus;
        $totalNetSalary += $net_salary;

        $contents .= '
        <tr>
            <td>' . $row['full_name'] . '</td>
            <td>' . $row['department_name'] . '</td>
            <td>' . $row['position'] . '</td>
            <td align="right">' . number_format($row['pakyawan'] > 0 ? $row['pakyawan'] : $row['rate_perday'], 2) . '</td>
           
            <td align="right">' . number_format($cash_advance, 2) . '</td>
            <td align="right"><b>' . number_format($net_salary, 2) . '</b></td>
        </tr>';
    }

    $contents .= '
        <tr>
            <td colspan="7" align="right"><b>Total</b></td>
            <td align="right"><b>' . number_format($totalNetSalary, 2) . '</b></td>
        </tr>
    ';

    return $contents;
}


function calculateBonus($employee_id, $from, $to, $conn)
{
    // Fetch total bonus incentives within the date range for the employee
    $sql = "SELECT SUM(bonus_amount) AS total_bonus FROM bonus_incentives 
            WHERE employee_id = '$employee_id' 
            AND bonus_period BETWEEN '$from' AND '$to' 
            AND status = 'pending'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_bonus'] ? $row['total_bonus'] : 0;
}
// Calculate Deductions

// Calculate Mandatory Deductions
function calculateMandatoryDeductions($from, $to, $conn)
{
    $sql = "SELECT amount FROM mandatory_benefits WHERE created_at BETWEEN '$from' AND '$to'";
    $result = $conn->query($sql);

    $total_mandatory = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_mandatory += $row['amount'];
        }
    }

    // Debug: log the total mandatory amount fetched
    error_log("Total Mandatory Deductions: " . $total_mandatory);

    // Calculate the shares for the employee and employer
    $employee_share = $total_mandatory / 2;
    $employer_share = $total_mandatory / 2;

    // Debug: log the shares
    error_log("Employee Share: " . $employee_share);
    error_log("Employer Share: " . $employer_share);

    return ['employee' => $employee_share, 'employer' => $employer_share];
}

function calculateCashAdvance($employee_id, $from, $to, $conn)
{
    $sql = "SELECT SUM(advance_amount) as total_cash FROM `cashadvance` 
            WHERE employee_id = '$employee_id' AND advance_date BETWEEN '$from' AND '$to'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_cash'] ? $row['total_cash'] : 0;
}



// Calculate Allowances


// Handling POST data and generating PDF
$empid = $_POST['employee_id'];
$range = $_POST['date_range'];
$payroll_period = $_POST['payroll_period'];

$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

$from_title = date('M d, Y', strtotime($ex[0]));
$to_title = date('M d, Y', strtotime($ex[1]));

require_once('../tcpdf/tcpdf.php');
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // Set to landscape ('L')
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Payroll: ' . $from_title . ' - ' . $to_title);
$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 10); // Reduced font size
$pdf->AddPage();

// Content for the PDF
$content = '';
$content .= '
    <h2 align="center">J-VENUS TUNA PRODUCTS TRADING</h2>
    <h4 align="center">' . $from_title . " - " . $to_title . '</h4>
    <h4 align="center">' . ucwords($payroll_period) . '</h4>
    <table border="1" cellspacing="0" cellpadding="2">  
        <tr>  
            <th  align="center" style="background-color: skyblue;"><b>Employee Name</b></th>
            <th  align="center" style="background-color: skyblue;"><b>Department</b></th>
            <th  align="center" style="background-color: skyblue;"><b>Position</b></th>
            <th  align="center" style="background-color: skyblue;"><b>Rate/Day or Fixed</b></th>
            <th  align="center" style="background-color: skyblue;"><b>Deductions</b></th>
            <th  align="center" style="background-color: skyblue;"><b>Allowances </b></th>
            <th  align="center" style="background-color: skyblue;"><b>Cash Advance </b></th>
            <th  align="center" style="background-color: skyblue;"><b>Net Salary</b></th>
        </tr>  
';

// Add generated rows to the content
$content .= generateRow($from, $to, $payroll_period, $conn);
$content .= '</table>';

// Write content to PDF
$pdf->writeHTML($content);

// Output the PDF
$pdf->Output('payroll.pdf', 'I');

