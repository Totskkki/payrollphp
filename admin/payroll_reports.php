<?php include 'includes/session.php'; ?>
<?php include '../timezone.php'; ?>

<?php
$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 days', strtotime($range_to)));
?>

<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Payslip Reports</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Payslip reports</li>
                </ol>
            </section>

            <section class="content">
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-warning'></i> Error!</h4>{$_SESSION['error']}
                      </div>";
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo "<div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Success!</h4>{$_SESSION['success']}
                      </div>";
                    unset($_SESSION['success']);
                }
                ?>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header with-border">

                                <div class="col-md-2">
                                    <label for="year">Year</label>
                                    <select id="year" class="form-control">
                                        <?php
                                        $currentYear = date("Y");
                                        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                                            echo "<option value=\"$i\">$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="month">Month</label>
                                    <select id="month" class="form-control">
                                        <?php
                                        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                        foreach ($months as $key => $month) {
                                            echo "<option value=\"" . ($key + 1) . "\">$month</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2 ">
                                    <label for="fetch_reports"></label>
                                    <button type="button" id="fetch_reports" class="form-control btn btn-primary">Show Reports</button>
                                </div>

                            </div>
                            <div class="box-body">
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <th>Employee Name</th>
                                        <th>Employee ID</th>
                                        <th>Date Hired</th>
                                        <th>Position</th>
                                        <th>Gross Salary</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <!-- <th>Payslip</th> -->
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include 'includes/footer.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>


    <!-- <script>
        document.getElementById('fetch_reports').addEventListener('click', function() {
            var year = document.getElementById('year').value;
            var month = document.getElementById('month').value;

            fetch('fetch_payslip_reports.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        year: year,
                        month: month
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.message);
                        alert('Error fetching reports: ' + data.message);
                        return;
                    }

                    var tbody = document.querySelector('#example1 tbody');
                    tbody.innerHTML = ''; // Clear existing rows

                    data.data.forEach(function(row) {
                        var tr = document.createElement('tr');

                        tr.innerHTML = `
                <td>${row.full_name}</td>
                <td>${row.userid}</td>
                <td>${row.date_hired}</td>
                <td>${row.position}</td>
                <td>${row.gross_salary}</td>
                <td>${row.deductions}</td>
                <td>${row.net_salary}</td>
                <td><a href="payslip.php?employee_id=${row.userid}&year=${year}&month=${month}" class="btn btn-primary btn-sm">View Payslip</a></td>
            `;

                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error fetching reports:', error);
                    alert('Error fetching reports: ' + error.message);
                });
        });
    </script> -->

    <script>
$(document).ready(function() {
    $('#fetch_reports').click(function() {
        var year = $('#year').val();
        var month = $('#month').val();

        $.ajax({
            url: 'ajax/payroll_reports.php',
            type: 'POST',
            dataType: 'json',
            data: {
                year: year,
                month: month
            },
            success: function(data) {
                var tbody = $('#example1 tbody');
                tbody.empty(); // Clear existing rows

                if (data.error) {
                    console.error('Error:', data.message);
                    alert('Error fetching reports: ' + data.message);
                } else {
                    if (data.data.length === 0) {
                        // No data for the selected month, clear the table
                        tbody.append('<tr><td colspan="7" class="text-center">No payroll records found for the selected month.</td></tr>');
                    } else {
                        // Populate the table with data
                        data.data.forEach(function(row) {
                            var tr = $('<tr>');
                            var gross_salary = row.selected_month.gross_salary;
                            var deductions = row.selected_month.deductions;
                            var net_salary = row.selected_month.net_salary;

                            tr.html(`
                                <td>${row.full_name}</td>
                                <td>${row.QR_code}</td>
                                <td>${row.date_hired}</td>
                                <td>${row.position}</td>
                                <td>${gross_salary}</td>
                                <td>${deductions}</td>
                                <td>${net_salary}</td>
                            `);
                            tbody.append(tr);
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching reports:', error);
                alert('Error fetching reports: ' + error.message);
            }
        });
    });
});
</script>

</body>

</html>