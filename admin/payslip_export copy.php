<?php
require_once('../tcpdf/tcpdf.php'); 


if (isset($_GET['export']) && ($_GET['export'] === 'csv' || $_GET['export'] === 'pdf')) {
    $exportType = $_GET['exportType'];
    $full_name = $_GET['full_name'];
    $position = $_GET['position'];
    $employee_id = $_GET['employee_id'];
    $joining_date = $_GET['joining_date'];
    $rate = $_GET['rate'];
    $total_hours = $_GET['total_hours'];
    $overtime_pay = $_GET['overtime_pay'];
    $gross_salary = $_GET['gross_salary'];
    $late_duration = $_GET['late_duration'];
    $deductions = json_decode($_GET['deductions'], true);
    $total_deductions = $_GET['total_deductions'];
    $net_salary = $_GET['net_salary'];
    $month = isset($_GET['month']) ? $_GET['month'] : ''; 



    // Handle CSV export
    if ($exportType === 'csv') {
        // CSV export logic (already implemented)
        $filename = "payslip_" . $employee_id . "_" . $month . ".csv";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Field', 'Value']);

        fputcsv($output, ['Full Name', $full_name]);
        fputcsv($output, ['Position', $position]);
        fputcsv($output, ['Employee ID', $employee_id]);
        fputcsv($output, ['Joining Date', date('d M Y', strtotime($joining_date))]);
        fputcsv($output, ['Rate per Hour', $rate]);
        fputcsv($output, ['Total Hours worked', number_format($total_hours, 2)]);
        fputcsv($output, ['Overtime Pay', number_format($overtime_pay, 2)]);
        fputcsv($output, ['Total Earnings', number_format($gross_salary, 2)]);
        fputcsv($output, ['Late Duration', $late_duration]);

        foreach ($deductions as $deduction) {
            fputcsv($output, [ucwords($deduction['description']), number_format($deduction['amount'], 2)]);
        }

        fputcsv($output, ['Total Deductions', number_format($total_deductions, 2)]);
        fputcsv($output, ['Net Salary', number_format($net_salary, 2)]);

        fclose($output);
        exit();
    } 
    // Handle PDF export
    elseif ($exportType === 'pdf') {
        // PDF export logic (already implemented)
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        $html = '<h1>Payslip for ' . $month . '</h1>';
        $html .= '<p>Full Name: ' . $full_name . '</p>';
        $html .= '<p>Position: ' . $position . '</p>';
        $html .= '<p>Employee ID: ' . $employee_id . '</p>';
        $html .= '<p>Joining Date: ' . date('d M Y', strtotime($joining_date)) . '</p>';
        $html .= '<p>Rate per Hour: ' . $rate . '</p>';
        $html .= '<p>Total Hours worked: ' . number_format($total_hours, 2) . '</p>';
        $html .= '<p>Overtime Pay: ' . number_format($overtime_pay, 2) . '</p>';
        $html .= '<p>Total Earnings: ' . number_format($gross_salary, 2) . '</p>';
        $html .= '<p>Late Duration: ' . $late_duration . '</p>';

        foreach ($deductions as $deduction) {
            $html .= '<p>' . ucwords($deduction['description']) . ': ' . number_format($deduction['amount'], 2) . '</p>';
        }

        $html .= '<p>Total Deductions: ' . number_format($total_deductions, 2) . '</p>';
        $html .= '<p>Net Salary: ' . number_format($net_salary, 2) . '</p>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output('payslip_' . $employee_id . '_' . $month . '.pdf', 'D');
        exit();
    }
    }

