<div class="app-brand px-3 py-3 d-flex align-items-center justify-content-center">
    <a href="home.php" class="text-decoration-none">
        <h5 class="text-white mb-0 py-2 px-3 rounded">Payroll System</h5>
    </a>
</div>

<!-- App brand ends -->

<!-- Sidebar profile starts -->
<div class="sidebar-user-profile">
	<img src="<?php echo (!empty($user['photo'])) ? '../images/' . $user['photo'] : '../images/profile.jpg'; ?>"
		class="profile-thumb rounded-circle p-1 d-lg-flex d-none" alt="Bootstrap Gallery" />
	<h5 class="profile-name lh-lg mt-2 text-truncate"><?php echo $user['fname'] . ' ' . $user['lname']; ?></h5>
	<!-- <ul class="profile-actions d-flex m-0 p-0">
		<li>
			<a href="javascript:void(0)">
				<i class="bi bi-skype fs-4"></i>
				<span class="count-label"></span>
			</a>
		</li>
		<li>
			<a href="javascript:void(0)">
				<i class="bi bi-dribbble fs-4"></i>
			</a>
		</li>
		<li>
			<a href="javascript:void(0)">
				<i class="bi bi-twitter fs-4"></i>
			</a>
		</li>
	</ul> -->
</div>