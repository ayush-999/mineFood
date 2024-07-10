<?php
include_once('header.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateAction'])) {
    $action = $_POST['updateAction'];

    $profileId = $_POST['id'];
    $profileName = $_POST['name'];
    $profileUsername = $_POST['username'];
    $profileEmail = $_POST['email'];
    $profilePassword = $_POST['password'];
    $profileAddress = $_POST['address'];
    $profileMobile = $_POST['mobile'];
    $added_on = date('Y-m-d h:i:s');
    // Image upload handling
    $imagePath = '';
    if (isset($_FILES['profileImg']) && $_FILES['profileImg']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/admin/profile-pic/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        // Remove old image
        if (!empty($adminDetails['admin_img']) && file_exists($uploadDir . $adminDetails['admin_img'])) {
            unlink($uploadDir . $adminDetails['admin_img']);
        }
        $imagePath = basename($_FILES['profileImg']['name']);
        $uploadFile = $uploadDir . $imagePath;
        if (!move_uploaded_file($_FILES['profileImg']['tmp_name'], $uploadFile)) {
            $_SESSION['message'] = json_encode(["message" => "Image upload failed"]);
            header("Location: profile.php");
            exit;
        }
    } else {
        if (!empty($admin)) {
            try {
                $adminDetails = json_decode($admin->getAdminDetails(), true);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
        $imagePath = $adminDetails['admin_img'];
    }
    try {
        if ($action == 'update') {
            $result = $admin->updateAdmin($profileId, $profileName, $profileUsername, $profileEmail, $profilePassword, $profileAddress, $profileMobile, $added_on, $imagePath);
            $_SESSION['message'] = $result;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
    }
    header("Location: profile.php");
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
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="profile-wrapper mb-2">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="<?php if (!empty($imagePath)) {
                                        echo $imagePath;
                                    } ?>" alt="Admin" class="mainProfileImg">
                                    <div class="mt-3">
                                        <h5><?php if (!empty($adminDetails)) {
                                                echo $adminDetails['name'];
                                            } ?></h5>
                                        <p class="text-muted font-size-sm"><?php echo $adminDetails['address']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-7">
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control"
                                               value="<?php echo $adminDetails['name']; ?>"
                                               disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="email" class="form-control"
                                               value="<?php echo $adminDetails['email']; ?>" disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Username</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" class="form-control"
                                               value="<?php echo $adminDetails['username']; ?>" disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Password</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="password" class="form-control"
                                               value="<?php echo $adminDetails['password']; ?>" disabled>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Mobile</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <span><?php echo $adminDetails['mobile_no']; ?></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <span><?php echo $adminDetails['address']; ?></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-sm-6">
                                        <button class="btn bg-gradient-success btn-block edit-btn" type="button"
                                                data-toggle="modal" data-target="#profile-modal"
                                                data-id="<?php echo $adminDetails['id']; ?>"
                                                data-name="<?php echo $adminDetails['name']; ?>"
                                                data-username="<?php echo $adminDetails['username']; ?>"
                                                data-email="<?php echo $adminDetails['email']; ?>"
                                                data-mobile="<?php echo $adminDetails['mobile_no']; ?>"
                                                data-password="<?php echo $adminDetails['password']; ?>"
                                                data-address="<?php echo $adminDetails['address']; ?>">
                                            <i class="fa-regular fa-pen-to-square fs-14 mr-1"></i>Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once('./modals/profile-modal.php') ?>

    <script type="text/javascript">
        $(document).ready(function () {
            const input = document.querySelector("#mobile");
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
            document.getElementById('profileForm').addEventListener('submit', function (event) {
                const formattedNumber = iti.getNumber();
                input.value = formattedNumber; // Update the input with the formatted number
            });

            $('.edit-btn').on('click', function () {
                let profileId = $(this).data('id');
                let name = $(this).data('name');
                let username = $(this).data('username');
                let email = $(this).data('email');
                let mobile = $(this).data('mobile');
                let password = $(this).data('password');
                let address = $(this).data('address');

                $('#updateAction').val('update');
                $('#profileId').val(profileId);
                $('#fullName').val(name);
                $('#username').val(username);
                $('#email').val(email);
                $('#password').val(password);
                $('#address').val(address);
                // Set the phone number
                iti.setNumber(mobile);

                $('#profile-modal').modal('show');
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

            let message = '<?php echo addslashes($msg); ?>';
            if (message) {
                message = JSON.parse(message);
                if (message.hasOwnProperty("message")) {
                    if (message.message === "Profile updated successfully") {
                        toastr.success(message.message);
                    } else {
                        toastr.error(message.message);
                    }
                }
            }

            $('#profileImg').imoViewer({
                'preview': '#image-preview',
            })
        });
    </script>


<?php include_once('footer.php') ?>