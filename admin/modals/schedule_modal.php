<!-- Add -->


<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="addnew">
					Add Schedule
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>


			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<div class="form-group">
						<label for="time_in" class="col-sm-3 control-label">Time In</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="time_in" name="time_in" required>
							</div>
						</div>

					</div>
					
					<div class="form-group">
						<label for="time_out" class="col-sm-3 control-label">Time Out</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="time_out" name="time_out"
									required>
							</div>
						</div>
					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="addschedules" class="btn btn-primary">
					Save
				</button>
				</form>
			</div>

		</div>
	</div>
</div>

<!-- Edit -->



<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="edit">
					Update Schedule
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="timeid" name="id">
					<div class="form-group">
						<label for="edit_time_in" class="col-sm-3 control-label">Time In</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="edit_time_in" name="time_in">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="edit_time_out" class="col-sm-3 control-label">Time out</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="edit_time_out" name="time_out">
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="editschedules" class="btn btn-primary">
					Save
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
					Deleting....
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="del_timeid" name="id">
					<div class="text-center">
						<p>DELETE SCHEDULE</p>
						<h2 id="del_schedule" class="bold"></h2>
					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="deleteschedules" class="btn btn-primary">
					Save
				</button>
				</form>
			</div>


		</div>
	</div>
</div>