<div class="modal fade" id="category-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Category</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars((string) $_SERVER["PHP_SELF"]); ?>" id="categoryForm">
                <div class="modal-body">
                    <input type="hidden" id="categoryId" name="categoryId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="form-group">
                        <label for="categoryName">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName"
                               placeholder="Enter category name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="orderNumber">Order Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="orderNumber" name="orderNumber"
                               placeholder="Enter order number" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="categoryStatus">Category Status <span class="text-danger">*</span></label>
                        <select class="form-control" name="categoryStatus" id="categoryStatus">
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