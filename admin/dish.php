<?php
include_once('header.php');
$msg = '';
if (!empty($admin)) {
    try {
        $get_dish = json_decode((string) $admin->get_dish(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
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
                        onclick="window.location.href='dishDetails.php'">
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
                                    <td><?php echo htmlspecialchars((string) $dish['dish_name']); ?></td>
                                    <td><?php echo htmlspecialchars((string) $dish['category_name']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $imagePath = '';
                                        // Check specific dish category folder
                                        $specificImagePath = "uploads/admin/dish/{$dish['category_id']}/" . $dish['image'];
                                        if (!empty($dish['image']) && file_exists($specificImagePath)) {
                                            $imagePath = $specificImagePath;
                                        }
                                        // Check temp folder if not found in specific dish category folder
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
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" class="dish-img view-img"
                                            alt="<?php echo htmlspecialchars((string) $dish['dish_name']); ?>">
                                    </td>
                                    <td><?php echo truncateText($dish['dish_detail'], 3); ?></td>
                                    <td class="text-center">
                                        <?php if ($dish['type'] == 'veg') : ?>
                                            <img src="assets/img/veg_symbol.svg" alt="Veg"
                                                class="img-fluid dish-type-img">
                                        <?php else : ?>
                                            <img src="assets/img/non-veg_symbol.svg" alt="Non-Veg"
                                                class="img-fluid dish-type-img">
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
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                            type="button"
                                            onclick="window.location.href='dishDetails.php?id=<?php echo $dish['id']; ?>'">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-dish"
                                            data-id="<?php echo $dish['id']; ?>" type="button">
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
<script type="text/javascript">
    $(document).ready(function() {
        tippy('.see-more', {
            arrow: true,
            allowHTML: true
        })
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
    });
</script>
<?php include_once('footer.php') ?>