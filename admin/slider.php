<?php
include_once('header.php');

$msg = '';
if (!empty($admin)) {
    try {
        $get_slider = json_decode((string) $admin->get_slider(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitAction'])) {
    $targetDir = "uploads/admin/slider/";
    $imageName = '';

    // Get form data
    $sliderId = $_POST['sliderId'] ?? null;
    $heading = $_POST['sliderHeading'] ?? '';
    $subHeading = $_POST['sliderSubHeading'] ?? '';
    $link = $_POST['sliderLink'] ?? '';
    $linkText = $_POST['sliderLinkText'] ?? '';
    $orderNumber = $_POST['sliderOrderNumber'] ?? '';
    $status = $_POST['sliderStatus'] ?? 0;
    $added_on = date('Y-m-d h:i:s');

    // Handle image upload
    if (isset($_POST['removeImage']) && $_POST['removeImage'] == '1') {
        // Remove image if requested
        $imageName = '';

        // If updating, delete the old image
        if ($_POST['submitAction'] == 'update' && !empty($sliderId)) {
            // Find the slider to get its current image
            $currentSlider = null;
            foreach ($get_slider as $slider) {
                if ($slider['id'] == $sliderId) {
                    $currentSlider = $slider;
                    break;
                }
            }

            if ($currentSlider && !empty($currentSlider['image'])) {
                $oldImagePath = $targetDir . $currentSlider['order_number'] . '/' . $currentSlider['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                    // Remove directory if empty
                    $dirPath = $targetDir . $currentSlider['order_number'];
                    if (is_dir($dirPath) && count(scandir($dirPath)) == 2) { // 2 for . and ..
                        rmdir($dirPath);
                    }
                }
            }
        }
    } elseif (!empty($_FILES['sliderImage']['name'])) {
        // New image uploaded
        $uploadDir = $targetDir . $orderNumber . '/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get file info
        $fileName = basename((string) $_FILES['sliderImage']['name']);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Generate unique filename
        $imageName = "slider_" . time() . "." . $fileType;
        $targetFilePath = $uploadDir . $imageName;

        // Check if image file is valid
        $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowTypes)) {
            // If updating, delete the old image first
            if ($_POST['submitAction'] == 'update' && !empty($sliderId)) {
                $currentSlider = null;
                foreach ($get_slider as $slider) {
                    if ($slider['id'] == $sliderId) {
                        $currentSlider = $slider;
                        break;
                    }
                }

                if ($currentSlider && !empty($currentSlider['image'])) {
                    $oldImagePath = $targetDir . $currentSlider['order_number'] . '/' . $currentSlider['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                        // Remove old directory if it's different from new one and empty
                        if ($currentSlider['order_number'] != $orderNumber) {
                            $oldDirPath = $targetDir . $currentSlider['order_number'];
                            if (is_dir($oldDirPath) && count(scandir($oldDirPath)) == 2) {
                                rmdir($oldDirPath);
                            }
                        }
                    }
                }
            }

            // Upload new image
            if (move_uploaded_file($_FILES['sliderImage']['tmp_name'], $targetFilePath)) {
                // Image uploaded successfully
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                header("Location: slider.php");
                exit;
            }
        } else {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: banner.php");
            exit;
        }
    } elseif ($_POST['submitAction'] == 'update') {
        // No new image uploaded but updating, keep the existing image
        $currentSlider = null;
        foreach ($get_slider as $slider) {
            if ($slider['id'] == $sliderId) {
                $currentSlider = $slider;
                break;
            }
        }

        if ($currentSlider) {
            $imageName = $currentSlider['image'];

            // If order number changed, move the image to new directory
            if ($currentSlider['order_number'] != $orderNumber) {
                $oldPath = $targetDir . $currentSlider['order_number'] . '/' . $imageName;
                $newDir = $targetDir . $orderNumber . '/';

                if (!is_dir($newDir)) {
                    mkdir($newDir, 0755, true);
                }

                if (file_exists($oldPath)) {
                    $newPath = $newDir . $imageName;
                    if (rename($oldPath, $newPath)) {
                        // Remove old directory if empty
                        $oldDir = $targetDir . $currentSlider['order_number'];
                        if (is_dir($oldDir) && count(scandir($oldDir)) == 2) {
                            rmdir($oldDir);
                        }
                    }
                }
            }
        }
    }

    try {
        if ($_POST['submitAction'] == 'add') {
            $result = $admin->add_slider(
                $heading,
                $subHeading,
                $link,
                $linkText,
                $orderNumber,
                $status,
                $added_on,
                $imageName
            );
            $_SESSION['message'] = "Slider added successfully";
        } else {
            $result = $admin->update_slider(
                $sliderId,
                $heading,
                $subHeading,
                $link,
                $linkText,
                $orderNumber,
                $status,
                $added_on,
                $imageName
            );
            $_SESSION['message'] = "Slider updated successfully";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
    }

    header("Location: slider.php");
    exit;
}

// Check for session message and clear it
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message so it doesn't persist on refresh
}
?>

<!-- Rest of your HTML and JavaScript remains the same -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <b><?= htmlspecialchars($pageSubTitle ?? 'Slider') ?></b>
                    </h5>
                    <button class="btn bg-gradient-success btn-sm rounded-circle add-btn" type="button"
                        data-toggle="modal" data-target="#slider-modal">
                        <i class="fa-regular fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="slider" class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-center">O.No.</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Heading</th>
                            <th class="text-center">Sub Heading</th>
                            <th class="text-center">Link</th>
                            <th class="text-center">Link Text</th>
                            <th class="text-center">Added Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($get_slider)): ?>
                            <?php foreach ($get_slider as $index => $slider) : ?>
                                <tr>
                                    <td class="text-center"><?php echo htmlspecialchars((string) $slider['order_number']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $imagePath = '';
                                        $specificImagePath = "uploads/admin/slider/" . $slider['order_number'] . '/' . $slider['image'];
                                        if (!empty($slider['image']) && file_exists($specificImagePath)) {
                                            $imagePath = $specificImagePath;
                                        }
                                        // Fallback to default no-img.png
                                        if (empty($imagePath)) {
                                            $imagePath = 'assets/img/no-img.png';
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="slider-img view-img"
                                            alt="<?php echo htmlspecialchars((string) $slider['heading']); ?>">
                                    </td>
                                    <td><?php echo truncateText($slider['heading'], 2); ?></td>
                                    <td><?php echo truncateText($slider['sub_heading'], 2); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars((string) $slider['link']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars((string) $slider['link_txt']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date = new DateTime($slider['added_on']);
                                        echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="<?php echo $slider['status'] == 0 ? 'inactive-badge' : ($slider['status'] == 1 ? 'active-badge' : 'blocked-badge'); ?>">
                                            <?php
                                            echo $slider['status'] == 0 ? 'Inactive' : ($slider['status'] == 1 ? 'Active' : 'Blocked');
                                            ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                            data-toggle="modal" data-target="#slider-modal"
                                            data-id="<?php echo $slider['id']; ?>"
                                            data-image="<?php echo htmlspecialchars((string) $slider['image']); ?>"
                                            data-heading="<?php echo htmlspecialchars((string) $slider['heading']); ?>"
                                            data-subheading="<?php echo htmlspecialchars((string) $slider['sub_heading']); ?>"
                                            data-link="<?php echo $slider['link']; ?>"
                                            data-linktxt="<?php echo $slider['link_txt']; ?>"
                                            data-ordernumber="<?php echo $slider['order_number']; ?>"
                                            data-status="<?php echo $slider['status']; ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-slider"
                                            data-id="<?php echo $slider['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" style="text-align:center;">No slider found</td>
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
<?php include_once('./modals/slider-modal.php') ?>
<script type="text/javascript">
    $(document).ready(function() {

        const uploadArea = document.querySelector(".upload-area");
        const fileInput = document.getElementById("sliderImage");
        const removeBtnContainer = document.querySelector(".remove-btn-container");
        const removeImageBtn = document.querySelector(".remove-image-btn");
        const removeImageFlag = document.getElementById("removeImageFlag");

        uploadArea.addEventListener("dragover", (e) => {
            e.preventDefault();
            uploadArea.classList.add("dragover");
        });

        uploadArea.addEventListener("dragleave", () => {
            uploadArea.classList.remove("dragover");
        });

        uploadArea.addEventListener("drop", (e) => {
            e.preventDefault();
            uploadArea.classList.remove("dragover");

            const file = e.dataTransfer.files[0];
            if (file && file.type.match("image.*")) {
                handleImageUpload(file);
            }
        });

        uploadArea.addEventListener("click", () => {
            fileInput.click();
        });

        fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            if (file && file.type.match("image.*")) {
                handleImageUpload(file);
            }
        });

        function handleImageUpload(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                uploadArea.innerHTML = "";
                const img = document.createElement("img");
                img.src = event.target.result;
                uploadArea.appendChild(img);
                removeBtnContainer.style.display = "block";
                removeImageFlag.value = "0";
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            };
            reader.readAsDataURL(file);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
        }

        // Handle remove image button click
        removeImageBtn.addEventListener("click", function() {
            uploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
            removeBtnContainer.style.display = "none";
            fileInput.value = "";
            removeImageFlag.value = "1";
        });

        tippy('.see-more', {
            arrow: true,
            allowHTML: true
        })

        //Initialize Select2 Elements
        $('#sliderStatus').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: -1
        });

        $('.add-btn').on('click', function() {
            $('#slider-modal .modal-title').text('Add Slider');
            $('#slider-modal .btn-block').text('Add');
            $('#submitAction').val('add');
            $('#sliderId').val('');
            // $('#sliderImage').val('');
            $('#sliderHeading').val('');
            $('#sliderSubHeading').val('');
            $('#sliderLink').val('');
            $('#sliderLinkText').val('');
            $('#sliderOrderNumber').val('');
            $('#sliderStatus').val('').trigger('change');

            uploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
            removeBtnContainer.style.display = "none";
            fileInput.value = "";
            removeImageFlag.value = "0";

            $('#slider-modal').modal('show');
        });

        $('.edit-btn').on('click', function() {
            $('#slider-modal .modal-title').text('Edit Slider');
            $('#slider-modal .btn-block').text('Update');
            let sliderId = $(this).data('id');
            let sliderHeading = $(this).data('heading');
            let sliderSubHeading = $(this).data('subheading');
            let sliderLink = $(this).data('link');
            let sliderLinkText = $(this).data('linktxt');
            let sliderOrderNumber = $(this).data('ordernumber');
            let status = $(this).data('status');
            let sliderImage = $(this).data('image');
            $('#submitAction').val('update');
            $('#sliderId').val(sliderId);
            // $('#sliderImage').val(sliderImage);
            $('#sliderHeading').val(sliderHeading);
            $('#sliderSubHeading').val(sliderSubHeading);
            $('#sliderLink').val(sliderLink);
            $('#sliderLinkText').val(sliderLinkText);
            $('#sliderOrderNumber').val(sliderOrderNumber);
            $('#sliderStatus').val(status).trigger('change');
            $('#slider-modal').modal('show');

            if (sliderImage) {
                // uploadArea.innerHTML = `<img src="uploads/admin/slider/${sliderImage}">`;
                uploadArea.innerHTML = `<img src="uploads/admin/slider/${sliderOrderNumber}/${sliderImage}">`;
                removeBtnContainer.style.display = "block";
                removeImageFlag.value = "0";
            } else {
                uploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
                removeBtnContainer.style.display = "none";
                removeImageFlag.value = "0";
            }
        });

        $('#slider-modal').on('hidden.bs.modal', function() {
            // Reset the form when modal is closed
            $('#sliderForm')[0].reset();
            $('#sliderStatus').val('').trigger('change');

            // Reset the upload area
            uploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
            removeBtnContainer.style.display = "none";
            fileInput.value = "";
            removeImageFlag.value = "0";
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
        let message = <?php echo json_encode(value: $msg); ?>;
        if (message) {
            if (message === "Slider already exists" || message === "Slider heading already exists") {
                toastr.error(message);
            } else if (message === "Slider added successfully" || message === "Slider updated successfully") {
                toastr.success(message);
            }
        }
    })
</script>
<?php include_once('footer.php') ?>