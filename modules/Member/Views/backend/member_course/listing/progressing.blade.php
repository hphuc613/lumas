@if($progressing_courses->isNotEmpty())
    <div class="card mb-2">
        <div class="card-header">
            <h4>Course Progressing</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="50px">#</th>
                        <th>{{ trans('Code') }}</th>
                        <th>{{ trans('Course') }}</th>
                        <th>{{ trans('Voucher') }}</th>
                        <th class="text-center">{{ trans('Remaining') }}
                        <th class="text-center">{{ trans('Quantity') }}
                        <th>{{ trans('Price') }}</th>
                        <th>{{ trans('Total Price') }}</th>
                        <th>{{ trans('Created At') }}</th>
                        <th width="200px" class="action text-center">{{ trans('Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($key = 1)
                    @foreach($progressing_courses as $value)
                        @if($value->status === \Modules\Member\Model\MemberCourse::PROGRESSING_STATUS)
                            @php($total_price = !empty($value->voucher)
                                    ? $value->voucher->price * $value->quantity
                                    : $value->course->price * $value->quantity)
                            <tr>
                                <td>{{$key++}}</td>
                                <td>
                                    <a href="javascript:" class="tooltip-content"
                                       data-tooltip="{{ generateQRCode($value->code)}}" title="">
                                        {{$value->code}}
                                    </a>
                                </td>
                                <td>
                                    <a target="_blank"
                                       href="{{ route('get.course.update', $value->course_id) }}">
                                        {{ $value->course->name }}
                                    </a>
                                </td>
                                <td>
                                    @if(!empty($value->voucher))
                                        <a target="_blank"
                                           href="{{ route('get.course_voucher.update', $value->voucher_id) }}">
                                            {{ $value->voucher->code }}
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">{{ $value->getRemaining() }}</td>
                                <td>{{ $value->quantity }}</td>
                                <td>{{ $value->voucher->price ?? $value->course->price }}</td>
                                <td>{{ $total_price }}</td>
                                <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="link-action">
                                    <a href="{{ route('get.member_course.view',$value->id) }}"
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('get.member_course.out_progress',$value->id) }}"
                                       class="btn btn-danger">
                                        <i class="fas fa-feather-alt"></i>
                                    </a>
                                    <a href="{{ route('get.member_course.e_sign',$value->id) }}"
                                       class="btn btn-info" data-toggle="modal" data-target="#form-modal"
                                       data-title="{{ trans('E-sign') }}">
                                        <i class="fas fa-file-signature"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
