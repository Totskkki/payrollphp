<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';

$year = date('Y');
$today = date('Y-m-d');
if (isset($_GET['year'])) {
    $year = $_GET['year'];
}


require '../vendor/autoload.php'; // Include the Composer autoloader
use PhpOffice\PhpSpreadsheet\IOFactory;




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

                <!-- App brand -->
                <?php include 'includes/navbar.php'; ?>

                <!-- Sidebar menu -->
                <?php include 'includes/menubar.php'; ?>

            </nav>
            <!-- Sidebar wrapper end -->

            <!-- App container -->
            <div class="app-container">

                <!-- App header -->
                <div class="app-header d-flex align-items-center">

                    <!-- Toggle buttons -->
                    <div class="d-flex">
                        <button class="btn btn-outline-dark me-2 toggle-sidebar" id="toggle-sidebar">
                            <i class="bi bi-chevron-left fs-5"></i>
                        </button>
                        <button class="btn btn-outline-dark me-2 pin-sidebar" id="pin-sidebar">
                            <i class="bi bi-chevron-left fs-5"></i>
                        </button>
                    </div>

                    <!-- App brand for small screens -->
                    <div class="app-brand-sm d-md-none d-sm-block">
                        <!-- <a href="index.html">
              <img src="assets/images/logo-dark.svg" class="logo" alt="Logo">
            </a> -->
                    </div>

                    <!-- App header actions -->
                    <?php include 'includes/navheader.php'; ?>

                </div>
                <!-- App header ends -->

                <!-- App hero header -->
                <div class="app-hero-header">
                    <!-- Page Title -->
                    <div>
                        <h3 class="fw-light">
                            <span>Home</span> / <span class="menu-text">Attendance</span>
                        </h3>
                    </div>
                </div>
                <!-- App hero header ends -->

                <!-- App body -->
                <div class="app-body">

                    <!-- PHP alert messages -->
                    <?php include 'flash_messages.php'; ?>

                    <!-- Main content area -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                                    <!-- Title Section -->
                                    <div>
                                        <h5>Payroll Audit </h5>
                                    </div>
                                    <!-- File Upload Form -->

                                </div>



                                <div class="card-body">


                                    <table id="13monthpay" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>User</th>
                                                <th>Details</th>
                                                <th>Date & Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = $conn->query("SELECT *,users.* FROM audit_logs
                                                    JOIN users on users.userid = audit_logs.user_id 
                                            
                                                 ORDER BY audit_logs.log_id DESC");

                                            if ($result->num_rows > 0) {
                                                $counter = 1; // Initialize the counter variable
                                                while ($log = $result->fetch_assoc()) {
                                                    echo "<tr>
                                                    <td>{$counter}</td> <!-- Display the counter value -->
                                                    <td>{$log['action']}</td>
                                                    <td>{$log['username']}</td>
                                                    <td>{$log['description']}</td>
                                                    <td>{$log['timestamp']}</td>
                                                </tr>";
                                                    $counter++; // Increment the counter after each row
                                                }
                                            }
                                            ?>



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Reports -->


                </div>
                <!-- App body ends -->

                <!-- Footer -->
                <?php include 'includes/footer.php'; ?>

            </div>
            <!-- App container ends -->

        </div>
        <!-- Main container ends -->

    </div>
    <!-- Page wrapper end -->

    <?php include 'modals/attendance_modal.php'; ?>

    <?php include 'includes/scripts.php'; ?>



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