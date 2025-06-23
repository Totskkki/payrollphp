<?php
include '../includes/conn.php';
include '../../vendor/autoload.php'; // For PHPMailer
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['payrollid'])) {
    $payrollid = intval($_POST['payrollid']);
    $admin_id = $_SESSION['admin'];

    $conn->begin_transaction();

    try {
        // 1. Update Payroll Status
        $sql = "UPDATE payroll SET status = 'approve' WHERE payrollid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $payrollid);
        $stmt->execute();

        // 2. Fetch Payroll and Employee Details
        $employee_sql = "SELECT employee.email, concat(employee.first_name,' ',employee.last_name) as `name`, payroll.net_salary, 
                                pay_periods.from_date, pay_periods.to_date, employee.employee_id  
                         FROM payroll 
                         JOIN employee ON employee.employee_id = payroll.employee_id
                         JOIN pay_periods ON pay_periods.payid = payroll.pay_period_id 
                         WHERE payrollid = ?";
        $employee_stmt = $conn->prepare($employee_sql);
        $employee_stmt->bind_param("i", $payrollid);
        $employee_stmt->execute();
        $employee_result = $employee_stmt->get_result();

        if ($employee_result->num_rows > 0) {
            $employee = $employee_result->fetch_assoc();
            $employeeEmail = $employee['email'];
            // $employeeName = $employee['first_name'];
            $name = $employee['name'];
            $fromDate = $employee['from_date'];
            $toDate = $employee['to_date'];
            $netSalary = number_format($employee['net_salary'], 2);
            $employeeId = $employee['employee_id']; // employee ID for notifications

            // 3. Insert into Audit Logs
            $log_sql = "INSERT INTO audit_logs (user_id, action, description) 
                        VALUES (?, 'approve', 'Approved payroll for employee: $name')";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("i", $admin_id);
            $log_stmt->execute();

            // 4. Send Email Notification
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'jvpayroll@lutayanrhu.site'; // Replace with your email
            $mail->Password = 'Q4a@39nq;'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('jvpayroll@lutayanrhu.site', 'JVENUS Payroll System');
            $mail->addAddress($employeeEmail, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Payroll Approved Notification';
            $mail->Body = "
                <p>Dear <strong>$name</strong>,</p>
                <p>Your payroll has been <strong>approved</strong> with the following details:</p>
                <ul>
                    <li><strong>Payroll Period:</strong> $fromDate to $toDate</li>
                    <li><strong>Net Salary:</strong> ₱$netSalary</li>
                </ul>
                <p>Thank you for your hard work and dedication!</p>
                <p>Best Regards,<br><strong>JVENUS Payroll System</strong></p>
            ";

            $mail->send();

            // 5. Insert Notification into payroll_notifications table
            $notification_message = "Your payroll for the period $fromDate to $toDate has been approved. Net Salary: ₱$netSalary.";
            $notification_sql = "INSERT INTO payroll_notifications (employee_id, payroll_id, message, status) 
                                 VALUES (?, ?, ?, 'unread')";
            $notification_stmt = $conn->prepare($notification_sql);
            $notification_stmt->bind_param("iis", $employeeId, $payrollid, $notification_message);
            $notification_stmt->execute();
        }

        // 6. Commit Transaction
        $conn->commit();

        echo "Success";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
    $log_stmt->close();
    $employee_stmt->close();
    $notification_stmt->close();
    $conn->close();
}
?>
