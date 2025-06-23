<!-- Add -->
<div class="modal fade" id="addnew">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Apply for Leave</b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="main.php">
					<div class="form-group">
						<label for="employee" class="col-sm-3 control-label">Employee</label>

						<div class="col-sm-9">
							<select name="employee" id="employee" class="form-select " style="width:100%;">
								<option value="">Select Employee</option>
								<?php


								$sql = "SELECT addr.*,n.*,users.userid FROM users
									LEFT JOIN address addr ON addr.addressid = users.address_id
									LEFT JOIN names n ON n.namesid = users.names_id 
									where users.username not in ('admin')
									";
								$result = $conn->query($sql);

								if ($result->num_rows > 0) {
									while ($row = $result->fetch_assoc()) {
										echo '<option value="' . $row['userid'] . '">' . $row['firstname'] . $row['middlename'] . $row['lastname'] . '</option>';
									}
								}

								$conn->close();
								?>
							</select>

							<!-- <input type="text" class="form-control" id="employee" name="employee" required> -->
						</div>
					</div>
					<div class="form-group">
						<label for="datepicker_add" class="col-sm-3 control-label">Date</label>

						<div class="col-sm-9">
							<div class="date">
								<!-- <input type="text" class="form-control" id="datepicker_add" name="date" required> -->
								<input type="text" class="form-control multidatepicker" name="leave_dates" id="leave_dates" required />
								<small class="text-muted">You can select multiple dates separated by comma.</small>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="leave_type" class="col-sm-3 control-label">Leave Type</label>
						<div class="col-sm-9">
							<select class="form-control " style="width:100%;" name="leave_type" id="leave_type" required>
								<option value="">Please make a choice</option>
								<option value="Casual Leave">Casual Leave</option>
								<option value="Earned Leave">Privileged / Earned Leave</option>
								<option value="Sick Leave">Medical / Sick Leave</option>
								<option value="Maternity Leave">Maternity Leave</option>
								<option value="Leave Without Pay">Leave Without Pay</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="amount" class="col-sm-3 control-label">Reason</label>

						<div class="col-sm-9">
							<textarea style="resize: none;" class="form-control" name="leave_message" id="leave_message" rows="3" required></textarea>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				<button type="submit" class="btn btn-primary btn-flat" name="add_leave"><i class="fa fa-save"></i> Save</button>
				</form>
			</div>
		</div>
	</div>
</div>