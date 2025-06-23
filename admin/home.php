<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
	$year = $_GET['year'];
}



$payrollTrendData = [];
$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

foreach ($months as $key => $month) {
	$monthIndex = $key + 1; // MySQL months are 1-indexed
	$query = $conn->query("SELECT SUM(net_salary) as total FROM payroll WHERE MONTH(created_at) = $monthIndex AND YEAR(created_at) = YEAR(CURDATE())");
	$payrollTrendData[] = $query->fetch_assoc()['total'] ?? 0;
}

// Salary distribution data (categories: Basic Pay, Allowances, Deductions)
$query = $conn->query("SELECT 
    SUM(gross_salary) as basicPay, 
    SUM(allowances) as allowances, 
    SUM(tot_deductions) as tot_deductions,
	  SUM(overtime) as tot_overtime 
    FROM payroll");


$distributionData = $query->fetch_assoc();
$basicPay = $distributionData['gross_salary'] ?? 0;
$allowances = $distributionData['allowances'] ?? 0;
$deductions = $distributionData['tot_deductions'] ?? 0;
$overtime = $distributionData['tot_overtime'] ?? 0;

// Pass PHP data to JavaScript
echo "
<script>
    const payrollTrendData = " . json_encode($payrollTrendData) . ";
    const salaryDistributionData = {
        basicPay: $basicPay,
        allowances: $allowances,
        deductions: $deductions,
		Overtime:$overtime

    };
</script>
";

?>



<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>
<style>
	.custom-chart {
    max-height: 300px; /* Adjust as needed */
    height: 100%; 
    width: 100%;
}
</style>
<body>
	<!-- Page wrapper start -->
	<div class="page-wrapper">

		<!-- Main container start -->
		<div class="main-container">

			<!-- Sidebar wrapper start -->
			<nav id="sidebar" class="sidebar-wrapper">

				<!-- App brand starts -->
				<?php include 'includes/navbar.php'; ?>
				<!-- Sidebar profile ends -->

				<!-- Sidebar menu starts -->
				<?php include 'includes/menubar.php'; ?>
				<!-- Sidebar menu ends -->

			</nav>
			<!-- Sidebar wrapper end -->

			<!-- App container starts -->
			<div class="app-container">

				<!-- App header starts -->
				<div class="app-header d-flex align-items-center">

					<!-- Toggle buttons start -->
					<div class="d-flex">
						<button class="btn btn-outline-dark me-2 toggle-sidebar" id="toggle-sidebar">
							<i class="bi bi-chevron-left fs-5"></i>
						</button>
						<button class="btn btn-outline-dark me-2 pin-sidebar" id="pin-sidebar">
							<i class="bi bi-chevron-left fs-5"></i>
						</button>
					</div>
					<!-- Toggle buttons end -->

					<!-- App brand sm start -->
					<div class="app-brand-sm d-md-none d-sm-block">
						<!-- <a href="home.php">
								<img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">
							</a> -->
					</div>
					<!-- App brand sm end -->

					<!-- App header actions start -->
					<?php include 'includes/navheader.php'; ?>
					<!-- App header actions end -->

				</div>
				<!-- App header ends -->

				<!-- App hero header starts -->
				<div class="app-hero-header">

					<!-- Page Title start -->
					<div>
						<h5 class="fw-light">Hello <?php echo ucwords($user['fname']) . ' ' . ucwords($user['lname']); ?>,</h5>
						<h3 class="fw-light">Have a good day :)</h3>
					</div>
					<!-- Page Title end -->

					<!-- Header graphs start -->

					<!-- Header graphs end -->

				</div>
				<!-- App Hero header ends -->

				<!-- App body starts -->
				<div class="app-body">



					<?php include 'flash_messages.php'; ?>

					<!-- Row start -->
					<div class="row">
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card mb-4 rounded-4 py-2 bg-light-blue">
								<div class="card-body d-flex align-items-center text-white">
									<div class="icon-box lg p-4 rounded-4 me-3 shadow-solid-rb border border-white">
										<i class="bi bi-pie-chart fs-3 lh-1"></i>
									</div>
									<?php
									
									$sql = "SELECT COUNT(*) AS total FROM employee 
											left join employee_details on employee_details.employee_id = employee.employee_id
											 where employee_details.status = 'Active' and employee.is_archived = 0";
									$query = $conn->query($sql);

								
									$totalemployee = 0;
									if ($query) {
										$row = $query->fetch_assoc();
										$totalemployee = $row['total'];
									}
									?>
									<div class="m-0">
										<h5 class="fw-light mb-1">Total Employees</h5>
										<h2 class="m-0"><?php echo $totalemployee; ?></h2>
									</div>
								</div>
							</div>
						</div>


						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card mb-4 rounded-4 py-2 bg-orange">
								<div class="card-body d-flex align-items-center text-white">
									<div class="icon-box lg p-4 rounded-4 me-3 shadow-solid-rb border border-white">
										<i class="bi bi-sticky fs-3 lh-1"></i>
									</div>


									<div class="m-0">
										<?php
										$totalPayroll = $conn->query("SELECT SUM(net_salary) as total FROM payroll WHERE status = 'paid' AND MONTH(created_at) = MONTH(CURDATE())")->fetch_assoc()['total'];


										?>
										<h5 class="fw-light mb-1">Total Payroll (This Month)</h5>
										<h2 id="total-payroll"><?php echo '₱' . number_format($totalPayroll, 2); ?></h3>


									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card mb-4 rounded-4 py-2 bg-danger">
								<div class="card-body d-flex align-items-center text-white">
									<div class="icon-box lg p-4 rounded-4 me-3 shadow-solid-rb border border-white">
										<i class="bi bi-cash-stack fs-3 lh-1"></i>
									</div>
									<div class="m-0">
										<?php
										$pendingDisbursementsQuery = $conn->query("SELECT SUM(net_salary) as total FROM payroll WHERE status = 'approve'");
										$pendingDisbursements = $pendingDisbursementsQuery->fetch_assoc()['total'] ?? 0;
										?>
										<h5>Pending Disbursements</h5>
										<h3 id="pending-disbursements"><?php echo '₱' . number_format($pendingDisbursements, 2); ?></h3>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card mb-4 rounded-4 py-2 bg-orange">
								<div class="card-body d-flex align-items-center text-white">
									<div class="icon-box lg p-4 rounded-4 me-3 shadow-solid-rb border border-white">
										<i class="bi bi-star fs-3 lh-1"></i>
									</div>
									<div class="m-0">
										<?php
										$paidSalariesQuery = $conn->query("SELECT SUM(net_salary) as total FROM payroll WHERE status = 'paid' AND MONTH(created_at) = MONTH(CURDATE())");
										$paidSalaries = $paidSalariesQuery->fetch_assoc()['total'] ?? 0;
										?>
										<h5>Paid Salaries</h5>
										<h3 id="paid-salaries"><?php echo '₱' . number_format($paidSalaries, 2); ?></h3>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-sm-6 col-12">
							<div class="card mb-4 rounded-4 py-2 bg-orange">
								<div class="card-body d-flex align-items-center text-white">
									<div class="icon-box lg p-4 rounded-4 me-3 shadow-solid-rb border border-white">
										<i class="bi bi-star fs-3 lh-1"></i>
									</div>
									<div class="m-0">
										<h5 class="fw-light mb-1">Department</h5>
										<?php
										$sql = "SELECT * FROM position";
										$query = $conn->query($sql);

										echo "<h2 class='m-0'>" . $query->num_rows . "</h3>"
										?>
									</div>
								</div>
							</div>
						</div>


					</div>


					<div class="row">
						<div class="col-xxl-6 col-xl-12">
							<div class="card mb-4">
								<div class="card-header">
									Payroll Trend					
								</div>
								<canvas id="payroll-trend"></canvas>
							</div>
						</div>
						<div class="col-xxl-6 col-xl-12">
							<div class="card mb-4">
								<div class="card-header">
									Salary Distribution
								</div>
								<canvas id="salary-distribution" class="custom-chart"></canvas>
							</div>
						</div>
					</div>








				</div>
				<!-- App body ends -->

				<!-- App footer start -->


				<?php include 'includes/footer.php'; ?>
				<!-- App footer end -->

			</div>
			<!-- App container ends -->

		</div>
		<!-- Main container end -->

	</div>
	<!-- Page wrapper end -->

	<?php include 'includes/scripts.php'; ?>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<script>
		const payrollTrendCtx = document.getElementById('payroll-trend').getContext('2d');
		const salaryDistributionCtx = document.getElementById('salary-distribution').getContext('2d');

		// Payroll Trend Chart
		new Chart(payrollTrendCtx, {
			type: 'line',
			data: {
				labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
				datasets: [{
					label: 'Payroll Trend',
					data: payrollTrendData,
					borderColor: 'rgba(75, 192, 192, 1)',
					backgroundColor: 'rgba(75, 192, 192, 0.2)',
				}]
			}
		});

		// Salary Distribution Chart
		new Chart(salaryDistributionCtx, {
			type: 'pie',
			data: {
				labels: ['Gross Pay', 'Allowances', 'Deductions', 'Overtime'],
				datasets: [{
					label: 'Salary Distribution',
					data: [salaryDistributionData.basicPay, salaryDistributionData.allowances, salaryDistributionData.deductions, salaryDistributionData.overtime],
					backgroundColor: ['#007bff', '#28a745', '#ffc107', '#a107fa']
				}]
			},
			options: {
        responsive: true,
        maintainAspectRatio: false, // Allows custom height and width
    }
		});
	</script>




</body>


</html>