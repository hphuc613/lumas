<div class="card">
    <div class="card-header">
        <h5>{{ trans('Course Listing') }}</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            @if(!isset($filter['code_completed']) && !isset($filter['course_search_completed']))
                <li class="nav-item">
                    <a class="nav-link active" id="doing-tab" data-toggle="pill" href="#doing-section"
                       aria-selected="true">{{ trans("Course") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="completed-tab" data-toggle="pill" href="#completed-section"
                       aria-selected="false">{{ trans("Completed") }}</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" id="doing-tab" data-toggle="pill" href="#doing-section"
                       aria-selected="true">{{ trans("Course") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="completed-tab" data-toggle="pill" href="#completed-section"
                       aria-selected="false">{{ trans("Completed") }}</a>
                </li>
            @endif
        </ul>
        @php($route_form_search = !isset($member_course) ? route('get.member_course.add', $member->id) : route('get.member_course.edit', $member_course->id))
        <div class="tab-content">
            <div class="tab-pane fade
        @if(!isset($filter['code_completed']) && !isset($filter['course_search_completed'])) show active @endif"
                 id="doing-section">
                <div class="doing">
                    <form action="{{ $route_form_search }}" method="get" class="mb-3">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" name="code"
                                       placeholder="{{ trans("Search Code") }}">
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::select('course_search', [null => trans('Search Course')] + $search_courses, null, [
                                    'class' => 'select2 form-control',
                                    'style' => 'width: 100%']) !!}
                            </div>
                            <div class="form-group col-md-4">
                                <button class="btn btn-main-color"
                                        type="submit">{{ trans("Search") }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="sumary">
                        {!! summaryListing($member_courses) !!}
                    </div>
                </div>

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
                        <tbody>
                        @php($key = ($member_courses->currentpage()-1)*$member_courses->perpage()+1)
                        @foreach($member_courses as $value)
                            @if($value->status === \Modules\Member\Model\MemberCourse::COMPLETED_STATUS)
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
                                    <td>{{ $value->price }}</td>
                                    <td>{{ $value->price * $value->quantity }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="link-action">
                                        <a href="{{ route('get.member_course.edit',$value->id) }}"
                                           class="btn btn-main-color">
                                            <i class="fas fa-pencil-alt"></i></a>
                                        <a href="{{ route('get.member_course.delete',$value->id) }}"
                                           class="btn btn-danger btn-delete">
                                            <i class="fas fa-trash-alt"></i></a>
                                        <a href="{{ route('get.member_course.into_progress',$value->id) }}"
                                           class="btn btn-info">
                                            <i class="fas fa-feather-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $member_courses->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
            <div
                    class="tab-pane fade @if(isset($filter['code_completed']) || isset($filter['course_search_completed'])) show active @endif"
                    id="completed-section">
                <div class="completed">
                    <form action="{{ $route_form_search }}" method="get" class="mb-3">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" name="code_completed"
                                       placeholder="{{ trans("Search Code") }}">
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::select('course_search_completed', [null => trans('Search Course')] + $search_completed_courses, null, [
                                    'class' => 'select2 form-control',
                                    'style' => 'width: 100%']) !!}
                            </div>
                            <div class="form-group col-md-4">
                                <button class="btn btn-main-color"
                                        type="submit">{{ trans("Search") }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="sumary">
                        {!! summaryListing($completed_member_courses) !!}
                    </div>
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
                            @php($key = ($completed_member_courses->currentpage()-1)*$completed_member_courses->perpage()+1)
                            @foreach($completed_member_courses as $value)
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
                                    <td>{{ $value->price }}</td>
                                    <td>{{ $value->price*$value->quantity }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="link-action text-center">
                                        <a href="{{ route('get.member_course.edit',$value->id) }}"
                                           class="btn btn-main-color">
                                            <i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5 pagination-style">
                            {{ $completed_member_courses->render('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

