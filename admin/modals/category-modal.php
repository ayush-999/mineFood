<div class="modal fade" id="category-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category here</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" novalidate>
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName"
                            placeholder="Enter category name">
                    </div>
                    <div class="form-group">
                        <label for="orderNumber">Order Number</label>
                        <input type="number" class="form-control" id="orderNumber" name="orderNumber"
                            placeholder="Enter order number">
                    </div>
                    <div class="form-group">
                        <label for="categoryStatus">Category Status</label>
                        <select class="form-control" name="categoryStatus" id="categoryStatus">
                            <option value="">Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" id="saveButton">Save</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>