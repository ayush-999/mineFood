<div class="modal fade" id="user-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">User</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="userForm">
                <div class="modal-body">
                    <input type="hidden" id="userId" name="userId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="form-group">
                        <label for="userName">Full Name</label>
                        <input type="text" class="form-control" id="userName" name="userName"
                            placeholder="Enter user full name" value="" required>
                    </div>
                    <div class="form-group tel-wrapper">
                        <!-- TODO User initialCountry not working properly need to look -->
                        <label for="userMobile">Mobile</label>
                        <input type="tel" class="form-control" id="userMobile" name="userMobile"
                            placeholder="Enter user mobile number" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email</label>
                        <input type="tel" class="form-control" id="userEmail" name="userEmail"
                            placeholder="Enter user email" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="userStatus">Status</label>
                        <select class="form-control" name="userStatus" id="userStatus">
                            <option value="">Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                            <option value="2">Blocked</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>