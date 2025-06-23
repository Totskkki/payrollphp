<!-- Add -->



<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="addnew">
					Add Overtime
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php" id="overtime">
					<div class="form-group">
						<label for="employee" class="col-sm-3 control-label">Employee</label>

						<div class="col-sm-9 ">
							<label for="employee_name">Search Employee Name: <span class="text-danger">*</span></label>
							<input type="text" id="employee_name" name="employee_name" class="form-control" required
								placeholder="Search Employee..">
							<input type="hidden" id="employee_id" name="employee_id">
						</div>
					</div>
					<div class="form-group">
						<label for="datepicker_add" class="col-sm-3 control-label ">Date <span class="text-danger">*</span></label>

						<div class="col-sm-9">
							<div class="date">
								<input type="text" class="form-control datepickeradd" id="datepicker_add" name="date">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="hours" class=" control-label">Overtime hours <span class="text-danger">*</span></label>

						<div class="col-sm-9">
							<input type="number" class="form-control" id="hours" name="maxhours" min="0">
						</div>
					</div>
					<div class="form-group">
						<label for="hours" class=" control-label">Maximum No. of Hours <span class="text-danger">*</span></label>

						<div class="col-sm-9">
							<input type="number" class="form-control" id="maxhours" name="hours" min="0">
						</div>
					</div>
					<div class="form-group">
						<label for="mins" class="col-sm-3 control-label">No. of Mins <span class="text-danger">*</span></label>

						<div class="col-sm-9">
							<input type="number" class="form-control" id="mins" name="mins" min="0">
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="rate" class="col-sm-3 control-label">Rate <span class="text-danger">*</span></label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="rate" name="rate" min="0">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<button type="button" class="btn btn-primary" id="Calculate">Calculate <span class="text-danger">*</span></button>
							<input type="text" class="form-control" id="total_compensation" readonly>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="add_overtime" class="btn btn-primary">
					Save
				</button>
				</form>
			</div>

		</div>
	</div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b><span class="employee_name"></span></b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="overtime_edit.php">
					<input type="hidden" class="otid" name="id">
					<div class="form-group">
						<label for="datepicker_edit" class="col-sm-3 control-label">Date</label>

						<div class="col-sm-9">
							<div class="date">
								<input type="text" class="form-control" id="datepicker_edit" name="date" required>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="hours_edit" class="col-sm-3 control-label">No. of Hours</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="hours_edit" name="hours" required>
						</div>
					</div>
					<div class="form-group">
						<label for="mins_edit" class="col-sm-3 control-label">No. of Mins</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="mins_edit" name="mins" required>
						</div>
					</div>
					<div class="form-group">
						<label for="rate_edit" class="col-sm-3 control-label">Rate</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="rate_edit" name="rate" required>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
						class="fa fa-close"></i> Close</button>
				<button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i>
					Update</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b><span id="overtime_date"></span></b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="overtime_delete.php">
					<input type="hidden" class="otid" name="id">
					<div class="text-center">
						<p>DELETE OVERTIME</p>
						<h2 class="employee_name bold"></h2>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
						class="fa fa-close"></i> Close</button>
				<button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i>
					Delete</button>
				</form>
			</div>
		</div>
	</div>
</div>