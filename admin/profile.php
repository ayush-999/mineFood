<?php
include_once('header.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateAction'])) {
        $action = $_POST['updateAction'];
        $profileId = $_POST['id'];
        $profileName = $_POST['name'];
        $profileMobile = $_POST['mobile'];
        $added_on = date('Y-m-d h:i:s');

        try {
            $adminDetails = json_decode($admin->getAdminDetails(), true);
            $profileUsername = $adminDetails['username'];
            $profileEmail = $adminDetails['email'];
            $profilePassword = $adminDetails['password'];
            $profileAddress = $adminDetails['address'];
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['message'] = json_encode(["message" => "Error fetching admin details"]);
            header("Location: profile.php");
            exit;
        }

        $imagePath = '';
        if (isset($_FILES['profileImg']) && $_FILES['profileImg']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/admin/profile-pic/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
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
                $result = $admin->updateAdmin(
                    $profileId,
                    $profileName,
                    $profileUsername,
                    $profileEmail,
                    $profilePassword,
                    $profileAddress,
                    $profileMobile,
                    $added_on,
                    $adminDetails['area'],
                    $adminDetails['city'],
                    $adminDetails['district'],
                    $adminDetails['pincode'],
                    $adminDetails['state'],
                    $adminDetails['country'],
                    $imagePath
                );
                $_SESSION['message'] = $result;
            }
        } catch (Exception $e) {
            $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
        }
        header("Location: profile.php");
        exit;
    } elseif (isset($_POST['changePassword'])) {
        $adminId = $_POST['adminId'];
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        try {
            $adminDetails = json_decode($admin->getAdminDetails(), true);

            if (!$admin->verifyPassword($oldPassword, $adminDetails['password'])) {
                $_SESSION['message'] = json_encode(["message" => "Old password is incorrect", "password_changed" => false]);
                header("Location: profile.php");
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['message'] = json_encode(["message" => "New password and confirm password don't match", "password_changed" => false]);
                header("Location: profile.php");
                exit;
            }

            $hashedPassword = $admin->hashPassword($newPassword);

            $result = $admin->updateAdmin(
                $adminDetails['id'],
                $adminDetails['name'],
                $adminDetails['username'],
                $adminDetails['email'],
                $hashedPassword,
                $adminDetails['address'],
                $adminDetails['mobile_no'],
                date('Y-m-d h:i:s'),
                $adminDetails['area'],
                $adminDetails['city'],
                $adminDetails['district'],
                $adminDetails['pincode'],
                $adminDetails['state'],
                $adminDetails['country'],
                $adminDetails['admin_img']
            );

            $_SESSION['message'] = $result;
        } catch (Exception $e) {
            $_SESSION['message'] = json_encode(["message" => $e->getMessage(), "password_changed" => false]);
        }
        header("Location: profile.php");
        exit;
    } elseif (isset($_POST['updateAddress'])) {
        // Handle address update
        $profileId = $_POST['id'];
        $added_on = date('Y-m-d h:i:s');

        // Get address components
        $area = $_POST['area'] ?? '';
        $city = $_POST['city'] ?? '';
        $district = $_POST['district'] ?? '';
        $pincode = $_POST['pincode'] ?? '';
        $state = $_POST['state'] ?? '';
        $country = $_POST['country'] ?? '';

        // Combine address components with comma separation
        $addressParts = array_filter([$area, $city, $district, $pincode, $state, $country]);
        $profileAddress = implode(', ', $addressParts);

        try {
            $adminDetails = json_decode($admin->getAdminDetails(), true);
            $profileName = $adminDetails['name'];
            $profileMobile = $adminDetails['mobile_no'];
            $profileUsername = $adminDetails['username'];
            $profileEmail = $adminDetails['email'];
            $profilePassword = $adminDetails['password'];

            $result = $admin->updateAdmin(
                $profileId,
                $profileName,
                $profileUsername,
                $profileEmail,
                $profilePassword,
                $profileAddress,
                $profileMobile,
                $added_on,
                $area,
                $city,
                $district,
                $pincode,
                $state,
                $country,
                $adminDetails['admin_img']
            );

            $_SESSION['message'] = $result;
        } catch (Exception $e) {
            $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
        }
        header("Location: profile.php");
        exit;
    }
}

// Check for session message and clear it
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
                </div>
            </div>
            <div class="card-body">
                <div class="profile-wrapper mb-2">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="<?php if (!empty($imagePath)) {
                                                echo $imagePath;
                                            } ?>" alt="Admin" class="mainProfileImg">
                                <div class="mt-3">
                                    <h5><?php if (!empty($adminDetails)) {
                                            echo $adminDetails['name'];
                                        } ?></h5>
                                    <p class="text-muted font-size-sm text-left mb-0"><?php echo $adminDetails['address']; ?></p>
                                </div>
                            </div>
                            <button class="btn bg-gradient-success btn-block edit-address-btn mt-4" type="button"
                                data-toggle="modal" data-target="#address-modal"
                                data-id="<?php echo $adminDetails['id']; ?>"
                                data-area="<?php echo $adminDetails['area']; ?>"
                                data-state="<?php echo $adminDetails['state']; ?>"
                                data-district="<?php echo $adminDetails['district']; ?>"
                                data-pincode="<?php echo $adminDetails['pincode']; ?>"
                                data-city="<?php echo $adminDetails['city']; ?>"
                                data-country="<?php echo $adminDetails['country']; ?>">
                                Edit address
                            </button>
                        </div>
                        <div class="col-md-7">
                            <div class="nav nav-pills nav-fill mb-3" id="profileInfoTabs" role="tablist">
                                <a class="nav-item nav-link active mr-1" id="profile-details-tab" data-toggle="pill" href="#profile-details" role="tab" aria-controls="profile-details" aria-selected="true">Profile Details</a>
                                <a class="nav-item nav-link ml-1" id="change-password-tab" data-toggle="pill" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">Change Password</a>
                            </div>
                            <div class="tab-content" id="profileInfoTabsContent">
                                <div class="tab-pane fade show active" id="profile-details" role="tabpanel" aria-labelledby="profile-details-tab">
                                    <div class="card shadow-none border">
                                        <div class="card-body">
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
                                                    <h6 class="mb-0">Mobile</h6>
                                                </div>
                                                <div class="col-sm-9 text-secondary">
                                                    <span><?php echo $adminDetails['mobile_no']; ?></span>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row align-items-center">
                                                <div class="col-sm-12">
                                                    <button class="btn bg-gradient-success btn-block edit-btn" type="button"
                                                        data-toggle="modal" data-target="#profile-modal"
                                                        data-id="<?php echo $adminDetails['id']; ?>"
                                                        data-name="<?php echo $adminDetails['name']; ?>"
                                                        data-username="<?php echo $adminDetails['username']; ?>"
                                                        data-email="<?php echo $adminDetails['email']; ?>"
                                                        data-mobile="<?php echo $adminDetails['mobile_no']; ?>">
                                                        Edit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                                    <div class="card shadow-none border">
                                        <div class="card-body">
                                            <form id="changePasswordForm" method="POST" action="">
                                                <input type="hidden" name="changePassword" value="1">
                                                <input type="hidden" name="adminId" value="<?php echo $adminDetails['id']; ?>">

                                                <div class="row align-items-center">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Old Password</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
                                                            <div class="input-group-append">
                                                                <button class="input-group-text toggle-password" type="button" data-target="#oldPassword">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row align-items-center">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">New Password</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                                            <div class="input-group-append">
                                                                <button class="input-group-text toggle-password" type="button" data-target="#newPassword">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row align-items-center">
                                                    <div class="col-sm-4">
                                                        <h6 class="mb-0">Confirm Password</h6>
                                                    </div>
                                                    <div class="col-sm-8 text-secondary">
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                                            <div class="input-group-append">
                                                                <button class="input-group-text toggle-password" type="button" data-target="#confirmPassword">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row align-items-center">
                                                    <div class="col-sm-12">
                                                        <button type="submit" class="btn bg-gradient-success btn-block">
                                                            Change Password
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
<?php include_once('./modals/address-modal.php') ?>

<script type="text/javascript">
    $(document).ready(function() {
        window.customAddressDropdowns = new AddressDropdowns({
            stateSelector: '#address_state',
            districtSelector: '#address_district',
            pincodeSelector: '#address_pincode',
            citySelector: '#address_city',
            countrySelector: '#address_country',
            jsonUrl: 'assets/js/address/pincode.json'
        });

        const passwordTippy = tippy('#newPassword', {
            content: generatePasswordTooltipContent(false, false, false, false, false),
            allowHTML: true,
            interactive: true,
            trigger: 'manual',
            placement: 'right',
            theme: 'light',
            arrow: true,
            maxWidth: 300,
        })[0];

        $('#newPassword').on('keyup focus', function() {
            var password = $(this).val();
            var strength = checkPasswordStrength(password);
            updatePasswordTippy(strength, passwordTippy);
        });

        $('#newPassword').on('blur', function() {
            if ($(this).val() === '') {
                passwordTippy.hide();
            }
        });

        function checkPasswordStrength(password) {
            return {
                length: password.length >= 8 && password.length <= 15,
                capital: /[A-Z]/.test(password),
                small: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };
        }

        function generatePasswordTooltipContent(length, capital, small, number, special) {
            return `
                    <ul class="pswd_info">
                        <li class="${length ? 'valid' : 'invalid'}">8-15 <strong>Characters</strong></li>
                        <li class="${capital ? 'valid' : 'invalid'}">At least <strong>one capital letter</strong></li>
                        <li class="${small ? 'valid' : 'invalid'}">At least <strong>one small letter</strong></li>
                        <li class="${number ? 'valid' : 'invalid'}">At least <strong>one number</strong></li>
                        <li class="${special ? 'valid' : 'invalid'}">At least <strong>one Special Character</strong></li>
                    </ul>
                `;
        }

        function updatePasswordTippy(strength, tippyInstance) {
            const content = generatePasswordTooltipContent(
                strength.length,
                strength.capital,
                strength.small,
                strength.number,
                strength.special
            );

            tippyInstance.setProps({
                content: content
            });

            if ($('#newPassword').val().length > 0) {
                tippyInstance.show();
            } else {
                tippyInstance.hide();
            }
        }

        const confirmPasswordTippy = tippy('#confirmPassword', {
            content: generateConfirmPasswordTooltipContent(false),
            allowHTML: true,
            interactive: true,
            trigger: 'manual',
            placement: 'right',
            theme: 'light',
            arrow: true,
            maxWidth: 300,
        })[0];

        $('#confirmPassword').on('keyup focus', function() {
            var newPassword = $('#newPassword').val();
            var confirmPassword = $(this).val();
            var isMatching = newPassword === confirmPassword && newPassword !== '';
            updateConfirmPasswordTippy(isMatching, confirmPasswordTippy);
        });

        $('#confirmPassword').on('blur', function() {
            if ($(this).val() === '') {
                confirmPasswordTippy.hide();
            }
        });

        $('#newPassword').on('keyup', function() {
            var newPassword = $(this).val();
            var confirmPassword = $('#confirmPassword').val();
            if (confirmPassword !== '') {
                var isMatching = newPassword === confirmPassword;
                updateConfirmPasswordTippy(isMatching, confirmPasswordTippy);
            }
        });

        function generateConfirmPasswordTooltipContent(isMatching) {
            return `
                <ul class="pswd_info">
                    <li class="${isMatching ? 'valid' : 'invalid'}">Passwords must <strong>match</strong></li>
                </ul>
            `;
        }

        function updateConfirmPasswordTippy(isMatching, tippyInstance) {
            const content = generateConfirmPasswordTooltipContent(isMatching);

            tippyInstance.setProps({
                content: content
            });

            if ($('#confirmPassword').val().length > 0) {
                tippyInstance.show();
            } else {
                tippyInstance.hide();
            }
        }

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

        $('#newPassword').keyup(function(event) {
            var password = $('#newPassword').val();
            checkPasswordStrength(password);
        });

        document.getElementById('profileForm').addEventListener('submit', function(event) {
            const formattedNumber = iti.getNumber();
            input.value = formattedNumber;
        });

        $('.edit-btn').on('click', function() {
            let profileId = $(this).data('id');
            let name = $(this).data('name');
            let username = $(this).data('username');
            let email = $(this).data('email');
            let mobile = $(this).data('mobile');
            $('#updateAction').val('update');
            $('#profileId').val(profileId);
            $('#fullName').val(name);
            $('#username').val(username);
            $('#email').val(email);
            iti.setNumber(mobile);

            $('#profile-modal').modal('show');
        });

        $('.edit-address-btn').on('click', function() {
            let profileId = $(this).data('id');
            let area = $(this).data('area');
            let state = $(this).data('state');
            let district = $(this).data('district');
            let pincode = $(this).data('pincode');
            let city = $(this).data('city');
            let country = $(this).data('country');

            $('#address_updateAction').val('update');
            $('#address_profileId').val(profileId);
            $('#address_country').val(country).trigger('change');
            $('#address_area').val(area);
            $('#address_state').val(state).trigger('change');
            $('#address_district').val(district).trigger('change');
            $('#address_pincode').val(pincode).trigger('change');
            $('#address_city').val(city).trigger('change');

            $('#address-modal').modal('show');
        });

        $('.toggle-password').click(function() {
            const target = $(this).data('target');
            const input = $(target);
            const icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
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
                if (message.message === "Profile updated successfully" || message.message === "Password updated successfully") {
                    toastr.success(message.message);
                } else {
                    toastr.error(message.message);
                }
            }
        }

        $('#profileImg').imoViewer({
            'preview': '#image-preview',
        })

        $('#address_state, #address_district, #address_pincode, #address_city, #address_country').select2({
            theme: 'bootstrap4'
        });
    });
</script>

<?php include_once('footer.php') ?>