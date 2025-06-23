<?php include 'includes/session.php'; ?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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
                            <span>Home</span> / <span class="menu-text">Personal Details </span>
                        </h3>
                    </div>
                    <!-- Page Title end -->

                    <!-- Header graphs start -->
                    <div class="ms-auto d-flex gap-2">
                        <a href="javascript:void(0)" onclick="history.back();"
                            class="btn bg-secondary position-relative" title="Go back to the previous page">
                            <i class="bi bi-arrow-left-circle-fill"></i> Back
                        </a>

                    </div>
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
                        <div class="col-lg-6 col-sm-6 col-12">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token"
                                    value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <div class="card mb-4">
                                    <card class="card-header">
                                        <h5>Financial Details</h5>
                                    </card>
                                    <div class="card-body">
                                        <!-- Email -->


                                        <div id="allowance-container">
                                            <!-- First row will be here, initially loaded -->
                                            <div class="row mb-3 allowance-row">
                                                <label for="text" class="col-sm-2 col-form-label">Allowance:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control allowance-select" name="allowance[]">
                                                        <option value="">-Select</option>
                                                        <!-- Options will be populated by JS -->
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" min="0"
                                                            class="form-control allowance-amount"
                                                            name="allowance_amount[]" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-light addallowance">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="deduction-container">
                                            <!-- First row will be here, initially loaded -->
                                            <div class="row mb-3 deduction-row">
                                                <label for="text" class="col-sm-2 col-form-label">Deduction:</label>
                                                <div class="col-sm-3">
                                                    <select class="form-control deduction-select" name="deduction[]">
                                                        <option value="">-Select</option>
                                                        <!-- Options will be populated by JS -->
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₱</span>
                                                        <input type="number" min="0"
                                                            class="form-control deduction-amount"
                                                            name="deduction_amount[]" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-light adddeduction">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>








                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <card class="card-header">
                                        <h5>Personal Details</h5>
                                    </card>
                                    <div class="card-body">



                                        <div class="row mb-3">
                                            <label for="firstName" class="col-sm-4 col-form-label">First Name<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="first_name"
                                                    name="first_name" required>
                                                <small id="nameFeedback" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <!-- Middle Name -->
                                        <div class="row mb-3">
                                            <label for="middleName" class="col-sm-4 col-form-label">Middle Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="middle_name"
                                                    name="middle_name">
                                            </div>
                                        </div>
                                        <!-- Last Name -->
                                        <div class="row mb-3">
                                            <label for="lastName" class="col-sm-4 col-form-label">Last Name<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="last_name" name="last_name"
                                                    required>
                                                <small id="nameFeedback" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <!-- Name Extension -->
                                        <div class="row mb-3">
                                            <label for="nameExtension" class="col-sm-4 col-form-label">Name Extension
                                                (e.g., Jr., Sr.)</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="nameExtension"
                                                    name="name_extension">
                                            </div>
                                        </div>
                                        <!-- Birthdate -->
                                        <div class="row mb-3">
                                            <label for="birthdate" class="col-sm-4 col-form-label">
                                                Birthdate<span class="text-danger">*</span>
                                            </label>
                                            <div class="col-sm-8 position-relative">
                                                <input type="text" class="form-control datepickeradd" name="birthdate"
                                                    id="birthdate" required>
                                                <span class="position-absolute top-50 end-0 translate-middle-y pe-3">
                                                    <i class="bi bi-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Gender -->
                                        <div class="row mb-3">
                                            <label for="gender" class="col-sm-4 col-form-label">Gender<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <select class="form-select" id="gender" name="gender" required>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Contact Number -->
                                        <div class="row mb-3">
                                            <label for="contactNumber" class="col-sm-4 col-form-label">Contact
                                                Number<span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="tel" class="form-control" id="contactNumber"
                                                    name="contact_number">
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="row mb-3">
                                            <label for="streetAddress" class="col-sm-4 col-form-label">Street
                                                Address</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="streetAddress"
                                                    name="street_address">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="city" class="col-sm-4 col-form-label">City<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="city" name="city">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="state" class="col-sm-4 col-form-label">Province<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="province" name="province">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="postalCode" class="col-sm-4 col-form-label">Postal Code</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="postalCode"
                                                    name="postal_code">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="postalCode" class="col-sm-4 col-form-label">Country</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="country" name="country">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="photo" class="col-sm-4 col-form-label">Photo</label>
                                            <div class="col-sm-4">
                                                <input type="file" class="form-control" id="photo" name="photo">

                                            </div>
                                            <div class="col-sm-3">

                                                <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-custom-class="custom-tooltip-success"
                                                    data-bs-title="This top tooltip is themed via CSS variables."
                                                    data-bs-target="#cameraModal">
                                                    <i class="icon-bi bi-camera"></i>

                                                </button>


                                            </div>

                                        </div>




                                    </div>

                                </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">

                            <div class="card mb-4">
                                <card class="card-header">
                                    <h5>Account login</h5>
                                </card>
                                <div class="card-body">
                                    <!-- Email -->
                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Email: <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="email" class="form-control" id="email" name="email" required>
                                            <small id="emailFeedback" class="text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Password:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Confirm Password:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" id="cpassword" name="password"
                                                required>
                                            <div id="passwordError" style="color: red; display: none;">Passwords do not
                                                match!</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card mb-4">
                                <card class="card-header">
                                    <h5>Company Details</h5>
                                </card>
                                <div class="card-body">
                                    <!-- Email -->
                                    <div class="row mb-3">
                                        <label for="department" class="col-sm-4 col-form-label">Department:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="department" id="department" required>
                                                <option value="" selected>- Select -</option>
                                                <?php
                                                // Fetch departments from the database
                                                $sql = "SELECT * FROM department";
                                                $query = $conn->query($sql);
                                                while ($prow = $query->fetch_assoc()) {
                                                    echo "<option value='" . $prow['depid'] . "'>" . $prow['department'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="position" class="col-sm-4 col-form-label">Position:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="position" id="position" required>
                                                <option value="" selected>- Select -</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <label for="rate" class="col-sm-4 col-form-label">Rate:</label>
                                        <div class="col-sm-2 position-relative">

                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" min="0" id="rate" class="form-control " name="rate"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <small for="text" class="text-warning">Schedule not required for
                                            Pakyawan</small>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="schdule" class="col-sm-4 col-form-label">Schedule:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="schdule" id="schdule">
                                                <option value="" disabled selected>- Select -</option>
                                                <?php
                                                // Fetch schedules from the database
                                                $sql = "SELECT * FROM schedules";
                                                $query = $conn->query($sql);
                                                if ($query) {
                                                    while ($prow = $query->fetch_assoc()) {
                                                        // Convert time_in and time_out to 12-hour AM/PM format
                                                        $time_in = date('g:i A', strtotime($prow['scheduled_start']));
                                                        $time_out = date('g:i A', strtotime($prow['scheduled_end']));
                                                        echo "<option value='" . htmlspecialchars($prow['scheduleid']) . "'>" . htmlspecialchars($time_in) . " - " . htmlspecialchars($time_out) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option value='' disabled>No schedules available</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="row mb-2">
                                        <label for="email" class="col-sm-4 col-form-label">Hire Date:<span
                                                class="text-danger">*</span></label>

                                        <div class="col-sm-8 position-relative">
                                            <input type="text" class="form-control datepickeradd" name="datehire"
                                                id="datehire" required>
                                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3">
                                                <i class="bi bi-calendar" aria-hidden="true"></i>
                                            </span>
                                        </div>


                                    </div>


                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-4 col-form-label">Employment Type:<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="employment_type" name="employment_type"
                                                required>
                                                <option value="">-Select-</option>
                                                <option value="full-time">Full-time</option>
                                                <option value="part-time">Part-time</option>
                                                <option value="contract">Contract</option>
                                                <option value="temporary">Temporary</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>




                            </div>





                            <button type="submit" id="submitButton" name="saveEmployee"
                                class="btn btn-primary float-end">Submit</button>

                        </div>
                        <!-- <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cameraModalLabel">Capture Attendance
                                            Photos</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <video id="video" width="100%" height="auto" autoplay></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-success" id="capture">Capture
                                                Photo</button>
                                        </div>
                                        <div id="capturedImages" class="mt-3 d-flex flex-wrap">
                                           
                                        </div>
                                        <input type="hidden" name="face_images" id="face_images">
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cameraModalLabel">Capture Attendance Photos</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <video id="video" width="100%" height="auto" autoplay></video>
                                        <canvas id="canvas" style="display: none;"></canvas>
                                        <div class="mt-3 d-flex justify-content-between">
                                            <button type="button" class="btn btn-success" id="capture">Capture
                                                Photos</button>
                                        </div>
                                        <div id="capturedImages" class="mt-3 d-flex flex-wrap"></div>
                                        <input type="hidden" name="face_images" id="face_images">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        </form>


                    </div>
                    <!-- Row end -->



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

    <script type="text/javascript">
        $(document).ready(function () {
            // When department is selected
            $('#department').change(function () {
                var departmentId = $(this).val();
                if (departmentId != "") {
                    // Fetch positions based on the selected department
                    $.ajax({
                        url: 'ajax/get_positions.php',
                        method: 'GET',
                        data: { department_id: departmentId },
                        success: function (data) {
                            $('#position').html(data);
                        }
                    });
                } else {
                    $('#position').html('<option value="">- Select -</option>');
                    $('#rate').val(''); // Clear rate
                }
            });

            // When position is selected
            $('#position').change(function () {
                var positionId = $(this).val();
                if (positionId != "") {
                    // Fetch rate based on the selected position
                    $.ajax({
                        url: 'ajax/get_rate.php',
                        method: 'GET',
                        data: { position_id: positionId },
                        success: function (rate) {
                            $('#rate').val(rate); // Set the rate
                        }
                    });
                } else {
                    $('#rate').val(''); // Clear rate
                }
            });
        });

    </script>
    </script>

    <script>
        $(document).ready(function () {
            $('#email').on('keyup', function () {
                const email = $(this).val();

                if (email) {
                    $.ajax({
                        url: 'ajax/checkduplicates.php',
                        type: 'POST',
                        data: {
                            email: email
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.exists) {
                                $('#emailFeedback').text('Email already exists.');
                            } else {
                                $('#emailFeedback').text('');
                            }
                        },
                        error: function () {
                            $('#emailFeedback').text('Error checking email.');
                        }
                    });
                } else {
                    $('#emailFeedback').text('');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function () {
            function checkDuplicateName() {
                const firstName = $('#first_name').val();
                const lastName = $('#last_name').val();
                const middleName = $('#middle_name').val();

                if (firstName && lastName && middleName) {
                    $.ajax({
                        url: 'ajax/check_name.php', // Path to your PHP script
                        type: 'POST',
                        data: {
                            first_name: firstName,
                            last_name: lastName,
                            middle_name: middleName
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.exists) {
                                $('#nameFeedback').text('This name combination already exists.');
                                $('#submitButton').prop('disabled', true);
                            } else {
                                $('#nameFeedback').text('');
                                $('#submitButton').prop('disabled', false);
                            }
                        },
                        error: function () {
                            $('#nameFeedback').text('Error checking name.');
                        }
                    });
                } else {
                    $('#nameFeedback').text('');
                    $('#submitButton').prop('disabled', false);
                }
            }

            $('#first_name, #last_name, #middle_name').on('keyup', checkDuplicateName);
        });
    </script>



    <script>
        $(document).ready(function () {
            // Function to load allowance options into the select element
            function loadAllowanceOptions(selectElement) {
                $.ajax({
                    url: 'ajax/get_allowances.php', // Make sure to adjust the path if needed
                    type: 'GET',
                    success: function (response) {
                        let options = '<option value="">-Select</option>';
                        const allowances = JSON.parse(response);

                        // Get all the selected allowances in the form (from all rows)
                        const selectedAllowances = [];
                        $('.allowance-select').each(function () {
                            selectedAllowances.push($(this).val());
                        });

                        // Add options to the current select element
                        allowances.forEach(function (allowance) {
                            // If the allowance is already selected, disable it in the current select
                            const isDisabled = selectedAllowances.includes(allowance.allowid) ? 'disabled' : '';
                            options += `<option value="${allowance.allowid}" data-amount="${allowance.amount}" ${isDisabled}>${allowance.allowance}</option>`;
                        });

                        // Update the options in the current select element
                        $(selectElement).html(options);

                        // Set the selected value for this specific row (if any)
                        const selectedValue = $(selectElement).val();
                        $(selectElement).val(selectedValue).change(); // Ensure that the selected value is reflected
                    },
                    error: function () {
                        alert('Error loading allowances');
                    }
                });
            }

            // Load options for the first allowance row
            loadAllowanceOptions('.allowance-select');

            // Add a new allowance row when the addallowance button is clicked
            $(document).on('click', '.addallowance', function () {
                let newAllowanceRow = `
                <div class="row mb-3 allowance-row">
                    <label for="text" class="col-sm-2 col-form-label">Allowance:</label>
                    <div class="col-sm-3">
                        <select class="form-control allowance-select" name="allowance[]">
                            <option value="">-Select</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" min="0" class="form-control allowance-amount" name="allowance_amount[]" readonly>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-danger remove-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>`;

                // Append the new row inside the form's container
                $('#allowance-container').append(newAllowanceRow);

                // Load options for the new select element
                loadAllowanceOptions($('#allowance-container .allowance-row:last-child .allowance-select'));
            });

            // Remove a dynamically added row
            $(document).on('click', '.remove-btn', function () {
                $(this).closest('.allowance-row').remove();
            });

            // Update the amount field when an allowance is selected
            $(document).on('change', '.allowance-select', function () {
                let selectedOption = $(this).find('option:selected');
                let allowanceValue = selectedOption.val();

                // Check if the selected allowance is already selected in another row
                let isDuplicate = false;
                $('.allowance-select').each(function () {
                    if ($(this).val() === allowanceValue && this !== $(this)[0]) {
                        isDuplicate = true;
                    }
                });

                if (isDuplicate) {
                    // Alert the user if the allowance is already selected
                    alert('This allowance has already been selected.');
                    // Clear the selection and set focus back to the select element
                    $(this).val('');
                } else {
                    // Update the amount field if the selection is valid
                    let amount = selectedOption.data('amount') || 0;
                    $(this).closest('.allowance-row').find('.allowance-amount').val(amount);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Function to load deduction options into the select element
            function loadDeductionOptions(selectElement) {
                $.ajax({
                    url: 'ajax/get_deduction.php', // Adjust this URL to match your data source
                    type: 'GET',
                    success: function (response) {
                        let options = '<option value="">-Select</option>';
                        const deductions = JSON.parse(response);

                        // Get all the selected deductions in the form (from all rows)
                        const selectedDeductions = [];
                        $('.deduction-select').each(function () {
                            selectedDeductions.push($(this).val());
                        });

                        // Add options to the current select element
                        deductions.forEach(function (deduction) {
                            // If the deduction is already selected, disable it in the current select
                            const isDisabled = selectedDeductions.includes(deduction.dedID) ? 'disabled' : '';
                            options += `<option value="${deduction.dedID}" data-amount="${deduction.amount}" ${isDisabled}>${deduction.deduction}</option>`;
                        });

                        // Update the options in the current select element
                        $(selectElement).html(options);

                        // Set the selected value for this specific row (if any)
                        const selectedValue = $(selectElement).val();
                        $(selectElement).val(selectedValue).change(); // Ensure that the selected value is reflected
                    },
                    error: function () {
                        alert('Error loading deductions');
                    }
                });
            }

            // Load options for the first deduction row
            loadDeductionOptions('.deduction-select');

            // Add a new deduction row when the adddeduction button is clicked
            $(document).on('click', '.adddeduction', function () {
                let newDeductionRow = `
        <div class="row mb-3 deduction-row">
            <label for="text" class="col-sm-2 col-form-label">Deduction:</label>
            <div class="col-sm-3">
                <select class="form-control deduction-select" name="deduction[]">
                    <option value="">-Select</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" min="0" class="form-control deduction-amount" name="deduction_amount[]" readonly>
                </div>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger remove-btn">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;

                // Append the new row inside the form's container
                $('#deduction-container').append(newDeductionRow);

                // Load options for the new select element
                loadDeductionOptions($('#deduction-container .deduction-row:last-child .deduction-select'));
            });

            // Remove a dynamically added row
            $(document).on('click', '.remove-btn', function () {
                $(this).closest('.deduction-row').remove();
            });

            // Update the amount field when a deduction is selected
            $(document).on('change', '.deduction-select', function () {
                let selectedOption = $(this).find('option:selected');
                let amount = selectedOption.data('amount') || 0;
                $(this).closest('.deduction-row').find('.deduction-amount').val(amount);

                // Check if the selected deduction is already selected in another row
                let deductionValue = selectedOption.val();
                let isDuplicate = false;

                $('.deduction-select').each(function () {
                    if ($(this).val() === deductionValue && this !== $(this)[0]) {
                        isDuplicate = true;
                    }
                });

                if (isDuplicate) {
                    // Alert the user if the deduction is already selected
                    alert('This deduction has already been selected.');
                    // Clear the selection and set focus back to the select element
                    $(this).val('');
                }
            });
        });
    </script>


    <!-- 
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const capturedImages = document.getElementById('capturedImages');
        const faceImagesInput = document.getElementById('face_images');

        let capturedImageArray = [];

        // Function to open the camera when modal is shown
        document.getElementById('cameraModal').addEventListener('shown.bs.modal', () => {
            navigator.mediaDevices.getUserMedia({
                video: true
            })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(error => console.error("Error accessing the camera: ", error));
        });

        // Function to stop the camera when modal is hidden
        document.getElementById('cameraModal').addEventListener('hidden.bs.modal', () => {
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
            video.srcObject = null;
        });

        // Function to capture an image
        captureButton.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            // Get the captured image as a base64 string
            const imageData = canvas.toDataURL('image/png');
            capturedImageArray.push(imageData);

            // Display the captured image as a thumbnail
            const imgElement = document.createElement('img');
            imgElement.src = imageData;
            imgElement.style.width = '100px';
            imgElement.style.margin = '5px';
            capturedImages.appendChild(imgElement);

            // Update the hidden input field with the JSON string of captured images
            faceImagesInput.value = JSON.stringify(capturedImageArray);
        });
    </script> -->

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const capturedImages = document.getElementById('capturedImages');
        const faceImagesInput = document.getElementById('face_images');

        let capturedImageArray = [];
        let captureInterval;

        // Open camera when modal is shown
        document.getElementById('cameraModal').addEventListener('shown.bs.modal', () => {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => video.srcObject = stream)
                .catch(error => console.error("Error accessing the camera: ", error));
        });

        // Stop camera and clear interval when modal is hidden
        document.getElementById('cameraModal').addEventListener('hidden.bs.modal', () => {
            const stream = video.srcObject;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            video.srcObject = null;
            clearInterval(captureInterval);
        });

        // Start automatic capture
        captureButton.addEventListener('click', () => {
            startAutomaticCapture();
        });

        // Automatic capture for 5 photos
        function startAutomaticCapture() {
            let count = 0;
            capturedImageArray = []; // Clear previous images
            renderCapturedImages();
            updateHiddenInput();

            captureInterval = setInterval(() => {
                if (count < 5) {
                    capturePhoto();
                    count++;
                } else {
                    clearInterval(captureInterval);
                }
            }, 1000); // Capture every 1 second
        }

        // Capture a single photo
        function capturePhoto() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');
            capturedImageArray.push(imageData);

            renderCapturedImages();
            updateHiddenInput();
        }

        // Render captured images with remove button
        function renderCapturedImages() {
            capturedImages.innerHTML = ''; // Clear the container

            capturedImageArray.forEach((imageData, index) => {
                const imgContainer = document.createElement('div');
                imgContainer.style.margin = '5px';
                imgContainer.style.position = 'relative';
                imgContainer.style.display = 'inline-block';

                const imgElement = document.createElement('img');
                imgElement.src = imageData;
                imgElement.style.width = '100px';
                imgElement.style.marginBottom = '5px';

                // Remove Button
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'X';
                removeBtn.className = 'btn btn-sm btn-danger';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '0';
                removeBtn.style.right = '0';
                removeBtn.addEventListener('click', () => removePhoto(index));

                imgContainer.appendChild(imgElement);
                imgContainer.appendChild(removeBtn);
                capturedImages.appendChild(imgContainer);
            });
        }

        // Remove a photo
        function removePhoto(index) {
            capturedImageArray.splice(index, 1); // Remove the selected image from the array
            renderCapturedImages();
            updateHiddenInput();
        }

        // Update the hidden input with the image array
        function updateHiddenInput() {
            faceImagesInput.value = JSON.stringify(capturedImageArray);
        }
        saveButton.addEventListener('click', () => {
            // Minimize modal using Bootstrap's modal backdrop handling
            const modal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
            modal.hide(); // Hide modal without clearing the data
            console.log('Photos saved:', capturedImageArray);
        });

    </script>




    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('cpassword');
        const errorDiv = document.getElementById('passwordError');

        confirmPassword.addEventListener('input', function () {
            if (password.value !== confirmPassword.value) {
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    </script>




</body>


</html>