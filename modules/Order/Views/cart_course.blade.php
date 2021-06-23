@php
    use Modules\Order\Model\Order;
    /** @var Order $order */
    $order_details = $order->orderDetails ?? [];
@endphp
<form action="{{ $order ? route('post.order.purchase_order', $order->id) : '#'}}" method="post" id="cart-form">
    @csrf
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('Course') }}</th>
                <th>{{ trans('Voucher') }}</th>
                <th style="width: 15%">{{ trans('Quantity') }}</th>
                <th>{{ trans('Price') }}</th>
                <th>{{ trans('Total Price') }}</th>
                <th>{{ trans('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order_details as $key => $order_detail)
                <tr>
                    <td>
                        {{ $key+1 }}
                        <input type="hidden" name="product[{{$order_detail->id}}][member_id]"
                               value="{{ $order_detail->order->member->id }}">
                    </td>
                    <td>
                        {{ $order_detail->product->name }}
                        <input type="hidden" name="product[{{$order_detail->id}}][course_id]"
                               value="{{ $order_detail->product->id }}">
                    </td>
                    <td>
                        {{ $order_detail->productVoucher->code ?? NULL }}
                        <input type="hidden" name="product[{{$order_detail->id}}][voucher_id]"
                               value="{{ $order_detail->productVoucher->id ?? NULL }}">
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" name="product[{{$order_detail->id}}][quantity]"
                                   class="form-control item-quantity-cart"
                                   min="1"
                                   value="{{ $order_detail->quantity }}">
                        </div>
                    </td>
                    <td>
                        {{ moneyFormat($order_detail->price) }}
                        <input type="hidden" name="product[{{$order_detail->id}}][price]"
                               value="{{ $order_detail->price }}">
                    </td>
                    <td>{{ moneyFormat($order_detail->amount, 0) }}</td>
                    <td><a href="{{ route('get.order.delete_from_cart', $order_detail->id) }}"
                           class="btn btn-danger btn-delete"><i class="fas fa-trash"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-2">
        <h4>{{ trans('Money to be paid:') }} <span
                class="text-danger font-size-clearfix">{{ moneyFormat($order ? $order->getTotalPrice() : 0) }}</span>
        </h4>

    </div>
    @if($order && !empty($order->orderDetails->count()))
        <div class="modal-footer">
            <button type="submit" class="btn btn-success btn-purchase">{{ trans('Purchase') }}</button>
            <a href="{{ route('get.order.abort_order', $order->id) }}"
               class="btn btn-danger btn-abort">{{ trans('Abort') }}</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('Close') }}</button>
        </div>
    @endif
</form>

<script>
    /***** Action Abort *****/
    $(document).on('click', '.btn-abort', function (e) {
        e.preventDefault();
        var action = $(this).attr('href');
        swal.fire({
            title: "{{ trans('Are you sure?') }}",
            text: "{{ trans("You  will not be able to revert this!") }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "{{ trans('Abort') }}",
            confirmButtonColor: "#d33",
            cancelButtonText: "{{ trans('Cancel') }}",
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.location.replace(action);
            }
        });
    });

    /***** Action Purchase *****/
    $(document).on('click', '.btn-purchase', function (e) {
        e.preventDefault();
        var form = $(document).find('#cart-form');
        var validate = true;
        $('.item-quantity-cart').each(function () {
            var value = $(this).val();
            var parent = $(this).parent('.form-group');
            if (value === "") {
                $(this).val(1);
            }
            if (parseInt(value) < 1) {
                validate = checkInputMinNumber(value, parent);
            }
        });

        if (validate) {
            swal.fire({
                title: "{{ trans('Has the client paid yet?') }}",
                text: "{{ trans("You will not be able to revert this!") }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{ trans('Purchase') }}",
                confirmButtonColor: "#28a745",
                cancelButtonText: "{{ trans('Cancel') }}",
            }).then((willSubmit) => {
                if (willSubmit.isConfirmed) {
                    form.submit()
                }
            });
        }
    });

    /** Validate Quantity */
    $(document).on('change', '.item-quantity-cart', function () {
        var value = $(this).val();
        var parent = $(this).parent('.form-group');
        checkInputMinNumber(value, parent)
    });

    /**
     *
     * @param value
     * @param parent
     * @returns {boolean}
     */
    function checkInputMinNumber(value, parent) {
        var check = true;
        if (parseInt(value) < 1) {
            parent.addClass('has-error')
            parent.removeClass('has-success');
            if (parent.find('.help-block').length === 0) {
                parent.append('<span id="quantity-error" class="help-block error-help-block">{{ trans("Quantity must be greater than 0") }}</span>')
            }
            check = false;
        } else {
            parent.removeClass('has-error');
            parent.addClass('has-success')
            parent.find('.help-block').remove();
        }

        return check;
    }


</script>
