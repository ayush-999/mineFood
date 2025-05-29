<?php
include_once('header.php');
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
                <div class="calendar-container">
                    <!-- Calendar Header -->
                    <div class="calendar-header">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Calendar</h4>
                            <button class="btn bg-gradient-success rounded-pill" id="new-event-btn">
                                <i class="fas fa-plus"></i> New event
                            </button>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button class="btn btn-outline-secondary" id="prev-month">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="btn btn-outline-secondary mx-2" id="today">Today</button>
                                <button class="btn btn-outline-secondary" id="next-month">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="current-month" id="date-range">
                                <!-- Month/year will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Calendar View -->
                    <div class="calendar-view" id="calendar-view">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php include_once('modals/event-modal.php'); ?>
<script type="text/javascript">
    $(document).ready(function() {
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