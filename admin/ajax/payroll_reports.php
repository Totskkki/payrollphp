<?php
include '../includes/conn.php';
session_start();
// Ensure JSON header is set
header('Content-Type: application/json');

$output = ['error' => false, 'data' => []];

// Validate request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve input
    $year = $_POST['year'] ?? '';
    $month = $_POST['month'] ?? '';

    // Validate input
    if (!is_numeric($year) || !is_numeric($month) || $year < 1970 || $year > 2100 || $month < 1 || $month > 12) {
        $output['error'] = true;
        $output['message'] = 'Invalid year or month values';
    } else {
        // Construct date range for the whole year
        $from = "$year-01-01";
        $to = "$year-12-31";

        // Query to fetch payroll data for the entire year
        $sql = "SELECT users.userid, users.QR_code, CONCAT(names.firstname, ' ', names.middlename, ' ', names.lastname, ' ', names.name_extension) AS full_name,
                users.created_on AS date_hired, position.description AS position,
                payroll.gross_salary, payroll.deductions, payroll.net_salary,
                MONTH(payroll.date_generated) AS payroll_month
                FROM payroll
                LEFT JOIN users ON payroll.employee_id = users.userid
                LEFT JOIN names ON names.namesid = users.names_id
                LEFT JOIN position ON position.positionid = users.position_id
                WHERE payroll.date_generated BETWEEN '$from' AND '$to'";

        $query = $conn->query($sql);

        if ($query) {
            $data = $query->fetch_all(MYSQLI_ASSOC);
            $groupedData = [];

            // Group data by employee ID
            foreach ($data as $row) {
                $userid = $row['userid'];
                if (!isset($groupedData[$userid])) {
                    $groupedData[$userid] = [
                        'QR_code' => $row['QR_code'],
                        'full_name' => $row['full_name'],
                        'date_hired' => $row['date_hired'],
                        'position' => $row['position'],
                        'payrolls' => []
                    ];
                }
                $groupedData[$userid]['payrolls'][$row['payroll_month']] = [
                    'gross_salary' => $row['gross_salary'],
                    'deductions' => $row['deductions'],
                    'net_salary' => $row['net_salary']
                ];
            }

            // Format output data
            foreach ($groupedData as &$employee) {
                $employee['selected_month'] = $employee['payrolls'][(int)$month] ?? null;
            }

            $output['data'] = array_values($groupedData);
        } else {
            $output['error'] = true;
            $output['message'] = $conn->error;
        }
    }
} else {
    $output['error'] = true;
    $output['message'] = 'Invalid request method';
}

echo json_encode($output);
?>
