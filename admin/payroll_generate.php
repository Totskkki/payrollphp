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
                AND employee.is_archived = 0
                AND attendance.status = 'present'
            GROUP BY employee.employee_id
            ORDER BY employee.first_name,employee.last_name ";

    $query = $conn->query($sql);
    $totalNetSalary = 0;

    while ($row = $query->fetch_assoc()) {
        $rate_per_day = $row['rate_per_day'];
        $pakyawan = $row['pakyawan']; 

        if ($pakyawan > 0) {
            // For Pakyawan, no lateness or undertime deductions
            $total_late_minutes = 0;
            $lateness_deduction = 0;
            $undertime_deduction = 0;
            $total_undertime_minutes = 0;
        } else {

            $total_late_minutes = $row['total_late_minutes'] ?? 0;
            $lateness_deduction = ($total_late_minutes / 60) * ($rate_per_day / 8);
            $total_undertime_minutes = $row['total_undertime_minutes'] ?? 0;
            $undertime_deduction = ($total_undertime_minutes / 60) * ($rate_per_day / 8);
        }





        $pakyawan = calculatePakyawan($row['employee_id'], $from, $to, $row['pakyawan'], $conn);


     
            
        $deductions = calculateDeductions($row['employee_id'], $from, $to, $payroll_period, $conn);
        $allowances = calculateAllowances($row['employee_id'], $from, $to, $payroll_period, $conn);
        $cash_advance = calculateCashAdvance($row['employee_id'], $from, $to, $conn);
        $bonus = calculateBonus($row['employee_id'], $from, $to, $conn);
        
        $mandatory_deductions = calculateMandatoryDeductions($from, $to, $payroll_period,  $conn);
        $employee_mandatory_share = $mandatory_deductions['employee'] + $deductions;
        
        if ($row['total_days_work'] == 0) {
            // No attendance, set net salary to zero or a default value
            $net_salary = 0;
        } else {
            $gross_salary = $pakyawan > 0
            ? $pakyawan + $bonus + $allowances
            : ($rate_per_day * $row['total_days_work']) + $row['overtime_pay'] + $bonus + $allowances;

            $sss_contribution = calculateSSSContribution($gross_salary, $payroll_period);
            $employee_sss = $sss_contribution['employee_sss'];
            $employer_sss = $sss_contribution['employer'];
            $total_sss = $sss_contribution['total'];

            error_log('Employee: ' . $row['full_name']);
            error_log('employee_sss : ' . $employee_sss);
            error_log('employer_sss : ' . $employer_sss);
            error_log('total_sss : ' . $total_sss);
            error_log('deductions : ' . $deductions);

            if ($pakyawan > 0) {
                // For Pakyawan employees
                $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share + $employee_sss);
            } else {
                // For Regular employees
                $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share + $employee_sss + $lateness_deduction + $undertime_deduction);
            }

            error_log("Gross Salary: " . $gross_salary);
            error_log("Employee Mandatory Deductions: " . $employee_mandatory_share);
            error_log("Lateness Deduction: " . $lateness_deduction);
            error_log("Undertime Deduction: " . $undertime_deduction);
            error_log("Net Salary: " . $net_salary);
            error_log('Payroll Period: ' . $payroll_period);
        }


        $totalNetSalary += $net_salary;


        $contents .= '
        <tr>
            <td>' . htmlspecialchars($row['full_name']) . '</td>
            <td>' . htmlspecialchars($row['department_name']) . '</td>
            <td>' . htmlspecialchars($row['position']) . '</td>
            <td align="right">' . number_format($row['pakyawan'] > 0 ? $row['pakyawan'] : $rate_per_day, 2) . '</td>
            <td>' . htmlspecialchars($row['total_days_work']) . '</td>
            <td align="right">' . number_format($lateness_deduction, 2) . ' </td> <!-- Aggregated late minutes -->
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






    $contents .= '
    <tr>
        <td colspan="13" align="right"><b>Total</b></td>
        <td align="right"><b>' . number_format($totalNetSalary, 2) . '</b></td>
    </tr>';

    return $contents;
}



function calculateSSSContribution($gross_salary, $payroll_period)
{
    // Define SSS Contribution Table (Partial Example)
    $sss_table = [
        ['min' => 0, 'max' => 4249.99, 'msc' => 4000],
        ['min' => 4250, 'max' => 4749.99, 'msc' => 4500],
        ['min' => 4750, 'max' => 5249.99, 'msc' => 5000],
        ['min' => 5250, 'max' => 5749.99, 'msc' => 5500],
        ['min' => 5750, 'max' => 6249.99, 'msc' => 6000],
        ['min' => 6250, 'max' => 6749.99, 'msc' => 6500],
        ['min' => 6750, 'max' => 7249.99, 'msc' => 7000],
        ['min' => 7250, 'max' => 7749.99, 'msc' => 7500],
        ['min' => 7750, 'max' => 8249.99, 'msc' => 8000],
        ['min' => 8250, 'max' => 8749.99, 'msc' => 8500],
        ['min' => 8750, 'max' => 9249.99, 'msc' => 9000],
        ['min' => 9250, 'max' => 9749.99, 'msc' => 9500],
        ['min' => 9750, 'max' => 10249.99, 'msc' => 10000],
        ['min' => 10250, 'max' => 10749.99, 'msc' => 10500],
        ['min' => 10750, 'max' => 11249.99, 'msc' => 11000],
        ['min' => 11250, 'max' => 11749.99, 'msc' => 11500],
        ['min' => 11750, 'max' => 12249.99, 'msc' => 12000],
        ['min' => 12250, 'max' => 12749.99, 'msc' => 12500],
        ['min' => 12750, 'max' => 13249.99, 'msc' => 13000],
        ['min' => 13250, 'max' => 13749.99, 'msc' => 13500],
        ['min' => 13750, 'max' => 14249.99, 'msc' => 14000],
        ['min' => 14250, 'max' => 14749.99, 'msc' => 14500],
        ['min' => 14750, 'max' => 15249.99, 'msc' => 15000],
        ['min' => 15250, 'max' => 15749.99, 'msc' => 15500],
    ];


    // Determine MSC based on gross salary
    $msc = 0;
    foreach ($sss_table as $range) {
        if ($gross_salary >= $range['min'] && $gross_salary <= $range['max']) {
            $msc = $range['msc'];
            break;
        }
        error_log('gross_salary : ' . $gross_salary);
    }

    if ($msc === 0) {
        $msc = 15000; // Default max MSC if salary exceeds table
    }

    // Calculate Employee and Employer Contributions
    $employee_share = $msc * 0.045; // 4.5%
    $employer_share = $msc * 0.095; // 9.5%

    // Adjust for Pay Period
    switch ($payroll_period) {
        case 'weekly':
            $employee_share /= 4;
            $employer_share /= 4;
            break;
        case 'semi-monthly':
            $employee_share /= 2;
            $employer_share /= 2;
            break;
        case 'monthly':
            // No adjustment needed
            break;
            case 'custom':  // Handle 'custom' payroll frequency like monthly
                // No adjustment needed for custom (you can modify if necessary)
                break;
        default:
            throw new Exception("Invalid pay period selected");
    }
       // Logging for verification
       error_log("Gross Salary: $gross_salary");
       error_log("MSC: $msc");
       error_log("Employee SSS: $employee_share");
       error_log("Employer SSS: $employer_share");
    

    return [
        'employee_sss' => round($employee_share, 2),
        'employer' => round($employer_share, 2),
        'total' => round($employee_share + $employer_share, 2)
    ];
}


function calculateMandatoryDeductions($from, $to, $payroll_period, $conn)
{
    $sql = "SELECT benefit_type, amount FROM mandatory_benefits 
    WHERE created_at <= '$to' 
    AND status = 'active'";
    $result = $conn->query($sql);

    $total_mandatory = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Exclude SSS from mandatory deductions
            if ($row['benefit_type'] === 'SSS') {
                continue;
            }
            $total_mandatory += $row['amount'];
        }
    }

    // Adjust for payroll frequency
    switch ($payroll_period) {
        case 'weekly':
            $total_mandatory /= 4;
            break;
        case 'semi-monthly':
            $total_mandatory /= 2;
            break;
        case 'monthly':
            // No adjustment needed
            break;
            case 'custom':  // Handle 'custom' payroll frequency like monthly
                // No adjustment needed for custom (you can modify if necessary)
                break;
        default:
            throw new Exception("Invalid payroll frequency");
    }

    // Split into employee and employer shares
    $employee_share = $total_mandatory / 2;
    $employer_share = $total_mandatory / 2;

    return [
        'employee' => round($employee_share, 2),
        'employer' => round($employer_share, 2)
    ];
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
        
    ");
    $stmt->bind_param("iss", $employee_id, $startMonthYear, $endMonthYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_bonus'] ?? 0;
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
            WHERE deductions_employees.employee_id = '$employee_id'
            AND deductions_employees.created_on BETWEEN '$from' AND '$to'";

    $result = $conn->query($sql);

    $total_deductions = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            error_log('Deduction Type: ' . $row['deduction_type']);
            error_log('Deduction Amount: ' . $row['deduc_amount']);
            
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
        error_log('No deductions found for employee ID: ' . $employee_id);
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
