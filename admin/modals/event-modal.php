
    <!-- New Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">New Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="event-form">
                        <div class="form-group">
                            <label for="event-title">Event title</label>
                            <input type="text" class="form-control" id="event-title" required>
                        </div>
                        <div class="form-group">
                            <label for="event-date">Date</label>
                            <input type="date" class="form-control" id="event-date" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start-time">Start time</label>
                                <input type="time" class="form-control" id="start-time" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end-time">End time</label>
                                <input type="time" class="form-control" id="end-time" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="event-description">Description (optional)</label>
                            <textarea class="form-control" id="event-description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-success btn-block" id="saveNewPageBtn">Save</button>
            </div>
            </div>
        </div>
    </div>