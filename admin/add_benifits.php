<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
    $year = $_GET['year'];
}



?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>

<style>
    .form-section {
        margin-bottom: 20px;
    }

    .tooltip-inner {
        max-width: 300px;
        font-size: 14px;
    }

    .search-input {
        margin-bottom: 10px;
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
                        <a href="index.html">
                            <img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">
                        </a>
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
                            <span>Home</span> / <span class="menu-text">Mandatory Benifits</span>
                        </h3>
                    </div>
                    <!-- Page Title end -->

                    <!-- Header graphs start -->

                    <!-- Header graphs end -->

                </div>
                <!-- App Hero header ends -->

                <!-- App body starts -->
                <div class="app-body">

                    <?php
                    if (isset($_SESSION['error'])) {
                        echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert' id='errorAlert'>
        <i class='fa fa-exclamation-circle me-2'></i>
        <strong>Error!</strong> {$_SESSION['error']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
                        unset($_SESSION['error']);
                    }
                    if (isset($_SESSION['success'])) {
                        echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert' id='successAlert'>
        <i class='fa fa-check-circle me-2'></i>
        <strong>Success!</strong> {$_SESSION['success']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
                        unset($_SESSION['success']);
                    }
                    ?>




                    <!-- Row start -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">



                                </div>
                                <div class="card-body">
                                    <!-- Row start -->
                                    <div class="col-lg-3 col-sm-4 col-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="employeeSearch">Search Employee</label>
                                            <input type="text" class="form-control" id="employeeSearch"
                                                placeholder="Search Employee...">
                                        </div>
                                    </div>
                                    <!-- Employee Selection -->
                                    <div class="form-section">
                                        <label for="employeeSelect">Employee</label>
                                        <select class="form-control select2" id="employeeSelect" name="employee_id"
                                            required>
                                            <option value="">Select an Employee</option>
                                            <!-- Options will be populated dynamically via AJAX -->
                                        </select>
                                    </div>


                                    <div class="row">

                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc">Name</label>
                                                <input type="text" class="form-control" id="abc"
                                                    placeholder="Enter fullname">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc2">Email</label>
                                                <input type="email" class="form-control" id="abc2"
                                                    placeholder="Enter email address">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc3">Phone</label>
                                                <input type="number" class="form-control" id="abc3"
                                                    placeholder="Enter phone number">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc4">Company</label>
                                                <input type="text" class="form-control" id="abc4"
                                                    placeholder="Enter company name">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc5">Business Address</label>
                                                <input type="text" class="form-control" id="abc5"
                                                    placeholder="Enter business address">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc6">Province/Territory</label>
                                                <input type="text" class="form-control" id="abc6"
                                                    placeholder="Enter province/territory">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc7">Industry Type</label>
                                                <select class="form-select" id="abc7">
                                                    <option value="0">Select</option>
                                                    <option value="1">One</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc8">Postal Code</label>
                                                <input type="number" class="form-control" id="abc8"
                                                    placeholder="Enter postal code">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="abc9">Message</label>
                                                <textarea class="form-control" placeholder="Enter message" id="abc9"
                                                    rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Row end -->
                                </div>
                            </div>
                        </div>

                    </div>




                </div>
                <!-- App body ends -->

                <!-- App footer start -->
                <?php include 'includes/footer.php'; ?>
                <?php include 'modals/employee_modal.php'; ?>
                <!-- App footer end -->

            </div>
            <!-- App container ends -->

        </div>
        <!-- Main container end -->

    </div>
    <!-- Page wrapper end -->

    <?php include 'includes/scripts.php'; ?>

    <script>
        $(document).ready(function () {
            $('.select2').select2(); // Initialize Select2 for dropdowns

            // Listen for input in the search field
            $('#employeeSearch').on('input', function () {
                var query = $(this).val();

                if (query.length >= 2) { // Minimum length of input to start searching
                    $.ajax({
                        url: 'ajax/searchEmployees.php',
                        method: 'GET',
                        data: { query: query },
                        success: function (response) {
                            $('#employeeSelect').html(response);
                        },
                        error: function () {
                            alert('Error fetching data');
                        }
                    });
                } else {
                    // Clear the dropdown if the query length is less than 2 characters
                    $('#employeeSelect').html('<option value="">Select an Employee</option>');
                }
            });
        });
    </script>
</body>


</html>