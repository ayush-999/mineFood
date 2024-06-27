<div class="modal fade" id="delivery-boy-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Delivery Boy</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="deliveryBoyForm">
                <div class="modal-body">
                    <input type="hidden" id="deliveryBoyId" name="deliveryBoyId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="form-group">
                        <label for="deliveryBoyName">Full Name</label>
                        <input type="text" class="form-control" id="deliveryBoyName" name="deliveryBoyName"
                               placeholder="Enter delivery boy full name" value="" required>
                    </div>
                    <div class="form-group tel-wrapper">
                        <!-- TODO Delivery Boy initialCountry not working properly need to look -->
                        <label for="deliveryBoyMobile">Mobile</label>
                        <input type="tel" class="form-control" id="deliveryBoyMobile" name="deliveryBoyMobile"
                               placeholder="Enter delivery boy mobile number" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="deliveryBoyEmail">Email</label>
                        <input type="tel" class="form-control" id="deliveryBoyEmail" name="deliveryBoyEmail"
                               placeholder="Enter delivery boy email" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="deliveryBoyStatus">Status</label>
                        <select class="form-control" name="deliveryBoyStatus" id="deliveryBoyStatus">
                            <option value="">Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                            <option value="2">Blocked</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-success btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>