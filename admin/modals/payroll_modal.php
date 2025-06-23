<!-- Edit Payroll Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editPayrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="codes.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title name" id="editPayrollModalLabel">Edit Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

              
                    <input type="hidden" name="payrollid" id="payrollid">

                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pay_period_id" class="form-label">Pay Period </label>
                                <input type="text" class="form-control" name="pay_period_id" id="pay_period_id"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="payslip_no" class="form-label">Payslip Number</label>
                                <input type="text" class="form-control" name="payslip_no" id="payslip_no" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="gross_salary" class="form-label">Gross Salary</label>
                                <input type="text" class="form-control" name="gross_salary" id="gross_salary"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="tot_deductions" class="form-label">Total Deductions</label>
                                <input type="text" class="form-control" name="tot_deductions" id="tot_deductions"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="deductions" class="form-label">Deductions</label>
                                <input type="text" class="form-control" name="deductions" id="deductions" >
                            </div>
                         
                            <div class="mb-3">
                                <label for="late" class="form-label">Late</label>
                                <input type="text" class="form-control" name="late" id="late" >
                            </div>
                            <div class="mb-3">
                                <label for="undertime" class="form-label">Undertime</label>
                                <input type="text" class="form-control" name="undertime" id="undertime" >
                            </div>
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="present" class="form-label">Present Days</label>
                                <input type="text" class="form-control" name="present" id="present" >
                            </div>
                            <div class="mb-3">
                                <label for="overtime" class="form-label">Overtime</label>
                                <input type="text" class="form-control" name="overtime" id="overtime" >
                            </div>
                            <div class="mb-3">
                                <label for="allowances" class="form-label">Allowances</label>
                                <input type="text" class="form-control" name="allowances" id="allowances" >
                            </div>
                            <div class="mb-3">
                                <label for="cash_advance" class="form-label">Cash Advance</label>
                                <input type="text" class="form-control" name="cash_advance" id="cash_advance"
                                    >
                            </div>
                            <div class="mb-3">
                                <label for="bonus" class="form-label">Bonus</label>
                                <input type="text" class="form-control" name="bonus" id="bonus" >
                            </div>
                            <div class="mb-3">
                                <label for="net_salary" class="form-label">Net Salary</label>
                                <input type="text" class="form-control" name="net_salary" id="net_salary" >
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status" >
                                <option selected id="status_val"></option>
                                    <option value="paid">Paid</option>
                                    <option value="pending">Pending</option>
                                    <option value="approve">Approve</option>
                                </select>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_payroll" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>