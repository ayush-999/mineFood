<?php
include_once('header.php');

$msg = '';
if (!empty($admin)) {
    try {
        $get_categories = json_decode((string) $admin->get_all_categories(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}
if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $categoryName = $_POST['categoryName'];
    $orderNumber = $_POST['orderNumber'];
    $status = $_POST['categoryStatus'];
    $added_on = date('Y-m-d h:i:s');
    try {
        if ($action == 'add') {
            $result = $admin->add_category($categoryName, $orderNumber, $status, $added_on);
            if ($result == "Category already exists") {
                $_SESSION['message'] = 'Category already exists';
            } else {
                $_SESSION['message'] = 'Category added successfully';
            }
        } else if ($action == 'update') {
            $categoryId = $_POST['categoryId']; // Make sure this input is included in the form for updates
            $result = $admin->update_category($categoryId, $categoryName, $orderNumber, $status, $added_on);
            $_SESSION['message'] = $result;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
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
                        <b>
                            <?php
                            if (!empty($pageSubTitle)) {
                                echo $pageSubTitle;
                            }
                            ?>
                        </b>
                    </h5>
                    <button class="btn bg-gradient-success btn-sm rounded-circle add-btn" type="button"
                        data-toggle="modal" data-target="#category-modal"><i class="fa-regular fa-plus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="category" class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>O.No.</th>
                            <th>Category Name</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Added Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($get_categories)): ?>
                            <?php foreach ($get_categories as $index => $category): ?>
                                <tr>
                                    <td><?php echo $category['order_number']; ?></td>
                                    <td><?php echo htmlspecialchars((string) $category['category_name']); ?></td>
                                    <td class="text-center">
                                        <span
                                            class="<?php echo $category['status'] == 0 ? 'inactive-badge' : 'active-badge'; ?>">
                                            <?php echo $category['status'] == 0 ? 'Inactive' : 'Active'; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date = new DateTime($category['added_on']);
                                        echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                            type="button" data-toggle="modal" data-target="#category-modal"
                                            data-id="<?php echo $category['id']; ?>"
                                            data-name="<?php echo htmlspecialchars((string) $category['category_name']); ?>"
                                            data-order="<?php echo $category['order_number']; ?>"
                                            data-status="<?php echo $category['status']; ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-category"
                                            data-id="<?php echo $category['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
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
<?php include_once('./modals/category-modal.php') ?>

<script type="text/javascript">
    $(document).ready(function() {

        //Initialize Select2 Elements
        $('#categoryStatus').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: -1
        });

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
            if (message === "Category already exists" || message === "Category name already exists") {
                toastr.error(message);
            } else if (message === "Category added successfully" || message === "Category updated successfully") {
                toastr.success(message);
            }
        }

        $('.add-btn').on('click', function() {
            $('#category-modal .modal-title').text('Add Category');
            $('#category-modal .btn-block').text('Submit');
            $('#submitAction').val('add');
            $('#categoryId').val(''); // Clear in case of previously set
            $('#categoryName').val('');
            $('#orderNumber').val('');
            $('#categoryStatus').val('').trigger('change');
            $('#category-modal').modal('show');
        });

        $('.edit-btn').on('click', function() {
            $('#category-modal .modal-title').text('Edit Category');
            $('#category-modal .btn-block').text('Update');
            let categoryId = $(this).data('id');
            let categoryName = $(this).data('name');
            let orderNumber = $(this).data('order');
            let status = $(this).data('status');
            $('#submitAction').val('update');
            $('#categoryId').val(categoryId);
            $('#categoryName').val(categoryName);
            $('#orderNumber').val(orderNumber);
            $('#categoryStatus').val(status).trigger('change');
            $('#category-modal').modal('show');
        });
    });
</script>

<?php include_once('footer.php') ?>