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
    $openingHours = [];
    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    foreach ($days as $day) {
        if (isset($_POST[$day])) {
            $openingHours[$day] = [
                'status' => $_POST[$day]['status'] ?? '0',
                'opening' => $_POST[$day]['opening'] ?? '09:00',
                'closing' => $_POST[$day]['closing'] ?? '17:00'
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
                                $dayData = $openingHours[$short] ?? [
                                    'status' => '0',
                                    'opening' => '09:00',
                                    'closing' => '17:00'
                                ];
                            ?>
                                <div class="form-group mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class=""><?php echo $long; ?></label>
                                        <label class="switch">
                                            <input type="checkbox" class="day-status" name="<?php echo $short; ?>[status]"
                                                value="1" <?php echo $dayData['status'] == '1' ? 'checked' : ''; ?>>
                                            <span class="slider round"></span>
                                        </label>
                                        <input type="hidden" name="<?php echo $short; ?>[status]"
                                            value="<?php echo $dayData['status']; ?>" class="status-value">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Open</span>
                                                </div>
                                                <input type="time" class="form-control opening-time"
                                                    name="<?php echo $short; ?>[opening]"
                                                    value="<?php echo $dayData['opening']; ?>"
                                                    <?php echo $dayData['status'] == '0' ? 'disabled' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Close</span>
                                                </div>
                                                <input type="time" class="form-control closing-time"
                                                    name="<?php echo $short; ?>[closing]"
                                                    value="<?php echo $dayData['closing']; ?>"
                                                    <?php echo $dayData['status'] == '0' ? 'disabled' : ''; ?>>
                                            </div>
                                        </div>
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
                    <a class="nav-item nav-link ml-1" id="event-calendar-tab" data-toggle="pill" href="#event-calendar" role="tab" aria-controls="event-calendar" aria-selected="false">
                        Event Calendar <!-- TODO Need to work on this module -->
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="siteInfoTabsContent">
                    <div class="tab-pane fade show active" id="seo-details" role="tabpanel" aria-labelledby="seo-details-tab">
                        <?php include_once('tab-content/settings/seo-details.php'); ?>
                    </div>
                    <div class="tab-pane fade" id="event-calendar" role="tabpanel" aria-labelledby="event-calendar-tab">
                        <?php include_once('tab-content/settings/event-calendar.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('modals/addPage-modal.php'); ?>
<?php include_once('modals/event-modal.php'); ?>
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
            // Enable only the toggle switches
            $('.day-status').prop('disabled', false);

            // Keep time inputs disabled/enabled based on current toggle status
            $('.day-status').each(function() {
                const dayRow = $(this).closest('.form-group');
                const isChecked = $(this).is(':checked');
                dayRow.find('.opening-time, .closing-time').prop('disabled', !isChecked);
            });

            // Show update and cancel buttons, hide edit button
            $('#updateHoursBtn, #cancelHoursBtn').show();
            $(this).hide();
        });

        // Cancel button click handler
        $('#cancelHoursBtn').click(function() {
            // Disable all elements
            $('.day-status, .opening-time, .closing-time').prop('disabled', true);

            // Show edit button, hide update and cancel buttons
            $('#editHoursBtn').show();
            $('#updateHoursBtn, #cancelHoursBtn').hide();
        });

        // Toggle switch change handler (keep this as is)
        $('.day-status').change(function() {
            const dayRow = $(this).closest('.form-group');
            const isChecked = $(this).is(':checked');
            const statusValue = dayRow.find('.status-value');

            // Update hidden input value
            statusValue.val(isChecked ? '1' : '0');

            // Enable/disable time inputs
            dayRow.find('.opening-time, .closing-time').prop('disabled', !isChecked);
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

            $('.breadcrumb-item').each(function() {
                breadcrumbs.push({
                    title: $(this).find('.breadcrumb-title').val(),
                    link: $(this).find('.breadcrumb-link').val()
                });
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

        // ========================= Shift JS code start here ========================= //
        let currentDate = new Date();
        let events = [];

        // Initialize calendar
        renderCalendar();

        // Event listeners
        $('#today').click(function() {
            currentDate = new Date();
            renderCalendar();
        });

        $('#prev-month').click(function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        $('#next-month').click(function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        $('#new-event-btn').click(function() {
            $('#eventModal').modal('show');
            // Set default date/time to today
            const now = new Date();
            $('#event-date').val(now.toISOString().split('T')[0]);
            $('#start-time').val('09:00');
            $('#end-time').val('10:00');
        });

        $('#save-event').click(function() {
            if ($('#event-form')[0].checkValidity()) {
                const event = {
                    id: Date.now(),
                    title: $('#event-title').val(),
                    date: $('#event-date').val(),
                    startTime: $('#start-time').val(),
                    endTime: $('#end-time').val(),
                    description: $('#event-description').val()
                };
                events.push(event);
                $('#eventModal').modal('hide');
                $('#event-form')[0].reset();
                renderCalendar();
            } else {
                $('#event-form')[0].reportValidity();
            }
        });

        function renderCalendar() {
            updateMonthDisplay();
            renderMonthView();
        }

        function updateMonthDisplay() {
            const options = {
                month: 'long',
                year: 'numeric'
            };
            $('#date-range').text(currentDate.toLocaleDateString(undefined, options));
        }

        function renderMonthView() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Get first day of month and last day of month
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);

            // Get days from previous month to show
            const startDay = getStartOfWeek(firstDay);

            // Get days from next month to show
            const endDay = new Date(getStartOfWeek(lastDay));
            endDay.setDate(endDay.getDate() + 6);

            let html = '<div class="month-view">';

            // Weekday headers
            const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            weekdays.forEach(day => {
                html += `<div class="month-day-header">${day}</div>`;
            });

            // Calendar days
            const currentDay = new Date(startDay);
            while (currentDay <= endDay) {
                const isCurrentMonth = currentDay.getMonth() === month;
                const isToday = isSameDay(currentDay, new Date());
                const dateStr = formatDate(currentDay);

                const dayEvents = events.filter(event => event.date === dateStr);

                html += `<div class="month-day ${isToday ? 'today' : ''} ${!isCurrentMonth ? 'other-month' : ''}" data-date="${dateStr}">`;
                html += `<div class="date">${currentDay.getDate()}</div>`;

                dayEvents.forEach(event => {
                    html += `<div class="event" data-id="${event.id}" title="${event.title} (${formatTime(event.startTime)} - ${formatTime(event.endTime)})">`;
                    html += `${event.title}`;
                    html += '</div>';
                });

                html += '</div>';

                currentDay.setDate(currentDay.getDate() + 1);
            }

            html += '</div>'; // Close month-view

            $('#calendar-view').html(html);

            // Add click event to days for adding new events
            $('.month-day').click(function() {
                const date = $(this).data('date');
                $('#eventModal').modal('show');
                $('#event-date').val(date);
                $('#start-time').val('09:00');
                $('#end-time').val('10:00');
            });
        }

        // Helper functions
        function getStartOfWeek(date) {
            const day = date.getDay();
            const diff = date.getDate() - day;
            return new Date(date.setDate(diff));
        }

        function isSameDay(date1, date2) {
            return date1.getFullYear() === date2.getFullYear() &&
                date1.getMonth() === date2.getMonth() &&
                date1.getDate() === date2.getDate();
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function formatTime(timeStr) {
            const [hours, minutes] = timeStr.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

    });
</script>
<?php include_once('footer.php') ?>