<?php
$prompt                  = [null => trans('Select')];
$member_display_id       = (int)request()->get('member_id');
$type                    = request()->get('type');
?>

<div class="appointment-form">
    <form action="" method="post" id="appointment-bulk-form">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="name">{{ trans('Subject') }}</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="{{ $appointment->name ?? old('name') }}">
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6 form-group">
                <label for="member">{{ trans('Client') }}</label>
                {!! Form::select('member_id', $prompt + $members, $member_display_id ?? null, [
                    'id' => 'member',
                    'class' => 'select2 form-control',
                    'style' => 'width: 100%']) !!}
            </div>
            <div class="col-md-6 form-group">
                <label for="status">{{ trans('Status') }}</label>
                {!! Form::select('status', $statuses, null,
                ['id' => 'status', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
            </div>
            <div class="d-none form-group">
                <label for="type">{{ trans('Appointment Type') }}</label>
                {!! Form::select('type', $appointment_types, \Modules\Appointment\Model\Appointment::SERVICE_TYPE,
                    ['id'   => 'type',
                    'class' => 'select2 form-control',
                    'style' => 'width: 100%']) !!}
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6 form-group">
                <label for="day_of_week">{{ trans('Day of Week') }}</label>
                <select name="day_of_week[]" class="select2 form-control w-100" multiple id="day-of-week">
                    <option value="Monday">{{ trans("Monday") }}</option>
                    <option value="Tuesday">{{ trans("Tuesday") }}</option>
                    <option value="Wednesday">{{ trans("Wednesday") }}</option>
                    <option value="Thursday">{{ trans("Thursday") }}</option>
                    <option value="Friday">{{ trans("Friday") }}</option>
                    <option value="Saturday">{{ trans("Saturday") }}</option>
                    <option value="Sunday">{{ trans("Sunday") }}</option>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label for="time">{{ trans('Time') }}</label>
                <input type="text" class="form-control time" id="time" name="time" placeholder="h:m">
            </div>
            <div class="col-md-6 form-group">
                <label for="from">{{ trans('Start Date') }}</label>
                <input type="text" class="form-control date" id="from" name="from" placeholder="d-m-Y">
            </div>
            <div class="col-md-6 form-group">
                <label for="from">{{ trans('End Date') }}</label>
                <input type="text" class="form-control date" id="to" name="to" placeholder="d-m-Y">
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-6 form-group">
                <label for="store">{{ trans('Store') }}</label>
                {!! Form::select('store_id', $stores, null, [
                    'id' => 'store',
                    'class' => 'select2 form-control',
                    'disabled' => 'disabled',
                    'style' => 'width: 100%']) !!}
                <input type="hidden" name="store_id" value="{{ array_key_first($stores) }}">
            </div>
            <div class="col-md-6 form-group">
                <label for="room">{{ trans('Room') }}</label>
                {!! Form::select('room_id[]', $rooms, null, [
                    'id' => 'room',
                    'class' => 'select2 form-control',
                    'multiple' => 'multiple',
                    'style' => 'width: 100%']) !!}
            </div>
            <div class="col-md-6 form-group">
                <label for="instrument">{{ trans('Instrument') }}</label>
                {!! Form::select('instrument_id[]', $instruments, null, [
                    'id' => 'instrument',
                    'class' => 'select2 form-control',
                    'multiple' => 'multiple',
                    'style' => 'width: 100%']) !!}
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->getRoleAttribute()->name === 'Manager')
                <div class="col-md-6 form-group">
                    <label for="staff-appointment-form">{{ trans('Staff') }}</label>
                    {!! Form::select('user_id', $prompt + $users, null,
                    ['id' => 'staff-appointment-form', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                </div>
                <div class="col-md-6 form-group">
                    <label>{{ trans('Assign More') }}</label>
                    {!! Form::select( 'assign_more[]', [], [],
                        ['class'    => 'form-control select2',
                         'multiple' => 'multiple',
                         'id'       => 'assign-more']) !!}
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
            @endif
            <div class="col-md-12 form-group">
                <label for="description">{{ trans('Description') }}</label>
                <textarea name="description" id="description" class="form-control"rows="4"></textarea>
            </div>
            <div class="col-md-12">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h4>{{ trans('Service Listing') }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="select-service w-100">
                            {!! Form::select('service_ids',$prompt + $services, null, [
                            'id' => 'service-select',
                            'class' => 'select2 form-control select-product',
                            'style' => 'width: 100%']) !!}
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="product-list">
                        <thead>
                        <tr>
                            <th>{{ trans('Service/Course Name') }}</th>
                            <th class="text-center">{{ trans('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 mt-5 d-flex justify-content-between">
                <div>
                    <button type="submit" id="submit-btn"
                            class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
                    <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
{!! JsValidator::formRequest('Modules\Appointment\Http\Requests\BulkAppointmentRequest', '#appointment-bulk-form') !!}

<script>
    $(document).ready(function () {
        $('#appointment-bulk-form #type').prop('disabled', true);
        /** Add booking time is current if new record*/
        $('input#booking-time').val($('input#get-date').val());

        /** Member display */
        @if (!empty($member_display_id))
        $('#appointment-bulk-form #member').prop('disabled', true);
        @endif

        /** Show Service/Course drop down */
        if ($("#appointment-bulk-form #type").val() === "{{ \Modules\Appointment\Model\Appointment::SERVICE_TYPE }}") {
            $('.select-course').hide();
            $('.select-service').show();
        } else {
            $('.select-course').show();
            $('.select-service').hide();
        }

        /** Datetimepicker */
        $('input.date').datetimepicker({
            format: 'dd-mm-yyyy',
            language: $('html').attr('lang'),
            todayHighlight: true,
            todayBtn: true,
            autoclose: true,
            fontAwesome: true,
            startView: 2,
            minView: 2,
            container: '.datetime-modal'
        });

        /** Datetimepicker */
        $('input.time').datetimepicker({
            format: 'hh:ii',
            language: $('html').attr('lang'),
            todayHighlight: true,
            todayBtn: true,
            autoclose: true,
            startView: 1,
            fontAwesome: true,
            container: '.datetime-modal'
        });
    });
</script>
