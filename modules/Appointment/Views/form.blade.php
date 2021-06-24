<form action="" method="post" id="appointment-form">
    @csrf
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="name">{{ trans('Subject') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ $appointment->name ?? old('name') }}">
        </div>
        <div class="col-md-3 form-group">
            <label for="status">{{ trans('Status') }}</label>
            {!! Form::select('status', $statuses, $appointment->status ?? null,
            ['id' => 'status', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-3 form-group">
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
            <label for="booking-time">{{ trans('Time') }}</label>
            <input type="text" class="form-control datetime" id="booking-time" name="time"
                   placeholder="d-m-y h:m"
                   value="{{ $appointment->time ?? old('time') }}">
        </div>
        @if(Auth::user()->isAdmin() && isset($appointment))
            <div class="col-md-6 form-group">
                <label for="end-time">{{ trans('End Time') }}</label>
                <input type="text" class="form-control datetime" id="end-time" name="end_time"
                       placeholder="d-m-y h:m"
                       value="{{ $appointment->end_time ?? old('end_time') }}">
            </div>
        @endif
        <div class="col-md-12">
            <hr>
        </div>
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
            <label for="store">{{ trans('Store') }}</label>
            {!! Form::select('store_id', $prompt + $stores, $appointment->store_id ?? null, [
                'id' => 'store',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        @if(Auth::user()->isAdmin() || Auth::user()->getRoleAttribute()->name === 'Manager')
            <div class="col-md-6 form-group">
                <label for="user-id">{{ trans('Staff') }}</label>
                {!! Form::select('user_id', $users, $appointment->user_id ?? null,
                ['id' => 'user-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
            </div>
            <div class="col-md-12">
                <hr>
            </div>
        @endif
        <div class="col-md-12 form-group">
            <label for="description">{{ trans('Description') }}</label>
            <textarea name="description" id="description" class="form-control"
                      rows="4">{{ $appointment->description ?? null }}</textarea>
        </div>
        <div class="col-md-12">
            <div class="row p-2">
                <div class="col-md-6">
                    <h4>{{ trans('Service Listing') }}</h4>
                </div>
                <div class="col-md-6">
                    <div class="select-course w-100">
                        {!! Form::select('course_ids', [null => trans("Select Course")] + $courses, null, [
                        'id' => 'course-select',
                        'class' => 'select2 form-control select-product',
                        'style' => 'width: 100%']) !!}
                    </div>
                    <div class="select-service w-100">
                        {!! Form::select('service_ids', [null => trans("Select Service")] + $services, null, [
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
