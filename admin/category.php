<?php
include_once ('header.php');
include_once ('classes/Admin.php');

$category = new Admin($conn);

$get_categories = json_decode($category->get_all_categories(), true);

?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <b><?php echo $pageSubTitle; ?></b>
                    </h5>
                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal"
                        data-target="#category-modal"><i class="fas fa-plus mr-1"></i>Add</button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="category" class="table table-bordered table-striped table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 10px">S.No.</th>
                            <th>Category</th>
                            <th>Order Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($get_categories)): ?>
                        <?php foreach ($get_categories as $index => $category): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                            <td><?php echo $category['order_number']; ?></td>
                            <td>
                                <span
                                    class="badge <?php echo $category['status'] == 0 ? 'bg-danger' : 'bg-success'; ?>">
                                    <?php echo $category['status'] == 0 ? 'Inactive' : 'Active'; ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-xs mr-2" type="button">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button class="btn btn-danger btn-xs delete-category"
                                    data-id="<?php echo $category['id']; ?>" type="button">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No categories found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<?php include_once ('./modals/category-modal.php') ?>
<?php include_once ('footer.php') ?>