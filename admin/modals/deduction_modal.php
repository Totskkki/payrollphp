<!-- Add -->

<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="addnew">
					Add Deduction
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
			<form class="form-horizontal" method="POST" action="codes.php">
					
					<div class="form-group mb-3">
						<label for="edit_description" class="col-sm-3 control-label">Deduction</label>

						<div class="col-sm-9">
							<input type="text" class="form-control"  name="Deduction">
						</div>
					</div>

					<div class="form-group mb-3">
						<label for="frequency">Deduction Type</label>
						<div class="col-sm-9">
							<select name="frequency" class="form-control" required>
								<option value="Once">Once</option>
								<option value="Weekly">Weekly</option>
								<option value="Monthly">Monthly</option>

							</select>
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="edit_amount" class="col-sm-3 control-label">Amount</label>

						<div class="col-sm-9">
							<input type="text" class="form-control"  name="amount">
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="edit_amount" class="col-sm-3 control-label">Description</label>

						<div class="col-sm-9">
							<textarea name="description"  class="form-control"></textarea>

						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="add_deduction" class="btn btn-primary">
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
					Update Deduction
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>


			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="decid" name="id">
					<div class="form-group mb-3">
						<label for="edit_description" class="col-sm-3 control-label">Deduction</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="edit_Deduction" name="Deduction">
						</div>
					</div>

					<div class="form-group mb-3">
						<label for="frequency">Deduction Type</label>
						<div class="col-sm-9">
							<select name="frequency" id="frequency" class="form-control" required>
								<option value="Once">Once</option>
								<option value="Weekly">Weekly</option>
								<option value="Monthly">Monthly</option>

							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="edit_amount" class="col-sm-3 control-label">Amount</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="amount" name="amount">
						</div>
					</div>
					<div class="form-group">
						<label for="edit_amount" class="col-sm-3 control-label">Description</label>

						<div class="col-sm-9">
							<textarea name="description" id="edit_Description" class="form-control"></textarea>

						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="edit_deduction" class="btn btn-primary">
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
				<h5 class="modal-title del_allow" id="delete">
					Deleting...
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="del_al" class="del_al" name="id">
					<div class="text-center">
						<p>DELETE DEDUCTION</p>
						<h2 id="del_deduction" class="bold"></h2>
					</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					No
				</button>
				<button type="submit" name="delete_deduction" class="btn btn-primary">
					Yes
				</button>
				</form>
			</div>

		</div>
	</div>
</div>