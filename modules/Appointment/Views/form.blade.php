@php
    use App\AppHelpers\Helper;$prompt = [null => trans('Select')];
    $segment = Helper::segment(2)
@endphp
<form action="{{ route("post.appointment.create") }}" method="post" id="appointment-form">
    @csrf
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="name">{{ trans('Subject') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ $appointment->name ?? old('name') }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="client">{{ trans('Client') }}</label>
            {!! Form::select('member_id', $prompt + $clients, $appointment->member_id ?? null, [
                'id' => 'client',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="service-type">{{ trans('Service Type') }}</label>
            {!! Form::select('', $prompt + $service_types, $appointment->service->type_id ?? null, [
                'id' => 'service-type',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="service">{{ trans('Service') }}</label>
            @if(isset($services))
                {!! Form::select('', $prompt + $services, $appointment->service_id ?? null, [
                'id' => 'service',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
            @else
                <select name="service_id" id="service" class="select2 form-control" style="width: 100%">
                    <option value="">{{ trans("Please Select Service Type") }}</option>
                </select>
            @endif

        </div>
        <div class="col-md-6 form-group">
            <label for="store">{{ trans('Store') }}</label>
            {!! Form::select('store_id', $prompt + $stores, $appointment->store_id ?? null, [
                'id' => 'store',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="booking-time">{{ trans('Time') }}</label>
            <input type="text" class="form-control datetime" id="booking-time" name="time"
                   placeholder="dd-mm-yyyy hh:ii"
                   value="{{ $appointment->time ?? old('name') }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="status">{{ trans('Status') }}</label>
            {!! Form::select('status', $statuses, $appointment->status ?? null,
            ['id' => 'status', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
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
                      rows="5">{{ $appointment->description ?? null }}</textarea>
        </div>
        <div class="col-md-12 mt-5 d-flex justify-content-between">
            <div>
                @if(isset($appointment))
                    <button type="button" id="edit-btn" class="btn btn-primary mr-2">{{ trans('Edit') }}</button>
                @endif
                <button type="submit" id="submit-btn" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
                <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
            </div>
            @if(isset($appointment))
                <div>
                    <a href="{{ route("get.appointment.delete", $appointment->id) }}"
                       class="btn btn-danger btn-delete">{{ trans('Delete') }}</a>
                </div>
            @endif
        </div>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Appointment\Http\Requests\AppointmentRequest') !!}
<script>
    $(document).ready(function () {
        if ($('input#booking-time').val() !== "") {
            /*Read only*/
            $("input").prop('disabled', true);
            $('select').prop('disabled', true);
            $("textarea").prop('disabled', true);
            $("#edit-btn").show();
            $("#submit-btn").hide();

            /*Edit*/
            $(document).on("click", "#edit-btn", function () {
                $("input").prop('disabled', false);
                $('select').prop('disabled', false);
                $("textarea").prop('disabled', false);
                $("#edit-btn").hide();
                $("#submit-btn").show();
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

        /*Get service list by service type*/
        $(document).on('change', '#service-type', function () {
            var service_type = $(this);
            var type_id = service_type.val();
            $.ajax({
                url: "{{ route("get.appointment.get_list_service_by_type", '') }}/" + type_id,
                method: "get"
            }).done(function (response) {
                service_type.parents('form').find('#service').html(response);
            });
        });
    })
</script>
