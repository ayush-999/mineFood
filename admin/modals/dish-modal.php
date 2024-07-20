<?php
if (!empty($admin)) {
    try {
        $get_categories = json_decode($admin->get_all_categories(), true);
        $get_dish = json_decode($admin->get_dish(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}
$dishImg = $get_dish['image'] ?? '';
$imagePath = $dishImg ? 'uploads/dish/' . $dishImg : 'assets/img/no-img.png';
?>
<div class="modal fade" id="dish-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Dish</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="dishForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="dishId" name="dishId" value="">
                    <input type="hidden" id="submitAction" name="submitAction" value="">
                    <div class="row mb-3">
                        <div class="col-md-10 form-group mb-0">
                            <label for="dishName">Dish Name</label>
                            <input type="text" class="form-control" id="dishName" name="dishName"
                                   placeholder="Enter Dish name" value="" required>
                        </div>
                        <div class="col-md-32 form-group mb-0 d-flex justify-content-end">
                            <div class="imgWrapper">
                                <label class="-label" for="dishImg">
                                    <span>Change Image</span>
                                </label>
                                <input type="file" class="form-control" id="dishImg" name="dishImg" accept="image/*">
                                <img src="<?php echo $imagePath; ?>" class="img-circle"
                                     alt="" id="image-preview">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12 form-group mb-0">
                            <label for="dishCategory">Category</label>
                            <select class="form-control" name="dishCategory" id="dishCategory">
                                <option value="">Select Category</option>
                                <?php
                                if (!empty($get_categories)) {
                                    foreach ($get_categories as $category) {
                                        echo '<option class="" value="' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['category_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group mb-0">
                            <label for="dishStatus">Status</label>
                            <select class="form-control" name="dishStatus" id="dishStatus">
                                <option value="">Select Status</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label for="dishType">Type</label>
                            <select class="form-control" name="dishType" id="dishType">
                                <option value="">Select Type</option>
                                <option value="veg">Veg</option>
                                <option value="non-veg">Non-Veg</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12 form-group mb-0">
                            <label for="dishDetail">Detail</label>
                            <textarea class="form-control" id="dishDetail" name="dishDetail" rows="3"></textarea>
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