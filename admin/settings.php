<?php
include_once('header.php');

$msg = '';


if (!empty($admin)) {
    try {
        $getAdminDetails = json_decode($admin->getAdminDetails(), true);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

if (isset($_POST['submit'])) {
    $id = $_POST['id'] ?? null;
    $contactEmail = $_POST['contactEmail'];
    $contactNumber = $_POST['contactNumber'];
    $added_on = date('Y-m-d h:i:s');
    $name = $getAdminDetails['name'];
    $mobile = $getAdminDetails['mobile_no'];
    $username = $getAdminDetails['username'];
    $email = $getAdminDetails['email'];
    $password = $getAdminDetails['password'];
    $address = $getAdminDetails['address'];
    $area = $getAdminDetails['area'];
    $state = $getAdminDetails['state'];
    $district = $getAdminDetails['district'];
    $pincode = $getAdminDetails['pincode'];
    $city = $getAdminDetails['city'];
    $country = $getAdminDetails['country'];
    $admin_img = $getAdminDetails['admin_img'];
    try {
        $result = $admin->updateAdmin(
            $id,
            $name,
            $username,
            $email,
            $password,
            $address,
            $mobile,
            $added_on,
            $area,
            $city,
            $district,
            $pincode,
            $state,
            $country,
            $admin_img,
            $contactEmail,
            $contactNumber
        );
        $_SESSION['message'] = $result;
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
    }
    header("Location: settings.php");
    exit;
}

// Check for session message and clear it
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>
<div class="row">
    <div class="col-3">
        <div class="card">
            <div class="card-header p-0">
                <h5 class="contact-details-title">
                    Edit contact details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contactDetailsForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" id="adminId" name="id" value="<?php echo $getAdminDetails['id']; ?>">
                            <div class="form-group mb-3">
                                <label for="contactEmail">Contact Email</label>
                                <input type="email" class="form-control" id="contactEmail" name="contactEmail"
                                    placeholder="Enter contact email"
                                    value="<?php echo $getAdminDetails['contact_email']; ?>" required disabled>
                            </div>
                            <div class="form-group mb-4 tel-wrapper">
                                <label for="contactNumber">Contact number</label>
                                <input type="tel" class="form-control" id="contactNumber" name="contactNumber"
                                    placeholder="Enter mobile number" value="<?php echo $getAdminDetails['contact_phone']; ?>" required disabled>
                            </div>
                            <button type="button" id="editBtn" class="btn bg-gradient-secondary btn-block">Edit</button>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" name="submit" id="updateBtn" class="btn bg-gradient-success btn-block" style="display: none;">Update</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="cancelBtn" class="btn btn-outline-secondary btn-block" style="display: none;">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <div class="nav nav-pills" id="siteInfoTabs" role="tablist">
                    <a class="nav-item nav-link active mr-1" id="work-details-tab" data-toggle="pill" href="#work-details" role="tab" aria-controls="work-details" aria-selected="true">
                        Work Details
                    </a>
                    <a class="nav-item nav-link ml-1" id="seo-details-tab" data-toggle="pill" href="#seo-details" role="tab" aria-controls="seo-details" aria-selected="false">
                        SEO Details
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="siteInfoTabsContent">
                    <div class="tab-pane fade show active" id="work-details" role="tabpanel" aria-labelledby="work-details-tab">
                        Work
                    </div>
                    <div class="tab-pane fade" id="seo-details" role="tabpanel" aria-labelledby="seo-details-tab">
                        SEO
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        const originalPhoneNumber = '<?php echo $getAdminDetails['contact_phone']; ?>';

        const input = document.querySelector("#contactNumber");
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

        iti.setNumber(originalPhoneNumber);

        $('#editBtn').click(function() {
            $('#contactEmail, #contactNumber').prop('disabled', false);

            $('#updateBtn, #cancelBtn').show();
            $('#editBtn').hide();
        });

        $('#cancelBtn').click(function() {
            $('#contactEmail, #contactNumber').prop('disabled', true);

            $('#contactEmail').val('<?php echo $adminDetails['contact_email']; ?>');

            iti.setNumber(originalPhoneNumber);

            $('#editBtn').show();
            $('#updateBtn, #cancelBtn').hide();
        });

        $('#contactDetailsForm').submit(function(e) {
            const fullNumber = iti.getNumber();
            $('#contactNumber').val(fullNumber);
            return true;
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

    });
</script>
<?php include_once('footer.php') ?>