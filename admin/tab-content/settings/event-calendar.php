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
