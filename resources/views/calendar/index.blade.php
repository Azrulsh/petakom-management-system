@extends('layouts.header-sidebar')

@section('title', 'Calendar')

@push('styles')

<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css"
      rel="stylesheet">

<style>
    .calendar-page {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .calendar-heading {
        margin-bottom: 25px;
    }

    .calendar-heading h3 {
        font-weight: 700;
        color: #333;
    }

    .calendar-heading p {
        color: #777;
        margin-bottom: 0;
    }

    #calendar {
        margin-top: 20px;
    }

    .calendar-breadcrumb {
        color: #777;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .calendar-page .fc-day-number {
        color: inherit !important;
        text-decoration: none !important;
    }

    .calendar-page .fc-day-number:hover {
        color: inherit !important;
        text-decoration: none !important;
    }

    @media(max-width: 768px) {
        .calendar-page {
            padding: 12px;
        }
    }


</style>

@endpush


@section('content')

<div class="container-fluid">

    <!-- Calendar Content -->
    <div class="calendar-page">

        <div id="calendar"></div>

    </div>

</div>


<!-- Reminder Details Modal -->
<div class="modal fade" id="reminderDetailsModal"
     tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Reminder Details</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="fw-bold">Title</label>
                    <p id="detailTitle" class="mb-0"></p>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Start Date</label>
                    <p id="detailStart" class="mb-0"></p>
                </div>

                <div>
                    <label class="fw-bold">End Date</label>
                    <p id="detailEnd" class="mb-0"></p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>

                <button type="button" class="btn btn-danger"
                        id="btnDeleteReminder">
                    Delete
                </button>

                <button type="button" class="btn btn-primary"
                        id="btnEditReminder">
                    Edit
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Edit Reminder Modal -->
<div class="modal fade" id="editReminderModal"
     tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="editReminderForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Edit Reminder</h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="editReminderId">

                    <div class="mb-3">
                        <label class="form-label">Reminder Title</label>
                        <input type="text" id="editReminderTitle"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" id="editReminderStart"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" id="editReminderEnd"
                               class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Delete Reminder Modal -->
<div class="modal fade" id="deleteReminderModal"
     tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Delete Reminder</h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this reminder?
                This action cannot be undone.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button" class="btn btn-danger"
                        id="confirmDeleteReminder">
                    Yes, Delete
                </button>
            </div>

        </div>
    </div>
</div>

@endsection



@push('scripts')

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<!-- FullCalendar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js"></script>

<script>
$(document).ready(function () {
     
    let selectedReminder = null;
    $('#calendar').fullCalendar({

        header: {
            left: 'today',
            center: 'prev, title, next',
            right: 'month,basicWeek,basicDay'
        },

        navLinks: true,
        editable: false,

        // Retrieve Calendar Events
        events: "{{ route('calendar.index') }}",

        displayEventTime: false,

        
        eventRender: function(event, element) {

            if (event.type === 'activity') {

                element.css('cursor', 'pointer');

                element.attr(
                    'title',
                    'View Activity: ' + event.title
                );

            } else if (event.type === 'reminder') {

                element.attr(
                    'title',
                    'Reminder: ' + event.title
                );

            }

        },


        selectable: true,
        selectHelper: true,

        // Create Event
        select: function(start, end, allDay) {

            var title = prompt('Remind about....');

            if (title) {

                // Convert Moment.js objects to strings
                var startDate = start.format('YYYY-MM-DD');
                var endDate = end.format('YYYY-MM-DD');

                $.ajax({
                    url: "{{ route('calendar.create') }}",
                    type: "POST",

                    data: {
                        title: title,
                        start: startDate,
                        end: endDate,
                        _token: "{{ csrf_token() }}"
                    },

                    success: function(response) {
                        if (response.success) {
                            $('#calendar').fullCalendar('refetchEvents');
                            alert(response.message);
                        }
                    },

                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Unable to create reminder.');
                    }
                });
            }

            $('#calendar').fullCalendar('unselect');
        },

        // Delete Event      
        eventClick: function(event) {

            // PETAKOM Activity
            if (event.type === 'activity') {

                var activityId = event.id.replace('activity_', '');

                var activityUrl = "{{ route('manage-activity.show', ['activity' => '__ID__']) }}";

                window.location.href = activityUrl.replace('__ID__', activityId);

                return false;
            }

            // Personal Reminder
            if (event.type === 'reminder') {

                selectedReminder = {
                    id: event.id.replace('reminder_', ''),
                    title: event.title,
                    start: event.start.format('YYYY-MM-DD'),
                    end: event.end
                       ? event.end.clone().subtract(1, 'day').format('YYYY-MM-DD')
                       : event.start.format('YYYY-MM-DD')

                };

                $('#detailTitle').text(selectedReminder.title);
                $('#detailStart').text(selectedReminder.start);
                $('#detailEnd').text(selectedReminder.end);

                const detailsModal = bootstrap.Modal.getOrCreateInstance(
                    document.getElementById('reminderDetailsModal')
                );

                detailsModal.show();

                return false;
            }
        },

    });

    // Open Edit Reminder Modal
    $('#btnEditReminder').on('click', function() {

        if (!selectedReminder) return;

        $('#editReminderId').val(selectedReminder.id);
        $('#editReminderTitle').val(selectedReminder.title);
        $('#editReminderStart').val(selectedReminder.start);
        $('#editReminderEnd').val(selectedReminder.end);

        const detailsModal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('reminderDetailsModal')
        );

        detailsModal.hide();

        const editModal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('editReminderModal')
        );

        editModal.show();
    });

    // Update Reminder
    $('#editReminderForm').on('submit', function(e) {

        e.preventDefault();

        const title = $('#editReminderTitle').val().trim();
        const start = $('#editReminderStart').val();
        const end = $('#editReminderEnd').val();

        if (!title || !start || !end) {
            alert('All fields are required.');
            return;
        }

        if (end < start) {
            alert('End date cannot be before start date.');
            return;
        }

        $.ajax({
            url: "{{ route('calendar.update') }}",
            type: "PUT",
            data: {
                id: $('#editReminderId').val(),
                title: title,
                start: start,
                end: end,
                _token: "{{ csrf_token() }}"
            },

            success: function(response) {

                if (response.success) {

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('editReminderModal')
                    ).hide();

                    $('#calendar').fullCalendar('refetchEvents');

                    alert(response.message);
                }
            },

            error: function(xhr) {

                if (xhr.status === 403) {
                    alert('You cannot edit this reminder.');
                } else if (xhr.status === 422) {
                    alert('Invalid reminder information.');
                } else {
                    alert('Unable to update reminder.');
                }
            }
        });
    });
    
    // Open Delete Confirmation Modal
    $('#btnDeleteReminder').on('click', function() {

        if (!selectedReminder) return;

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('reminderDetailsModal')
        ).hide();

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById('deleteReminderModal')
        ).show();
    });

    // Confirm Delete Reminder
    $('#confirmDeleteReminder').on('click', function() {

        if (!selectedReminder) return;

        $.ajax({
            url: "{{ route('calendar.destroy') }}",
            type: "POST",
            data: {
                id: selectedReminder.id,
                _token: "{{ csrf_token() }}"
            },

            success: function(response) {

                if (response.success) {

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById('deleteReminderModal')
                    ).hide();

                    $('#calendar').fullCalendar('refetchEvents');

                    selectedReminder = null;

                    alert(response.message);
                }
            },

            error: function(xhr) {

                alert(
                    xhr.status === 403
                        ? 'You cannot delete this reminder.'
                        : 'Unable to delete reminder.'
                );
            }
        });
    });

});
</script>

@endpush

