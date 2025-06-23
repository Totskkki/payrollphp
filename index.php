<?php
  session_start();
  if(isset($_SESSION['admin'])){
    header('location:home.php');
  }
?>
<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">

	
<!-- Mirrored from www.bootstrap.gallery/demos/adminday-admin-template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 29 Feb 2024 05:06:15 GMT -->
<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>J-VENUS PAYROLL SYSTEM</title>

		<!-- Meta -->
		<meta name="description" content="Marketplace for Bootstrap Admin Dashboards" />
		<meta name="author" content="Bootstrap Gallery" />
		<link rel="canonical" href="https://www.bootstrap.gallery/">
		<meta property="og:url" content="https://www.bootstrap.gallery/">
		<meta property="og:title" content="Admin Templates - Dashboard Templates | Bootstrap Gallery">
		<meta property="og:description" content="Marketplace for Bootstrap Admin Dashboards">
		<meta property="og:type" content="Website">
		<meta property="og:site_name" content="Bootstrap Gallery">
		<link rel="shortcut icon" href="assets/images/favicon.svg" />

		<!-- *************
			************ CSS Files *************
		************* -->
		<link rel="stylesheet" href="assets/css/animate.css" />
		<link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.css" />
		<link rel="stylesheet" href="assets/css/main.min.css" />
	  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		
		<style>
    /* Add your CSS styles here */
    .login-page {
        background: url('../images/logo.png') no-repeat center center fixed;
        background-size: cover;
    }
    .login-box {
        background: rgba(255, 255, 255, 0.95); /* White background with slight transparency */
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .login-box-body {
        margin-top: 10px;
    }
</style>
	</head>

	<body class="login-page">
		<!-- Container start -->
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-4 col-lg-5 col-sm-6 col-12">
					<form action="login.php" class="my-5" method="POST">
						<div class="bg-white border border-dark rounded-2 p-4 mt-5">
							<div class="login-form">
								<!-- <a href="#" class="mb-4 d-flex">
									<img src="assets/images/logo-dark.svg" class="img-fluid login-logo" alt="Bootstrap Gallery" />
								</a> -->
								<?php

											// Include database connection or necessary files here

											if (isset($_SESSION['error'])) {
												echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
												unset($_SESSION['error']); // Clear the session error
											}

											if (isset($_SESSION['success'])) {
												echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
												unset($_SESSION['success']); // Clear the session success message
											}
											?>
								<h2 class="fw-bold mb-4 text-center">Admin Login</h2>
								<div class="mb-3">
									<label class="form-label" for="email">Username</label>
									<input type="text" class="form-control" name="username"  placeholder="Enter your username" required />
								</div>
								<div class="mb-3">
									<label class="form-label" for="pwd">Your Password</label>
									<input type="password" class="form-control" name="password"  placeholder="Enter password" required/>
								</div>
								<div class="d-flex align-items-center justify-content-between">
								<div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="remember" id="rememberPassword" />
                                        <label class="form-check-label" for="rememberPassword">Remember</label>
                                    </div>
                                  

									<!-- <a href="#" class="text-blue text-decoration-underline">Lost password?</a> -->
								</div>
								    <div class="g-recaptcha" data-sitekey="6Ld6U68qAAAAAPSrlMQeWSDvj2QWjKe8vrNxaFUk"></div> 
								<div class="d-grid py-3">
									<button type="submit"id="loginButton" name="login" class="btn btn-lg btn-primary">
										Login
									</button>
								</div>
								
								
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Container end -->
	</body>

<script>
  $(document).on('click', '#loginButton', function () {
    var response = grecaptcha.getResponse();
    if (response.length == 0) {
      alert("Please verify you are not a robot");
      return false;
    }

  });
</script>

<!-- Mirrored from www.bootstrap.gallery/demos/adminday-admin-template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 29 Feb 2024 05:06:15 GMT -->
</html>