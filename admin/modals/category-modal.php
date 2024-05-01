<div class="modal fade" id="category-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="categoryForm">
                <div class="modal-body">
                    <input type="hidden" id="categoryId" name="categoryId" value="">
                    <input type="hidden" name="submitAction" id="submitAction" value="">
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName"
                            placeholder="Enter category name" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="orderNumber">Order Number</label>
                        <input type="number" class="form-control" id="orderNumber" name="orderNumber"
                            placeholder="Enter order number" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="categoryStatus">Category Status</label>
                        <select class="form-control" name="categoryStatus" id="categoryStatus">
                            <option value="">Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>