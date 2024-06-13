<div class="modal fade" id="profile-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Update profile</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="profileForm"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="profileId" name="id" value="">
                    <input type="hidden" id="updateAction" name="updateAction" value="">
                    <div class="profileImg-wrapper mb-2">
                        <div class="imgWrapper">
                            <label class="-label" for="profileImg">
                                <span>Change Image</span>
                            </label>
                            <input type="file" class="form-control" id="profileImg" name="profileImg" accept="image/*">
                            <img src="<?php echo $imagePath; ?>" class="img-circle"
                                alt="<?php echo $adminDetails['name']; ?>" id="image-preview">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 form-group mb-0">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" id="fullName" name="name"
                                placeholder="Enter full name" value="" required>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Enter username" value="" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 form-group mb-0">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email address" value="" required>
                        </div>
                        <div class="col-md-6 form-group mb-0 tel-wrapper">
                            <!-- TODO Profile initialCountry not working properly need to look -->
                            <label for="mobile">Mobile</label>
                            <input type="tel" class="form-control" id="mobile" name="mobile"
                                placeholder="Enter mobile number" value="" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 form-group mb-0">
                            <label for="password">Password</label>
                            <input type="password" class="form-control mb-2" id="password" name="password" value=""
                                required>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="passwordCheckbox"
                                    onclick="togglePassword(document.getElementById('password'))">
                                <label class="form-check-label" for="passwordCheckbox">Show Password</label>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-2">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                value="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-success btn-block">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>