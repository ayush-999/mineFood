<?php
include_once('header.php');

$msg = '';

try {
    $getAdminDetails = json_decode((string) $admin->getAdminDetails(), true);
    $getSettingDetails = json_decode((string) $admin->getSettingDetails(), true);
} catch (Exception $e) {
    error_log($e->getMessage());
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

    // Collect opening hours data
    $openingHours = [
        'opening' => $_POST['opening_time'] ?? '09:00',
        'closing' => $_POST['closing_time'] ?? '17:00'
    ];

    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    foreach ($days as $day) {
        if (isset($_POST[$day])) {
            $openingHours[$day] = [
                'status' => $_POST[$day]['status'] ?? '0'
            ];
        }
    }
    $openingHoursJson = json_encode($openingHours);

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
            $contactNumber,
            $openingHoursJson
        );
        $_SESSION['message'] = $result;
    } catch (Exception $e) {
        $_SESSION['message'] = json_encode(["message" => $e->getMessage()]);
    }
    header("Location: settings.php");
    exit;
}

// Add this with your other initialization code
$pageSettings = [];
try {
    $getAdminDetails = json_decode((string) $admin->getAdminDetails(), true);
    $getSettingDetails = json_decode((string) $admin->getSettingDetails(), true);

    // Initialize pageSettings - you'll need to implement a method to get these
    $pageSettings = json_decode((string) $admin->getPageSettings(), true) ?: [];
} catch (Exception $e) {
    error_log($e->getMessage());
}

// Check for session message and clear it
if (isset($_SESSION['message'])) {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-header p-0">
                <h5 class="contact-details-title">
                    Edit contact details
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contactDetailsForm" method="POST" action="<?php echo htmlspecialchars((string) $_SERVER["PHP_SELF"]); ?>">
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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <b>Edit opening and closing times</b>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="openingHoursForm" method="POST" action="<?php echo htmlspecialchars((string) $_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="id" value="<?php echo $getAdminDetails['id']; ?>">
                            <input type="hidden" name="contactEmail" value="<?php echo $getAdminDetails['contact_email']; ?>">
                            <input type="hidden" name="contactNumber" value="<?php echo $getAdminDetails['contact_phone']; ?>">
                            <?php
                            $openingHours = [];
                            if (!empty($getAdminDetails['opening_hours'])) {
                                $openingHours = json_decode($getAdminDetails['opening_hours'], true);
                            }

                            $openingTime = $openingHours['opening'] ?? '09:00';
                            $closingTime = $openingHours['closing'] ?? '17:00';
                            ?>

                            <!-- Common time fields -->
                            <div class="form-group mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Open</span>
                                            </div>
                                            <input type="time" class="form-control" id="commonOpeningTime"
                                                name="opening_time" value="<?php echo $openingTime; ?>"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Close</span>
                                            </div>
                                            <input type="time" class="form-control" id="commonClosingTime"
                                                name="closing_time" value="<?php echo $closingTime; ?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $days = [
                                'Sun' => 'Sunday',
                                'Mon' => 'Monday',
                                'Tue' => 'Tuesday',
                                'Wed' => 'Wednesday',
                                'Thu' => 'Thursday',
                                'Fri' => 'Friday',
                                'Sat' => 'Saturday'
                            ];

                            foreach ($days as $short => $long):
                                $dayStatus = $openingHours[$short]['status'] ?? '0';
                            ?>
                                <div class="form-group mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class=""><?php echo $long; ?></label>
                                        <label class="switch">
                                            <input type="checkbox" class="day-status" name="<?php echo $short; ?>[status]"
                                                value="1" <?php echo $dayStatus == '1' ? 'checked' : ''; ?>>
                                            <span class="slider round"></span>
                                        </label>
                                        <input type="hidden" name="<?php echo $short; ?>[status]"
                                            value="<?php echo $dayStatus; ?>" class="status-value">
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <button type="button" id="editHoursBtn" class="btn bg-gradient-secondary btn-block">Edit</button>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" name="submit" id="updateHoursBtn" class="btn bg-gradient-success btn-block" style="display: none;">Save</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="cancelHoursBtn" class="btn btn-outline-secondary btn-block" style="display: none;">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="nav nav-pills" id="siteInfoTabs" role="tablist">
                    <a class="nav-item nav-link active mr-1" id="seo-details-tab" data-toggle="pill" href="#seo-details" role="tab" aria-controls="seo-details" aria-selected="true">
                        SEO Details
                    </a>
                    <a class="nav-item nav-link ml-1" id="social-media-tab" data-toggle="pill" href="#social-media" role="tab" aria-controls="social-media" aria-selected="false">
                        Social Media
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="siteInfoTabsContent">
                    <div class="tab-pane fade show active" id="seo-details" role="tabpanel" aria-labelledby="seo-details-tab">
                        <?php include_once('tab-content/settings/seo-details.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="social-media" role="tabpanel" aria-labelledby="social-media-tab">
                        <?php include_once('tab-content/settings/social-media.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('modals/addPage-modal.php'); ?>
<?php include_once('modals/addSocialMedia-modal.php'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        // ========================= Common JS code start here ========================= //
        $('#seoPageSelect').select2({
            theme: 'bootstrap4'
        });
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

        // Toggle switch change handler
        $('.day-status').change(function() {
            const dayRow = $(this).closest('.form-group');
            const isChecked = $(this).is(':checked');
            const statusValue = dayRow.find('.status-value');

            // Update hidden input value
            statusValue.val(isChecked ? '1' : '0');

            // Enable/disable time inputs
            dayRow.find('.opening-time, .closing-time').prop('disabled', !isChecked);
        });

        // Initially disable all elements
        $('.day-status, .opening-time, .closing-time').prop('disabled', true);


        // Edit button click handler
        $('#editHoursBtn').click(function() {
            // Enable the toggle switches and time inputs
            $('.day-status, #commonOpeningTime, #commonClosingTime').prop('disabled', false);

            // Show update and cancel buttons, hide edit button
            $('#updateHoursBtn, #cancelHoursBtn').show();
            $(this).hide();
        });

        // Cancel button click handler
        $('#cancelHoursBtn').click(function() {
            // Disable all elements
            $('.day-status, #commonOpeningTime, #commonClosingTime').prop('disabled', true);

            // Show edit button, hide update and cancel buttons
            $('#editHoursBtn').show();
            $('#updateHoursBtn, #cancelHoursBtn').hide();
        });

        // Toggle switch change handler
        $('.day-status').change(function() {
            const isChecked = $(this).is(':checked');
            const statusValue = $(this).siblings('.status-value');

            // Update hidden input value
            statusValue.val(isChecked ? '1' : '0');
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

        let message = '<?php echo addslashes((string) $msg); ?>';
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

        // ========================= SEO JS code start here ========================= //

        const seoSettings = <?= $admin->getSeoSettings() ?>;
        const pageSettings = <?= isset($pageSettings) ? json_encode($pageSettings) : '{}' ?>;

        // Load SEO data when page is selected
        $('#seoPageSelect').change(function() {
            const pageName = $(this).val();
            loadSeoData(pageName);
        });

        // Add breadcrumb button
        $('#addBreadcrumbBtn').click(function() {
            addBreadcrumbField('', '');
        });

        // Form submission
        $('#seoSettingsForm').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serializeArray();
            const breadcrumbs = [];

            // Correct selector to match your HTML structure
            $('.breadcrumb-field').each(function() {
                const title = $(this).find('input[name*="[title]"]').val();
                const link = $(this).find('input[name*="[link]"]').val();

                if (title || link) { // Only add if either field has value
                    breadcrumbs.push({
                        title: title,
                        link: link
                    });
                }
            });

            // Add breadcrumbs to form data
            formData.push({
                name: 'breadcrumbs',
                value: JSON.stringify(breadcrumbs)
            });

            $.ajax({
                url: 'ajax/save-seo-settings.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Reload the data
                        loadSeoData($('#seoPageName').val());
                        // Refresh page
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        });

        // Load initial data
        loadSeoData($('#seoPageSelect').val());

        function loadSeoData(pageName) {
            let seoData = null;

            // Find in existing SEO settings
            for (const seo of seoSettings) {
                if (seo.page_name === pageName) {
                    seoData = seo;
                    break;
                }
            }

            // If not found, use default from pageSettings
            if (!seoData && pageSettings[pageName]) {
                seoData = {
                    page_name: pageName,
                    page_title: pageSettings[pageName].title,
                    sub_title: pageSettings[pageName].sub_title,
                    breadcrumbs: pageSettings[pageName].breadcrumbs || []
                };
            }

            // Populate form
            if (seoData) {
                $('#seoId').val(seoData.id || '');
                $('#seoPageName').val(pageName);
                $('#seoPageTitle').val(seoData.page_title || '');
                $('#seoSubTitle').val(seoData.sub_title || '');
                $('#seoMetaDescription').val(seoData.meta_description || '');
                $('#seoMetaKeywords').val(seoData.meta_keywords || '');
                $('#seoCanonicalUrl').val(seoData.canonical_url || '');
                $('#seoOgTitle').val(seoData.og_title || '');
                $('#seoOgDescription').val(seoData.og_description || '');
                $('#seoOgImage').val(seoData.og_image || '');

                // Clear existing breadcrumbs
                $('#breadcrumbsContainer').empty();

                // Add breadcrumbs
                if (seoData.breadcrumbs && seoData.breadcrumbs.length > 0) {
                    seoData.breadcrumbs.forEach(breadcrumb => {
                        addBreadcrumbField(breadcrumb.title, breadcrumb.link);
                    });
                }
            }
        }

        function addBreadcrumbField(title = '', link = '') {
            const index = $('.breadcrumb-field').length;
            const html = `<div class="breadcrumb-field mb-2 row justify-content-between align-items-center"><div class="col-md-6"><input type="text" class="form-control" placeholder="Title" value="${title}" name="breadcrumbs[${index}][title]"></div><div class="col-md-6"><input type="text" class="form-control" placeholder="Link" value="${link}" name="breadcrumbs[${index}][link]"><button type="button" class="btn btn-danger btn-sm remove-breadcrumb"><i class="fa fa-close"></i></button></div></div>`;
            $('#breadcrumbsContainer').append(html);
        }

        // Remove breadcrumb
        $(document).on('click', '.remove-breadcrumb', function() {
            $(this).closest('.breadcrumb-field').remove();
        });

        // Remove breadcrumb
        $(document).on('click', '.remove-breadcrumb', function() {
            $(this).closest('.breadcrumb-item').remove();
        });

        // Add Page button click handler
        $('#addPageBtn').click(function() {
            $('#addPageModal').modal('show');
        });

        // Add Page button click handler
        $('#addSocialBtn').click(function() {
            $('#addSocialModal').modal('show');
        });

        // Save new page handler
        $('#saveNewPageBtn').click(function() {
            const pageName = $('#newPageName').val();
            const pageTitle = $('#newPageTitle').val();
            const subTitle = $('#newSubTitle').val();

            if (!pageName || !pageTitle) {
                toastr.error('Page name and title are required');
                return;
            }

            $.ajax({
                url: 'ajax/save-seo-settings.php',
                type: 'POST',
                data: {
                    page_name: pageName,
                    page_title: pageTitle,
                    sub_title: subTitle,
                    breadcrumbs: '[]'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#addPageModal').modal('hide');
                        // Add the new page to the dropdown
                        const optionText = pageName.replace('.php', '').replace('-', ' ');
                        $('#seoPageSelect').append(new Option(ucfirst(optionText), pageName));
                        $('#seoPageSelect').val(pageName).trigger('change');
                        // Clear the form
                        $('#addPageForm')[0].reset();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        });

        // Helper function to capitalize first letter
        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });
</script>
<?php include_once('footer.php') ?>