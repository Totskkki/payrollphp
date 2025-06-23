<div class="header-actions">
							
							
                            <div class="dropdown ms-3">
								<!-- <a class="dropdown-toggle action-icon" href="#!" role="button" data-bs-toggle="dropdown"
									aria-expanded="false">
									<i class="bi bi-bell fs-5 lh-1"></i>
									<span class="count-label bg-danger animated infinite swing">7</span>
								</a> -->
								<!-- <div class="dropdown-menu dropdown-menu-end shadow">
									<div class="dropdown-item">
										<div class="d-flex py-2 border-bottom">
											<img src="assets/images/user.png" class="img-4x me-3 rounded-3" alt="Admin Theme" />
											<div class="m-0">
												<h5 class="mb-1 fw-semibold">Lesley Preston</h5>
												<p class="mb-1">Membership has been ended.</p>
												<p class="small m-0 text-primary">Today, 07:30pm</p>
											</div>
										</div>
									</div>
									<div class="dropdown-item">
										<div class="d-flex py-2 border-bottom">
											<img src="assets/images/user2.png" class="img-4x me-3 rounded-3" alt="Admin Theme" />
											<div class="m-0">
												<h5 class="mb-1 fw-semibold">Jannie Reilly</h5>
												<p class="mb-1">Congratulate, James for new job.</p>
												<p class="small m-0 text-primary">Today, 08:00pm</p>
											</div>
										</div>
									</div>
									<div class="dropdown-item">
										<div class="d-flex py-2">
											<img src="assets/images/user1.png" class="img-4x me-3 rounded-3" alt="Admin Theme" />
											<div class="m-0">
												<h5 class="mb-1 fw-semibold">Elsa Gregory</h5>
												<p class="mb-2">Lewis added new schedule release.</p>
												<p class="small m-0 text-primary">Today, 09:30pm</p>
											</div>
										</div>
									</div>
									<div class="border-top p-2 d-grid">
										<a href="javascript:void(0)" class="btn btn-info">View all</a>
									</div>
								</div> -->
							</div>
							<div class="dropdown ms-3">
								<a id="userSettings" class="dropdown-toggle d-flex py-2 align-items-center text-decoration-none"
									href="#!" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<span class="d-none d-md-block me-2"><?php echo $user['email']; ?></span>
									<img src="<?php echo (!empty($user['photo'])) ? '../images/' . $user['photo'] : '../images/profile.jpg'; ?>" class="rounded-4 img-3x" alt="Bootstrap Gallery" />
								</a>
								<div class="dropdown-menu dropdown-menu-end shadow">
									
									<a class="dropdown-item d-flex align-items-center" href="settings.php"><i
											class="bi bi-gear fs-4 me-2"></i>Account Settings</a>
									<a class="dropdown-item d-flex align-items-center" href="logout.php"><i
											class="bi bi-escape fs-4 me-2"></i>Logout</a>
								</div>
							</div>
						</div>