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
    $dishPrice = $_POST['dishPrice'];
    $dishAttribute = $_POST['dishAttribute'];
    $added_on = date('Y-m-d h:i:s');
    $imagePath = '';
    $dishAttributes = [];

    // Set upload directory
    $dishId = $_POST['dishId'] ?? null;
    $uploadDir = 'uploads/admin/dish/';
    if ($dishId) {
        $uploadDir .= $dishId . '/';
    } else {
        $uploadDir .= 'temp/';
    }

    // Create the directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    // Handle image upload
    if (isset($_FILES['dishImg']) && $_FILES['dishImg']['error'] == UPLOAD_ERR_OK) {
        $imagePath = basename($_FILES['dishImg']['name']);
        $uploadFile = $uploadDir . $imagePath;

        if (!move_uploaded_file($_FILES['dishImg']['tmp_name'], $uploadFile)) {
            $_SESSION['message'] = json_encode(["message" => "Image upload failed"]);
            header("Location: dish.php");
            exit;
        }
    } elseif ($dishId) {
        // Retain the existing image if no new image is uploaded
        $dishDetails = json_decode($admin->get_dish($dishId), true)['dish'][0] ?? [];
        $imagePath = $dishDetails['image'] ?? '';
    }

    if (!empty($dishPrice) && !empty($dishAttribute)) {
        foreach ($dishPrice as $key => $price) {
            $attribute = $dishAttribute[$key];
            $dishAttributes[] = ['attribute' => $attribute, 'price' => $price];
        }
    }
    try {
        if ($action == 'add') {
            $result = $admin->add_dish(
                $dishName,
                $dishCategory,
                $dishStatus,
                $dishType,
                $dishDetail,
                $added_on,
                $imagePath,
                $dishAttributes
            );
        } elseif ($action == 'update') {
            $result = $admin->update_dish(
                $dishId,
                $dishName,
                $dishCategory,
                $dishStatus,
                $dishType,
                $dishDetail,
                $added_on,
                $imagePath,
                $dishAttributes
            );
        }
        $_SESSION['message'] = $result;
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
                    <button class="btn bg-gradient-success btn-sm rounded-circle add-btn" type="button" data-toggle="modal" data-target="#dish-modal">
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
                        <?php if (!empty($get_dish['dish'])) : ?>
                            <?php foreach ($get_dish['dish'] as $index => $dish) : ?>
                                <tr class="<?php echo $dish['category_status'] == 0 ? 'disabled-cell' : ''; ?>">
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($dish['dish_name']); ?></td>
                                    <td><?php echo htmlspecialchars($dish['category_name']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $dishId = $dish['id'];
                                        $imagePath = '';
                                        // Check specific dish folder
                                        $specificImagePath = "uploads/admin/dish/{$dishId}/" . $dish['image'];
                                        if (!empty($dish['image']) && file_exists($specificImagePath)) {
                                            $imagePath = $specificImagePath;
                                        }
                                        // Check temp folder if not found in specific dish folder
                                        if (empty($imagePath)) {
                                            $tempImagePath = "uploads/admin/dish/temp/" . $dish['image'];
                                            if (!empty($dish['image']) && file_exists($tempImagePath)) {
                                                $imagePath = $tempImagePath;
                                            }
                                        }
                                        // Fallback to default no-img.png
                                        if (empty($imagePath)) {
                                            $imagePath = 'assets/img/no-img.png';
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="dish-img view-img" alt="<?php echo htmlspecialchars($dish['dish_name']); ?>">
                                    </td>
                                    <td><?php echo truncateText($dish['dish_detail'], 3); ?></td>
                                    <td class="text-center">
                                        <?php if ($dish['type'] == 'veg') : ?>
                                            <img src="assets/img/veg_symbol.svg" alt="Veg" class="img-fluid dish-type-img">
                                        <?php else : ?>
                                            <img src="assets/img/non-veg_symbol.svg" alt="Non-Veg" class="img-fluid dish-type-img">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="<?php echo $dish['status'] == 0 ? 'inactive-badge' : 'active-badge'; ?>">
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
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn" type="button" data-toggle="modal" data-target="#dish-modal" data-id="<?php echo $dish['id']; ?>" data-name="<?php echo htmlspecialchars($dish['dish_name'] ?? ''); ?>" data-category="<?php echo $dish['category_id']; ?>" data-status="<?php echo $dish['status']; ?>" data-type="<?php echo htmlspecialchars($dish['type'] ?? ''); ?>" data-detail="<?php echo htmlspecialchars($dish['dish_detail'] ?? ''); ?>" data-image="<?php echo htmlspecialchars($dish['image'] ?? ''); ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-dish" data-id="<?php echo $dish['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
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
<div id="image-viewer">
    <span class="close"><i class="fa-solid fa-xmark"></i></span>
    <img class="image-modal-content" id="full-image">
</div>
<?php include_once('./modals/dish-modal.php') ?>
<script type="text/javascript">
    $(document).ready(function() {
        let editorInstance;

        function initializeEditor() {
            if (editorInstance) {
                editorInstance.destroy()
                    .then(() => {
                        ClassicEditor.create(document.querySelector('#dishDetail'), {
                                toolbar: {
                                    items: [
                                        'undo', 'redo', '|',
                                        'bold', 'italic', '|',
                                        'blockQuote', 'alignment', '|',
                                        'bulletedList', 'numberedList', '|',
                                        'outdent',
                                        'indent'
                                    ],
                                    shouldNotGroupWhenFull: false
                                },
                                language: 'en'
                            })
                            .then(editor => {
                                editorInstance = editor;
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                ClassicEditor.create(document.querySelector('#dishDetail'), {
                        toolbar: {
                            items: [
                                'undo', 'redo', '|',
                                'bold', 'italic', '|',
                                'blockQuote', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent',
                                'indent'
                            ],
                            shouldNotGroupWhenFull: false
                        },
                        language: 'en'
                    })
                    .then(editor => {
                        editorInstance = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }

        // $('.add-btn, .edit-btn').on('click', function() {
        //     initializeEditor();
        // });

        $('#dish-modal').on('shown.bs.modal', function() {
            initializeEditor();
        });

        $('#dishForm').on('submit', function(e) {
            if (editorInstance) {
                $('#dishDetail').val(editorInstance.getData());
            }
        });

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
        $('#dishAttribute').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: -1
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
        $('.add-btn').on('click', function() {
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
        $('.edit-btn').on('click', function() {
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
            // $('#dishDetail').val(dishDetail).trigger('change');
            $('#dishDetail').val(dishDetail);
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

        let attributeCount = 1;
        $('#addMoreAttributes').on('click', function() {
            attributeCount++;
            let newAttributeRow = `<div class="row attribute-item mt-2" id="attributeItem${attributeCount}"><div class="col-md-6 form-group mb-0"><div class="input-group"><div class="input-group-append"><div class="input-group-text rounded-left"><i class="fa-regular fa-indian-rupee-sign"></i></div></div><input type="text" class="form-control dishPrice" id="dishPrice" name="dishPrice[]" placeholder="0.00" value=""></div></div><div class="col-md-5 form-group mb-0"><select class="form-control dishAttribute" name="dishAttribute[]" id="dishAttribute"><option value="">Select Quantity</option><option value="full">Full</option><option value="half">Half</option></select></div><div class="col-md-1 form-group d-flex justify-content-center align-items-end mb-1"><button class="btn bg-gradient-danger btn-sm rounded-circle" id="dish-attribute-remove" type="button"><i class="fa-regular fa-trash"></i></button></div></div>`;
            $('#attributeContainer').append(newAttributeRow);
        });

        $('#attributeContainer').on('click', '#dish-attribute-remove', function() {
            $(this).closest('.attribute-item').remove();
        });
    });
</script>
<?php include_once('footer.php') ?>