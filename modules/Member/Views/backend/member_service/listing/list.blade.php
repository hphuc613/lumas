<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h5>{{ trans('Treatment Information') }}</h5>
            <div class="group-btn">
                <a href="{{ route("get.export.treatment_client",$member->id) }}"
                   class="btn btn-info">{{ trans('Export') }}</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            @if(!isset($filter['code_completed']) && !isset($filter['service_search_completed']))
                <li class="nav-item">
                    <a class="nav-link active" id="service-doing-tab" data-toggle="pill"
                       href="#service-doing-section"
                       aria-selected="true">{{ trans("Available") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="service-completed-tab" data-toggle="pill"
                       href="#service-completed-section"
                       aria-selected="false">{{ trans("Completed") }}</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" id="service-doing-tab" data-toggle="pill" href="#service-doing-section"
                       aria-selected="true">{{ trans("Available") }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="service-completed-tab" data-toggle="pill"
                       href="#service-completed-section"
                       aria-selected="false">{{ trans("Completed") }}</a>
                </li>
            @endif
        </ul>
        @php($route_form_search = !isset($member_service) ? route('get.member_service.add', $member->id) : route('get.member_service.view', $member_service->id))
        <div class="tab-content">
            <div class="tab-pane fade
                     @if(!isset($filter['code_completed']) && !isset($filter['service_search_completed'])) show active @endif"
                 id="service-doing-section">
                <div class="service-doing">
                    <form action="{{ $route_form_search }}" method="get" class="mb-3">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" name="code"
                                       placeholder="{{ trans("Search Code") }}">
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::select('service_search', [null => trans('Search Service')] + $search_services, null, [
                                    'class' => 'select2 form-control',
                                    'style' => 'width: 100%']) !!}
                            </div>
                            <div class="form-group col-md-4">
                                <button class="btn btn-main-color"
                                        type="submit">{{ trans("Search") }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <div class="sumary">
                            {!! summaryListing($member_services) !!}
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('Code') }}</th>
                                <th>{{ trans('Service') }}</th>
                                <th>{{ trans('Voucher') }}</th>
                                <th class="text-center">{{ trans('Remaining') }}</th>
                                <th class="text-center">{{ trans('Quantity') }}</th>
                                <th>{{ trans('Price') }}</th>
                                <th>{{ trans('Total Price') }}</th>
                                <th>{{ trans('Created At') }}</th>
                                <th style="width: 14%" class="text-center">{{ trans('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = ($member_services->currentpage()-1)*$member_services->perpage()+1)
                            @foreach($member_services as $value)
                                @if($value->status === \Modules\Member\Model\MemberService::COMPLETED_STATUS)
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
                                               href="{{ route('get.service.update', $value->service_id) }}">
                                                {{ ($value->service->name  ?? "N/A") }}
                                                <br> {{ ($value->service->price  ?? "N/A") }}
                                            </a>
                                        </td>
                                        <td>
                                            @if(!empty($value->voucher))
                                                <a target="_blank"
                                                   href="{{ route('get.service_voucher.update', $value->voucher_id) }}">
                                                    {{ $value->voucher->code ?? "N/A" }}
                                                    <br> {{ ($value->voucher->price  ?? "N/A") }}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $value->getRemaining() }}</td>
                                        <td>{{ $value->quantity }}</td>
                                        <td>{{ moneyFormat($value->price)." (-".$value->discount."%)" }}</td>
                                        <td>{{ moneyFormat($value->price * $value->quantity, 0) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                                        <td class="text-center">
                                            <a href="{{ route('get.member_service.view',$value->id) }}"
                                               class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i></a>
                                            <a href="{{ route('get.member_service.delete',$value->id) }}"
                                               class="btn btn-danger btn-delete">
                                                <i class="fas fa-trash-alt"></i></a>
                                            <a href="{{ route('get.member_service.into_progress',$value->id) }}"
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
                            {{ $member_services->withQueryString()->render('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade
                    @if(isset($filter['code_completed']) || isset($filter['service_search_completed'])) show active @endif"
                 id="service-completed-section">
                <div class="service-completed">
                    <form action="{{ $route_form_search }}" method="get" class="mb-3">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <input type="text" class="form-control" name="code_completed"
                                       placeholder="{{ trans("Search Code") }}">
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::select('service_search_completed', [null => trans('Search Service')] + $search_completed_services, null, [
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
                        {!! summaryListing($completed_member_services) !!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50px">#</th>
                                <th>{{ trans('Code') }}</th>
                                <th>{{ trans('Service') }}</th>
                                <th>{{ trans('Voucher') }}</th>
                                <th class="text-center">{{ trans('Remaining') }}</th>
                                <th class="text-center">{{ trans('Quantity') }}</th>
                                <th>{{ trans('Price') }}</th>
                                <th>{{ trans('Total Price') }}</th>
                                <th>{{ trans('Created At') }}</th>
                                <th style="width: 14%" class="text-center">{{ trans('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = ($completed_member_services->currentpage()-1)*$completed_member_services->perpage()+1)
                            @foreach($completed_member_services as $value)
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
                                           href="{{ route('get.service.update', $value->service_id) }}">
                                            {{ ($value->service->name  ?? "N/A") }}
                                            <br>  {{ ($value->service->price  ?? "N/A") }}
                                        </a>
                                    </td>
                                    <td>
                                        @if(!empty($value->voucher))
                                            <a target="_blank"
                                               href="{{ route('get.service_voucher.update', $value->voucher_id) }}">
                                                {{ $value->voucher->code ?? "N/A" }}
                                                <br> {{ ($value->voucher->price  ?? "N/A") }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $value->getRemaining() }}</td>
                                    <td>{{ $value->quantity }}</td>
                                    <td>{{ moneyFormat($value->price)." (-".$value->discount."%)" }}</td>
                                    <td>{{ moneyFormat($value->price * $value->quantity, 0) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="text-center">
                                        <a href="{{ route('get.member_service.view',$value->id) }}"
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5 pagination-style">
                            {{ $completed_member_services->withQueryString()->render('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
