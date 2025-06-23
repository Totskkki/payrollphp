<!-- Add User Modal -->
<div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="codes.php" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                            </div>
                            <div class="mb-3">
                                <label for="middlename" class="form-label">Middlename</label>
                                <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Optional">
                            </div>
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact Info</label>
                                <input type="text" class="form-control" id="contact" name="contact">
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" name="photo" id="photo">
                            </div>

                        </div>
                        <!-- Right Column -->
                        <div class="col-md-6">
                           
                            <div class="mb-3">
                                <label for="address" class="form-label">Username</label>
                                <input type="text" class="form-control" id="Username" name="Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" >
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Password</label>
                                <input type="password" class="form-control" id="Password" name="Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="cPassword" name="cPassword" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="adduser" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>