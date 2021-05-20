@extends("Base::layouts.master")
@push('css')
    <link href='{{ asset('assets/fullcalendar/lib/main.css') }}' rel='stylesheet'/>
    <style>
        #fullcalendar {
            margin: 40px auto;
            max-height: 600px;
        }

        button.fc-button {
            text-transform: capitalize !important;
        }

    </style>
@endpush
@section("content")
    <div id="appointment-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Appointment") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Appointment Listing") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.appointment.create') }}" id="create-booking" class="btn btn-primary"
                   data-toggle="modal"
                   data-target="#form-modal" data-title="Create Appointment">
                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                </a>
                <a href="#" class="d-none" id="update-booking" data-toggle="modal" data-target="#form-modal"></a>
            </div>
        </div>
    </div>
    <div class="appointment">
        <div class="card">
            <div class="card-body">
                <div id="fullcalendar"></div>
                <input type="hidden" id="get-date">
                <textarea id="event" class="d-none">{{ $events }}</textarea>
            </div>
        </div>
    </div>
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => ' modal-lg'])  !!}
@endsection
@push('js')
    <script src='{{ asset('assets/fullcalendar/lib/main.js') }}'></script>
    <script src='{{ asset('assets/fullcalendar/lib/locales-all.js') }}'></script>
    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById('fullcalendar');
            var initialLocaleCode = "{{ App::getLocale() }}";
            var events = JSON.parse($('#event').val());
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                },
                initialView: 'timeGridWeek',
                selectable: true,
                locale: initialLocaleCode,
                weekNumbers: true,
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventDrop: function (info) {
                    var eventObj = info.event;
                    var time = info.event.start.toISOString();
                    $.ajax({
                        url: "{{ route("post.appointment.update_time",'') }}/" + eventObj.id,
                        method: "post",
                        data: {'time': formatDateTime(time)}
                    }).done(function (response) {
                        if (response.status !== 200) {
                            alert(response.message);
                        }
                        location.reload()
                    });
                    location.reload()
                },
                eventDurationEditable: false,
                dayMaxEvents: true, // allow "more" link when too many events
                events: events,
                dateClick: function (info) {
                    $('#create-booking').click();
                    $('input#get-date').val(formatDateTime(info.dateStr));
                },
                eventClick: function (info) {
                    var eventObj = info.event;
                    $("#update-booking").attr("href", "{{ route("get.appointment.update",'') }}/" + eventObj.id);
                    $("#update-booking").click();
                },
            });

            calendar.render();
        })
    </script>
@endpush
