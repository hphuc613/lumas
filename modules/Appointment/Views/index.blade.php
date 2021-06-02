@extends("Base::layouts.master")
@push('css')
    <link href='{{ asset('assets/fullcalendar/lib/main.css') }}' rel='stylesheet'/>
    <style>
        #fullcalendar {
            max-height: 700px;
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
                    @if(isset($member))
                        <li class="breadcrumb-item"><a href="#">{{ $member->name }}</a></li> @endif
                </ol>
            </nav>
        </div>
    </div>
    <div class="appointment">
        <div class="card">
            <div class="card-body">
                <div id="head-page" class="d-flex justify-content-between">
                    <div class="page-title">
                        <h3>
                            {{ trans("Appointment Listing") }}
                            @if(isset($member)) {{ trans("of") }} <span class="text-info"
                                                                        style="font-size: inherit">{{ $member->name }}</span> @endif
                        </h3>
                    </div>
                    <div class="group-btn">
                        <div class="d-inline-block" style="width: 150px">
                            {!! Form::select('type', $appointment_types, $filter['type'] ?? null,
                            ['id' => 'appointment_type', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                        </div>
                        <a href="{{ route('get.appointment.create') }}" id="create-booking" class="btn btn-primary"
                           data-toggle="modal"
                           data-target="#form-modal" data-title="{{ trans('Create Appointment') }}">
                            <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                        </a>
                        <a href="#" class="d-none" id="update-booking" data-toggle="modal"
                           data-target="#form-modal" data-title="{{ trans('Update Appointment') }}"></a>
                    </div>
                </div>
                <div id="fullcalendar"></div>
                <input type="hidden" id="get-date"
                       value="{{ \Carbon\Carbon::createFromTimestamp(time())->format('d-m-Y H:i') }}">
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
            $('#appointment_type').change(function () {
                calendarStyleView();
                location.href = "{{  route(\Illuminate\Support\Facades\Route::currentRouteName(),$member->id ?? $user->id ?? null) }}?type=" + $(this).val();
            });

            var initialView;
            var calendarEl = document.getElementById('fullcalendar');
            var initialLocaleCode = "{{ App::getLocale() }}";
            var events = JSON.parse($('#event').val());
            if (window.localStorage.getItem('calendarStyle')) {
                initialView = window.localStorage.getItem('calendarStyle');
                window.localStorage.removeItem('calendarStyle');
            } else {
                initialView = "timeGridWeek";
            }
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                },
                initialView: initialView,
                selectable: true,
                locale: initialLocaleCode,
                weekNumbers: true,
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventDidMount: function (info) {
                    $(info.el).attr("id", "event_id_" + info.event.id);
                },
                eventDrop: function (info) {
                    var eventObj = info.event;
                    var time = info.event.start.toISOString();
                    $.ajax({
                        url: "{{ route("post.appointment.update_time",'') }}/" + eventObj.id,
                        method: "post",
                        data: {'time': formatDateTime(time)}
                    }).done(function (response) {
                        if (response.status !== 200) {
                            info.revert();
                        } else {
                            calendarStyleView();
                            swal({
                                title: "Change success",
                                icon: "success",
                                button: "OK"
                            }).then((done) => {
                                if (done) {
                                    location.reload()
                                }
                            });
                        }
                    });
                },
                eventDurationEditable: false,
                dayMaxEvents: true, // allow "more" link when too many events
                events: events,
                dateClick: function (info) {
                    $('#create-booking').click();
                    $('input#get-date').val(formatDateTime(info.dateStr));
                    calendarStyleView();
                },
                eventClick: function (info) {
                    var eventObj = info.event;
                    $("#update-booking").attr("href", "{{ route("get.appointment.update",'') }}/" + eventObj.id);
                    $("#update-booking").click();
                    calendarStyleView();
                }
            });
            calendar.render();

            selectService() //Form Handle
        })


        /** Form Handle */
        function selectService() {
            $(document).on('change', '#appointment-form #type', function () {
                if ($(this).val() === "{{ \Modules\Appointment\Model\Appointment::SERVICE_TYPE }}") {
                    $("#appointment-form .select-service").show();
                    $("#appointment-form .select-course").hide();
                } else {
                    $("#appointment-form .select-service").hide();
                    $("#appointment-form .select-course").show();
                }
            });

            $(document).on('change', '.select-product', function () {
                var product = $(this);
                var html =
                    '<tr id="' + product.val() + '">' +
                    '<td>' +
                    '<input type="hidden" name="product_ids[]" value="' + product.val() + '">' +
                    '<span class="text-option">' + product.children(':selected').text() + '</span>' +
                    '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-danger delete-product"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';
                $("#product-list tbody").append(html);
                product.children(':selected').remove();
            });

            /** Delete product*/
            $(document).on('click', '.delete-product', function () {
                var tr_parent = $(this).parents('tr');
                var value = tr_parent.attr('id');
                var option = tr_parent.find('.text-option').html();
                var html = '<option value="' + value + '">' + option + '</option>';
                var form = $(document).find('#appointment-form');
                if (form.find('#type').val() === "{{ \Modules\Appointment\Model\Appointment::SERVICE_TYPE }}") {
                    $(document).find('#service-select').append(html);
                } else {
                    $(document).find('#course-select').append(html);
                }
                $(this).parents('tr').remove();
            });
        }
    </script>
@endpush
