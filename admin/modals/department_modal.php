<!-- Add -->



<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title employee_id" id="addnew">
					Add Department
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<div class="form-group">
						<label for="title" class="col-sm-3 control-label">Department Title</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="dep" name="dep" required>
						</div>
					</div>
					<div class="form-group">
						<label for="title" class="col-sm-3 control-label">Description</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="title" name="Description" required>
						</div>
					</div>
					
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="add_department" class="btn btn-primary">
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
					Update Position
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="depid" name="id">

					<div class="form-group">

						<label for="edit_title" class="col-sm-3 control-label">Department Title</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="edit_dep" name="dep">
						</div>
					</div>
					<div class="form-group">

						<label for="edit_title" class="col-sm-3 control-label">Description</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="edit_title" name="title">
						</div>
					</div>
				
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" name="edit_dep" class="btn btn-primary">
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
						<p>DELETE DEPARTMENT</p>
						<h2 id="del_position" class="bold"></h2>
					</div>
			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					No
				</button>
				<button type="submit" name="delete_department" class="btn btn-primary">
					Yes
				</button>
				</form>
			</div>


		</div>
	</div>
</div>