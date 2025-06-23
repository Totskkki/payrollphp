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
                        <!-- <a href="index.html">
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


                        <h3 class="fw-light">
                            <span>Home</span> / <span class="menu-text">Profile</span>
                        </h3>
                    </div>
                    <!-- Page Title end -->

                    <!-- Header graphs start -->

                    <!-- Header graphs end -->

                </div>


                <div class="app-body">

                    <!-- Row start -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="custom-tabs-container">
                                        <ul class="nav nav-tabs" id="customTab2" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                                                    aria-controls="oneA" aria-selected="true">General</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="oneA" role="tabpanel">
                                                <!-- Row start -->
                                                <div class="row justify-content-between">
                                                    <div class="col-sm-8 col-12">
                                                        <div class="card mb-4">
                                                            <form action="profile_update.php" method="POST" enctype="multipart/form-data">
                                                                <div class="card-body">
                                                                    <!-- Row start -->
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <!-- Form Field Start -->
                                                                            <div class="mb-3">
                                                                                <label for="fullName" class="form-label">First Name</label>
                                                                                <input type="text" class="form-control" value="<?php echo $user['fname']; ?>" name="fname" placeholder="Full Name" />
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="fullName" class="form-label">Middle Name</label>
                                                                                <input type="text" class="form-control" value="<?php echo  $user['mname']; ?>" name="middleName" placeholder="Middle Name" />
                                                                            </div>


                                                                            <div class="mb-3">
                                                                                <label for="fullName" class="form-label">Middle Name</label>
                                                                                <input type="text" class="form-control" value="<?php echo  $user['lname']; ?>" name="lname" placeholder="Last Name" />
                                                                            </div>

                                                                            <!-- Form Field Start -->


                                                                        </div>
                                                                        <div class="col-6">
                                                                            <!-- Form Field Start -->
                                                                            <div class="mb-3">
                                                                                <label for="emailId" class="form-label">Email</label>
                                                                                <input type="email" class="form-control" value="<?php echo $user['email']; ?>" name="email" placeholder="Email ID"
                                                                                    value="info@email.com" />
                                                                            </div>

                                                                            <!-- Form Field Start -->
                                                                            <div class="mb-3">
                                                                                <label for="contactNumber" class="form-label">Username</label>
                                                                                <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>"
                                                                                    placeholder="Username" />
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="contactNumber" class="form-label">Contact</label>
                                                                                <input type="number" min="0" class="form-control" value="<?php echo $user['contact']; ?>" name="contactNumber"
                                                                                    placeholder="Contact" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <!-- Form Field Start -->
                                                                            <div class="mb-3">
                                                                                <label for="file" class="form-label">Profile</label>
                                                                                <input type="file" class="form-control" value="<?php echo $user['photo']; ?>" name="photo" />
                                                                            </div>


                                                                        </div>

                                                                    </div>
                                                                    <!-- Row end -->
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-12">
                                                        <div class="card mb-4">
                                                            <div class="card-header">
                                                                <h5 class="card-title">Reset Password</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <!-- Form Field Start -->
                                                                        <div class="mb-3">
                                                                            <label for="currentPassword" class="form-label">Current Password</label>
                                                                            <input type="password" class="form-control" name="curr_password"
                                                                                placeholder="Enter Current Password" />
                                                                        </div>
                                                                        <!-- Form Field Start -->
                                                                        <div class="mb-3">
                                                                            <label for="newPassword" class="form-label">New Password</label>
                                                                            <input type="password" class="form-control" name="newPassword"
                                                                                placeholder="Enter New Password" />
                                                                        </div>
                                                                        <!-- Form Field Start -->
                                                                        <div class="mb-3">
                                                                            <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                                                            <input type="password" class="form-control" name="confirmNewPassword"
                                                                                placeholder="Confirm New Password" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Row end -->
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-outline-secondary">
                                                        Reset
                                                    </button>
                                                    <button type="submit" name="updateprofile" class="btn btn-primary">
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                            </form>
                                            <div class="tab-pane fade" id="twoA" role="tabpanel">
                                                <!-- Row start -->

                                            </div>
                                            <div class="tab-pane fade" id="threeA" role="tabpanel">
                                                <!-- Row start -->


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row end -->

                </div>
                <!-- App container ends -->
                <?php include 'includes/footer.php'; ?>
            </div>
            <!-- Main container end -->

        </div>
        <!-- Page wrapper end -->
        <?php include 'includes/scripts.php'; ?>






</body>

</html>