<?php
include_once('header.php');

$msg = '';
if (!empty($admin)) {
    try {
        $get_couponCode = json_decode((string) $admin->get_all_couponList(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $couponCodeName = $_POST['couponCodeName'];
    $status = $_POST['couponCodeStatus'];
    $couponCodeBgColor = $_POST['couponCodeBgColor'];
    $couponCodeTxtColor = $_POST['couponCodeTxtColor'];
    $couponCodeType = $_POST['couponCodeType'];
    $couponCodeStartDate = $_POST['couponCodeStartDate'];
    $couponCodeEndDate = $_POST['couponCodeEndDate'];
    $couponCodeCartValue = $_POST['couponCodeCartValue'];
    $couponCodeMinCartValue = $_POST['couponCodeMinCartValue'];
    $added_on = date('Y-m-d h:i:s');
    try {
        if ($action == 'add') {
            $result = $admin->add_couponCode(
                strtoupper((string) $couponCodeName),
                $status,
                $couponCodeBgColor,
                $couponCodeTxtColor,
                $couponCodeType,
                $couponCodeStartDate,
                $couponCodeEndDate,
                $couponCodeCartValue,
                $couponCodeMinCartValue,
                $added_on
            );
            $_SESSION['message'] = $result;
        } else if ($action == 'update') {
            $couponCodeId = $_POST['couponCodeId'];
            $result = $admin->update_couponCode(
                $couponCodeId,
                strtoupper((string) $couponCodeName),
                $status,
                $couponCodeBgColor,
                $couponCodeTxtColor,
                $couponCodeType,
                $couponCodeStartDate,
                $couponCodeEndDate,
                $couponCodeCartValue,
                $couponCodeMinCartValue
            );
            $_SESSION['message'] = $result;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
    }
    header("Location: coupon-code.php");
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
                                data-toggle="modal" data-target="#couponCode-modal">
                            <i class="fa-regular fa-plus"></i>
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
                                Code
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Cart Value
                            </th>
                            <th>
                                Minimum Cart
                            </th>
                            <th>
                                Start Date
                            </th>
                            <th>
                                Expire Date
                            </th>
                            <th>
                                Status
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
                                                background: <?php echo $couponCode['bg_color']; ?>;
                                                border: 1px solid <?php echo $couponCode['bg_color']; ?>;
                                                ">
                                    <span class="coupon-inner" style="
                                            color:  <?php echo $couponCode['txt_color']; ?>;
                                            ">
                                        <?php echo htmlspecialchars((string) $couponCode['coupon_name']); ?>
                                    </span>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo htmlspecialchars((string) $couponCode['coupon_type']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars((string) $couponCode['coupon_value']); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars((string) $couponCode['cart_min_value']); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date = new DateTime($couponCode['started_on']);
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
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                                type="button" data-toggle="modal" data-target="#couponCode-modal"
                                                data-id="<?php echo $couponCode['id']; ?>"
                                                data-name="<?php echo htmlspecialchars((string) $couponCode['coupon_name']); ?>"
                                                data-type="<?php echo $couponCode['coupon_type']; ?>"
                                                data-cart="<?php echo $couponCode['coupon_value']; ?>"
                                                data-min="<?php echo $couponCode['cart_min_value']; ?>"
                                                data-start="<?php echo htmlspecialchars((string) $couponCode['started_on']); ?>"
                                                data-end="<?php echo htmlspecialchars((string) $couponCode['expired_on']); ?>"
                                                data-status="<?php echo $couponCode['status']; ?>"
                                                data-bgcolor="<?php echo $couponCode['bg_color']; ?>"
                                                data-txtcolor="<?php echo $couponCode['txt_color']; ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-couponCode"
                                                data-id="<?php echo $couponCode['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align:center;">No Coupon found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include_once('./modals/couponCode-modal.php') ?>

    <script type="text/javascript">
        $(document).ready(function () {

            //Initialize Select2 Elements
            $('#couponCodeStatus').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: -1
            });
            $('#couponCodeType').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: -1
            });

            //Initialize color picker with addon
            $('.txtColorPicker').colorpicker({
                format: "rgba"
            })
            $('.txtColorPicker').on('colorpickerChange', function (event) {
                $('.txtColorPicker .fa-circle').css('color', event.color.toString());
            });

            $('.bgColorPicker').colorpicker({
                format: "rgba"
            })
            $('.bgColorPicker').on('colorpickerChange', function (event) {
                $('.bgColorPicker .fa-circle').css('color', event.color.toString());
            });

            // Add button click event
            $('.add-btn').on('click', function () {
                // Set today's date and tomorrow's date
                let today = new Date().toISOString().split('T')[0];
                let tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                let tomorrowDate = tomorrow.toISOString().split('T')[0];

                $('#couponCode-modal .modal-title').text('Add Coupon Code');
                $('#couponCode-modal .btn-block').text('Submit');
                $('#submitAction').val('add');
                // Reset form fields
                $('#couponCodeId').val('');
                $('#couponCodeName').val('');
                $('#couponCodeType').val('').trigger('change');
                $('#couponCodeMinCartValue').val('');
                $('#couponCodeStartDate').val(today);
                $('#couponCodeEndDate').val(tomorrowDate);
                $('#couponCodeStatus').val('').trigger('change');
                $('#couponCodeBgColor').val('rgba(255, 176, 29, 1)');
                $('#couponCodeTxtColor').val('rgba(184, 65, 0, 1)');
                $('#couponCodeCartValue').val('');
                // Reset color pickers and icons
                $('.bgColorPicker').colorpicker('setValue', 'rgba(255, 176, 29, 1)');
                $('.bgColorPicker .fa-circle').css('color', 'rgba(255, 176, 29, 1)');
                $('.txtColorPicker').colorpicker('setValue', 'rgba(184, 65, 0, 1)');
                $('.txtColorPicker .fa-circle').css('color', 'rgba(184, 65, 0, 1)');

                $('#couponCode-modal').modal('show');
            });

            // Edit button click event
            $('.edit-btn').on('click', function () {
                $('#couponCode-modal .modal-title').text('Edit Coupon Code');
                $('#couponCode-modal .btn-block').text('Update');

                var couponCodeId = $(this).data('id');
                var couponCodeName = $(this).data('name');
                var couponCodeType = $(this).data('type');
                var couponCodeCartValue = $(this).data('cart');
                var couponCodeMinCartValue = $(this).data('min');
                var startDate = $(this).data('start');
                var endDate = $(this).data('end');
                var status = $(this).data('status');
                var bgColor = $(this).data('bgcolor');
                var txtColor = $(this).data('txtcolor');

                $('#submitAction').val('update');

                $('#couponCodeId').val(couponCodeId);
                $('#couponCodeName').val(couponCodeName);
                $('#couponCodeType').val(couponCodeType).trigger('change');
                $('#couponCodeCartValue').val(couponCodeCartValue);
                $('#couponCodeMinCartValue').val(couponCodeMinCartValue);
                $('#couponCodeStartDate').val(startDate);
                $('#couponCodeEndDate').val(endDate);
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
                if (message === "Coupon already exists") {
                    toastr.error(message);
                } else if (message === "Coupon added successfully" || message ===
                    "Coupon updated successfully") {
                    toastr.success(message);
                }
            }
        });
    </script>
<?php include_once('footer.php') ?>