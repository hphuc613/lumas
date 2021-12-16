<div class="row">
    <div class="col-md-6 form-group">
        <label for="name">{{ trans('Subject') }}</label>
        <div class="w-100 text-info"><h4>{{ $appointment->name }}</h4></div>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6 form-group">
        <label for="member">{{ trans('Client') }}</label>
        <h5 class="text-success">
            <a href="{{ route('get.member.update',$appointment->member_id) }}" target="_blank">
                {{ $members[$appointment->member_id] ?? 'N/A'}}
            </a>
        </h5>
    </div>
    <div class="col-md-3 form-group">
        <label for="status">{{ trans('Status') }}</label>
        <div class="w-100">
            <span class="status-box" style="background-color: {{ $appointment->getColorStatus() }}">
                @if($appointment->status === \Modules\Appointment\Model\Appointment::WAITING_STATUS && strtotime($appointment->time) < time())
                    {{ trans('Missing') }}
                @else
                    {{ $statuses[$appointment->status] }}
                @endif
            </span>
        </div>
    </div>
    <div class="d-none form-group">
        <label for="type">{{ trans('Appointment Type') }}</label>
        <div class="w-100">{{ $appointment_types[$appointment->type] }} </div>
    </div>
    <div class="col-md-12">
        <hr>
    </div>
    <div class="col-md-6 form-group">
        <label for="booking-time">{{ trans('Appointment Time') }}</label>
        <h4 class="w-100 text-info">{{ $appointment->time }}</h4>
    </div>
    <div class="col-md-6 form-group"></div>
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
    <div class="col-md-12">
        <hr>
    </div>
    <div class="col-md-6 form-group">
        <label for="store">{{ trans('Store') }}</label>
        <div class="w-100">{{ $stores[$appointment->store_id] ?? 'N/A' }} </div>
    </div>
    <div class="col-md-6 form-group">
        <label for="store">{{ trans('Room') }}</label>
        <div class="w-100">{{ $rooms[$appointment->room_id] ?? 'N/A' }} </div>
    </div>
    <div class="col-md-6 form-group">
        <label for="store">{{ trans('Instrument') }}</label>
        <div class="w-100">{{ $instruments[$appointment->instrument_id] ?? 'N/A' }} </div>
    </div>
    <div class="col-md-12">
        <hr>
    </div>
    <div class="col-md-6 form-group">
        <label for="user-id">{{ trans('Staff') }}</label>
        <div class="w-100">{{ $appointment->user->name ?? "N/A"}} </div>
    </div>
    <div class="col-md-6 form-group">
        <label>{{ trans('Assign More') }}</label>
        <div class="w-100">
            {{ isset($appointment) ? implode($appointment->staffs->pluck('name')->toArray(), ", ") : null }}
        </div>
    </div>
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
                <div class="form-group">
                    <label>{{ trans('Total Intend Time: ') }}</label>
                    <span
                        class="text-danger">{{ $appointment->getTotalIntendTimeService() ?? 0 }}</span> {{ trans(' minutes') }}
                    ({{ trans('End time') }} :
                    <span class="text-danger">
                    {{ formatDate(strtotime($appointment->time) + (($appointment->getTotalIntendTimeService()) * 60), 'H:i') }}
                    </span>)
                </div>
            </div>
            <table class="table table-striped" id="product-list">
                <thead>
                <tr>
                    <th>{{ trans('Service/Course Name') }}</th>
                    <th>{{ trans('Intend Time') }}</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($appointment))
                    @if($appointment->type === \Modules\Appointment\Model\Appointment::SERVICE_TYPE)
                        @foreach($appointment->service_ids as $item)
                            @if(!empty($item))
                                <tr class="pl-2">
                                    <td>
                                        <span class="text-option">{{ $item->name }}</span>
                                    </td>
                                    <td>
                                        <span class="text-option">{{ $item->intend_time . trans(" minutes") }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        @foreach($appointment->course_ids as $item)
                            @if(!empty($item))
                                <tr class="pl-2">
                                    <td>
                                        <span class="text-option">{{ $item->name }}</span>
                                    </td>
                                    <td>
                                        <span class="text-option">{{ $item->intend_time . trans(" minutes")}}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 mt-5 d-flex justify-content-between">
        @if($route_previous !== "get.user.salary")
            @if(!$route_is_member_product)
                <div>
                    <button type="button" id="edit-btn" class="btn btn-main-color mr-2">{{ trans('Edit') }}</button>
                </div>
            @endif
            @if($appointment->checkProgressing() && $route_is_member_product)
                <div class="w-100">
                    <a href="{{ route("get.appointment.check_out", $appointment->member_id) }}"
                       class="btn btn-warning w-100 text-light">
                        {{ trans('Check Out') }}
                    </a>
                </div>
            @else
                <div>
                    <a href="{{ route("get.appointment.check_in", [$appointment->id, $appointment->member_id]) }}"
                       class="btn btn-outline-info">
                        {{ trans('Check In') }}
                    </a>
                    <a href="{{ route("get.appointment.delete", $appointment->id) }}"
                       class="btn btn-danger btn-delete">{{ trans('Delete') }}</a>
                </div>
            @endif
        @endif
    </div>
</div>
