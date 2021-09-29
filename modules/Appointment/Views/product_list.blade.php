<div class="table-responsive">
    <div>
        @php($title    = (Auth::user()->isAdmin())
                ? ($appointment->member->name ?? "N/A") . ' | ' . ($appointment->user->name ?? "N/A")
                : ($appointment->member->name ?? "N/A") . ' | ' . $appointment->name)
        <h5>{{ $title }}</h5>
        <div class="form-group">
            <label>{{ trans('Total Intend Time: ') }}</label>
            <span
                class="text-danger">{{ $appointment->getTotalIntendTimeService() ?? 0 }}</span> {{ trans(' minutes') }}
        </div>
    </div>
    <table class="table table-striped" id="product-list">
        <thead>
        <tr>
            <th>{{ trans('Service') }}</th>
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
