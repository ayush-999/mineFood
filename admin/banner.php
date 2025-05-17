<?php
include_once('header.php');

$msg = '';
if (!empty($admin)) {
    try {
        $get_banner = json_decode((string) $admin->get_banner(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitAction'])) {
    $targetDir = "uploads/admin/banner/";
    $imageName = '';

    // Get form data
    $bannerId = $_POST['bannerId'] ?? null;
    $heading = $_POST['bannerHeading'] ?? '';
    $subHeading = $_POST['bannerSubHeading'] ?? '';
    $link = $_POST['bannerLink'] ?? '';
    $linkText = $_POST['bannerLinkText'] ?? '';
    $orderNumber = $_POST['bannerOrderNumber'] ?? '';
    $status = $_POST['bannerStatus'] ?? 0;
    $added_on = date('Y-m-d h:i:s');

    // Handle image upload
    if (isset($_POST['removeImage']) && $_POST['removeImage'] == '1') {
        // Remove image if requested
        $imageName = '';

        // If updating, delete the old image
        if ($_POST['submitAction'] == 'update' && !empty($bannerId)) {
            // Find the banner to get its current image
            $currentBanner = null;
            foreach ($get_banner as $banner) {
                if ($banner['id'] == $bannerId) {
                    $currentBanner = $banner;
                    break;
                }
            }

            if ($currentBanner && !empty($currentBanner['image'])) {
                $oldImagePath = $targetDir . $currentBanner['order_number'] . '/' . $currentBanner['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                    // Remove directory if empty
                    $dirPath = $targetDir . $currentBanner['order_number'];
                    if (is_dir($dirPath) && count(scandir($dirPath)) == 2) { // 2 for . and ..
                        rmdir($dirPath);
                    }
                }
            }
        }
    } elseif (!empty($_FILES['bannerImage']['name'])) {
        // New image uploaded
        $uploadDir = $targetDir . $orderNumber . '/';

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get file info
        $fileName = basename((string) $_FILES['bannerImage']['name']);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Generate unique filename
        $imageName = "banner_" . time() . "." . $fileType;
        $targetFilePath = $uploadDir . $imageName;

        // Check if image file is valid
        $allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($fileType, $allowTypes)) {
            // If updating, delete the old image first
            if ($_POST['submitAction'] == 'update' && !empty($bannerId)) {
                $currentBanner = null;
                foreach ($get_banner as $banner) {
                    if ($banner['id'] == $bannerId) {
                        $currentBanner = $banner;
                        break;
                    }
                }

                if ($currentBanner && !empty($currentBanner['image'])) {
                    $oldImagePath = $targetDir . $currentBanner['order_number'] . '/' . $currentBanner['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                        // Remove old directory if it's different from new one and empty
                        if ($currentBanner['order_number'] != $orderNumber) {
                            $oldDirPath = $targetDir . $currentBanner['order_number'];
                            if (is_dir($oldDirPath) && count(scandir($oldDirPath)) == 2) {
                                rmdir($oldDirPath);
                            }
                        }
                    }
                }
            }

            // Upload new image
            if (move_uploaded_file($_FILES['bannerImage']['tmp_name'], $targetFilePath)) {
                // Image uploaded successfully
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                header("Location: banner.php");
                exit;
            }
        } else {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: banner.php");
            exit;
        }
    } elseif ($_POST['submitAction'] == 'update') {
        // No new image uploaded but updating, keep the existing image
        $currentBanner = null;
        foreach ($get_banner as $banner) {
            if ($banner['id'] == $bannerId) {
                $currentBanner = $banner;
                break;
            }
        }

        if ($currentBanner) {
            $imageName = $currentBanner['image'];

            // If order number changed, move the image to new directory
            if ($currentBanner['order_number'] != $orderNumber) {
                $oldPath = $targetDir . $currentBanner['order_number'] . '/' . $imageName;
                $newDir = $targetDir . $orderNumber . '/';

                if (!is_dir($newDir)) {
                    mkdir($newDir, 0755, true);
                }

                if (file_exists($oldPath)) {
                    $newPath = $newDir . $imageName;
                    if (rename($oldPath, $newPath)) {
                        // Remove old directory if empty
                        $oldDir = $targetDir . $currentBanner['order_number'];
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
            $result = $admin->add_banner(
                $heading,
                $subHeading,
                $link,
                $linkText,
                $orderNumber,
                $status,
                $added_on,
                $imageName
            );
            $_SESSION['message'] = "Banner added successfully";
        } else {
            $result = $admin->update_banner(
                $bannerId,
                $heading,
                $subHeading,
                $link,
                $linkText,
                $orderNumber,
                $status,
                $added_on,
                $imageName
            );
            $_SESSION['message'] = "Banner updated successfully";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
    }

    header("Location: banner.php");
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
                        <b><?= htmlspecialchars($pageSubTitle ?? 'Banner') ?></b>
                    </h5>
                    <button class="btn bg-gradient-success btn-sm rounded-circle add-btn" type="button"
                        data-toggle="modal" data-target="#banner-modal">
                        <i class="fa-regular fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="banner" class="table table-bordered table-hover text-nowrap">
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
                        <?php if (!empty($get_banner)): ?>
                            <?php foreach ($get_banner as $index => $banner) : ?>
                                <tr>
                                    <td class="text-center"><?php echo htmlspecialchars((string) $banner['order_number']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $imagePath = '';
                                        $specificImagePath = "uploads/admin/banner/" . $banner['order_number'] . '/' . $banner['image'];
                                        if (!empty($banner['image']) && file_exists($specificImagePath)) {
                                            $imagePath = $specificImagePath;
                                        }
                                        // Fallback to default no-img.png
                                        if (empty($imagePath)) {
                                            $imagePath = 'assets/img/no-img.png';
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="banner-img view-img"
                                            alt="<?php echo htmlspecialchars((string) $banner['heading']); ?>">
                                    </td>
                                    <td><?php echo truncateText($banner['heading'], 2); ?></td>
                                    <td><?php echo truncateText($banner['sub_heading'], 2); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars((string) $banner['link']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars((string) $banner['link_txt']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date = new DateTime($banner['added_on']);
                                        echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="<?php echo $banner['status'] == 0 ? 'inactive-badge' : ($banner['status'] == 1 ? 'active-badge' : 'blocked-badge'); ?>">
                                            <?php
                                            echo $banner['status'] == 0 ? 'Inactive' : ($banner['status'] == 1 ? 'Active' : 'Blocked');
                                            ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                            data-toggle="modal" data-target="#banner-modal"
                                            data-id="<?php echo $banner['id']; ?>"
                                            data-image="<?php echo htmlspecialchars((string) $banner['image']); ?>"
                                            data-heading="<?php echo htmlspecialchars((string) $banner['heading']); ?>"
                                            data-subheading="<?php echo htmlspecialchars((string) $banner['sub_heading']); ?>"
                                            data-link="<?php echo $banner['link']; ?>"
                                            data-linktxt="<?php echo $banner['link_txt']; ?>"
                                            data-ordernumber="<?php echo $banner['order_number']; ?>"
                                            data-status="<?php echo $banner['status']; ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-banner"
                                            data-id="<?php echo $banner['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" style="text-align:center;">No banner found</td>
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
<?php include_once('./modals/banner-modal.php') ?>
<script type="text/javascript">
    $(document).ready(function() {

        const uploadArea = document.querySelector(".upload-area");
        const fileInput = document.getElementById("bannerImage");
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
        $('#bannerStatus').select2({
            theme: 'bootstrap4',
            minimumResultsForSearch: -1
        });

        $('.add-btn').on('click', function() {
            $('#banner-modal .modal-title').text('Add Banner');
            $('#banner-modal .btn-block').text('Add');
            $('#submitAction').val('add');
            $('#bannerId').val('');
            // $('#bannerImage').val('');
            $('#bannerHeading').val('');
            $('#bannerSubHeading').val('');
            $('#bannerLink').val('');
            $('#bannerLinkText').val('');
            $('#bannerOrderNumber').val('');
            $('#bannerStatus').val('').trigger('change');

            uploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
            removeBtnContainer.style.display = "none";
            fileInput.value = "";
            removeImageFlag.value = "0";

            $('#banner-modal').modal('show');
        });

        $('.edit-btn').on('click', function() {
            $('#banner-modal .modal-title').text('Edit Banner');
            $('#banner-modal .btn-block').text('Update');
            let bannerId = $(this).data('id');
            let bannerHeading = $(this).data('heading');
            let bannerSubHeading = $(this).data('subheading');
            let bannerLink = $(this).data('link');
            let bannerLinkText = $(this).data('linktxt');
            let bannerOrderNumber = $(this).data('ordernumber');
            let status = $(this).data('status');
            let bannerImage = $(this).data('image');
            $('#submitAction').val('update');
            $('#bannerId').val(bannerId);
            // $('#bannerImage').val(bannerImage);
            $('#bannerHeading').val(bannerHeading);
            $('#bannerSubHeading').val(bannerSubHeading);
            $('#bannerLink').val(bannerLink);
            $('#bannerLinkText').val(bannerLinkText);
            $('#bannerOrderNumber').val(bannerOrderNumber);
            $('#bannerStatus').val(status).trigger('change');
            $('#banner-modal').modal('show');

            if (bannerImage) {
                // uploadArea.innerHTML = `<img src="uploads/admin/banner/${bannerImage}">`;
                uploadArea.innerHTML = `<img src="uploads/admin/banner/${bannerOrderNumber}/${bannerImage}">`;
                removeBtnContainer.style.display = "block";
                removeImageFlag.value = "0";
            } else {
                uploadArea.innerHTML = `<svg class="icon icon-tabler icon-tabler-photo-up icons-tabler-outline"fill=none height=24 stroke=currentColor stroke-linecap=round stroke-linejoin=round stroke-width=2 viewBox="0 0 24 24"width=24 xmlns=http://www.w3.org/2000/svg><path d="M0 0h24v24H0z"fill=none stroke=none /><path d="M15 8h.01"/><path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"/><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5"/><path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526"/><path d="M19 22v-6"/><path d="M22 19l-3 -3l-3 3"/></svg><p>Drag and drop or click here to upload image</p>`;
                removeBtnContainer.style.display = "none";
                removeImageFlag.value = "0";
            }
        });

        $('#banner-modal').on('hidden.bs.modal', function() {
            // Reset the form when modal is closed
            $('#bannerForm')[0].reset();
            $('#bannerStatus').val('').trigger('change');

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
            if (message === "Banner already exists" || message === "Banner heading already exists") {
                toastr.error(message);
            } else if (message === "Banner added successfully" || message === "Banner updated successfully") {
                toastr.success(message);
            }
        }
    })
</script>
<?php include_once('footer.php') ?>