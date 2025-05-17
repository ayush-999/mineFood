<div class="modal fade" id="couponCode-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Coupon Code</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars((string) $_SERVER["PHP_SELF"]); ?>" id="couponCodeForm">
                <div class="modal-body">
                    <input type="hidden" id="couponCodeId" name="couponCodeId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="row mb-3">
                        <div class="col-md-12 form-group mb-0">
                            <label for="couponCodeName">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="couponCodeName" name="couponCodeName"
                                   placeholder="Enter coupon code" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeCartValue">Cart Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="couponCodeCartValue" name="couponCodeCartValue"
                                   placeholder="Enter cart value" value="">
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeMinCartValue">Minimum Cart Value <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="couponCodeMinCartValue"
                                   name="couponCodeMinCartValue" placeholder="Enter min cart value" value="">
                        </div>
                    </div> 
                    <div class="row mb-3">
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeStatus">Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="couponCodeStatus" id="couponCodeStatus">
                                <option value="">Select Status</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeType">Coupon Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="couponCodeType" id="couponCodeType">
                                <option value="">Select Type</option>
                                <option value="P">Percentage</option>
                                <option value="F">Fixed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Color Picker -->
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeBgColor">Background Color</label>
                            <div class="input-group bgColorPicker">
                                <input type="text" class="form-control" name="couponCodeBgColor" id="couponCodeBgColor"
                                       value="">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeTxtColor">Text Color</label>
                            <div class="input-group txtColorPicker">
                                <input type="text" class="form-control" name="couponCodeTxtColor"
                                       id="couponCodeTxtColor" value="">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Date Picker -->
                    <div class="row mb-3">
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeStartDate">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="couponCodeStartDate" name="couponCodeStartDate"
                                   value="">
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeEndDate">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="couponCodeEndDate" name="couponCodeEndDate"
                                   value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mb-0">
                            <button type="submit" class="btn bg-gradient-success btn-block">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>