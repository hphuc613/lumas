<div class="card">
    <div class="card-header">
        <h5>{{ trans('History') }}
            @if(isset($member_service))
                {{ trans('of') }} <span class="text-info" style="font-size: inherit;">{{ $member_service->code }}</span>
            @endif
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('get.member_service.add', $member->id) }}" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="search-service-" name="code_history"
                       placeholder="{{ trans("Search Service") }}">
                <div class="input-group-prepend">
                    <button class="btn btn-primary"
                            type="submit">{{ trans("Search") }}</button>
                </div>
            </div>
        </form>
        <div class="sumary">
            <span class="listing-information">
                {{ trans('Showing') }}
                <b>
                    {{($histories->currentpage()-1)*$histories->perpage()+1}}
                    {{ trans('to') }}
                    {{($histories->currentpage()-1) * $histories->perpage() + $histories->count()}}
                </b>
                {{ trans('of') }}
                <b>{{$histories->total()}}</b> {{ trans('entries') }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="50px">#</th>
                    <th>{{ trans('Code order') }}</th>
                    <th>{{ trans('Signature') }}</th>
                    <th>{{ trans('Service') }}
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
                                {{$value->code}}
                            </a>
                        </td>
                        <td>{{ $history->signature }}</td>
                        <td>{{ $history->memberService->service->name }}</td>
                        <td>{{ $history->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i:s')}}</td>
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