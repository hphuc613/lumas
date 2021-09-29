@extends('Base::layouts.master')
@php
    $previous_page = request()->previous_page
@endphp
@section('content')
    <div id="salary-section">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Salary') }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title">
                <h3>{{ trans('Salary') }}</h3>
            </div>
            <div>
                <a href="{{ route('get.salary.single_reload', $user->id) }}"
                   class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <a href="{{ route('get.salary.update', $user->id) }}"
                   class="btn btn-main-color"
                   data-toggle="modal" data-title="{{ trans('Update Basic Salary') }}"
                   data-target="#form-modal">
                    {{ trans('Update Basic Salary') }}
                </a>
                <a href="{{ route('get.user.list') }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>
    <div id="salary" class="card">
        <div class="card-body">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab"
                       @if($previous_page === 'profile')
                       href="{{ route('get.profile.update') }}">{{ trans('Profile') }}
                        @else
                            href="{{ route('get.user.update', $user->id) }}">{{ trans('Update User') }}
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="salary-tab" href="javascript:"
                       data-toggle="tab">{{ trans('Salary') }}</a>
                </li>
            </ul>
            <div class="tab-content p-4">
                <div class="tab-pane fade show active" id="salary">
                    <div class="w-100">
                        <button class="btn btn-primary float-right" id="print-salary"><i class="fas fa-print"></i>
                        </button>
                    </div>
                    <div id="salary-info" class="mb-3">
                        <h4 class="mb-5">{{ trans('Salary Information') }}</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="">{{ trans('Staff Name') }}:</label>
                                    </div>
                                    <div class="col-6">
                                        {{ $user->name }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="">{{ trans('Role') }}:</label>
                                    </div>
                                    <div class="col-6">
                                        {{ $user->getRoleAttribute()->name }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="">{{ trans('Month') }}:</label>
                                    </div>
                                    <div class="col-6">
                                        {{ $salary->month  ?? formatDate(time(), 'm/Y')}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="">{{ trans('Basic Salary') }}:</label>
                                    </div>
                                    <div class="col-6">
                                        <h6>{{ moneyFormat($salary->basic_salary ?? $user->basic_salary) }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                @if($target_by === \Modules\Setting\Model\CommissionRateSetting::PERSON_INCOME)
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="">{{ trans('Personal Total Sales Income') }}:</label>
                                        </div>
                                        <div class="col-6">
                                            <h6>{{ moneyFormat($orders->sum('total_price')) }}</h6>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="">{{ trans('Total Company income') }}:</label>
                                        </div>
                                        <div class="col-6">
                                            <h6>{{ moneyFormat($orders->sum('total_price')) }}</h6>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="">{{ trans('Payment rate') }}:</label>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-success">{{ $salary->payment_rate ?? 0 }}%</span>
                                        <span
                                            class="text-info">(Next target: {{ $user->getNextCommissionRate() }})</span>
                                    </div>
                                </div>
                                @if($target_by === \Modules\Setting\Model\CommissionRateSetting::PERSON_INCOME)
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="">{{ trans('Sale Commission') }}:</label>
                                        </div>
                                        <div class="col-6">
                                            {{ moneyFormat($salary->sale_commission ?? 0) }}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="">{{ trans('Service rate') }}:</label>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-success">{{ $salary->service_rate ?? 0 }}%</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="">{{ trans('Service Commission') }}:</label>
                                        </div>
                                        <div class="col-6">
                                            {{ moneyFormat($salary->service_commission ?? 0) }}
                                        </div>
                                    </div>
                                @endif

                                @if($target_by === \Modules\Setting\Model\CommissionRateSetting::COMPANY_INCOME)
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label for="">{{ trans('Company Income Commission') }}:</label>
                                        </div>
                                        <div class="col-6">
                                            {{ moneyFormat($salary->company_commission ?? 0) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label for="">{{ trans('Total Commission') }}:</label>
                                    </div>
                                    <div class="col-6">
                                        <h6>{{ moneyFormat($salary->total_commission ?? 0) }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="total-salary pt-5">
                                    <h5>
                                        {{ trans('Total Salary:') }}
                                        <span class="text-danger font-size-clearfix">
                                            {{ moneyFormat($salary->total_salary ?? 0) }}
                                        </span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            @php($key = ($orders->currentpage() - 1) * $orders->perpage() + 1)
            <div id="order-list" class="p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h4>{{ trans('Invoice of This Month') }}</h4>
                    @if($user->getTargetBy() === \Modules\Setting\Model\CommissionRateSetting::PERSON_INCOME)
                        <a href="{{ route('get.user.supply_history', $user->id) }}" class="btn btn-info"
                           data-toggle="modal" data-title="{{ trans('Supply History Service') }}"
                           data-target="#form-modal">
                            <i class="fas fa-clipboard-list"></i> {{ trans('Supply History Service') }}
                        </a>
                    @endif
                </div>
                <div class="sumary d-flex justify-content-between">
                    <span class="listing-information">
                        {!! summaryListing($orders) !!}
                    </span>
                    <span class="total-price">
                         <h4>
                             {{ trans('Total:') }}
                             <span class="text-danger font-size-clearfix">
                                 {{ moneyFormat($orders->sum('total_price')) }}
                             </span>
                         </h4>
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans("Order Code") }}</th>
                            <th>{{ trans("Type") }}</th>
                            <th>{{ trans("Status") }}</th>
                            <th>{{ trans("Client Name") }}</th>
                            <th>{{ trans("Total Price") }}</th>
                            <th>{{ trans("Purchase/Abort Time") }}</th>
                            <th>{{ trans("Order Creator") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>
                                    <h5>
                                        <a href="{{ route('get.order.order_detail',$order->id) }}"
                                           data-toggle="modal" data-title="{{ trans('Invoice Detail') }}"
                                           data-target="#form-modal">
                                            {{ $order->code }}
                                        </a>
                                    </h5>
                                </td>
                                <td class="text-capitalize">{{ $order->order_type }}</td>
                                <td>
                                    <span class="@if($order->status === \Modules\Order\Model\Order::STATUS_DRAFT)
                                        text-warning
@elseif($order->status === \Modules\Order\Model\Order::STATUS_PAID)
                                        text-success
@else
                                        text-danger
@endif ">
                                        <h5>{{ $order_statuses[$order->status] }}</h5>
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('get.member.update', $order->member->id) }}"
                                       target="_blank">{{ $order->member->name  }}</a>
                                </td>
                                <td>{{ moneyFormat($order->total_price) }}</td>
                                <td>{{ formatDate(strtotime($order->updated_at), 'd-m-Y H:i') }}</td>
                                <td>{{ $order->creator->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $orders->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
                {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg'])  !!}
                {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'id' => 'salary-modal', 'size' => 'modal-lg'])  !!}
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).on('click', '#print-salary', function () {
            printJS({
                printable: 'salary-info',
                type: 'html',
                css: ['/assets/bootstrap/css/bootstrap.min.css']
            })
        });
    </script>
@endpush
