<?php
include_once('header.php');

$msg = '';
if (!empty($admin)) {
    try {
        $get_dish = json_decode($admin->get_dish(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $dishName = $_POST['dishName'];
    $status = $_POST['dishStatus'];
    $added_on = date('Y-m-d h:i:s');
//    try {
//        if ($action == 'add') {
//            $result = $admin->add_dish($dishName, $status, $added_on);
//            $_SESSION['message'] = $result;
//        } else if ($action == 'update') {
//            $dishId = $_POST['dishId'];
//            $result = $admin->update_dish($dishId, $dishName, $status, $added_on);
//            $_SESSION['message'] = $result;
//        }
//    } catch (Exception $e) {
//        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
//    }
    header("Location: dish.php");
    exit;
}
// Check for session message and clear it
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message so it doesn't persist on refresh
}

$dishImg = $adminDetails['image'] ?? '';
$imagePath = $dishImg ? 'uploads/dish/' . $dishImg : 'assets/img/no-img.png';

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
                                data-toggle="modal" data-target="#dish-modal">
                            <i class="fa-regular fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="dish" class="table table-bordered table-striped table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th style="width: 10px">S.No.</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Dish</th>
                            <th class="text-center">Detail</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Added Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($get_dish)): ?>
                            <?php foreach ($get_dish as $index => $dish): ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($dish['category_name']); ?></td>
                                    <td class="text-center">
                                        <img src="<?php echo $imagePath; ?>" class="dish-img"
                                             alt="<?php echo htmlspecialchars($dish['dish']); ?>">
                                    </td>
                                    <td><?php echo htmlspecialchars($dish['dish']); ?></td>
                                    <td><?php echo truncateText($dish['dish_detail'], 3); ?></td>
                                    <td class="text-center">
                                        <?php if ($dish['type'] == 'veg'): ?>
                                            <img src="assets/img/veg_symbol.svg" alt="Veg"
                                                 class="img-fluid dish-type-img">
                                        <?php else: ?>
                                            <img src="assets/img/non_veg_symbol.svg" alt="Non-Veg"
                                                 class="img-fluid dish-type-img">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                <span
                                        class="<?php echo $dish['status'] == 0 ? 'inactive-badge' : 'active-badge'; ?>">
                                    <?php echo $dish['status'] == 0 ? 'Inactive' : 'Active'; ?>
                                </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date = new DateTime($dish['added_on']);
                                        echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                                type="button" data-toggle="modal" data-target="#dish-modal"
                                                data-id="<?php echo $dish['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($dish['dish']); ?>"
                                                data-status="<?php echo $dish['status']; ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-dish"
                                                data-id="<?php echo $dish['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align:center;">No Dish found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include_once('./modals/dish-modal.php') ?>

    <script type="text/javascript">
        $(document).ready(function () {

            //Initialize Select2 Elements
            $('#dishStatus').select2({
                theme: 'bootstrap4'
            });

            tippy('.see-more', {
                arrow: true,
                allowHTML: true
            })

            $('.add-btn').on('click', function () {
                $('#dish-modal .modal-title').text('Add Dish');
                $('#dish-modal .btn-block').text('Submit');
                $('#submitAction').val('add');
                $('#dishId').val('');
                $('#dishName').val('');
                $('#dishStatus').val('').trigger('change');
                $('#dish-modal').modal('show');
            });

            $('.edit-btn').on('click', function () {
                $('#dish-modal .modal-title').text('Edit Dish');
                $('#dish-modal .btn-block').text('Update');
                let dishId = $(this).data('id');
                let dishName = $(this).data('name');
                let status = $(this).data('status');
                $('#submitAction').val('update');
                $('#dishId').val(dishId);
                $('#dishName').val(dishName);
                $('#dishStatus').val(status).trigger('change');
                $('#dish-modal').modal('show');
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
                if (message === "Dish already exists") {
                    toastr.error(message);
                } else if (message === "Dish added successfully" || message ===
                    "Dish updated successfully") {
                    toastr.success(message);
                }
            }
        });
    </script>

<?php include_once('footer.php') ?>