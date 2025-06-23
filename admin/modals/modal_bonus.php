<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="addNewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewModalLabel">Add Bonus Incentives
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="codes.php">
                    <!-- Employee Name -->
                    <div class="form-group mb-3">
                        <label for="add_employee_name">Search Employee Name: <span class="text-danger">*</span></label>
                        <input type="text" id="add_employee_name" class="employee_names form-control" placeholder="Search Employee..">
                        <input type="hidden" id="add_employee_id" name="add_employee_id" class="employee_ids">
                    </div>
                    <div class="form-group mb-3">
                        <label for="bonus_amount">Bonus Amount: <span
                                class="text-danger">*</span></label>
                        <input type="number" name="bonus_amount"
                            class="form-control" required
                            placeholder="Enter Bonus Amount" step="0.01" min="0">
                        <!-- Error message container -->
                        <small id="bonus_error" class="text-danger"
                            style="display: none;">
                            Bonus amount cannot be negative.
                        </small>
                    </div>

                    <!-- Bonus Type -->
                    <div class="form-group mb-3">
                        <label for="bonus_type">Bonus Type: <span
                                class="text-danger">*</span></label>
                        <select name="bonus_type" class="form-control"
                            required>
                            <option value="" disabled selected>Select Bonus Type
                            </option>
                            <option value="Performance">Performance</option>
                            <option value="Holiday">Holiday Bonus</option>
                            <option value="Attendance">Attendance Bonus</option>
                            <option value="Quality">Quality Bonus</option>
                            <option value="Team Efficiency">Team Efficiency Bonus
                            </option>
                            <option value="Christmas">Christmas Bonus</option>
                            <option value="Year-End">Year-End Bonus</option>
                            <option value="Birthday">Birthday Bonus</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Bonus Period -->
                    <div class="form-group mb-3">
                        <label for="bonus_period">Bonus Period: <span
                                class="text-danger">*</span></label>
                        <select name="bonus_period_select"
                            class="bonus-period-class form-control" required>
                            <option value="" disabled selected>Select a Month
                            </option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>

                        <!-- Hidden field to store month + year value -->
                        <input type="text" name="bonus_period" class="bonus-period-input form-control mt-2" readonly >
                    </div>


                    <!-- Bonus Description -->
                    <div class="form-group mb-3">
                        <label for="bonus_description">Bonus Description:</label>
                        <textarea name="bonus_description"
                            class="form-control"
                            placeholder="Describe the bonus (e.g., Year-End reward, Christmas gift, etc.)"
                            rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_bonus"
                            class="btn btn-primary">Save Bonus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="addNewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit">Edit Bonus Incentives
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="codes.php">
                    <!-- Employee Name -->
                    <input type="hidden" name="id" id="bonusid">
                    <div class="form-group mb-3">
                        <label for="edit_employee_name">Search Employee Name: <span class="text-danger">*</span></label>
                        <input type="text" id="edit_employee_name" class="employee_name form-control" placeholder="Search Employee..">
                        <input type="hidden" id="edit_employee_id" name="edit_employee_id" class="employee_id">
                    </div>
                    <div class="form-group mb-3">
                        <label for="bonus_amount">Bonus Amount: <span
                                class="text-danger">*</span></label>
                        <input type="number" name="bonus_amount" id="bonus_amount"
                            class="form-control" required
                            placeholder="Enter Bonus Amount" step="0.01" min="0">
                        <!-- Error message container -->
                        <small id="bonus_error" class="text-danger"
                            style="display: none;">
                            Bonus amount cannot be negative.
                        </small>
                    </div>

                    <!-- Bonus Type -->
                    <div class="form-group mb-3">
                        <label for="bonus_type">Bonus Type: <span
                                class="text-danger">*</span></label>
                        <select name="bonus_type" id="bonus_type" class="form-control"
                            required>
                            <option value="" disabled selected>Select Bonus Type
                            </option>
                            <option value="Performance">Performance</option>
                            <option value="Holiday">Holiday Bonus</option>
                            <option value="Attendance">Attendance Bonus</option>
                            <option value="Quality">Quality Bonus</option>
                            <option value="Team Efficiency">Team Efficiency Bonus
                            </option>
                            <option value="Christmas">Christmas Bonus</option>
                            <option value="Year-End">Year-End Bonus</option>
                            <option value="Birthday">Birthday Bonus</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Bonus Period -->
                    <div class="form-group mb-3">
                        <label for="bonus_period">Bonus Period: <span class="text-danger">*</span></label>
                        <select name="bonus_period_select" id="bonus_period_select" class="bonus-period-class form-control" required>
                            <option value="" disabled selected>Select a Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                        <input type="text" name="bonus_period" class="bonus-period-input form-control mt-2" readonly >
                    </div>


                    <!-- Bonus Description -->
                    <div class="form-group mb-3">
                        <label for="bonus_description">Bonus Description:</label>
                        <textarea name="bonus_description" id="bonus_description"
                            class="form-control"
                            placeholder="Describe the bonus (e.g., Year-End reward, Christmas gift, etc.)"
                            rows="3"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_bonus"
                            class="btn btn-primary">Update Bonus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




            <div class="modal fade" id="delete" tabindex="-1" aria-labelledby="addNewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete">Delete Bonus Incentives
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="codes.php">
					<input type="hidden" class="otid" name="id">	
					<div class="text-center">
						<p>Delete Bonus Incentives</p>
						<h2 class="employee_name bold"></h2>
					</div>
			</div>
            <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">No</button>
                        <button type="submit" name="delete_bonus"
                            class="btn btn-primary">Yes</button>
                    </div>


				</form>
			</div>
		</div>
	</div>
</div>
<!-- modal end  -->