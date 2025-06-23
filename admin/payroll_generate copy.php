<?php
include 'includes/session.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Retrieve form inputs
$from = $_POST['range_from'];
$to = $_POST['range_to'];



function generateRow($from, $to, $payroll_period, $conn)
{
    $contents = '';

    $sql = "SELECT 
                employee.*, schedules.*, attendance.*,
                CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', IFNULL(employee.name_extension, '')) AS full_name, 
                department.department AS department_name,
                position.position AS position, 
                position.rate_per_hour AS rate,
                position.pakyawan_rate AS pakyawan,
                COUNT(DISTINCT attendance.date) AS total_days_work,
                IFNULL(SUM(overtime.total_compensation), 0) AS overtime_pay
            FROM attendance 
            LEFT JOIN employee ON employee.employee_no = attendance.employee_no
            LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
            LEFT JOIN position ON position.positionid = employee_details.positionid      
            LEFT JOIN department ON department.depid = employee_details.departmentid
            LEFT JOIN schedules ON schedules.scheduleid = employee_details.scheduleid
            LEFT JOIN overtime ON overtime.employee_id = employee.employee_id 
                AND overtime.date_overtime BETWEEN '$from' AND '$to' 
                AND overtime.status = 2
            WHERE 
                attendance.date BETWEEN '$from' AND '$to' 
                AND employee_details.status = 'Active'
            GROUP BY employee.employee_id";

    $query = $conn->query($sql);
    $totalNetSalary = 0;
    $total_late_minutes = 0;
    while ($row = $query->fetch_assoc()) {
        // Calculate lateness deduction
        if ($row['pakyawan'] > 0) {
            $late_minutes = 0;
            $lateness_deduction = 0;
        } else {
            $time_in = new DateTime($row['time_in']);
            $start_time = new DateTime($row['scheduled_start']);
            
            // Calculate the difference in minutes and convert to hours/minutes
            $late_minutes = $time_in > $start_time ? ($time_in->diff($start_time)->h * 60) + $time_in->diff($start_time)->i : 0;
            
            // Lateness deduction
            $hours_late = (int)($late_minutes / 60);
            $additional_minutes_late = $late_minutes % 60;
            $lateness_deduction = ($late_minutes / 60) * $row['rate'];
        }

        $total_late_minutes += $late_minutes;

        // Calculate pakyawan based on daily units
        $pakyawan = calculatePakyawan($row['employee_id'], $from, $to, $row['pakyawan'], $conn);

        // Calculate gross salary
        $gross_salary = $pakyawan > 0
            ? $pakyawan
            : ($row['total_days_work'] * $row['rate']) + $row['overtime_pay'];

        // Fetch additional components
        $deductions = calculateDeductions($row['employee_id'], $from, $to, $conn);
        $allowances = calculateAllowances($row['employee_id'], $from, $to, $conn);
        $cash_advance = calculateCashAdvance($row['employee_id'], $from, $to, $conn);
        $bonus = calculateBonus($row['employee_id'], $from, $to, $conn);

        // Calculate mandatory deductions
        $payroll_frequency = $payroll_period; // e.g., 'weekly', 'semi-monthly', 'monthly'
        $mandatory_deductions = calculateMandatoryDeductions($from, $to, $payroll_frequency, $conn);
        $employee_mandatory_share = $mandatory_deductions['employee'] + $deductions;
        $employee_mandatory_share2 = $mandatory_deductions['employee'];

        // Compute net salary
        if ($pakyawan > 0) {
            $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share2) + $bonus + $allowances;
        } else {
            $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share + $lateness_deduction) + $bonus + $allowances;
        }

        $totalNetSalary += $net_salary;

    // Append the row for this employee
    $contents .= '
    <tr>
        <td>' . htmlspecialchars($row['full_name']) . '</td>
        <td>' . htmlspecialchars($row['department_name']) . '</td>
        <td>' . htmlspecialchars($row['position']) . '</td>
        <td align="right">' . number_format($row['pakyawan'] > 0 ? $row['pakyawan'] : $row['rate'], 2) . '</td>
        <td>' . htmlspecialchars($row['total_days_work']) . '</td>
        <td align="right">' . $total_late_minutes . '</td>
        <td align="right">' . number_format($row['pakyawan'] > 0 ? $employee_mandatory_share2 : $employee_mandatory_share, 2) . '</td>
        <td align="right">' . number_format($allowances, 2) . '</td>
        <td align="right">' . number_format($cash_advance, 2) . '</td>
        <td align="right">' . number_format($bonus, 2) . '</td>
        <td align="right"><b>' . number_format($net_salary, 2) . '</b></td>
    </tr>';
}

// Append total row
$contents .= '
    <tr>
        <td colspan="10" align="right"><b>Total</b></td>
        <td align="right"><b>' . number_format($totalNetSalary, 2) . '</b></td>
    </tr>';

return $contents;
}    



function calculatePakyawan($employee_id, $from, $to, $pakyawan_rate, $conn)
{
    // Fetch units completed by the employee during the payroll period
    $sql = "SELECT SUM(units_completed) AS total_units
            FROM daily_units 
            WHERE employee_id = ? 
            AND date_completed BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $employee_id, $from, $to);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Calculate pakyawan pay by multiplying the completed units by the pakyawan rate
    return ($row['total_units'] ?? 0) * $pakyawan_rate;
}





function calculateBonus($employee_id, $from, $to, $conn)
{
    // Extract month and year from $from and $to
    $startDate = new DateTime($from);
    $endDate = new DateTime($to);
    $startMonthYear = $startDate->format('F Y'); // e.g., "December 2024"
    $endMonthYear = $endDate->format('F Y');

    $stmt = $conn->prepare("
        SELECT SUM(bonus_amount) AS total_bonus 
        FROM bonus_incentives 
        WHERE employee_id = ? 
        AND bonus_period BETWEEN ? AND ? 
        AND status = 'Paid'
    ");
    $stmt->bind_param("iss", $employee_id, $startMonthYear, $endMonthYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_bonus'] ?? 0;
}




function calculateMandatoryDeductions($from, $to, $payroll_frequency, $conn)
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

    // Adjust the deduction amount based on payroll frequency
    switch ($payroll_frequency) {
        case 'weekly':
            $total_mandatory /= 4; // Divide by 4 for weekly payroll
            break;
        case 'semi-monthly':
            $total_mandatory /= 2; // Divide by 2 for semi-monthly payroll
            break;
        case 'monthly':
            // No change needed for monthly payroll
            break;
        default:
            // Handle other cases, e.g., if the payroll frequency is not recognized
            error_log("Unrecognized payroll frequency: " . $payroll_frequency);
            break;
    }

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




// function calculateAllowances($employee_id, $from, $to, $payroll_period, $conn)
// {
//     $sql = "SELECT allowances_employee.*, allowance.* 
//             FROM allowances_employee 
//             LEFT JOIN allowance ON allowance.allowid = allowances_employee.allowid
//             WHERE allowances_employee.employee_id = '$employee_id' AND allowances_employee.created_at BETWEEN '$from' AND '$to'";

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
//     $sql = "SELECT deductions_employees.* ,deductions.*
//             FROM deductions_employees 
//             LEFT JOIN deductions on deductions.dedID  = deductions_employees.deductionid 
//             WHERE deductions_employees.employee_id = '$employee_id' AND deductions_employees.created_on BETWEEN '$from' AND '$to'";
//     $result = $conn->query($sql);

//     $total_deductions = 0;

//     while ($row = $result->fetch_assoc()) {
//         switch ($row['deduction_type']) {
//             case 'once':

//                 $total_deductions += $row['deduc_amount'];
//                 break;
//             case 'weekly':

//                 if ($payroll_period == 'weekly') {
//                     $total_deductions += $row['deduc_amount'];
//                 }
//                 break;
//             case 'monthly':

//                 if ($payroll_period == 'monthly') {
//                     $total_deductions += $row['deduc_amount'];
//                 }
//                 break;
//         }
//     }

//     return $total_deductions;
// }



//Calculate Allowances
function calculateAllowances($employee_id, $from, $to, $conn)
{
    $sql = "SELECT SUM(allowance_amount) as total_allowances FROM allowances_employee 
            WHERE employee_id = '$employee_id' AND created_at BETWEEN '$from' AND '$to'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_allowances'] ? $row['total_allowances'] : 0;
}
function calculateDeductions($employee_id, $from, $to, $conn)
{
    $sql = "SELECT SUM(deduc_amount) as total_deductions FROM deductions_employees 
            WHERE employee_id = '$employee_id' AND created_on BETWEEN '$from' AND '$to'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_deductions'] ? $row['total_deductions'] : 0;
}









// Handling POST data and generating PDF
$empid = $_POST['employee_id'];
$range = $_POST['date_range'];
$payroll_period = $_POST['payroll_period'];

$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

$from_title = date('M d, Y', strtotime($ex[0]));
$to_title = date('M d, Y', strtotime($ex[1]));



require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arial'); // Set a default font to avoid font issues
$dompdf = new Dompdf($options);

$dompdf->setPaper('LEGAL', 'landscape');

// Create the HTML content
$html = '<h2 align="center">J-VENUS TUNA PRODUCTS TRADING</h2>';
$html .= '<h2 align="center">' . $from_title . " - " . $to_title . '</h2>';
$html .= '<h2 align="center">' . ucwords($payroll_period) . '</h2>';
$html .= '<style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid black;
                text-align: center;
                padding: 0; /* Remove padding */
            }
                th{
                background-color:gray;

}
        </style>';
$html .= '<table>

<tr><th>Employee Name</th><th>Department</th><th>Position</th><th>Rate/Day - Fixed</th><th>Days Work</th><th>Total Late</th><th>Deductions</th><th>Allowances</th><th>Cash Advance</th><th>Bonus Incentives</th><th>Net Salary</th></tr>';
$html .= generateRow($from, $to, $payroll_period, $conn);
$html .= '</table>';

// Load HTML to DomPDF
$dompdf->loadHtml($html);

// Render PDF (first pass for better performance)
$dompdf->render();

// Output the PDF
$dompdf->stream("payroll.pdf", array("Attachment" => 0));
