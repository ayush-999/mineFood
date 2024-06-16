<?php
include_once ('header.php');

$msg = '';
$get_users = json_decode($admin->get_all_users(), true);

if (isset($_POST['submitAction'])) {
    $action = $_POST['submitAction'];
    $userName = $_POST['userName'];
    $userMobile = $_POST['userMobile'];
    $userEmail = $_POST['userEmail'];
    $status = $_POST['userStatus'];
    $added_on = date('Y-m-d h:i:s');

    if ($action == 'add') {
        $result = $admin->add_user($userName, $userMobile, $userEmail, $status, $added_on);
        $_SESSION['message'] = $result;
    } else if ($action == 'update') {
        $userId = $_POST['userId'];
        $result = $admin->update_user($userId, $userName, $userMobile, $userEmail, $status, $added_on);
        $_SESSION['message'] = $result;
    }
    header("Location: user.php");
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
                        data-target="#user-modal">
                        <i class="fa-regular fa-plus mr-1"></i>Add
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="user" class="table table-bordered table-striped table-hover text-nowrap">
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
                        <?php if (!empty($get_users)): ?>
                        <?php foreach ($get_users as $index => $users): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($users['name']); ?></td>
                            <td><?php echo htmlspecialchars($users['mobile']); ?></td>
                            <td><?php echo htmlspecialchars($users['email']); ?></td>
                            <td class="text-center">
                                <span
                                    class="<?php echo $users['email_verify'] == 0 ? 'pending-badge' : 'verified-badge'; ?>">
                                    <?php echo $users['email_verify'] == 0 ? 'Pending' : 'Verified'; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="<?php echo $users['status'] == 0 ? 'inactive-badge' : ($users['status'] == 1 ? 'active-badge' : 'blocked-badge'); ?>">
                                    <?php 
                                        echo $users['status'] == 0 ? 'Inactive' : 
                                            ($users['status'] == 1 ? 'Active' : 'Blocked');
                                    ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php
                                    $date = new DateTime($users['added_on']);
                                    echo $date->format('d-m-Y');
                                ?>
                            </td>
                            <td class="text-center">
                                <button class="btn bg-gradient-success btn-xs mr-2 edit-btn" type="button"
                                    data-toggle="modal" data-target="#user-modal" data-id="<?php echo $users['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($users['name']); ?>"
                                    data-email="<?php echo htmlspecialchars($users['email']); ?>"
                                    data-mobile="<?php echo htmlspecialchars($users['mobile']); ?>"
                                    data-status="<?php echo $users['status']; ?>">
                                    <i class="fa-regular fa-pen-to-square mr-1"></i>Edit
                                </button>
                                <button class="btn bg-gradient-danger btn-xs delete-user"
                                    data-id="<?php echo $users['id']; ?>" type="button">
                                    <i class="fa-regular fa-trash mr-1"></i>Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No user found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once ('./modals/user-modal.php') ?>

<script type="text/javascript">
$(document).ready(function() {

    const input = document.querySelector("#userMobile");
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
    document.getElementById('userForm').addEventListener('submit', function(event) {
        const formattedNumber = iti.getNumber();
        input.value = formattedNumber; // Update the input with the formatted number
    });

    //Initialize Select2 Elements
    $('#userStatus').select2({
        theme: 'bootstrap4'
    });

    $('.add-btn').on('click', function() {
        $('#user-modal .modal-title').text('Add User');
        $('#user-modal .btn-block').text('Submit');
        $('#submitAction').val('add');
        $('#userId').val('');
        $('#userName').val('');
        $('#userMobile').val('');
        $('#userEmail').val('');
        $('#userStatus').val('').trigger('change');
        $('#user-modal').modal('show');
    });

    $('.edit-btn').on('click', function() {
        $('#user-modal .modal-title').text('Edit User');
        $('#user-modal .btn-block').text('Update');
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        var userMobile = $(this).data('mobile');
        var userEmail = $(this).data('email');
        var status = $(this).data('status');
        $('#submitAction').val('update');
        $('#userId').val(userId);
        $('#userName').val(userName);
        $('#userEmail').val(userEmail);
        $('#userStatus').val(status).trigger('change');
        iti.setNumber(userMobile);
        $('#user-modal').modal('show');
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
        if (message === "User with this mobile or email already exists") {
            toastr.error(message);
        } else if (message === "User added successfully" || message === "User updated successfully") {
            toastr.success(message);
        }
    }
});
</script>

<?php include_once ('footer.php') ?>