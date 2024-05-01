<?php
include_once ('header.php');
include_once ('classes/Admin.php');

$category = new Admin($conn);
$msg = '';

$get_categories = json_decode($category->get_all_categories(), true);

if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $categoryName = $_POST['categoryName'];
    $orderNumber = $_POST['orderNumber'];
    $status = $_POST['categoryStatus'];

    if ($action == 'add') {
        $result = $category->add_category($categoryName, $orderNumber, $status);
        if ($result == "Category already exists") {
            $_SESSION['message'] = 'Category already exists';
        } else {
            $_SESSION['message'] = 'Category added successfully';
        }
    } else if ($action == 'update') {
        $categoryId = $_POST['categoryId']; // Make sure this input is included in the form for updates
        $result = $category->update_category($categoryId, $categoryName, $orderNumber, $status);
        $_SESSION['message'] = $result;
    }
    header("Location: category.php");
    exit;
}


// Check for session message and clear it
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message so it doesn't persist on refresh
}
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <b><?php echo $pageSubTitle; ?></b>
                    </h5>
                    <button class="btn btn-success btn-sm add-btn" type="button" data-toggle="modal"
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
                                <button class="btn btn-success btn-xs mr-2 edit-btn" type="button" data-toggle="modal"
                                    data-target="#category-modal" data-id="<?php echo $category['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($category['category_name']); ?>"
                                    data-order="<?php echo $category['order_number']; ?>"
                                    data-status="<?php echo $category['status']; ?>">
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

<script type="text/javascript">
$(document).ready(function() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "100", // default : 300
        "hideDuration": "500", // default : 1000
        "timeOut": "2000", // default : 5000
        "extendedTimeOut": "500", // default : 1000
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };
    let message = <?php echo json_encode($msg); ?>;
    if (message) {
        if (message == "Category already exists") {
            toastr.error(message);
        } else if (message == "Category added successfully" || message == "Category updated successfully") {
            toastr.success(message);
        }
    }

    $('.add-btn').on('click', function() {
        $('#submitAction').val('add');
        $('#categoryId').val(''); // Clear in case of previously set
        $('#categoryName').val('');
        $('#orderNumber').val('');
        $('#categoryStatus').val('');
        $('#category-modal').modal('show');
    });

    $('.edit-btn').on('click', function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');
        var orderNumber = $(this).data('order');
        var status = $(this).data('status');
        $('#submitAction').val('update');
        $('#categoryId').val(categoryId);
        $('#categoryName').val(categoryName);
        $('#orderNumber').val(orderNumber);
        $('#categoryStatus').val(status);
        $('#category-modal').modal('show');
    });
});
</script>

<?php include_once ('footer.php') ?>