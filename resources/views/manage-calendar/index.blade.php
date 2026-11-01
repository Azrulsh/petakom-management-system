
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

    $('#calendar').fullCalendar({

        header: {
            left: 'today',
            center: 'prev, title, next',
            right: 'month,basicWeek,basicDay'
        },

        navLinks: true,
        editable: true,

        // Retrieve Calendar Events
        events: "{{ route('calendar.index') }}",

        displayEventTime: false,

        eventRender: function(event, element, view) {

            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }

        },

        selectable: true,
        selectHelper: true,

        // Create Event
        select: function(start, end, allDay) {

            var title = prompt('Remind about....');

            if (title) {

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

                    success: function(data) {

                        alert("Reminder Added Successfully");

                        $('#calendar').fullCalendar('refetchEvents');

                    },

                    error: function(xhr) {

                        alert("Unable to add reminder. Please try again.");

                        console.error(xhr.responseText);

                    }

                });

            }

            $('#calendar').fullCalendar('unselect');

        },

        // Delete Event
        eventClick: function(event) {

            var deleteMsg = confirm("Do you really want to delete?");

            if (deleteMsg) {

                $.ajax({

                    type: "POST",

                    url: "{{ route('calendar.destroy') }}",

                    data: {
                        id: event.id,
                        _token: "{{ csrf_token() }}"
                    },

                    success: function(response) {

                        if (parseInt(response) > 0) {

                            $('#calendar').fullCalendar(
                                'removeEvents',
                                event.id
                            );

                            alert("Reminder Deleted Successfully");

                        }

                    },

                    error: function(xhr) {

                        alert("Unable to delete reminder.");

                        console.error(xhr.responseText);

                    }

                });

            }

        }

    });

});
</script>

@endpush

