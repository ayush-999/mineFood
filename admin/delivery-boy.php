<?php
include_once('header.php');

$msg = '';
if (!empty($admin)) {
    try {
        $get_delivery_boy = json_decode($admin->get_all_delivery_boy(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $deliveryBoyName = $_POST['deliveryBoyName'];
    $deliveryBoyMobile = $_POST['deliveryBoyMobile'];
    $deliveryBoyEmail = $_POST['deliveryBoyEmail'];
    $status = $_POST['deliveryBoyStatus'];
    $added_on = date('Y-m-d h:i:s');
    try {
        if ($action == 'add') {
            $result = $admin->add_deliveryBoy($deliveryBoyName, $deliveryBoyMobile, $deliveryBoyEmail, $status, $added_on);
            $_SESSION['message'] = $result;
        } else if ($action == 'update') {
            $deliveryBoyId = $_POST['deliveryBoyId'];
            $result = $admin->update_deliveryBoy($deliveryBoyId, $deliveryBoyName, $deliveryBoyMobile, $deliveryBoyEmail, $status, $added_on);
            $_SESSION['message'] = $result;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
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
                            <b>
                                <?php
                                    if (!empty($pageSubTitle)) {
                                        echo $pageSubTitle;
                                    }
                                ?>
                            </b>
                        </h5>
                        <button class="btn bg-gradient-success btn-sm rounded-circle add-btn" type="button"
                                data-toggle="modal" data-target="#delivery-boy-modal">
                            <i class="fa-regular fa-plus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="deliveryBoy" class="table table-bordered table-striped table-hover text-nowrap">
                        <thead>
                        <tr>
                            <th style="width: 10px">S.No.</th>
                            <th>Full Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Verified</th>
                            <th>Status</th>
                            <th>Register Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($get_delivery_boy)): ?>
                            <?php foreach ($get_delivery_boy as $index => $delivery_boy): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($delivery_boy['name']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery_boy['mobile']); ?></td>
                                    <td><?php echo htmlspecialchars($delivery_boy['email']); ?></td>
                                    <td class="text-center">
                                <span
                                        class="<?php echo $delivery_boy['email_verify'] == 0 ? 'pending-badge' : 'verified-badge'; ?>">
                                    <?php echo $delivery_boy['email_verify'] == 0 ? 'Pending' : 'Verified'; ?>
                                </span>
                                    </td>
                                    <td class="text-center">
                                <span
                                        class="<?php echo $delivery_boy['status'] == 0 ? 'inactive-badge' : ($delivery_boy['status'] == 1 ? 'active-badge' : 'blocked-badge'); ?>">
                                    <?php
                                    echo $delivery_boy['status'] == 0 ? 'Inactive' :
                                        ($delivery_boy['status'] == 1 ? 'Active' : 'Blocked');
                                    ?>
                                </span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $date = new DateTime($delivery_boy['added_on']);
                                        echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn bg-gradient-success btn-sm rounded-circle mr-1 edit-btn"
                                                type="button" data-toggle="modal" data-target="#delivery-boy-modal"
                                                data-id="<?php echo $delivery_boy['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($delivery_boy['name']); ?>"
                                                data-email="<?php echo htmlspecialchars($delivery_boy['email']); ?>"
                                                data-mobile="<?php echo htmlspecialchars($delivery_boy['mobile']); ?>"
                                                data-status="<?php echo $delivery_boy['status']; ?>">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn bg-gradient-danger btn-sm rounded-circle delete-deliveryBoy"
                                                data-id="<?php echo $delivery_boy['id']; ?>" type="button">
                                            <i class="fa-regular fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align:center;">No Delivery Boy found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include_once('./modals/delivery-boy-modal.php') ?>

    <script type="text/javascript">
        $(document).ready(function () {

            const input = document.querySelector("#deliveryBoyMobile");
            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                separateDialCode: true,
            });

            // Handle form submission
            document.getElementById('deliveryBoyForm').addEventListener('submit', function (event) {
                const formattedNumber = iti.getNumber();
                input.value = formattedNumber; // Update the input with the formatted number
            });

            //Initialize Select2 Elements
            $('#deliveryBoyStatus').select2({
                theme: 'bootstrap4'
            });

            $('.add-btn').on('click', function () {
                $('#delivery-boy-modal .modal-title').text('Add Delivery Boy');
                $('#delivery-boy-modal .btn-block').text('Submit');
                $('#submitAction').val('add');
                $('#deliveryBoyId').val('');
                $('#deliveryBoyName').val('');
                $('#deliveryBoyMobile').val('');
                $('#deliveryBoyEmail').val('');
                $('#deliveryBoyStatus').val('').trigger('change');
                $('#delivery-boy-modal').modal('show');
            });

            $('.edit-btn').on('click', function () {
                $('#delivery-boy-modal .modal-title').text('Edit Delivery Boy');
                $('#delivery-boy-modal .btn-block').text('Update');
                let deliveryBoyId = $(this).data('id');
                let deliveryBoyName = $(this).data('name');
                let deliveryBoyMobile = $(this).data('mobile');
                let deliveryBoyEmail = $(this).data('email');
                let status = $(this).data('status');
                $('#submitAction').val('update');
                $('#deliveryBoyId').val(deliveryBoyId);
                $('#deliveryBoyName').val(deliveryBoyName);
                $('#deliveryBoyEmail').val(deliveryBoyEmail);
                $('#deliveryBoyStatus').val(status).trigger('change');
                iti.setNumber(deliveryBoyMobile);
                $('#delivery-boy-modal').modal('show');
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
                if (message === "Delivery boy with this mobile or email already exists") {
                    toastr.error(message);
                } else if (message === "Delivery boy added successfully" || message ===
                    "Delivery boy updated successfully") {
                    toastr.success(message);
                }
            }
        });
    </script>

<?php include_once('footer.php') ?>