<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title employee_id" id="addnew">
                    Add Allowance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="codes.php" method="post">

                    <div class="form-group mb-3">
                        <label for="allowance_type">Allowance </label>
                        <input type="text" class="form-control" name="allow" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="frequency">Allowance Type</label>
                        <select name="frequency" id="frequency" class="form-control" required>
                            <option value="one_time">One-Time</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>                           
                           
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="reason">Description</label>
                        <textarea name="description" class="form-control" rows="3" ></textarea>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" name="add_allowance" class="btn btn-primary">
                    Save
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title employee_id" id="edit">
                    Update Allowance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="codes.php" method="post">
                    
                <input type="hidden" class="form-control" name="allid" id="allid">

                    <div class="form-group mb-3">
                        <label for="allowance_type">Allowance </label>
                        <input type="text" class="form-control" name="allow" id="allow"required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="frequency">Allowance Type</label>
                        <select name="frequency" id="frequency" class="form-control" required>
                            <option value="one_time">One-Time</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                         
                           
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="reason">Description</label>
                        <textarea name="description" id="description" name="description" class="form-control" rows="3" required></textarea>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="submit" name="edit_allowance" class="btn btn-primary">
                    Update
                </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title del_posid" id="delete">
					Deleting...
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" id="del_al" name="allowid">
					<div class="text-center">
						<p>DELETE Allowance</p>
						<h2 id="del_allow" class="bold"></h2>
					</div>
			</div>


			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					No
				</button>
				<button type="submit" name="delete_allow" class="btn btn-primary">
					Yes
				</button>
				</form>
			</div>


		</div>
	</div>
</div>