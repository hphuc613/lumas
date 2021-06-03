@php
    use App\AppHelpers\Helper;
    $prompt = [null => trans('Select')];
    $segment = Helper::segment(2);
    $member_display_id = session()->get('member_display_id')
@endphp
<form action="" method="post" id="appointment-form">
    @csrf
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="type">{{ trans('Appointment Type') }}</label>
            {!! Form::select('type', $appointment_types, $appointment->type ?? null, [
                'id' => 'type',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-4 form-group">
            <label for="booking-time">{{ trans('Time') }}</label>
            <input type="text" class="form-control datetime" id="booking-time" name="time"
                   placeholder="dd-mm-yyyy hh:ii"
                   value="{{ $appointment->time ?? old('name') }}">
        </div>
        <div class="col-md-4 form-group">
            <label for="status">{{ trans('Status') }}</label>
            {!! Form::select('status', $statuses, $appointment->status ?? null,
            ['id' => 'status', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="name">{{ trans('Subject') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ $appointment->name ?? old('name') }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="member">{{ trans('Client') }}</label>
            {!! Form::select('member_id', $prompt + $members, !empty($member_display_id) ? $member_display_id : $appointment->member_id ?? null, [
                'id' => 'member',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="store">{{ trans('Store') }}</label>
            {!! Form::select('store_id', $prompt + $stores, $appointment->store_id ?? null, [
                'id' => 'store',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        @if(Auth::user()->isAdmin())
            <div class="col-md-6 form-group">
                <label for="user-id">{{ trans('Staff') }}</label>
                {!! Form::select('user_id', $users, $appointment->user_id ?? null,
                ['id' => 'user-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
            </div>
        @endif
        <div class="col-md-12 form-group">
            <label for="description">{{ trans('Description') }}</label>
            <textarea name="description" id="description" class="form-control"
                      rows="4">{{ $appointment->description ?? null }}</textarea>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <div class="d-flex justify-content-between p-2">
                    <h4>{{ trans('Service Listing') }}</h4>
                    <div class="select-course w-50">
                        {!! Form::select('course_ids', [null => trans("Select Course")] + $courses, null, [
                        'id' => 'course-select',
                        'class' => 'select2 form-control select-product',
                        'style' => 'width: 100%']) !!}
                    </div>
                    <div class="select-service w-50">
                        {!! Form::select('service_ids', [null => trans("Select Service")] + $services, null, [
                        'id' => 'service-select',
                        'class' => 'select2 form-control select-product',
                        'style' => 'width: 100%']) !!}
                    </div>
                </div>
                <table class="table table-striped" id="product-list">
                    <thead>
                    <tr>
                        <th>{{ trans('Service/Course Name') }}</th>
                        <th class="text-center">{{ trans('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($appointment))
                        @if($appointment->type === \Modules\Appointment\Model\Appointment::SERVICE_TYPE)
                            @foreach($appointment->service_ids as $item)
                                <tr class="pl-2">
                                    <td>
                                        <input type="hidden" name="product_ids[]" value="{{ $item->id }}">
                                        <span class="text-option">{{ $item->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger delete-product"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach($appointment->course_ids as $item)
                                <tr class="pl-2">
                                    <td>
                                        <input type="hidden" name="product_ids[]" value="{{ $item->id }}">
                                        <span class="text-option">{{ $item->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger delete-product"><i
                                                class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12 mt-5 d-flex justify-content-between">
            <div>
                <button type="submit" id="submit-btn" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
                <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
            </div>
            @if(isset($appointment))
                <div>
                    <a href="{{ route("get.appointment.check_in", [$appointment->id, $appointment->member_id]) }}"
                       class="btn btn-outline-info">
                        {{ trans('Check In') }}
                    </a>
                    <a href="{{ route("get.appointment.delete", $appointment->id) }}"
                       class="btn btn-danger btn-delete">{{ trans('Delete') }}</a>
                </div>
            @endif
        </div>
    </div>
</form>


@if(isset($appointment))
    <div id="appointment-info">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="type">{{ trans('Appointment Type') }}</label>
                <div class="w-100">{{ $appointment_types[$appointment->type] }} </div>
            </div>
            <div class="col-md-4 form-group">
                <label for="booking-time">{{ trans('Time') }}</label>
                <div class="w-100">{{ $appointment->time }} </div>
            </div>
            <div class="col-md-4 form-group">
                <label for="status">{{ trans('Status') }}</label>
                <div class="w-100"
                     style="color: {{ $appointment->getColorStatus() }}">{{ $statuses[$appointment->status] }} </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6 form-group">
                <label for="name">{{ trans('Subject') }}</label>
                <div class="w-100">{{ $appointment->name }} </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="member">{{ trans('Client') }}</label>
                <div class="w-100">{{ $members[$appointment->member_id] }} </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6 form-group">
                <label for="store">{{ trans('Store') }}</label>
                <div class="w-100">{{ $stores[$appointment->store_id] }} </div>
            </div>
            @if(Auth::user()->isAdmin())
                <div class="col-md-6 form-group">
                    <label for="user-id">{{ trans('Staff') }}</label>
                    <div class="w-100">{{ $appointment->user->name }} </div>
                </div>
            @endif
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12 form-group">
                <label for="description">{{ trans('Description') }}</label>
                <textarea name="description" id="description" class="form-control" readonly=""
                          rows="4">{{ $appointment->description }}</textarea>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <div class="d-flex justify-content-between p-2">
                        <h4>{{ trans('Service Listing') }}</h4>
                    </div>
                    <table class="table table-striped" id="product-list">
                        <thead>
                        <tr>
                            <th>{{ trans('Service/Course Name') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($appointment))
                            @if($appointment->type === \Modules\Appointment\Model\Appointment::SERVICE_TYPE)
                                @foreach($appointment->service_ids as $item)
                                    <tr class="pl-2">
                                        <td>
                                            <input type="hidden" name="product_ids[]" value="{{ $item->id }}">
                                            <span class="text-option">{{ $item->name }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($appointment->course_ids as $item)
                                    <tr class="pl-2">
                                        <td>
                                            <input type="hidden" name="product_ids[]" value="{{ $item->id }}">
                                            <span class="text-option">{{ $item->name }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 mt-5 d-flex justify-content-between">
                <div>
                    <button type="button" id="edit-btn" class="btn btn-primary mr-2">{{ trans('Edit') }}</button>
                </div>
                <div>
                    <a href="{{ route("get.appointment.check_in", [$appointment->id, $appointment->member_id]) }}"
                       class="btn btn-outline-info">
                        {{ trans('Check In') }}
                    </a>
                    <a href="{{ route("get.appointment.delete", $appointment->id) }}"
                       class="btn btn-danger btn-delete">{{ trans('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>
@endif


{!! JsValidator::formRequest('Modules\Appointment\Http\Requests\AppointmentRequest') !!}
<script>
    $(document).ready(function () {
        if ($("#appointment-form #type").val() === "{{ \Modules\Appointment\Model\Appointment::SERVICE_TYPE }}") {
            $('.select-course').hide();
            $('.select-service').show();
        } else {
            $('.select-course').show();
            $('.select-service').hide();
        }
        if ($('input#booking-time').val() !== "") {
            editAble(true) //Read only
            $(document).on("click", "#edit-btn", function () {
                editAble(false) //Edit
            });
            $(document).on("submit", "#appointment-form", function () {
                $('#appointment-form #member.select2').prop('disabled', false);
                $('#appointment-form #type.select2').prop('disabled', false);
            });
        } else {
            /*add booking time is current if new record*/
            $('input#booking-time').val($('input#get-date').val());
        }
        /*Datetimepicker*/
        $('input.datetime').datetimepicker({
            format: 'dd-mm-yyyy hh:ii',
            language: "{{ App::getLocale() }}",
            todayBtn: true,
            autoclose: true,
            fontAwesome: true,
            startDate: "{{  \Carbon\Carbon::createFromTimestamp(time()) }}"
        });
    });

    /** Edit able */
    function editAble(status) {
        if (status === true) {
            $("#appointment-form").hide();
            $("#appointment-info").show();
        } else {
            $("#appointment-form").show();
            $("#appointment-info").hide();
        }

        $('#appointment-form #type.select2').prop('disabled', true);
        $('#appointment-form #member.select2').prop('disabled', true);
    }
</script>
