<section id="section-appointment">
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ trans('Appointment Statistics') }}</h5>
                @php($appointment_all = (count($appointment_data['all']) == 0) ? 1 : count($appointment_data['all']))
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('get.appointment.overview') }}" class="appointment-hover">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h3><i class="icon-screen-desktop"></i></h3>
                                                    <p class="text-uppercase">{{ trans('Total') }}</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-primary">{{ count($appointment_data['all']) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                                <div class="progress-bar tooltip-content bg-primary"
                                                     title=""
                                                     style="width: {{ count($appointment_data['all'])/$appointment_all*100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('get.appointment.overview', ['status' => \Modules\Appointment\Model\Appointment::PROGRESSING_STATUS]) }}"
                           class="appointment-hover">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h3><i class="icon-screen-desktop"></i></h3>
                                                    <p class="text-uppercase">{{ trans('Progressing') }}</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-warning">{{ count($appointment_data['progressing']) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                                <div class="progress-bar tooltip-content bg-warning"
                                                     title=""
                                                     style="width: {{ count($appointment_data['progressing'])/$appointment_all*100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('get.appointment.overview', ['status' => \Modules\Appointment\Model\Appointment::WAITING_STATUS]) }}"
                           class="appointment-hover">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h3><i class="icon-screen-desktop"></i></h3>
                                                    <p class="text-uppercase">{{ trans('Waiting') }}</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-info">{{ count($appointment_data['waiting']) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                                <div class="progress-bar tooltip-content bg-info"
                                                     title=""
                                                     style="width: {{ count($appointment_data['waiting'])/$appointment_all*100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('get.appointment.overview', ['status' => \Modules\Appointment\Model\Appointment::COMPLETED_STATUS]) }}"
                           class="appointment-hover">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h3><i class="icon-screen-desktop"></i></h3>
                                                    <p class="text-uppercase">{{ trans('Completed') }}</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-success">{{ count($appointment_data['completed']) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                                <div class="progress-bar tooltip-content bg-success"
                                                     title=""
                                                     style="width: {{ count($appointment_data['completed'])/$appointment_all*100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('get.appointment.overview', ['status' => \Modules\Appointment\Model\Appointment::ABORT_STATUS]) }}"
                           class="appointment-hover">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex no-block align-items-center">
                                                <div>
                                                    <h3><i class="icon-screen-desktop"></i></h3>
                                                    <p class="text-uppercase">{{ trans('Abort') }}</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-danger">{{ count($appointment_data['abort']) }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                                <div class="progress-bar tooltip-content bg-danger"
                                                     title=""
                                                     style="width: {{ count($appointment_data['abort'])/$appointment_all*100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><span
                            class="text-primary font-size-clearfix">{{ count($appointment_data['progressing']) }}</span> {{ trans('Appointment(s) In Progress (Today)') }}
                </h5>
                <div class="table-responsive">
                    <table class="table table-fixed progressing-appointment">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 10%">#</th>
                            <th>{{ trans('Appointment') }}</th>
                            <th>{{ trans('Type') }}</th>
                            <th>{{ trans('Client') }}</th>
                            <th>{{ trans('Staff') }}</th>
                            <th>{{ trans('Check In Time') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($appointment_data['progressing'] as $key => $appointment)
                            @if(formatDate(strtotime($appointment->created_at)) === formatDate(time()))
                                <tr>
                                    <td class="text-center" style="width: 10%">{{ $key+1 }}</td>
                                    @if($appointment->type == \Modules\Appointment\Model\Appointment::SERVICE_TYPE)
                                        <td>
                                            <a href="{{ route('get.member_service.add', $appointment->member_id) }}">{{ $appointment->name }}</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="{{ route('get.member_course.add', $appointment->member_id) }}">{{ $appointment->name }}</a>
                                        </td>
                                    @endif
                                    <td class="text-capitalize">{{ $appointment->type }}</td>
                                    <td class="text-capitalize">
                                        <a href="{{ route('get.member.update', $appointment->member_id) }}">{{ $appointment->member->name }}</a>
                                    </td>
                                    <td class="text-capitalize">
                                        <a href="{{ route('get.user.update', $appointment->user_id) }}">{{ $appointment->user->name }}</a>
                                    </td>
                                    <td>{{ formatDate(strtotime($appointment->start_time), 'H:i:s') }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
