<div class="card">
    <div class="card-header">
        <h5>{{ trans('History') }}
            @if(isset($member_service))
                {{ trans('of') }} <span class="text-info" style="font-size: inherit;">{{ $member_service->code }}</span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        @php($route_form_search = !isset($member_service) ? route('get.member_service.add', $member->id) : route('get.member_service.view', $member_service->id))
        <form action="{{ $route_form_search }}" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="search-service-" name="code_history"
                       placeholder="{{ trans("Search Service") }}">
                <div class="input-group-prepend">
                    <button class="btn btn-main-color"
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
                    <th>{{ trans('Service') }}</th>
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
                               data-tooltip="{{ generateQRCode($history->memberService->code)}}" title="">
                                {{$history->memberService->code}}
                            </a>
                        </td>
                        <td>
                            @if(file_exists(public_path('storage/'.$history->signature)))
                                <a href="javascript:" class="tooltip-content"
                                   data-tooltip="<img src='{{ asset('storage/'.$history->signature) }}'
                                   alt='{{ $history->memberService->member->username_signature }}' width='280'>"
                                   title="">
                                    {{ trans('Signature Image') }}
                                </a>
                            @else
                                {{ $history->signature }}
                            @endif
                        </td>
                        <td>
                            @if(empty($history->appointment))
                                {{ trans('This appointment was deleted') }}
                            @else
                                <a href="{{ route("get.appointment.update",$history->appointment->id) }}"
                                   id="update-booking" data-toggle="modal"
                                   data-target="#form-modal"
                                   data-title="{{ trans('View Appointment') }}">{{ $history->appointment->name ?? "N/A" }}</a>
                            @endif
                        </td>
                        <td>{{ $history->memberService->service->name ?? "N/A" }}</td>
                        <td>{{ formatDate(strtotime($history->start), 'd/m/Y H:i:s')}}</td>
                        <td>{{ formatDate(strtotime($history->end), 'd/m/Y H:i:s')}}</td>
                        <td>{{ $history->user->name ?? "N/A" }}</td>
                        <td>{{ formatDate(strtotime($history->created_at), 'd/m/Y H:i:s')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-5 pagination-style">
                {{ $histories->withQueryString()->render('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</div>
