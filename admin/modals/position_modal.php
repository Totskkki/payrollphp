<!-- Add -->


<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="addnew">
					Add Position
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">

					<div class="form-group">
						<label for="departid" class="col-sm-3 control-label">Department</label>
						<div class="col-sm-9 mb-3">
							<select class="form-control" name="departid" id="departid" required>
								<option value="" disabled selected>Select a department</option>

								<?php
								$sql = "SELECT * FROM department";
								$query = $conn->query($sql);
								while ($prow = $query->fetch_assoc()) {
									echo "<option value='" . $prow['depid'] . "'>" . $prow['department'] . "</option>";
								}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="title" class="col-sm-3 control-label">Position Title</label>
						<div class="col-sm-9 mb-3">
							<input type="text" class="form-control" id="title" name="title" required>
						</div>
					</div>

					<!-- Rate per Hour -->
					<div class="form-group ratePerHourContainer">
					<label for="rate_per_hour" class="col-sm-3 control-label">Daily Rate</label>
					<div class="col-sm-9 mb-3">
						<input type="number" class="form-control" id="rate_per_hour" name="rate_per_hour" step="0.01" min="0">
					</div>
					</div>

					<!-- Pakyawan Rate -->
					<div class="form-group pakyawanRateContainer" style="display: none;">
					<label for="pakyawan_rate" class="col-sm-3 control-label">Pakyawan Rate</label>
					<div class="col-sm-9 mb-3">
						<input type="number" class="form-control" id="pakyawan_rate" name="pakyawan_rate" step="0.01" min="0">
					</div>
					</div>



			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" name="add_position" class="btn btn-primary">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>
<!-- Edit -->



<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="edit">
					Update Position
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="posid" name="id">

					<div class="form-group">

						<label for="edit_title" class="col-sm-3 control-label">Department</label>

						<div class="col-sm-9">


							<select class="form-control" name="edit_dep" required>
								<option value="" disabled selected>Select a department</option>
								<option selected id="edit_val"></option>

								<?php

								$sql = "SELECT * FROM department";
								$query = $conn->query($sql);
								while ($prow = $query->fetch_assoc()) {
									echo "<option value='" . $prow['depid'] . "'>" . $prow['department'] . "</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">

						<label for="edit_title" class="col-sm-3 control-label">Position Title</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="edit_title" name="title">
						</div>
					</div>

					<!-- Rate per Hour -->
						<div class="form-group ratePerHourContainer" style="display: block;">
						<label for="edit_rate" class="col-sm-3 control-label">Daily Rate</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" id="edit_rate" name="rate" step="0.01" min="0">
						</div>
						</div>

						<!-- Pakyawan Rate -->
						<div class="form-group pakyawanRateContainer" style="display: none;">
						<label for="pakyawan_rate" class="col-sm-3 control-label">Pakyawan Rate</label>
						<div class="col-sm-9 mb-3">
							<input type="number" class="form-control" id="edit_pakyawan" name="pakyawan_rate" step="0.01" min="0">
						</div>
						</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="edit_position" class="btn btn-primary">
					Update
				</button>
				</form>
			</div>


		</div>
	</div>
</div>

<!-- Delete -->

<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="delete">
					Deleting...
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="del_posid" name="position_id">
					<div class="text-center">
						<p>DELETE POSITION</p>
						<h2 id="del_position" class="bold"></h2>
					</div>
			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					No
				</button>
				<button type="submit" name="delete_position" class="btn btn-primary">
					Yes
				</button>
				</form>
			</div>


		</div>
	</div>
</div>