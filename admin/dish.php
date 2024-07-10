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
    $dishCategory = $_POST['dishCategory'];
    $dishStatus = $_POST['dishStatus'];
    $dishType = $_POST['dishType'];
    $dishDetail = $_POST['dishDetail'];
    $added_on = date('Y-m-d h:i:s');
    $imagePath = '';
    if (isset($_POST['dishId'])) {
        $dishId = $_POST['dishId'];
        $dishDetails = json_decode($admin->get_dish($dishId), true)[0];
        $existingImage = $dishDetails['image'];
        $uploadDir = 'uploads/admin/dish/' . $dishId . '/';
    } else {
        $dishId = null;
        $existingImage = '';
        $uploadDir = 'uploads/admin/dish/temp/';
    }
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (isset($_FILES['dishImg']) && $_FILES['dishImg']['error'] == UPLOAD_ERR_OK) {
        if (!empty($existingImage) && file_exists($uploadDir . $existingImage)) {
            unlink($uploadDir . $existingImage);
        }
        $imagePath = basename($_FILES['dishImg']['name']);
        $uploadFile = $uploadDir . $imagePath;
        if (!move_uploaded_file($_FILES['dishImg']['tmp_name'], $uploadFile)) {
            $_SESSION['message'] = json_encode(["message" => "Image upload failed"]);
            header("Location: dish.php");
            exit;
        }
    } else {
        $imagePath = $existingImage;
    }
    try {
        if ($action == 'add') {
            $result = $admin->add_dish($dishName, $dishCategory, $dishStatus, $dishType, $dishDetail, $added_on, $imagePath);
            $_SESSION['message'] = $result;
        } else if ($action == 'update') {
            $result = $admin->update_dish($dishId, $dishName, $dishCategory, $dishStatus, $dishType, $dishDetail, $added_on, $imagePath);
            $_SESSION['message'] = $result;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
    }
    header("Location: dish.php");
    exit;
}
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
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
                                data-toggle="modal" data-target="#dish-modal">
                            <i class="fa-regular fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="dish" class="table table-bordered table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th style="width: 10px">S.No.</th>
                            <th class="text-center">Dish</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Image</th>
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
                                <tr class="<?php echo $dish['category_status'] == 0 ? 'disabled-cell' : ''; ?>">
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($dish['dish_name']); ?></td>
                                    <td><?php echo htmlspecialchars($dish['category_name']); ?></td>
                                    <td class="text-center">
                                        <img src="<?php echo empty($dish['image']) ? 'assets/img/no-img.png' : 'uploads/admin/dish/' . $dish['id'] . '/' . $dish['image']; ?>" class="dish-img" alt="<?php echo htmlspecialchars($dish['dish_name']); ?>">
                                    </td>
                                    <td><?php echo truncateText($dish['dish_detail'], 3); ?></td>
                                    <td class="text-center">
                                        <?php if ($dish['type'] == 'veg'): ?>
                                            <img src="assets/img/veg_symbol.svg" alt="Veg"
                                                 class="img-fluid dish-type-img">
                                        <?php else: ?>
                                            <img src="assets/img/non-veg_symbol.svg" alt="Non-Veg"
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
                                                data-name="<?php echo htmlspecialchars($dish['dish_name']); ?>"
                                                data-category="<?php echo $dish['category_id']; ?>"
                                                data-status="<?php echo $dish['status']; ?>"
                                                data-type="<?php echo htmlspecialchars($dish['type']); ?>"
                                                data-detail="<?php echo htmlspecialchars($dish['dish_detail']); ?>"
                                                data-image="<?php echo htmlspecialchars($dish['image']); ?>"
                                        >
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
                                <td colspan="9" style="text-align:center;">No Dish found</td>
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
                theme: 'bootstrap4',
                minimumResultsForSearch: -1
            });
            $('#dishCategory').select2({
                theme: 'bootstrap4'
            });
            $('#dishType').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: -1,
                templateResult: formatDishType,
                templateSelection: formatDishType
            });
            function formatDishType(state) {
                if (!state.id) {
                    return state.text;
                }
                let baseUrl = "assets/img/";
                return $(
                    '<span><img src="' + baseUrl + state.element.value.toLowerCase().replace(" ", "_") + '_symbol.svg" class="img-fluid dish-type-img mr-1"  alt=""/> ' + state.text + '</span>'
                );
            }
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
                $('#dishCategory').val('').trigger('change');
                $('#dishStatus').val('').trigger('change');
                $('#dishType').val('').trigger('change');
                $('#dishDetail').val('').trigger('change');
                $('#image-preview').attr('src', 'assets/img/no-img.png');
                $('#dish-modal').modal('show');
            });
            $('.edit-btn').on('click', function () {
                $('#dish-modal .modal-title').text('Edit Dish');
                $('#dish-modal .btn-block').text('Update');
                let dishId = $(this).data('id');
                let dishName = $(this).data('name');
                let dishCategory = $(this).data('category');
                let dishStatus = $(this).data('status');
                let dishType = $(this).data('type');
                let dishDetail = $(this).data('detail');
                let dishImg = $(this).data('image');
                $('#submitAction').val('update');
                $('#dishId').val(dishId);
                $('#dishName').val(dishName);
                $('#dishCategory').val(dishCategory).trigger('change');
                $('#dishStatus').val(dishStatus).trigger('change');
                $('#dishType').val(dishType).trigger('change');
                $('#dishDetail').val(dishDetail).trigger('change');
                let imagePath = dishImg ? 'uploads/admin/dish/' + dishId + '/' + dishImg : 'assets/img/no-img.png';
                $('#image-preview').attr('src', imagePath);
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
                if (message === "Dish already exists" || message === "Image upload failed") {
                    toastr.error(message);
                } else if (message === "Dish added successfully" || message ===
                    "Dish updated successfully") {
                    toastr.success(message);
                }
            }
            $('#dishImg').imoViewer({
                'preview': '#image-preview',
            })
        });
    </script>
<?php include_once('footer.php') ?>