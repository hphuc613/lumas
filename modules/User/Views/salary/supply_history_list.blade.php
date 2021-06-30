<div class="pjax-table">
    <div class="sumary">
        {!! summaryListing($histories) !!}
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="50px">#</th>
                <th>{{ trans('Signature') }}</th>
                <th>{{ trans('Appointment') }}</th>
                <th>{{ trans('Service') }}</th>
                <th>{{ trans('Price of Service') }}</th>
                <th>{{ trans('Start At') }}</th>
                <th>{{ trans('End At') }}</th>
            </tr>
            </thead>
            <tbody>
            @php($key = ($histories->currentpage()-1)*$histories->perpage()+1)
            @foreach($histories as $history)
                <tr>
                    <td>{{$key++}}</td>
                    <td>{{ $history->signature }}</td>
                    <td><a href="{{ route("get.appointment.update",$history->appointment->id) }}"
                           id="update-booking" data-toggle="modal"
                           data-target="#salary-modal"
                           data-title="{{ trans('View Appointment') }}">{{ $history->appointment->name }}</a></td>
                    <td>{{ $history->memberService->service->name }}</td>
                    <td>{{ moneyFormat($history->memberService->price) }}</td>
                    <td>{{ formatDate(strtotime($history->start), 'd/m/Y H:i:s')}}</td>
                    <td>{{ formatDate(strtotime($history->end), 'd/m/Y H:i:s')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-5 pagination-style">
            {{ $histories->render('vendor.pagination.default') }}
        </div>
    </div>
</div>
