<?php
include 'conn.php'; 

$date = date('Y-m-d');
$sql = "SELECT `attendanceid`, `employee_no`, `date`, `time_in`, `time_out`, `num_hr`, `status` 
        FROM `attendance` 
        WHERE `date` = '$date'";

$query = $conn->query($sql);

while ($row = $query->fetch_assoc()) {
    echo "<tr>
            <td>" . $row['employee_no'] . "</td>
            <td>" . $row['time_in'] . "</td>
            <td>" . $row['time_out'] . "</td>
          </tr>";
}
?>
