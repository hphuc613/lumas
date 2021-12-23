<form action="" method="post" id="appointment-form">
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
            {!! Form::select('member_id', $prompt + $members, !empty($member_display_id) ? $member_display_id : $appointment->member_id ?? null, [
                'id' => 'member',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
            @if(isset($appointment))
                <input type="hidden" name="member_id"
                       value="{{ !empty($member_display_id) ? $member_display_id : $appointment->member_id }}">
            @endif
        </div>
        <div class="col-md-6 form-group">
            <label for="status">{{ trans('Status') }}</label>
            {!! Form::select('status', $statuses, $appointment->status ?? null,
            ['id' => 'status', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
        </div>
        <div class="d-none form-group">
            <label for="type">{{ trans('Appointment Type') }}</label>
            {!! Form::select('type', $appointment_types, $appointment->type
                                ?? (!empty($type) ? $type : \Modules\Appointment\Model\Appointment::SERVICE_TYPE),
                ['id'   => 'type',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
            @if(isset($appointment))
                <input type="hidden" name="type" value="{{ $appointment->type }}">
            @endif
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        <div class="col-md-6 form-group">
            <label for="booking-time">{{ trans('Appointment Time') }}</label>
            <input type="text" class="form-control datetime" id="booking-time" name="time"
                   placeholder="d-m-y h:m"
                   value="{{ $appointment->time ?? old('time') }}">
        </div>
        @if(isset($appointment))
            @if(Auth::user()->isAdmin())
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-6 form-group">
                    <label for="booking-time">{{ trans('Check In Time') }}</label>
                    <div class="w-100">{{ $appointment->start_time }} </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="end-time">{{ trans('Check Out Time') }}</label>
                    <input type="text" class="form-control datetime" id="end-time" name="end_time"
                           placeholder="d-m-y h:m"
                           value="{{ $appointment->end_time ?? old('end_time') }}">
                </div>
            @else
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-6 form-group">
                    <label for="booking-time">{{ trans('Check In Time') }}</label>
                    <div class="w-100">{{ $appointment->start_time }} </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="booking-time">{{ trans('Check Out Time') }}</label>
                    <div class="w-100">{{ $appointment->end_time }} </div>
                </div>
            @endif
        @endif
        <div class="col-md-12">
            <hr>
        </div>
        <div class="col-md-6 form-group">
            <label for="store">{{ trans('Store') }}</label>
            {!! Form::select('store_id', $stores, $appointment->store_id ?? null, [
                'id' => 'store',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
            <input type="hidden" name="store_id" value="{{ array_key_first($stores) }}">
        </div>
        <div class="col-md-6 form-group">
            <label for="room">{{ trans('Room') }}</label>
            {!! Form::select('room_id[]', $rooms, isset($appointment) ? $appointment->getRooms() : null, [
                'id' => 'room',
                'class' => 'select2 form-control',
                'multiple' => 'multiple',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="instrument">{{ trans('Instrument') }}</label>
            {!! Form::select('instrument_id[]', $instruments, isset($appointment) ? $appointment->getInstruments() : null, [
                'id' => 'instrument',
                'class' => 'select2 form-control',
                'multiple' => 'multiple',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        <div class="col-md-12 form-group mb-0">
            <label for="assign">{{ trans('Assign') }}</label>
            <div class="row mb-2">
                <div class="col-md-6">
                    {!! Form::select( 'user_id', ['' => trans('Select Staff')] + $users->toArray(), $assign[1]['staff_id'] ?? NULL,
                    ['class'    => 'form-control select2 w-100']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::select( 'assign[1][service]', ['' => trans('Select Service')] + $services, $assign[1]['service_id'] ?? NULL,
                    ['class'    => 'form-control select2 w-100']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-12 form-group">
            @for($i = 2; $i <= 4; $i++)
                <div class="row mb-2">
                    <div class="col-md-6">
                        {!! Form::select( 'assign['.$i.'][staff]', ['' => trans('Select Staff')] + $users->toArray(), $assign[$i]['staff_id'] ?? NULL,
                        ['class'    => 'form-control select2 w-100']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::select( 'assign['.$i.'][service]', ['' => trans('Select Service')] + $services, $assign[$i]['service_id'] ?? NULL,
                        ['class'    => 'form-control select2 w-100']) !!}
                    </div>
                </div>
            @endfor
        </div>
        <div class="col-md-12 form-group">
            <label for="description">{{ trans('Description') }}</label>
            <textarea name="description" id="description" class="form-control"
                      rows="4">{{ $appointment->description ?? null }}</textarea>
        </div>
        <div class="col-md-12 mt-5 d-flex justify-content-between">
            @if(!$route_is_member_product)
                <div>
                    <button type="submit" id="submit-btn"
                            class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
                    <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
                </div>
            @endif
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
