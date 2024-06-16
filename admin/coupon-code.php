<?php
include_once ('header.php');

$msg = '';
$get_couponCode = json_decode($admin->get_all_couponList(), true);

if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $couponCodeName = $_POST['couponCodeName'];
    $status = $_POST['couponCodeStatus'];
    $added_on = date('Y-m-d h:i:s');

    if ($action == 'add') {
        $result = $admin->add_couponCode($couponCodeName, $status, $added_on);
        $_SESSION['message'] = $result;
    } else if ($action == 'update') {
        $couponCodeId = $_POST['couponCodeId'];
        $result = $admin->update_couponCode($couponCodeId, $couponCodeName, $status, $added_on);
        $_SESSION['message'] = $result;
    }
    header("Location: delivery-boy.php");
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
                    <button class="btn bg-gradient-success btn-sm add-btn" type="button" data-toggle="modal"
                        data-target="#couponCode-modal">
                        <i class="fa-regular fa-plus mr-1"></i>Add
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="couponCode" class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 10px">S.No.</th>
                            <th>
                                <span class="couponCode-name-h">Name</span>
                            </th>
                            <th>
                                <span class="couponCode-type-h">Type</span>
                            </th>
                            <th>
                                <span class="couponCode-value-h">Value</span>
                            </th>
                            <th>
                                <span class="couponCode-cartValue-h">Cart Value</span>
                            </th>
                            <th>
                                <span class="couponCode-addDate-h">Add Date</span>
                            </th>
                            <th>
                                <span class="couponCode-exDate-h">Expire Date</span>
                            </th>
                            <th>
                                <span class="couponCode-status-h">Status</span>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($get_couponCode)): ?>
                        <?php foreach ($get_couponCode as $index => $couponCode): ?>
                        <tr>
                            <td class="text-center"><?php echo $index + 1; ?></td>
                            <td class="text-center">
                                <div class="coupon-outer" style="
                                    background: <?php echo $couponCode['bg-color']; ?>;
                                    border: 1px solid <?php echo $couponCode['bg-color']; ?>;
                                ">
                                    <span class="coupon-inner" style="
                                        color:  <?php echo $couponCode['txt-color']; ?>;
                                    ">
                                        <?php echo htmlspecialchars($couponCode['coupon_code']); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center"><?php echo htmlspecialchars($couponCode['coupon_type']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($couponCode['coupon_value']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($couponCode['cart_min_value']); ?></td>
                            <td class="text-center">
                                <?php
                                    $date = new DateTime($couponCode['added_on']);
                                    echo $date->format('d-m-Y');
                                ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    $date = new DateTime($couponCode['expired_on']);
                                    echo $date->format('d-m-Y');
                                ?>
                            </td>
                            <td class="text-center">
                                <span
                                    class="<?php echo $couponCode['status'] == 0 ? 'inactive-badge' : 'active-badge'; ?>">
                                    <?php echo $couponCode['status'] == 0 ? 'Inactive' : 'Active'; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn bg-gradient-success btn-xs mr-2 edit-btn" type="button"
                                    data-toggle="modal" data-target="#couponCode-modal"
                                    data-id="<?php echo $couponCode['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($couponCode['coupon_code']); ?>"
                                    data-status="<?php echo $couponCode['status']; ?>"
                                    data-bgcolor="<?php echo $couponCode['bg-color']; ?>"
                                    data-txtcolor="<?php echo $couponCode['txt-color']; ?>">
                                    <i class="fa-regular fa-pen-to-square mr-1"></i>Edit
                                </button>
                                <button class="btn bg-gradient-danger btn-xs delete-couponCode"
                                    data-id="<?php echo $couponCode['id']; ?>" type="button">
                                    <i class="fa-regular fa-trash mr-1"></i>Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No Coupon found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once ('./modals/couponCode-modal.php') ?>

<script type="text/javascript">
$(document).ready(function() {

    //Initialize Select2 Elements
    $('#couponCodeStatus').select2({
        theme: 'bootstrap4'
    });

    //Initialize color picker with addon
    $('.txtColorPicker').colorpicker({
        format: "rgba"
    })
    $('.txtColorPicker').on('colorpickerChange', function(event) {
        $('.txtColorPicker .fa-circle').css('color', event.color.toString());
    });

    $('.bgColorPicker').colorpicker({
        format: "rgba"
    })
    $('.bgColorPicker').on('colorpickerChange', function(event) {
        $('.bgColorPicker .fa-circle').css('color', event.color.toString());
    });

    tippy('.couponCode-value-h', {
        arrow: true,
        content: "Coupon cart value",
    });

    tippy('.couponCode-cartValue-h', {
        arrow: true,
        content: "Minimum cart value",
    });

    // Add button click event
    $('.add-btn').on('click', function() {
        $('#couponCode-modal .modal-title').text('Add Coupon Code');
        $('#couponCode-modal .btn-block').text('Submit');

        $('#submitAction').val('add');

        // Reset form fields
        $('#couponCodeId').val('');
        $('#couponCodeName').val('');
        $('#couponCodeStatus').val('').trigger('change');
        $('#couponCodeBgColor').val('rgba(255, 176, 29, 1)');
        $('#couponCodeTxtColor').val('rgba(184, 65, 0, 1)');

        // Reset color pickers and icons
        $('.bgColorPicker').colorpicker('setValue', 'rgba(255, 176, 29, 1)');
        $('.bgColorPicker .fa-circle').css('color', 'rgba(255, 176, 29, 1)');
        $('.txtColorPicker').colorpicker('setValue', 'rgba(184, 65, 0, 1)');
        $('.txtColorPicker .fa-circle').css('color', 'rgba(184, 65, 0, 1)');

        $('#couponCode-modal').modal('show');
    });

    // Edit button click event
    $('.edit-btn').on('click', function() {
        $('#couponCode-modal .modal-title').text('Edit Coupon Code');
        $('#couponCode-modal .btn-block').text('Update');

        var couponCodeId = $(this).data('id');
        var couponCodeName = $(this).data('name');
        var status = $(this).data('status');
        var bgColor = $(this).data('bgcolor');
        var txtColor = $(this).data('txtcolor');

        $('#submitAction').val('update');
        $('#couponCodeId').val(couponCodeId);
        $('#couponCodeName').val(couponCodeName);
        $('#couponCodeStatus').val(status).trigger('change');
        $('#couponCodeBgColor').val(bgColor);
        $('#couponCodeTxtColor').val(txtColor);

        // Update color pickers and icons
        $('.bgColorPicker').colorpicker('setValue', bgColor);
        $('.bgColorPicker .fa-circle').css('color', bgColor);
        $('.txtColorPicker').colorpicker('setValue', txtColor);
        $('.txtColorPicker .fa-circle').css('color', txtColor);

        $('#couponCode-modal').modal('show');
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
        if (message === "Coupon code already exists") {
            toastr.error(message);
        } else if (message === "Coupon code added successfully" || message ===
            "Coupon code updated successfully") {
            toastr.success(message);
        }
    }
});
</script>
<?php include_once ('footer.php') ?>