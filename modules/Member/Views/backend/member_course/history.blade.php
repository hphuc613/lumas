<div class="card">
    <div class="card-header">
        <h5>{{ trans('History') }}
            @if(isset($member_course))
                {{ trans('of') }} <span class="text-info" style="font-size: inherit;">{{ $member_course->code }}</span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @php($route_form_search = !isset($member_course) ? route('get.member_course.add', $member->id) : route('get.member_course.edit', $member_course->id))
        <form action="{{ $route_form_search }}" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="search-course" name="code_history"
                       placeholder="{{ trans("Search Course") }}">
                <div class="input-group-prepend">
                    <button class="btn btn-primary"
                            type="submit">{{ trans("Search") }}</button>
                </div>
            </div>
        </form>
        <div class="sumary">
            {!! summaryListing($histories) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="50px">#</th>
                    <th>{{ trans('Code') }}</th>
                    <th>{{ trans('Signature') }}</th>
                    <th>{{ trans('Appointment') }}</th>
                    <th>{{ trans('Course') }}</th>
                    <th>{{ trans('Start At') }}</th>
                    <th>{{ trans('End At') }}</th>
                    <th>{{ trans('Updated By') }}</th>
                    <th>{{ trans('Created At') }}</th>
                </tr>
                </thead>
                <tbody>
                @php($key = ($histories->currentpage()-1)*$histories->perpage()+1)
                @foreach($histories as $history)
                    <tr>
                        <td>{{$key++}}</td>
                        <td>
                            <a href="javascript:" class="tooltip-content"
                               data-tooltip="{{ generateQRCode($history->memberCourse->code)}}" title="">
                                {{$history->memberCourse->code}}
                            </a>
                        </td>
                        <td>{{ $history->signature }}</td>
                        <td><a href="{{ route("get.appointment.update",$history->appointment->id) }}"
                               id="update-booking" data-toggle="modal"
                               data-target="#form-modal"
                               data-title="{{ trans('View Appointment') }}">{{ $history->appointment->name }}</a></td>
                        <td>{{ $history->memberCourse->course->name }}</td>
                        <td>{{ formatDate(strtotime($history->start), 'd/m/Y H:i:s')}}</td>
                        <td>{{ formatDate(strtotime($history->end), 'd/m/Y H:i:s')}}</td>
                        <td>{{ $history->user->name }}</td>
                        <td>{{ formatDate(strtotime($history->created_at), 'd/m/Y H:i:s')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-5 pagination-style">
                {{ $histories->render('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</div>
