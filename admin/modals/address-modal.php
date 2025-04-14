<div class="modal fade" id="address-modal">
    <div class="modal-dialog modal-lg   ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Update address</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="addressForm"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="address_profileId" name="id" value="">
                    <input type="hidden" id="address_updateAction" name="updateAction" value="">
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" id="address_fullName" name="name"
                                placeholder="Enter full name" value="" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="address_mobile" name="mobile"
                                placeholder="Enter mobile number" value="" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <label for="country">Country</label>
                            <select class="form-control" id="address_country" name="country" disabled>
                                <option value="India" selected>India</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 form-group mb-0">
                            <label for="area">Area and Street</label>
                            <textarea class="form-control" id="address_area" name="area"
                                placeholder="Area and Street" value="" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 form-group mb-0">
                            <label for="state">State</label>
                            <select class="form-control" id="address_state" name="state" required>
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="district">District</label>
                            <select class="form-control" id="address_district" name="district" required disabled>
                                <option value="">Select District</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 form-group mb-0">
                            <label for="pincode">Pincode</label>
                            <select class="form-control" id="address_pincode" name="pincode" required disabled>
                                <option value="">Select Pincode</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="city">City</label>
                            <select class="form-control" id="address_city" name="city" required disabled>
                                <option value="">Select City</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-success btn-block">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>