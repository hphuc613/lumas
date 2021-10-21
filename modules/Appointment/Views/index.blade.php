@extends("Base::layouts.master")
@push('css')
    <link href='{{ asset('assets/fullcalendar/lib/main.css') }}' rel='stylesheet'/>
    <link href='{{ asset('assets/fullcalendar/custom/calendar.css') }}' rel='stylesheet'/>
@endpush
@section("content")
    <div id="appointment-module">
        <div class="d-flex justify-content-between">
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
            @php($segment = \App\AppHelpers\Helper::segment(1))
            <div class="p-3">
                <a href="{{ route("get.appointment.overview", ['member_id' => $member->id ?? NULL]) }}"
                   class="btn btn-main-color">{{ trans('Overview') }}</a>
                @if(isset($member) || isset($user))
                    <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
                @endif
            </div>
        </div>
        <div class="appointment">
            <div class="card">
                <div class="card-header">
                    <div id="head-page" class="d-flex justify-content-between">
                        <div>
                            <h3>
                                @if(isset($filter['type']) && $filter['type'] == \Modules\Appointment\Model\Appointment::COURSE_TYPE)
                                    {{ trans("Course") }}
                                @else
                                    {{ trans("Service") }}
                                @endif
                                {{ trans("Appointment Listing") }}
                                @if(isset($member))
                                    {{ trans("of") }}
                                    <span class="text-info" style="font-size: inherit">{{ $member->name }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="group-btn">
                            <div class="{{--d-inline-block--}} d-none" style="width: 150px">
                                {!! Form::select('type', $appointment_types, $filter['type'] ?? null,
                                ['id' => 'appointment_type', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                            </div>
                            <a href="{{ route('get.appointment.bulk_create',['member_id' => $member->id ?? NULL, 'type' => $filter['type'] ?? 'service']) }}"
                               id="create-booking" class="btn btn-primary"
                               data-toggle="modal"
                               data-target="#form-modal" data-title="{{ trans('Bulk Create Appointment') }}">
                                <i class="fa fa-plus"></i> &nbsp; {{ trans('Bulk Add New') }}
                            </a>
                            <a href="{{ route('get.appointment.create',['member_id' => $member->id ?? NULL, 'type' => $filter['type'] ?? 'service']) }}"
                               id="create-booking" class="btn btn-main-color"
                               data-toggle="modal"
                               data-target="#form-modal" data-title="{{ trans('Create Appointment') }}">
                                <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                            </a>
                            <a href="#" data-url="{{ route("get.appointment.update",'') }}" class="d-none"
                               id="update-booking" data-toggle="modal"
                               data-target="#form-modal" data-title="{{ trans('Update Appointment') }}"></a>
                            @if(isset($member))
                                <a href="{{ route('get.member_service.add',$member->id) }}"
                                   class="btn btn-warning text-light"><i
                                        class="fas fa-plus"></i> {{ trans('Add Service') }}</a>
                                <a href="{{ route('get.member_course.add',$member->id) }}"
                                   class="btn btn-info d-none"><i class="fas fa-plus"></i> {{ trans('Add Course') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="status-description" class="pb-4">
                        <h5 class="card-title">{{ trans('Appointment Status Color') }}</h5>
                        <div id="appointment-status" class="d-flex">
                            <div class="p-2">
                                <i class="fa fa-circle" style="color: #00c292"></i> {{ trans('Success') }}
                            </div>
                            <div class="p-2">
                                <i class="fa fa-circle text-info"></i> {{ trans('Waiting') }}
                            </div>
                            <div class="p-2">
                                <i class="fa fa-circle text-danger"></i> {{ trans('Missing') }}
                            </div>
                            <div class="p-2">
                                <i class="fa fa-circle text-warning"></i> {{ trans('Progressing') }}
                            </div>
                            <div class="p-2">
                                <i class="fa fa-circle" style="color: #aaaaaa"></i> {{ trans('Abort') }}
                            </div>
                        </div>
                    </div>
                    <div id="fullcalendar"></div>
                    <input type="hidden" id="get-date" value="{{ formatDate(time(),'d-m-Y H:i') }}">
                    <textarea id="event" class="d-none">{{ $events }}</textarea>
                </div>
            </div>
        </div>
    </div>
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => ' modal-lg'])  !!}
@endsection
@push('js')
    <script src='{{ asset('assets/fullcalendar/lib/main.js') }}'></script>
    <script src='{{ asset('assets/fullcalendar/lib/locales-all.js') }}'></script>
    <script src='{{ asset('assets/fullcalendar/custom/calendar.js') }}'></script>
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#form-modal').on('hidden.bs.modal', function () {
                var now = new Date();
                $('#get-date').val(formatDateTime(now));
            });

            $('#appointment_type').change(function () {
                calendarStyleView();
                location.href = "{{  route(\Illuminate\Support\Facades\Route::currentRouteName(),$member->id ?? $user->id ?? null) }}?type=" + $(this).val();
            });

            /** Generate Calendar Appointment */
            var calendar = generateCalendarAppointment(
                "{{ route("post.appointment.update_time",'') }}",
                "{{ route("get.appointment.product_list",'') }}",
                "{{ $member->id ?? NULL }}"
            );
            /** Upddate Event By Month **/
            $(document).on('click', 'button.fc-button', function () {
                const cdate = calendar.getDate();
                const date = new Date(cdate);
                const month = date.getMonth() + 1;
                const year = date.getFullYear();
                const member_id = "{{ $member->id ?? null }}";
                const user_id = "{{ $user->id ?? null }}";
                const param = "?month=" + month + "&year=" + year +  "&member_id=" + member_id +  "&user_id=" + user_id;
                $.ajax({
                    url: '{{ route("get.appointment.event_list") }}' + param,
                    method: "get",
                }).done(function (response) {
                    calendar.getEventSources()[0].remove();
                    calendar.addEventSource(JSON.parse(response));/** Tooltip in calendar */
                    $(".tooltip-content").tooltip({
                        content: function () {
                            return $(this).attr('data-tooltip');
                        },
                        position: {
                            my: "center bottom", // the "anchor point" in the tooltip element
                            at: "center top-10", // the position of that anchor point relative to selected element
                        },
                        open: function (event, ui) {
                            ui.tooltip.css("max-width", "500px");
                        }
                    });
                });
            });

            /** Appointment Form get product list by type*/
            getProductByType("{{ \Modules\Appointment\Model\Appointment::SERVICE_TYPE }}");

            /** Tooltip in calendar */
            $(".tooltip-content").tooltip({
                content: function () {
                    return $(this).attr('data-tooltip');
                },
                position: {
                    my: "center bottom", // the "anchor point" in the tooltip element
                    at: "center top-10", // the position of that anchor point relative to selected element
                },
                open: function (event, ui) {
                    ui.tooltip.css("max-width", "500px");
                }
            });

            $(document).on('mouseover', ".ui-tooltip", function () {
                $(this).remove();
            });

            $(document).click(function () {
                $(".tooltip-content").tooltip({
                    content: function () {
                        return $(this).attr('data-tooltip');
                    },
                    position: {
                        my: "center bottom",
                        at: "center top-10",
                    },
                    open: function (event, ui) {
                        ui.tooltip.css("max-width", "500px");
                    }
                });
            });
        })
    </script>
@endpush
