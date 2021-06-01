<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    @if(isset($filter['code_completed']))
        <li class="nav-item">
            <a class="nav-link" id="service-doing-tab" data-toggle="pill" href="#service-doing-section"
               aria-selected="true">{{ trans("Services") }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="service-completed-tab" data-toggle="pill" href="#service-completed-section"
               aria-selected="false">{{ trans("Completed") }}</a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link active" id="service-doing-tab" data-toggle="pill" href="#service-doing-section"
               aria-selected="true">{{ trans("Services") }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="service-completed-tab" data-toggle="pill" href="#service-completed-section"
               aria-selected="false">{{ trans("Completed") }}</a>
        </li>
    @endif
</ul>
@php($route_form_search = !isset($member_service) ? route('get.member_service.add', $member->id) : route('get.member_service.edit', $member_service->id))
<div class="tab-content">
    <div class="tab-pane fade @if(!isset($filter['code_completed'])) show active @endif" id="service-doing-section">
        <div class="service-doing">
            <form action="{{ $route_form_search }}" method="get" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" id="search-service" name="code"
                           placeholder="{{ trans("Search Service") }}">
                    <div class="input-group-prepend">
                        <button class="btn btn-primary"
                                type="submit">{{ trans("Search") }}</button>
                    </div>
                </div>
            </form>
            <div class="sumary"
            {!! summaryListing($member_services) !!}
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="50px">#</th>
                    <th>{{ trans('Code') }}</th>
                    <th>{{ trans('Service') }}</th>
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
                @php($key = ($member_services->currentpage()-1)*$member_services->perpage()+1)
                @foreach($member_services as $value)
                    <?php
                    $total_price = !empty($value->voucher)
                        ? $value->voucher->price * $value->quantity
                        : $value->service->price * $value->quantity;
                    ?>
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
                                {{ $value->service->name }}
                            </a>
                        </td>
                        <td>
                            @if(!empty($value->voucher))
                                <a target="_blank"
                                   href="{{ route('get.voucher.update', $value->voucher_id) }}">
                                    {{ $value->voucher->code }}
                                </a>
                            @endif
                        </td>
                        <td class="text-center">{{ $value->getRemaining() }}</td>
                        <td>{{ $value->quantity }}</td>
                        <td>{{ $value->voucher->price ?? $value->service->price }}</td>
                        <td>{{ $total_price }}</td>
                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                        <td class="link-action">
                            <a href="{{ route('get.member_service.edit',$value->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-pencil-alt"></i></a>
                            <a href="{{ route('get.member_service.delete',$value->id) }}"
                               class="btn btn-danger btn-delete"><i
                                    class="fas fa-trash-alt"></i></a>
                            <a href="{{ route('get.member_service.e_sign',$value->id) }}"
                               class="btn btn-info" data-toggle="modal" data-target="#form-modal"
                               data-title="{{ trans('E-sign') }}">
                                <i class="fas fa-file-signature"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-5 pagination-style">
                {{ $member_services->render('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</div>
<div class="tab-pane fade @if(isset($filter['code_completed'])) show active @endif" id="service-completed-section">
    <div class="service-completed">
        <form action="{{ $route_form_search }}" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="search-service" name="code_completed"
                       placeholder="{{ trans("Search Service") }}">
                <div class="input-group-prepend">
                    <button class="btn btn-primary"
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
                    <th class="text-center">{{ trans('Remaining') }}
                    <th class="text-center">{{ trans('Quantity') }}
                    <th>{{ trans('Price') }}</th>
                    <th>{{ trans('Total Price') }}</th>
                    <th>{{ trans('Created At') }}</th>
                    <th width="200px" class="action text-center">{{ trans('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @php($key = ($completed_member_services->currentpage()-1)*$completed_member_services->perpage()+1)
                @foreach($completed_member_services as $value)
                    <?php
                    $total_price = !empty($value->voucher)
                        ? $value->voucher->price * $value->quantity
                        : $value->service->price * $value->quantity;
                    ?>
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
                                {{ $value->service->name }}
                            </a>
                        </td>
                        <td>
                            @if(!empty($value->voucher))
                                <a target="_blank"
                                   href="{{ route('get.voucher.update', $value->voucher_id) }}">
                                    {{ $value->voucher->code }}
                                </a>
                            @endif
                        </td>
                        <td class="text-center">{{ $value->getRemaining() }}</td>
                        <td>{{ $value->quantity }}</td>
                        <td>{{ $value->voucher->price ?? $value->service->price }}</td>
                        <td>{{ $total_price }}</td>
                        <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                        <td class="link-action text-center">
                            <a href="{{ route('get.member_service.edit',$value->id) }}"
                               class="btn btn-primary">
                                <i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-5 pagination-style">
                {{ $member_services->render('vendor.pagination.default') }}
            </div>
        </div>
    </div>
</div>
</div>
