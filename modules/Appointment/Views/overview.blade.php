@extends("Base::layouts.master")

@section("content")
    <div id="member-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Appointment") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Overview") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Appointment Overview") }}</h3></div>
            <div class=""><a href="{{ route('get.appointment.list') }}"
                             class="btn btn-main-color">{{ trans("Calendar") }}</a></div>
        </div>
    </div>
    <!--Search box-->
    <div class="search-box">
        <div class="card">
            <div class="card-header" data-toggle="collapse" data-target="#form-search-box" aria-expanded="false"
                 aria-controls="form-search-box">
                <div class="title">{{ trans("Search") }}</div>
            </div>
            <div class="card-body collapse show" id="form-search-box">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="name-search">{{ trans("Subject") }}</label>
                            <input type="text" class="form-control" id="name-search" name="name"
                                   value="{{ $filter['name'] ?? null }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="member-id">{{ trans('Client') }}</label>
                            {{ Form::select('member_id', [null => 'Select'] + $members, $filter['member_id'] ?? null,
                                             ['id' => 'member-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) }}
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="appointment-type">{{ trans('Type') }}</label>
                            {{ Form::select('type', [null => 'Select'] + $appointment_types, $filter['type'] ?? null,
                                             ['id' => 'appointment-type', 'class' => 'select2 form-control', 'style' => 'width: 100%']) }}
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="status-search">{{ trans('Status') }}</label>
                            {{ Form::select('status', [null => 'Select'] + $statuses, $filter['status'] ?? null,
                                             ['id' => 'status-search', 'class' => 'select2 form-control', 'style' => 'width: 100%']) }}
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="status">{{ trans('Month') }}</label>
                            <input type="text" name="month" class="form-control month"
                                   value="{{ $filter['month'] ?? null }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="store-id">{{ trans('Store') }}</label>
                            {{ Form::select('store_id', [null => 'Select'] + $stores, $filter['store_id'] ?? null,
                                             ['id' => 'store-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) }}
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-main-color mr-2">{{ trans("Search") }}</button>
                        <button type="button" class="btn btn-default clear">{{ trans("Cancel") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="listing">
        <div class="card">
            <div class="card-body">
                <div class="sumary">
                    {!! summaryListing($appointments) !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans('Subject') }}</th>
                            <th>{{ trans('Type') }}</th>
                            <th style="width: 10%">{{ trans('Client') }}</th>
                            <th style="width: 10%">{{ trans('Appointment Time') }}</th>
                            <th>{{ trans('Check In') }}</th>
                            <th>{{ trans('Check Out') }}</th>
                            <th>{{ trans('Status') }}</th>
                            <th>{{ trans('Store') }}</th>
                            <th>{{ trans('Staff') }}</th>
                            <th style="width: 7%">{{ trans('Intend Time') }}</th>
                            <th class="text-center">{{ trans('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($key = ($appointments->currentpage()-1)*$appointments->perpage()+1)
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $appointment->name }}</td>
                                <td class="text-capitalize"><h6>{{ $appointment->type }}</h6></td>
                                <td>
                                    <a href="{{ route('get.member.update',$appointment->member_id) }}" target="_blank">
                                        {{ $members[$appointment->member_id] }}
                                    </a>
                                </td>
                                <td>{{ formatDate($appointment->time, 'd-m-Y H:i:s') }}</td>
                                <td>{{ !empty($appointment->start_time) ? formatDate($appointment->start_time, 'd-m-Y H:i:s') : ''}}</td>
                                <td>{{ !empty($appointment->start_time) ? formatDate($appointment->end_time, 'd-m-Y H:i:s') : ''}}</td>
                                <td>
                                    <span style="font-weight:500; color: {{ $appointment->getColorStatus() }}">
                                        {{ $statuses[$appointment->status] }}
                                    </span>
                                </td>
                                <td>{{ $appointment->store->name ?? "N/A" }}</td>
                                <td>{{ $appointment->user->name ?? "N/A" }}</td>
                                <td>{{ ($appointment->type === \Modules\Appointment\Model\Appointment::SERVICE_TYPE) ? $appointment->getTotalIntendTimeService() . trans(' minutes') : NULL }}</td>
                                <td class="text-center">
                                    <a href="{{ route("get.appointment.update",$appointment->id) }}"
                                       class="btn btn-main-color"
                                       id="update-booking" data-toggle="modal"
                                       data-target="#form-modal" data-title="{{ trans('Update Appointment') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $appointments->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg']) !!}
@endsection
