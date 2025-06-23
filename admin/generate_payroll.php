<?php
include 'includes/session.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php_errors.log");


ob_start();


if (isset($_POST['pay_period']) && !empty($_POST['pay_period'])) {
    $pay_period_id = $_POST['pay_period'];

    // Fetch the from_date and to_date from the database
    $sql = "SELECT payid, from_date, to_date FROM pay_periods WHERE payid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pay_period_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $from = $row['from_date'];
        $to = $row['to_date'];

        // Create the payroll_period string
        $deductions = calculateMandatoryDeductions($from, $to, $conn);

        // Create the payroll period string
        $payroll_period = "{$from} - {$to}";

        // Call the generateRow function
        $payroll_table = generateRow($from, $to, $payroll_period, $pay_period_id, $conn);
        echo $payroll_table;
    } else {
        echo "Pay period not found.";
        exit;
    }
} else {
    echo "No pay period selected.";
    exit;
}

function generateRow($from, $to, $payroll_period, $pay_period_id, $conn)
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
GROUP BY employee.employee_id";

    $query = $conn->query($sql);
    $totalNetSalary = 0;
    $total_late_minutes = 0;
    $overtime = 0;
    $payroll_frequency = detectPayrollFrequency($from, $to);

    while ($row = $query->fetch_assoc()) {

        $rate_per_day = $row['rate_per_day'];
        $pakyawan = $row['pakyawan'];  // Checking if the employee is Pakyawan
        $overtimepay = $row['overtime_pay'];
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

        // Log for debugging purposes (can be removed later)
        error_log('Employee: ' . $row['full_name']);
        // error_log('Total Late Minutes: ' . $total_late_minutes);
        // error_log('Late Deduction: ' . $lateness_deduction);
        // error_log('Total Undertime Minutes: ' . $total_undertime_minutes);
        // error_log('Undertime Deduction: ' . $undertime_deduction);



        // Calculate pakyawan based on daily units
        $pakyawan = calculatePakyawan($row['employee_id'], $from, $to, $row['pakyawan'], $conn);





        $deductions = calculateDeductions($row['employee_id'], $from, $to, $payroll_frequency, $conn);
        $allowances = calculateAllowances($row['employee_id'], $from, $to, $payroll_frequency, $conn);
        $cash_advance = calculateCashAdvance($row['employee_id'], $from, $to, $conn);
        $bonus = calculateBonus($row['employee_id'], $from, $to, $conn);
        $mandatory_deductions = calculateMandatoryDeductions($from, $to, $conn);
        $employee_mandatory_share = $mandatory_deductions['employee'] + $deductions;



        if ($row['total_days_work'] == 0) {
            // No attendance, set net salary to zero or a default value
            $net_salary = 0;
        } else {

            $gross_salary = $pakyawan > 0
                ? $pakyawan + $bonus + $allowances
                : ($rate_per_day * $row['total_days_work']) + $overtimepay + $bonus + $allowances;



            $sss_contribution = calculateSSSContribution($gross_salary, $from, $to);
            $employee_sss = $sss_contribution['employee_sss'];
            $employer_sss = $sss_contribution['employer'];
            $total_sss = $sss_contribution['total'];




            error_log('employee_sss : ' . $employee_sss);
            error_log('employer_sss : ' . $employer_sss);
            error_log('total_sss : ' . $total_sss);




            $deductions_array = $mandatory_deductions['deductions'];


            if (!is_array($deductions_array)) {
                $deductions_array = [];
            }

            // Add Employee and Employer SSS Contribution to deductions array
            $deductions_array['SSS_Employee'] = $employee_sss;
            $deductions_array['SSS_Employer'] = $employer_sss;
            $deductions_array['SSS_Total'] = $total_sss;

            // Optional: Include Total Mandatory Deductions
            $deductions_array['Total_Mandatory'] = $employee_mandatory_share + $employee_sss;


            if ($pakyawan > 0) {
                $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share + $employee_sss);
            } else {
                $net_salary = $gross_salary - ($cash_advance + $employee_mandatory_share + $employee_sss + $lateness_deduction + $undertime_deduction);
            }

            error_log("Gross Salary: " . $gross_salary);
            error_log("Employee Mandatory Deductions: " . $employee_mandatory_share);
            error_log("Lateness Deduction: " . $lateness_deduction);
            error_log("Undertime Deduction: " . $undertime_deduction);
            error_log("allowances: " . $allowances);    
            error_log("bonus: " . $bonus);
            error_log("cash_advance: " . $cash_advance);
            error_log("Net Salary: " . $net_salary);
            error_log('Payroll Period: ' . $payroll_period);

        }
        $totalNetSalary += $net_salary;

        $status = 'pending';
        $present = $row['total_days_work'];

        $mandatory_deductions_json = json_encode($deductions_array);

        savePayrollToDatabase(
            $row['employee_id'],
            $gross_salary,
            $employee_mandatory_share,
            $deductions,
            $mandatory_deductions_json,
            $lateness_deduction,
            $undertime_deduction,
            $present,
            $overtimepay,
            $allowances,
            $cash_advance,
            $bonus,
            $net_salary,
            $status,
            $pay_period_id,  // Ensure this is assigned properly
            $conn
        );

        markBonusAsPaid($row['employee_id'], $from, $to, $conn);



        $contents .= '
            <tr>
                <td>' . htmlspecialchars($row['full_name']) . '</td>
                <td>' . htmlspecialchars($row['department_name']) . '</td>
                <td>' . htmlspecialchars($row['position']) . '</td>
                <td align="right">' . number_format($row['pakyawan'] > 0 ? $row['pakyawan'] : $row['rate_per_day'], 2) . '</td>
                <td>' . htmlspecialchars($row['total_days_work']) . '</td>
                <td align="right">' . $total_late_minutes . '</td>
            <td align="right">' . number_format(
            $row['pakyawan'] > 0
            ? $employee_mandatory_share // Pakyawan
            : $employee_mandatory_share, // Regular
            2
        ) . '</td>

                <td align="right">' . number_format($allowances, 2) . '</td>
                <td align="right">' . number_format($overtime, 2) . '</td>
                <td align="right">' . number_format($cash_advance, 2) . '</td>
                <td align="right">' . number_format($bonus, 2) . '</td>
                <td align="right"><b>' . number_format($net_salary, 2) . '</b></td>
            </tr>';
    }


    if ($totalNetSalary == 0) {
        $_SESSION['error'] = "No payroll data for the selected period.";
        header('Location: payroll_runs.php');
        exit;
    }

    // Append total row
    $contents .= '
    <tr>
        <td colspan="10" align="right"><b>Total</b></td>
        <td align="right"><b>' . number_format($totalNetSalary, 2) . '</b></td>
    </tr>';

    return $contents;
}

function savePayrollToDatabase($employee_id, $gross_salary, $deductions, $deductionss, $mandatory_deductions, $late, $undertime, $present, $overtime, $allowances, $cash_advance, $bonus, $net_salary, $status, $pay_period_id, $conn)
{
    $conn->begin_transaction();
    try {
        // First, check if the payroll record already exists for the given employee and pay period
        $sql = "SELECT COUNT(*) FROM payroll WHERE employee_id = ? AND pay_period_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            return;
        }

        $stmt->bind_param("is", $employee_id, $pay_period_id); // Assuming pay_period_id is a string
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return;
        }

        $count = 0;

        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();




        // If the record exists, update it
        if ($count > 0) {
            $sql = "UPDATE payroll SET
                        gross_salary = ?,
                        tot_deductions = ?,
                        deductions = ?,

                          mandatory_deductions = ?,
                        late = ?,
                         undertime=?,
                        present = ?,
                        overtime = ?,
                        allowances = ?,
                        cash_advance = ?,
                        bonus = ?,
                        net_salary = ?,
                        `status` = ?
                    WHERE employee_id = ? AND pay_period_id = ?";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                return;
            }

            $stmt->bind_param("sssssssssssssss", $gross_salary, $deductions, $deductionss, $mandatory_deductions, $late, $undertime, $present, $overtime, $allowances, $cash_advance, $bonus, $net_salary, $status, $employee_id, $pay_period_id);
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
            } else {
                $_SESSION['success'] = 'Payroll successfully updated!';
                header('location: payroll_runs.php');
            }
        } else {
            // If the record doesn't exist, insert it
            $sql = "INSERT INTO payroll (employee_id, gross_salary, tot_deductions, deductions,mandatory_deductions, late, undertime, present,overtime, allowances, cash_advance, bonus, net_salary, `status`, pay_period_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . $conn->error);
                return;
            }

            $stmt->bind_param("issssssssssssss", $employee_id, $gross_salary, $deductions, $deductionss, $mandatory_deductions, $late, $undertime, $present, $overtime, $allowances, $cash_advance, $bonus, $net_salary, $status, $pay_period_id);
            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
            } else {
                $_SESSION['success'] = 'Payroll successfully saved!';
                header('location: payroll_runs.php');
            }
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
        header('location: payroll_runs.php');
    }
}






function calculateSSSContribution($gross_salary, $from_date, $to_date)
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

    // Detect payroll frequency
    $payroll_frequency = detectPayrollFrequency($from_date, $to_date);

    if ($payroll_frequency === 'unknown') {
        return [
            'employee_sss' => 0,
            'employer' => 0,
            'total' => 0
        ];
    }

    // Determine MSC based on gross salary
    $msc = 0;
    foreach ($sss_table as $range) {
        if ($gross_salary >= $range['min'] && $gross_salary <= $range['max']) {
            $msc = $range['msc'];
            break;
        }
    }

    if ($msc === 0) {
        $msc = 15000; // Default max MSC if salary exceeds table
    }

    // Standard monthly contributions (default for monthly)
    $employee_share = round($msc * 0.045, 2); // 4.5%
    $employer_share = round($msc * 0.095, 2); // 9.5%

    // Calculate the number of days in the payroll period
    $date1 = new DateTime($from_date);
    $date2 = new DateTime($to_date);
    $interval = $date1->diff($date2);
    $num_days = $interval->days + 1; // Include the last day

    // Adjust contributions based on payroll frequency
    switch ($payroll_frequency) {
        case 'weekly':
            // Weekly: Contributions based on number of days in the period
            $employee_share = round(($employee_share / 30) * $num_days, 2);
            $employer_share = round(($employer_share / 30) * $num_days, 2);
            break;
        case 'semi-monthly':
            // Semi-Monthly: Contributions based on number of days in the period
            $employee_share = round(($employee_share / 30) * $num_days, 2);
            $employer_share = round(($employer_share / 30) * $num_days, 2);
            break;
        case 'monthly':
            // Monthly: Contributions based on the actual number of days in the month
            $employee_share = round(($employee_share / 30) * $num_days, 2);
            $employer_share = round(($employer_share / 30) * $num_days, 2);
            break;
        default:
            error_log("Unrecognized payroll frequency: " . $payroll_frequency);
            return 'unknown';
    }

    // Calculate total contribution
    $total_contribution = $employee_share + $employer_share;

    return [
        'employee_sss' => $employee_share,
        'employer' => $employer_share,
        'total' => $total_contribution
    ];
}




// function calculateMandatoryDeductions($from, $to, $payroll_frequency, $conn)
// {
//     $sql = "SELECT benefit_type, amount FROM mandatory_benefits 
//     WHERE created_at <= '$to' 
//     AND status = 'active'";
//     $result = $conn->query($sql);

//     $total_mandatory = 0;

//     if ($result->num_rows > 0) {
//         while ($row = $result->fetch_assoc()) {
//             // Exclude SSS from mandatory deductions
//             if ($row['benefit_type'] === 'SSS') {
//                 continue;
//             }
//             $total_mandatory += $row['amount'];
//         }
//     }

//     // Adjust for payroll frequency
//     switch ($payroll_frequency) {
//         case 'weekly':
//             $total_mandatory /= 4;
//             break;
//         case 'semi-monthly':
//             $total_mandatory /= 2;
//             break;
//         case 'monthly':
//             // No adjustment needed
//             break;
//         default:
//             throw new Exception("Invalid payroll frequency");
//     }

//     // Split into employee and employer shares
//     $employee_share = $total_mandatory / 2;
//     $employer_share = $total_mandatory / 2;

//     return [
//         'employee' => round($employee_share, 2),
//         'employer' => round($employer_share, 2)
//     ];
// }


function calculateMandatoryDeductions($from, $to, $conn)
{
    $payroll_frequency = detectPayrollFrequency($from, $to); // Detect the frequency


    $sql = "SELECT benefit_type, amount FROM mandatory_benefits 
    WHERE created_at <= '$to' 
    AND status = 'active'";
    $result = $conn->query($sql);

    if (!$result) {
        error_log("SQL Query Failed: " . $conn->error);
        return ['employee' => 0, 'employer' => 0, 'deductions' => []]; // Return default values in case of failure
    }

    $mandatory_deductions = []; // Array to hold deductions by type
    $total_mandatory = 0;

    // Iterate over the results and accumulate the amounts
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            if ($row['benefit_type'] === 'SSS') {
                continue;
            }

            $benefit_type = $row['benefit_type'];
            $amount = $row['amount'];

            if (!is_numeric($amount) || $amount <= 0) {
                continue; // Skip invalid or zero amounts
            }

            $total_mandatory += $amount;

            // Store the amount for each benefit type
            $mandatory_deductions[] = [
                'benefit_type' => $benefit_type,
                'amount' => $amount
            ];
        }
    } else {
        error_log("No records found in mandatory_benefits for the given period: $from to $to");
    }

    switch ($payroll_frequency) {
        case 'weekly':
            $total_mandatory /= 4; // Assume 4 weeks in a month
            break;
        case 'semi-monthly':
            $total_mandatory /= 2; // Assume 2 pay periods in a month
            break;
        case 'monthly':
            // No adjustment needed for monthly
            break;
        default:
            error_log("Unrecognized payroll frequency: " . $payroll_frequency);
            break;
    }

    $employee_share = $total_mandatory / 2;
    $employer_share = $total_mandatory / 2;

    // Add the divided share amounts to each deduction
    foreach ($mandatory_deductions as &$deduction) {
        $deduction['employee_share'] = $employee_share;
        $deduction['employer_share'] = $employer_share;
    }

    // Return the result as an array
    return [
        'employee' => $employee_share,
        'employer' => $employer_share,
        'deductions' => $mandatory_deductions
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
        AND status = 'pending'
       
    ");
    $stmt->bind_param("iss", $employee_id, $startMonthYear, $endMonthYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['total_bonus'] ?? 0;
}

function markBonusAsPaid($employee_id, $from, $to, $conn)
{
    // Extract month and year from $from and $to
    $startDate = new DateTime($from);
    $endDate = new DateTime($to);
    $startMonthYear = $startDate->format('F Y'); // e.g., "December 2024"
    $endMonthYear = $endDate->format('F Y');

    // Update bonus status to 'Paid'
    $stmt = $conn->prepare("
        UPDATE bonus_incentives 
        SET status = 'Paid', updated_at = NOW()
        WHERE employee_id = ? 
        AND bonus_period BETWEEN ? AND ? 
        AND status = 'pending'
    ");
    $stmt->bind_param("iss", $employee_id, $startMonthYear, $endMonthYear);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true; // Bonuses were successfully updated
    } else {
        return false; // No bonuses to update or already marked as Paid
    }
}




function detectPayrollFrequency($from, $to)
{
    $start_date = new DateTime($from);
    $end_date = new DateTime($to);
    $interval = $start_date->diff($end_date)->days + 1; // Include both start and end dates

    // Determine payroll frequency based on interval
    if ($interval <= 7) {
        return 'weekly';
    } elseif ($interval > 7 && $interval <= 15) {
        return 'semi-monthly';
    } elseif ($interval > 15 && $interval <= 31) {
        return 'monthly';
    } else {
        error_log("Unrecognized payroll frequency: Interval = " . $interval);
        return 'unknown';
    }
}





function calculateCashAdvance($employee_id, $from, $to, $conn)
{
    $sql = "SELECT SUM(advance_amount) as total_cash FROM `cashadvance` 
            WHERE employee_id = '$employee_id' AND advance_date BETWEEN '$from' AND '$to'";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total_cash'] ? $row['total_cash'] : 0;
}



function calculateAllowances($employee_id, $from, $to, $payroll_frequency, $conn)
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

                if ($payroll_frequency == 'weekly') {
                    $total_allowances += $row['allowance_amount'];
                }
                break;
            case 'monthly':

                if ($payroll_frequency == 'monthly') {
                    $total_allowances += $row['allowance_amount'];
                }
                break;
        }
    }

    return $total_allowances;
}


function calculateDeductions($employee_id, $from, $to, $payroll_frequency, $conn)
{
    $sql = "SELECT deductions_employees.*, deductions.* 
            FROM deductions_employees 
            JOIN deductions ON deductions.dedID = deductions_employees.deducid 
            WHERE deductions_employees.employee_id = '$employee_id' AND deductions_employees.created_on BETWEEN '$from' AND '$to'";

    $result = $conn->query($sql);
    // error_log("Query executed: " . $sql);
    // error_log("Rows returned: " . $result->num_rows); // Log the number of rows

    $total_deductions = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            switch ($row['deduction_type']) {
                case 'Once':
                    $total_deductions += $row['deduc_amount'];
                    error_log("Once Deduction: " . $row['deduc_amount']);
                    break;
                case 'Weekly':
                    if ($payroll_frequency == 'weekly') {
                        $total_deductions += $row['deduc_amount'];
                        error_log("Weekly Deduction: " . $row['deduc_amount']);
                    }
                    break;
                case 'Monthly':
                    if ($payroll_frequency == 'monthly') {
                        $total_deductions += $row['deduc_amount'];
                        error_log("Monthly Deduction: " . $row['deduc_amount']);
                    }
                    break;
            }
        }
    } else {
        error_log("No deductions found for employee: $employee_id");
    }

    return $total_deductions;
}
