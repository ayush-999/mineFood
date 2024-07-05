<div class="modal fade" id="dish-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Dish</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="dishForm">
                <div class="modal-body">
                    <input type="hidden" id="dishId" name="dishId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="form-group">
                        <label for="dishName">Dish Name</label>
                        <input type="text" class="form-control" id="dishName" name="dishName"
                               placeholder="Enter Dish name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="dishStatus">Status</label>
                        <select class="form-control" name="dishStatus" id="dishStatus">
                            <option value="">Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                    <button type="submit" class="btn bg-gradient-success btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>