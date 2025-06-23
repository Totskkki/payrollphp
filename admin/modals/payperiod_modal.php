<!-- Add New Modal -->
<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="addnewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addnewLabel">Add Pay Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="codes.php">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="ref_no" class="form-label">Reference No</label>
                            <input type="text" name="ref_no" id="ref_no" class="form-control"
                                placeholder="E.g., PP-2024-01" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="number" name="year" id="year" class="form-control" value="<?= date('Y') ?>"
                                readonly required>
                        </div>
                        <div class="col-md-3 mb-3 position-relative">
                            <label for="from_date" class="form-label">From Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepickeradd" name="from_date" required>
                                <span class="input-group-text"><i class="bi bi-calendar4"></i></span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 position-relative">
                            <label for="to_date" class="form-label">To Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepickerpayperiod" name="to_date" required>
                                <span class="input-group-text"><i class="bi bi-calendar4"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_payperiod" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel">Edit Pay Period</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" action="codes.php">
                <input type="hidden" id="payid" name="payid">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="ref_no" class="form-label">Reference No</label>
                            <input type="text" name="ref_no" id="ref_nos" class="form-control"
                                 readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="year" class="form-label">Year</label>
                            <input type="number" name="year" id="year" class="form-control" value="<?= date('Y') ?>"
                                readonly required>
                        </div>
                        <div class="col-md-3 mb-3 position-relative">
                            <label for="from_date" class="form-label">From Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepickeradd" name="from_date"id="from_date" required>
                                <span class="input-group-text"><i class="bi bi-calendar4"></i></span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 position-relative">
                            <label for="to_date" class="form-label">To Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control datepickerpayperiod" name="to_date" id="to_date"required>
                                <span class="input-group-text"><i class="bi bi-calendar4"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_payperiod" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title del_employee_name">Delete Pay Period</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="codes.php">
          <!-- Hidden input to store payid -->
          <input type="hidden" id="del_pay" name="delpayid"> 

          <!-- Display the pay period details -->
          <div class="text-center">
            <h2 class="del_paytitle text-danger"></h2>
            <h4 class="text-danger">Are you sure you want to delete this pay period?</h4>
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          No
        </button>
        <button type="submit" name="delete_payid" class="btn btn-primary">
          Yes
        </button>
        </form>
      </div>
    </div>
  </div>
</div>
