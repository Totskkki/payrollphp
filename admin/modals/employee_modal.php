<!-- Delete -->


      
<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title del_employee_name" >
        Delete Employee
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="codes.php">
          <input type="hidden" class="empid" name="id">

          <input type="hidden" class="address_id" name="addID">
          <div class="text-center">
            <h4 class="text-secondary">Archive Employee</h4>
           
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          No
        </button>
        <button type="submit" name="archive" class="btn btn-primary">
          Yes
        </button>
        </form>
      </div>

    </div>
  </div>
</div>




<div class="modal fade" id="edit_photo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title del_employee_name" id="edit_photo">
          Edit Photo
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="codes.php" enctype="multipart/form-data">
          <input type="hidden" class="empid" name="id">
          <div class="form-group">
            <label for="photo" class="col-sm-3 control-label">Photo</label>

            <div class="col-sm-9">
              <input type="file" id="emp_photo" name="photo" class="form-control" required>
            </div>
          </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Close
        </button>
        <button type="submit" name="upload" class="btn btn-primary">
          Update
        </button>
        </form>
      </div>

    </div>
  </div>
</div>