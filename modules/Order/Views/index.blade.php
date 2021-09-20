@extends("Base::layouts.master")
<?php
use Modules\Order\Model\Order;
/**
 * @var Order $orders
 */
$key = ($orders->currentpage() - 1) * $orders->perpage() + 1;
?>

@section("content")
    <div id="order-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Order") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Order Listing") }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>
    <!--Search box-->
    <div class="search-box">
        <div class="card">
            <div class="card-header" data-toggle="collapse" data-target="#form-search-box" aria-expanded="false"
                 aria-controls="form-search-box">
                <div class="title">{{ trans("Search") }}</div>
            </div>
            <div class="card-body collapse show" id="form-search-box">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Order code") }}</label>
                                <input type="text" class="form-control" id="text-input" name="code"
                                       value="{{ $filter['code'] ?? NULL }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Order Type") }}</label>
                                {!! Form::select('order_type', ["" => trans("All")] + $order_types, $filter['order_type'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Client") }}</label>
                                {!! Form::select('member_id', ["" => trans("All")] + $members, $filter['member_id'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Status") }}</label>
                                {!! Form::select('status', ["" => trans("All")] + $statuses, $filter['status'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Month") }}</label>
                                <input type="text" name="month" class="form-control month"
                                       value="{{ $filter['month'] ?? NULL }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Creator") }}</label>
                                {!! Form::select('creator', ["" => trans("All")] + $creators, $filter['creator'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-main-color mr-2">{{ trans("Search") }}</button>
                        <button type="button" class="btn btn-default clear">{{ trans("Cancel") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="listing">
        <div class="card">
            <div class="card-body">
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
                            <th class="action text-center">{{ trans("Action") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td><h5>{{ $order->code }}</h5></td>
                                <td>{{ $order_types[$order->order_type] }}</td>
                                @php
                                    $bg_status = "bg-danger";
                                    if($order->status === \Modules\Order\Model\Order::STATUS_DRAFT)
                                        $bg_status = 'bg-warning';
                                    elseif($order->status === \Modules\Order\Model\Order::STATUS_PAID)
                                       $bg_status = 'bg-success';
                                @endphp
                                <td>
                                    <span class="status-box {{ $bg_status }}">

                                        {{ $statuses[$order->status] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('get.member.update', $order->member->id) }}"
                                       target="_blank">{{ $order->member->name  }}</a>
                                </td>
                                <td>{{ moneyFormat($order->total_price) }}</td>
                                <td>{{ formatDate(strtotime($order->updated_at), 'd-m-Y H:i') }}</td>
                                <td>{{ $order->creator->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('get.order.order_detail',$order->id) }}"
                                       class="btn btn-outline-primary"
                                       data-toggle="modal" data-title="{{ trans('Order Detail') }}"
                                       data-target="#form-modal">
                                        <i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $orders->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg'])  !!}
@endsection
