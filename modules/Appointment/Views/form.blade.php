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
            {!! Form::select('', $prompt + $service_types, $appointment->service->type->id ?? null, [
                'id' => 'service-type',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="service">{{ trans('Service') }}</label>
            <select name="service_id" id="service" class="select2 form-control" style="width: 100%">
                <option value="">{{ trans("Please Select Service Type") }}</option>
            </select>
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
            <input type="text" class="form-control datetime" id="booking-time" name="time" placeholder="dd-mm-yyyy"
                   value="{{ $appointment->time ?? old('name') }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="status">{{ trans('Status') }}</label>
            {!! Form::select('status', $statuses, $appointment->status ?? null,[
                'id' => 'status',
                'class' => 'select2 form-control',
                 'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-12 form-group">
            <label for="description">{{ trans('Description') }}</label>
            <textarea name="description" id="description" class="form-control"
                      rows="5">{{ $appointment->description ?? null }}</textarea>
        </div>
        <div class="col-md-12 mt-5 d-flex justify-content-between">
            <div>
                <button type="submit" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
                <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
            </div>
            <div>
                <a href="{{ route("get.appointment.delete", $appointment->id) }}"
                   class="btn btn-danger">{{ trans('Delete') }}</a>
            </div>
        </div>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Appointment\Http\Requests\AppointmentRequest') !!}
<script>
    $('input.datetime').datetimepicker({
        format: 'dd-mm-yyyy hh:ii',
        language: "{{ App::getLocale() }}",
        todayBtn: true,
        autoclose: true
    });
    $('input#time').val($('input#get-date').val());

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
</script>
