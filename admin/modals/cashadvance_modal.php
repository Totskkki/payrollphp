<!-- Add -->




<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="addnew">
					Add Cash Advance
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					

					<div class="form-group mb-3">
					<label for="employee_name">Search Employee Name: <span
							class="text-danger">*</span></label>
							<div class="col-sm-9">
					<input type="text" id="employee_name" name="employee_name"
						class="form-control" required
						placeholder="Search Employee..">
					<input type="hidden" id="employee_id" name="employee">
					</div>
				</div>
					<div class="form-group">
						<label for="amount" class="col-sm-3 control-label">Amount</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="amount" name="amount" required>
						</div>
					</div>
					<div class="form-group">
						<label for="amount" class="col-sm-3 control-label">Remarks</label>

						<div class="col-sm-12">

							<div class="col-sm-9">
								<textarea name="remarks" class="form-control"></textarea>

							</div>
						</div>
					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="addcash" class="btn btn-primary">
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
					<span class="employee_name"></span>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" class="caid" name="id">
					<div class="form-group">
						<label for="edit_amount" class="col-sm-3 control-label">Amount</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="edit_amount" name="amount" required>
						</div>

					</div>
					<div class="form-group">
						<label for="amount" class="col-sm-3 control-label">Remarks</label>

						<div class="col-sm-9">
							<textarea name="remarks" class="form-control" id="edit_remarks"></textarea>

						</div>
					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="editcash" class="btn btn-primary">
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
					<h5 class="modal-title " id="delete">
						<span class="date"></span></b>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="POST" action="codes.php">
						<input type="hidden" class="caid" name="id">
						<div class="text-center">
							<p>DELETE CASH ADVANCE</p>
							<h2 class="employee_name bold"></h2>
						</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						No
					</button>
					<button type="submit" name="cashdelete" class="btn btn-primary">
						Yes
					</button>
					</form>

				</div>

			</div>
		</div>
	</div>