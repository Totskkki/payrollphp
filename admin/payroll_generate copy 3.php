<?php
include 'includes/session.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php_errors.log");

// Retrieve form inputs
$from = $_POST['range_from'];
$to = $_POST['range_to'];

function convertTimeToMinutes($time)
{
    list($hours, $minutes, $seconds) = explode(':', $time);
    return ($hours * 60) + $minutes;
}

function generateRow($from, $to, $payroll_period, $conn)
{
    $contents = '';

    $sql = "SELECT 
                employee.*, schedules.*, attendance.*,
                CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', IFNULL(employee.name_extension, '')) AS full_name, 
                department.department AS department_name,
                position.position AS position, 
                position.rate_per_hour AS rate_per_day,
                position.pakyawan_rate AS pakyawan,
                SUM(attendance.num_hr) AS total_hourswork,
                COUNT(DISTINCT attendance.date) AS total_days_work,
                SUM(
                    CASE 
                        WHEN attendance.time_in > schedules.scheduled_start 
                        THEN TIMESTAMPDIFF(MINUTE, schedules.scheduled_start, attendance.time_in)
                        ELSE 0
                    END
                ) AS total_late_minutes,
                SUM(
                    CASE 
                        WHEN attendance.time_out < schedules.scheduled_end
                        THEN TIMESTAMPDIFF(MINUTE, attendance.time_out, schedules.scheduled_end)
                        ELSE 0
                    END
                ) AS total_undertime_minutes,
                (SELECT total_compensation FROM overtime WHERE overtime.employee_id = employee.employee_id AND date_overtime BETWEEN '$from' AND '$to' AND status = 2) AS overtime_pay
            FROM attendance 
            LEFT JOIN employee ON employee.employee_no = attendance.employee_no
            LEFT JOIN employee_details ON employee_details.employee_id = employee.employee_id
            LEFT JOIN position ON position.positionid = employee_details.positionid      
            LEFT JOIN department ON department.depid = employee_details.departmentid
            LEFT JOIN schedules ON schedules.scheduleid = employee_details.scheduleid        
            WHERE 
                attendance.date BETWEEN '$from' AND '$to' 
                AND employee_details.status = 'Active'
                AND attendance.status = 'present'
            GROUP BY employee.employee_id";

    $query = $conn->query($sql);
    $totalNetSalary = 0;

    while ($row = $query->fetch_assoc()) {
        $rate_per_day = $row['rate_per_day'];
        $pakyawan = $row['pakyawan'];  // Checking if the employee is Pakyawan

        if ($pakyawan > 0) {
            // For Pakyawan, no lateness or undertime deductions
            $total_late_minutes = 0;
            $lateness_deduction = 0;
            $undertime_deduction = 0;
        } else {
            // Get the total late minutes (aggregated over all attendance records)
            $total_late_minutes = $row['total_late_minutes'] ?? 0;
            $lateness_deduction = ($total_late_minutes / 60) * ($rate_per_day / 8);

            // Get the total undertime minutes (aggregated over all attendance records)
            $total_undertime_minutes = $row['total_undertime_minutes'] ?? 0;
            $undertime_deduction = ($total_undertime_minutes / 60) * ($rate_per_day / 8);
        }

        // Log for debugging purposes (can be removed later)
        error_log('Employee: ' . $row['full_name']);
        error_log('Total Late Minutes: ' . $total_late_minutes);
        error_log('Late Deduction: ' . $lateness_deduction);
        error_log('Total Undertime Minutes: ' . $total_undertime_minutes);
        error_log('Undertime Deduction: ' . $undertime_deduction);


  


   
        $pakyawan = calculatePakyawan($row['employee_id'], $from, $to, $row['pakyawan'], $conn);
    
       
        $gross_salary = $pakyawan > 0
            ? $pakyawan
            : ($rate_per_day * $row['total_days_work']) + $row['overtime_pay'];
    
        
        $deductions = calculateDeductions($row['employee_id'], $from, $to, $payroll_period, $conn);
        $allowances = calculateAllowances($row['employee_id'], $from, $to, $payroll_period, $conn);
        $cash_advance = calculateCashAdvance($row['employee_id'], $from, $to, $conn);
        $bonus = calculateBonus($row['employee_id'], $from, $to, $conn);
        $mandatory_deductions = calculateMandatoryDeductions($from, $to, $payroll_period, $conn);
        $employee_mandatory_share = $mandatory_deductions['employee'] + $deductions;
    
        
        if ($pakyawan > 0) {
            $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share) + $bonus + $allowances;
        } else {
            $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share + $lateness_deduction + $undertime_deduction) + $bonus + $allowances;
        }
    
        $totalNetSalary += $net_salary;
    
     
        $contents .= '
        <tr>
            <td>' . htmlspecialchars($row['full_name']) . '</td>
            <td>' . htmlspecialchars($row['department_name']) . '</td>
            <td>' . htmlspecialchars($row['position']) . '</td>
            <td align="right">' . number_format($row['pakyawan'] > 0 ? $row['pakyawan'] : $rate_per_day, 2) . '</td>
            <td>' . htmlspecialchars($row['total_days_work']) . '</td>
            <td align="right">' . number_format($total_late_minutes, 2) . ' mins</td> <!-- Aggregated late minutes -->
            <td align="right">' . number_format($total_undertime_minutes, 2) . '</td> <!-- Undertime deduction -->
            <td align="right">' . number_format($row['pakyawan'] > 0 ? $employee_mandatory_share : $employee_mandatory_share, 2) . '</td>
            <td align="right">' . number_format($allowances, 2) . '</td>
            <td align="right">' . number_format(floatval($row['overtime_pay']), 2) . '</td>
            <td align="right">' . number_format($cash_advance, 2) . '</td>
            <td align="right">' . number_format($bonus, 2) . '</td>
            <td align="right">' . number_format($gross_salary, 2) . '</td>
            <td align="right"><b>' . number_format($net_salary, 2) . '</b></td>
        </tr>';
    }    
    




    // Append total row
    $contents .= '
    <tr>
        <td colspan="11" align="right"><b>Total</b></td>
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
        AND status = 'pending'
    ");
    $stmt->bind_param("iss", $employee_id, $startMonthYear, $endMonthYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_bonus'] ?? 0;
}




function calculateMandatoryDeductions($from, $to, $payroll_frequency, $conn)
{
    $sql = "SELECT amount FROM mandatory_benefits WHERE created_at BETWEEN '$from' AND '$to'
    AND status ='active'";
    $result = $conn->query($sql);

    $total_mandatory = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $total_mandatory += $row['amount'];
        }
    }

    switch ($payroll_frequency) {
        case 'weekly':
            $total_mandatory /= 4;
            break;
        case 'semi-monthly':
            $total_mandatory /= 2;
            break;
        case 'monthly':

            break;
        default:

            break;
    }


    $employee_share = $total_mandatory / 2;
    $employer_share = $total_mandatory / 2;


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


function calculateDeductions($employee_id, $from, $to, $payroll_period, $conn)
{
    $sql = "SELECT deductions_employees.*, deductions.* 
            FROM deductions_employees 
            JOIN deductions ON deductions.dedID = deductions_employees.deducid 
            WHERE deductions_employees.employee_id = '$employee_id' AND deductions_employees.created_on BETWEEN '$from' AND '$to'";

    $result = $conn->query($sql);


    $total_deductions = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            switch ($row['deduction_type']) {
                case 'Once':
                    $total_deductions += $row['deduc_amount'];

                    break;
                case 'Weekly':
                    if ($payroll_period == 'weekly') {
                        $total_deductions += $row['deduc_amount'];
                    }
                    break;
                case 'Monthly':
                    if ($payroll_period == 'monthly') {
                        $total_deductions += $row['deduc_amount'];
                    }
                    break;
            }
        }
    } else {
    }

    return $total_deductions;
}

function calculateAllowances($employee_id, $from, $to, $payroll_period, $conn)
{
    $sql = "SELECT allowances_employee.*, allowance.* 
            FROM allowances_employee 
            LEFT JOIN allowance ON allowance.allowid = allowances_employee.allowid
            WHERE allowances_employee.employee_id = '$employee_id' AND allowances_employee.created_at BETWEEN '$from' AND '$to'";

    $result = $conn->query($sql);

    $total_allowances = 0;

    while ($row = $result->fetch_assoc()) {
        switch ($row['allowance_type']) {
            case 'one_time':

                $total_allowances += $row['allowance_amount'];
                break;
            case 'weekly':

                if ($payroll_period == 'weekly') {
                    $total_allowances += $row['allowance_amount'];
                }
                break;
            case 'monthly':

                if ($payroll_period == 'monthly') {
                    $total_allowances += $row['allowance_amount'];
                }
                break;
        }
    }

    return $total_allowances;
}


//Calculate Allowances
// function calculateAllowances($employee_id, $from, $to, $conn)
// {
//     $sql = "SELECT SUM(allowance_amount) as total_allowances FROM allowances_employee 
//             WHERE employee_id = '$employee_id' AND created_at BETWEEN '$from' AND '$to'";
//     $result = $conn->query($sql);
//     $row = $result->fetch_assoc();
//     return $row['total_allowances'] ? $row['total_allowances'] : 0;
// }
// function calculateDeductions($employee_id, $from, $to, $conn)
// {
//     $sql = "SELECT SUM(deduc_amount) as total_deductions FROM deductions_employees 
//             WHERE employee_id = '$employee_id' AND created_on BETWEEN '$from' AND '$to'";
//     $result = $conn->query($sql);
//     $row = $result->fetch_assoc();
//     return $row['total_deductions'] ? $row['total_deductions'] : 0;
// }









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
$options->set('defaultFont', 'Roboto'); // Set a default font to avoid font issues
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

<tr><th>Employee Name</th><th>Department</th>
<th>Position</th>
<th>Rate/Day - Fixed</th>
<th>Days Work</th>
<th>Total Late</th>
<th>Undertime</th>
<th>Deductions</th>
<th>Allowances</th>
<th>Overtime</th>
<th>Cash Advance</th>
<th>Bonus Incentives</th>
<th>Gross Salary</th>
<th>Net Salary</th>
</tr>';
$html .= generateRow($from, $to, $payroll_period, $conn);
$html .= '</table>';

// Load HTML to DomPDF
$dompdf->loadHtml($html);

// Render PDF (first pass for better performance)
$dompdf->render();

// Output the PDF
$dompdf->stream("payroll.pdf", array("Attachment" => 0));
ob_flush();
