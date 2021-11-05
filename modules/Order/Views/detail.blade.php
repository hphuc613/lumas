@php
    use Modules\Order\Model\Order;
    /** @var Order $order */
    $order_details = $order->orderDetails
@endphp
<div id="invoice" class="container">
    <div id="company-info">
        <h3>LUMAS</h3>
        <p class="mb-1">
            {{ trans('Address') }}: Causeway Bay
        </p>
        <p class="mb-1">
            {{ trans('Tel') }}: 123456789
        </p>
        <p class="mb-1">
            {{ trans('Fax') }}: 123456789
        </p>
        <hr>
    </div>
    <div id="content">
        <div class="text-center title">
            <h3>{{ trans('INVOICE') }}</h3>
        </div>
        <div class="info mb-3 row">
            <div class="col-6">
                <div class="row">
                    <div class="col-4">
                        {{ trans('To') }}
                    </div>
                    <div class="col-8">
                        : {{ $order->member->name ?? "N/A"}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Email') }}
                    </div>
                    <div class="col-8">
                        : {{ $order->member->email  ?? "N/A"}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Phone') }}
                    </div>
                    <div class="col-8">
                        : {{ $order->member->phone ?? "N/A" }}
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-4">
                        {{ trans('Invoice code') }}
                    </div>
                    <div class="col-8">
                        : <span
                            class="font-weight-bold">{{ (is_numeric($order->code)) ? 'CWB'.$order->code : $order->code }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Purchase/Abort At') }}
                    </div>
                    <div class="col-8">
                        : {{ formatDate(strtotime($order->updated_at), 'd-m-Y H:i') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Creator') }}
                    </div>
                    <div class="col-8">
                        : {!! $order->creator->name ?? $order->name." <span class='text-danger'>(".trans('This data has been deleted').")</span>" ?? "N/A" !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Created At') }}
                    </div>
                    <div class="col-8">
                        : {{ formatDate(strtotime($order->created_at), 'd-m-Y H:i') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Status') }}
                    </div>
                    <div class="col-8">
                        :
                        <span
                            class="font-weight-bold">{{ \Modules\Order\Model\Order::getStatus()[$order->status] }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        {{ trans('Payment Method') }}
                    </div>
                    <div class="col-8">
                        : {{ $order->paymentMethod->name ?? NULL }}
                    </div>
                </div>
            </div>
        </div>

        <div class="product-list">
            <div class="table-responsive mb-3">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('Product') }}</th>
                        <th>{{ trans('Voucher') }}</th>
                        <th>{{ trans('Cost') }}</th>
                        <th>{{ trans('Discount') }}</th>
                        <th>{{ trans('Price') }}</th>
                        <th class="text-center">{{ trans('Quantity') }}</th>
                        <th>{{ trans('Total Price') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order_details as $key => $order_detail)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                {{ $order_detail->product->name ?? NULL }}
                                <br> {{ moneyFormat($order_detail->product->price ?? 0) }}</td>
                            <td>
                                {{ $order_detail->productVoucher->code ?? NULL }}
                                <br> {{ isset($order_detail->productVoucher) ? moneyFormat($order_detail->productVoucher->price) : NULL }}
                            </td>
                            <td>{{ moneyFormat(empty($order_detail->voucher_id) ? $order_detail->product->price : $order_detail->productVoucher->price) }}</td>
                            <td class="text-center">{{ $order_detail->discount }}%</td>
                            <td>{{ moneyFormat($order_detail->price) }}</td>
                            <td class="text-center">{{ $order_detail->quantity }}</td>
                            <td>{{ moneyFormat($order_detail->amount) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6"></td>
                        <td><h6>{{ trans('Amounts') }}:</h6></td>
                        <td><h6>{{ moneyFormat($order->getTotalPrice()) }}</h6></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container">
    @if($order->status !== \Modules\Order\Model\Order::STATUS_DRAFT)
        <button class="btn btn-primary print"><i class="fas fa-print"></i></button>
    @else
        @php($route = ($order->order_type === \Modules\Order\Model\Order::SERVICE_TYPE) ? route('get.member_service.add',$order->member->id) : route('get.member_course.add',$order->member->id))
        <a href="{{ $route }}"
           class="btn btn-warning text-light"> {{ trans('Go to Purchase') }}</a>
    @endif
</div>

<script>
    $(document).on('click', '.print', function () {
        printJS({
            printable: 'invoice',
            type: 'html',
            css: ['/assets/bootstrap/css/bootstrap.min.css']
        })
    });
</script>
