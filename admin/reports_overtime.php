<?php include 'includes/session.php'; 
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php_errors.log");
?>

<?php
$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 days', strtotime($range_to)));


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>

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


                        <h3 class="fw-light">
                            <span>Home</span> / <span class="menu-text">Overtime Reports</span>
                        </h3>
                    </div>
                    <!-- Page Title end -->

                    <!-- Header graphs start -->

                    <!-- Header graphs end -->

                </div>
                <!-- App Hero header ends -->

                <!-- App body starts -->
                <div class="app-body">

                    <!-- Flash Messages -->
                    <?php include 'flash_messages.php'; ?>

                    <!-- Payroll Run Table -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Overtime Reports</h5>

                                </div>

                                <div class="card-body">
                                    <form method="GET" action="">
                                        <div class="row mb-3">
                                            <!-- Status Filter -->


                                            <!-- Month Filter -->
                                            <div class="col-md-2">
                                                <label for="date_completed">Filter by (Month-Year):</label>
                                                <input type="month" class="form-control" id="date_completed"
                                                    name="date_completed"
                                                    value="<?php echo isset($_GET['date_completed']) ? $_GET['date_completed'] : ''; ?>">
                                            </div>

                                            <!-- Filter Button -->
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-info mt-4">Search</button>
                                            </div>
                                        </div>
                                    </form>

                                    <table id="13monthpay" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Employee No.</th>
                                                <th>Employee Name.</th>
                                                <th>Hours</th>

                                                <th>Rate</th>
                                                <th>Date</th>
                                                <th>Total Compensation</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Initialize the query
                                            $sql = "SELECT overtime.*, employee.employee_no,
                                                CONCAT(employee.first_name, ' ', employee.middle_name, ' ', employee.last_name, ' ', employee.name_extension) AS full_name
                                                FROM overtime 
                                                LEFT JOIN employee ON employee.employee_id = overtime.employee_id     
                                                WHERE 1=1";

                                            // Apply filters if set
                                            $params = [];
                                            $types = ""; // Parameter types string for bind_param
                                            
                                            

                                            if (!empty($_GET['date_completed'])) {
                                               $sql .= " AND DATE_FORMAT(overtime.date_overtime, '%Y-%m') COLLATE utf8mb4_unicode_ci = ?";

                                                $params[] = $_GET['date_completed'];
                                                $types .= "s"; // Add type string
                                            }

                                            $stmt = $conn->prepare($sql);
                                            if ($stmt) {
                                                if ($params) {
                                                    $stmt->bind_param($types, ...$params);
                                                }
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                // Display results
                                                foreach ($result as $row): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['employee_no']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                                        <td><?php echo number_format($row['hours'], 2); ?></td>

                                                        <td><?php echo htmlspecialchars($row['rate']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['date_overtime']); ?></td>

                                                        <td>
                                                            <?php
                                                            $total_compensation = $row['hours'] * $row['rate'];
                                                            echo number_format($total_compensation, 2);
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;
                                            } 
                                            ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- App body end -->
                <?php include 'includes/footer.php'; ?>
            </div>
            <!-- App container end -->
        </div>
        <!-- Main container end -->

        <!-- Generate Payroll Modal -->


        <!-- View Details Modal -->


        <?php include 'includes/scripts.php'; ?>
    </div>
    <!-- Page wrapper end -->

    <!-- AJAX for View Details -->




    <script>
        $(document).ready(function () {
            // Initialize DataTable with buttons
            $("#13monthpay").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                dom:
                    '<"row"<"col-sm-6"f><"col-sm-6 text-right"B>>' + // Search and buttons in the same row
                    '<"row"<"col-sm-12"tr>>' +                      // Table
                    '<"row"<"col-sm-5"i><"col-sm-7"p>>',           // Info and pagination
                buttons: ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#13monthpay_wrapper .col-sm-6:eq(1)');
        });
    </script>

</body>

</html>