<div class="modal fade" id="couponCode-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Coupon Code</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="couponCodeForm">
                <div class="modal-body">
                    <input type="hidden" id="couponCodeId" name="couponCodeId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="form-group">
                        <label for="couponCodeName">Coupon Name</label>
                        <input type="text" class="form-control" id="couponCodeName" name="couponCodeName"
                            placeholder="Enter coupon name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="couponCodeStatus">Status</label>
                        <select class="form-control" name="couponCodeStatus" id="couponCodeStatus">
                            <option value="">Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                    <!-- Color Picker -->
                    <div class="row mb-2">
                        <div class="col-md-6 form-group mb-0">
                            <label for="couponCodeBgColor">Background Color</label>
                            <div class="input-group bgColorPicker">
                                <input type="text" class="form-control" name="couponCodeBgColor" id="couponCodeBgColor"
                                    value="">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                </div>
                            </div>
                            <!-- /.input group -->
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
                            <!-- /.input group -->
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-success btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>