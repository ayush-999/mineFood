<?php
include_once('header.php');
$msg = '';
$get_categories = [];
$get_dish = [];
$dishDetails = [];
$dishId = $_GET['id'] ?? null;
if (!empty($admin)) {
    try {
        $get_categories = json_decode((string) $admin->get_all_categories(), true);
        if ($dishId) {
            $dishData = json_decode((string) $admin->get_dish($dishId), true);
            if (isset($dishData['dish'][0])) {
                $dishDetails = $dishData['dish'][0];
            } else {
                $_SESSION['message'] = "Dish not found.";
                header("Location: dish.php");
                exit;
            }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $_SESSION['message'] = "Error retrieving data.";
    }
} else {
    $_SESSION['message'] = "Admin session not available.";
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $dishId = $_POST['dishId'] ?? null;
    $dishName = $_POST['dishName'];
    $dishCategory = $_POST['dishCategory'];
    $dishStatus = $_POST['dishStatus'];
    $dishType = $_POST['dishType'];
    $dishDetail = $_POST['dishDetail'];
    $dishPrice = $_POST['dishPrice'];
    $dishAttribute = $_POST['dishAttribute'];
    $added_on = date('Y-m-d h:i:s');

    $imagePath = '';
    if ($dishId && isset($dishDetails['image'])) {
        $imagePath = $dishDetails['image'];
    }

    // Handle image removal
    if (isset($_POST['removeImage']) && $_POST['removeImage'] == '1') {
        if ($dishId && !empty($dishDetails['image'])) {
            // Delete the old image from both possible locations
            $oldCategoryDir = $dishDetails['category_id'] ?? 'temp';
            $oldImagePathFull = 'uploads/admin/dish/' . $oldCategoryDir . '/' . $dishDetails['image'];
            if (file_exists($oldImagePathFull)) {
                @unlink($oldImagePathFull);
            }
            $oldTempPath = 'uploads/admin/dish/temp/' . $dishDetails['image'];
            if (file_exists($oldTempPath)) {
                @unlink($oldTempPath);
            }
            $imagePath = ''; // Set to empty to remove from database
        }
    }
    // Handle new image upload
    elseif (isset($_FILES['dishImg']) && $_FILES['dishImg']['error'] == UPLOAD_ERR_OK && $_FILES['dishImg']['size'] > 0) {
        $newImageName = basename((string) $_FILES['dishImg']['name']);
        $uploadDir = 'uploads/admin/dish/';

        if ($dishCategory) {
            $uploadDir .= $dishCategory . '/';
        } else {
            $uploadDir .= 'temp/';
            $_SESSION['message'] = "Please select a category for the dish.";
            header("Location: dishDetails.php" . ($dishId ? "?id=$dishId" : ""));
            exit;
        }

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                $_SESSION['message'] = "Failed to create upload directory.";
                header("Location: dishDetails.php" . ($dishId ? "?id=$dishId" : ""));
                exit;
            }
        }

        $uploadFile = $uploadDir . $newImageName;

        // Delete old image if exists
        if ($dishId && !empty($dishDetails['image'])) {
            $oldCategoryDir = $dishDetails['category_id'] ?? 'temp';
            $oldImagePathFull = 'uploads/admin/dish/' . $oldCategoryDir . '/' . $dishDetails['image'];
            if (file_exists($oldImagePathFull)) {
                @unlink($oldImagePathFull);
            }
            $oldTempPath = 'uploads/admin/dish/temp/' . $dishDetails['image'];
            if (file_exists($oldTempPath)) {
                @unlink($oldTempPath);
            }
        }

        if (move_uploaded_file($_FILES['dishImg']['tmp_name'], $uploadFile)) {
            $imagePath = $newImageName;
        } else {
            $_SESSION['message'] = "Image upload failed. Check permissions or disk space.";
            header("Location: dishDetails.php" . ($dishId ? "?id=$dishId" : ""));
            exit;
        }
    }

    // Prepare attributes array
    $dishAttributes = [];
    if (!empty($dishPrice) && !empty($dishAttribute) && count($dishPrice) == count($dishAttribute)) {
        foreach ($dishPrice as $key => $price) {
            if (isset($dishAttribute[$key])) {
                $attribute = $dishAttribute[$key];
                if (!empty($price) && !empty($attribute)) {
                    $dishAttributes[] = ['attribute' => $attribute, 'price' => $price];
                }
            }
        }
    }

    if (empty($dishAttributes)) {
        $_SESSION['message'] = "Please add at least one price and quantity.";
        header("Location: dishDetails.php" . ($dishId ? "?id=$dishId" : ""));
        exit;
    }

    try {
        if ($dishId) {
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
        } else {
            if (empty($dishCategory)) {
                $_SESSION['message'] = "Please select a category to add a new dish.";
                header("Location: dishDetails.php");
                exit;
            }
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
        }
        $_SESSION['message'] = $result;
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
        header("Location: dishDetails.php" . ($dishId ? "?id=$dishId" : ""));
        exit;
    }
    header("Location: dish.php");
    exit;
}

if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}

$displayImgFilename = $dishDetails['image'] ?? '';
$displayCategory = $dishDetails['category_id'] ?? '';
$displayImagePath = 'assets/img/no-img.png';

if ($displayImgFilename && $displayCategory) {
    $potentialPath = 'uploads/admin/dish/' . $displayCategory . '/' . $displayImgFilename;
    if (file_exists($potentialPath)) {
        $displayImagePath = $potentialPath;
    } else {
        $tempPath = 'uploads/admin/dish/temp/' . $displayImgFilename;
        if (file_exists($tempPath)) {
            $displayImagePath = $tempPath;
        }
    }
}
?>
<div class="row">
    <div class="col-4">
        <div class="card" style="height: 20rem;">
            <div class="card-body">
                <div class="dish-uploader">
                    <div class="dish-upload-area" id="dishUploadArea">
                        <?php if (!empty($displayImagePath) && $displayImagePath !== 'assets/img/no-img.png'): ?>
                            <img src="<?php echo htmlspecialchars($displayImagePath); ?>" alt="Dish Image">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-photo-up">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 8h.01" />
                                <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                                <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                                <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                                <path d="M19 22v-6" />
                                <path d="M22 19l-3 -3l-3 3" />
                            </svg>
                            <p>Drag and drop or click here to upload image</p>
                        <?php endif; ?>
                    </div>
                    <div class="remove-btn-container" style="<?php echo (!empty($displayImagePath) && $displayImagePath !== 'assets/img/no-img.png') ? 'display: block;' : 'display: none;' ?>">
                        <button type="button" class="btn bg-gradient-danger btn-sm rounded-circle remove-image-btn">
                            <i class="fa fa-close"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title dishTitle">
                        <b><?php echo !empty($pageSubTitle) ? $pageSubTitle : 'Dish Details'; ?></b>
                    </h5>
                    <button class="btn bg-gradient-secondary btn-sm rounded-pill add-btn" type="button"
                        onclick="window.location.href='dish.php'">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($msg)): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars((string) $msg); ?></div>
                <?php endif; ?>
                <form method="POST" action="<?php echo htmlspecialchars((string) $_SERVER["PHP_SELF"]) . ($dishId ? '?id=' . $dishId : ''); ?>" id="dishForm"
                    enctype="multipart/form-data">
                    <div class="form-body">
                        <input type="hidden" id="dishId" name="dishId" value="<?php echo htmlspecialchars($dishId ?? ''); ?>">
                        <input type="file" id="dishImg" name="dishImg" accept="image/*" style="display: none;">
                        <input type="hidden" id="removeImageFlag" name="removeImage" value="0">

                        <div class="row mb-3">
                            <div class="col-md-12 form-group mb-0">
                                <label for="dishName">Dish Name</label>
                                <input type="text" class="form-control" id="dishName" name="dishName"
                                    placeholder="Enter Dish name"
                                    value="<?php echo htmlspecialchars($dishDetails['dish_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 form-group mb-0">
                                <label for="dishCategory">Category</label>
                                <select class="form-control" name="dishCategory" id="dishCategory">
                                    <option value="">Select Category</option>
                                    <?php
                                    if (!empty($get_categories)) {
                                        foreach ($get_categories as $category) {
                                            $selected = (isset($dishDetails['category_id']) && $category['id'] == $dishDetails['category_id']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars((string) $category['id']) . '" ' . $selected . '>' . htmlspecialchars((string) $category['category_name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 form-group mb-0">
                                <label for="dishStatus">Status</label>
                                <select class="form-control" name="dishStatus" id="dishStatus">
                                    <option value="">Select Status</option>
                                    <option value="0" <?php echo (isset($dishDetails['status']) && $dishDetails['status'] == 0) ? 'selected' : ''; ?>>
                                        Inactive
                                    </option>
                                    <option value="1" <?php echo (isset($dishDetails['status']) && $dishDetails['status'] == 1) ? 'selected' : ''; ?>>
                                        Active
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group mb-0">
                                <label for="dishType">Type</label>
                                <select class="form-control" name="dishType" id="dishType">
                                    <option value="">Select Type</option>
                                    <option value="veg" <?php echo (isset($dishDetails['type']) && $dishDetails['type'] == 'veg') ? 'selected' : ''; ?>>
                                        Veg
                                    </option>
                                    <option value="non-veg" <?php echo (isset($dishDetails['type']) && $dishDetails['type'] == 'non-veg') ? 'selected' : ''; ?>>
                                        Non-Veg
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 form-group mb-0">
                                <label for="dishDetail">Detail</label>
                                <textarea class="form-control" id="dishDetail" name="dishDetail"
                                    rows="3"><?php echo htmlspecialchars($dishDetails['dish_detail'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="attributeContainer mb-3" id="attributeContainer">
                            <?php if (!empty($dishDetails['attributes'])): ?>
                                <?php foreach ($dishDetails['attributes'] as $index => $attribute): ?>
                                    <div class="row attribute-item mt-2"
                                        id="attributeItem<?php echo $index + 1; ?>">
                                        <div class="col-md-6 form-group mb-0">
                                            <label for="dishPrice<?php echo $index + 1; ?>">Price</label>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <div class="input-group-text rounded-left">
                                                        <i class="fa-regular fa-indian-rupee-sign"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control dishPrice"
                                                    id="dishPrice<?php echo $index + 1; ?>"
                                                    name="dishPrice[]" placeholder="0.00"
                                                    value="<?php echo $attribute['price']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5 form-group mb-0">
                                            <label for="dishAttribute<?php echo $index + 1; ?>">Quantity</label>
                                            <select class="form-control dishAttribute"
                                                id="dishAttribute<?php echo $index + 1; ?>"
                                                name="dishAttribute[]">
                                                <option value="">Select Quantity</option>
                                                <option value="full" <?php echo $attribute['attribute'] == 'full' ? 'selected' : ''; ?>>
                                                    Full
                                                </option>
                                                <option value="half" <?php echo $attribute['attribute'] == 'half' ? 'selected' : ''; ?>>
                                                    Half
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 form-group d-flex justify-content-center align-items-end mb-1">
                                            <?php if ($index === 0): ?>
                                                <button type="button"
                                                    class="btn bg-gradient-success btn-sm rounded-circle"
                                                    id="addMoreAttributes">
                                                    <i class="fa-regular fa-add"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                    class="btn bg-gradient-danger btn-sm rounded-circle dish-attribute-remove">
                                                    <i class="fa-regular fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="row attribute-item" id="attributeItem1">
                                    <div class="col-md-6 form-group mb-0">
                                        <label for="dishPrice1">Price</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text rounded-left">
                                                    <i class="fa-regular fa-indian-rupee-sign"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control dishPrice" id="dishPrice1"
                                                name="dishPrice[]"
                                                placeholder="0.00" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-5 form-group mb-0">
                                        <label for="dishAttribute1">Quantity</label>
                                        <select class="form-control dishAttribute" id="dishAttribute1"
                                            name="dishAttribute[]">
                                            <option value="">Select Quantity</option>
                                            <option value="full">Full</option>
                                            <option value="half">Half</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 form-group d-flex justify-content-center align-items-end mb-1">
                                        <button type="button" class="btn bg-gradient-success btn-sm rounded-circle"
                                            id="addMoreAttributes">
                                            <i class="fa-regular fa-add"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-0">
                                <button type="submit" class="btn bg-gradient-success btn-block" name="submit">
                                    <?php echo isset($dishId) ? 'Update Dish' : 'Add Dish'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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

        initializeEditor();
        $('#dishForm').on('submit', function(e) {
            if (editorInstance) {
                $('#dishDetail').val(editorInstance.getData());
            }
        });
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

        let attributeCount = <?php echo !empty($dishDetails['attributes']) ? count($dishDetails['attributes']) : 1; ?>;
        $('#addMoreAttributes').on('click', function() {
            attributeCount++;
            let newAttributeRow = `<div class="attribute-item mt-2 row" id=attributeItem${attributeCount}><div class="form-group mb-0 col-md-6"><label for=dishPrice${attributeCount}>Price</label><div class=input-group><div class=input-group-append><div class="input-group-text rounded-left"><i class="fa-regular fa-indian-rupee-sign"></i></div></div><input class="form-control dishPrice" id=dishPrice${attributeCount} name=dishPrice[] placeholder=0.00></div></div><div class="form-group mb-0 col-md-5"><label for=dishAttribute${attributeCount}>Quantity</label> <select class="form-control dishAttribute" id=dishAttribute${attributeCount} name=dishAttribute[]><option value="">Select Quantity<option value=full>Full<option value=half>Half</select></div><div class="form-group align-items-end col-md-1 d-flex justify-content-center mb-1"><button class="bg-gradient-danger btn btn-sm dish-attribute-remove rounded-circle" type=button><i class="fa-regular fa-trash"></i></button></div></div>`;
            $('#attributeContainer').append(newAttributeRow);
        });

        $('#attributeContainer').on('click', '.dish-attribute-remove', function() {
            $(this).closest('.attribute-item').remove();
        });

        $('.dishAttribute').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: -1
        });

        const dishUploadArea = document.getElementById("dishUploadArea");
        const dishFileInput = document.getElementById("dishImg");
        const dishRemoveBtnContainer = document.querySelector(".remove-btn-container");
        const dishRemoveImageBtn = document.querySelector(".remove-image-btn");
        const dishRemoveImageFlag = document.getElementById("removeImageFlag");

        dishUploadArea.addEventListener("dragover", (e) => {
            e.preventDefault();
            dishUploadArea.classList.add("dragover");
        });

        dishUploadArea.addEventListener("dragleave", () => {
            dishUploadArea.classList.remove("dragover");
        });

        dishUploadArea.addEventListener("drop", (e) => {
            e.preventDefault();
            dishUploadArea.classList.remove("dragover");

            const file = e.dataTransfer.files[0];
            if (file && file.type.match("image.*")) {
                handleDishImageUpload(file);
            }
        });

        dishUploadArea.addEventListener("click", () => {
            dishFileInput.click();
        });

        dishFileInput.addEventListener("change", () => {
            const file = dishFileInput.files[0];
            if (file && file.type.match("image.*")) {
                handleDishImageUpload(file);
            }
        });

        function handleDishImageUpload(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                dishUploadArea.innerHTML = "";
                const img = document.createElement("img");
                img.src = event.target.result;
                dishUploadArea.appendChild(img);
                dishRemoveBtnContainer.style.display = "block";
                dishRemoveImageFlag.value = "0";
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                dishFileInput.files = dataTransfer.files;
            };
            reader.readAsDataURL(file);
        }

        dishRemoveImageBtn.addEventListener("click", function() {
            dishUploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
            dishRemoveBtnContainer.style.display = "none";
            dishFileInput.value = "";
            dishRemoveImageFlag.value = "1"; // This is important for telling the server to remove the image
        });

    });
</script>
<?php include_once('footer.php'); ?>