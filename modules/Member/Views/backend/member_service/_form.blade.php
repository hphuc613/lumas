@php
    use Modules\Member\Model\Member;
    use Modules\Member\Model\MemberService;
    /**
     * @var Member $member
     * @var MemberService $member_service
     */
    $member = $member ?? $member_service->member
@endphp
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>{{ trans("Add Service") }}</h5>
        <div class="group-btn product-cart">
            <a href="{{ route('get.order.add_to_cart',[\Modules\Order\Model\Order::SERVICE_TYPE, $member->id]) }}"
               class="btn btn-outline-info p-0"
               data-toggle="modal"
               data-title="{{ trans('Order Detail') }}"
               data-target="#form-modal">
                <div class="cart d-flex justify-content-between">
                    <div class="icon">
                        <i class="fas fa-cart-plus"></i> &nbsp;
                    </div>
                    <span class="number-item text-light bg-info">{{ optional(optional($member->getDraftOrder())->orderDetails)->count() ?? 0}}</span>
                </div>
            </a>
            <a href="{{ route('get.service_voucher.create_popup') }}" class="btn btn-main-color"
               data-toggle="modal"
               data-target="#form-modal" data-title="{{ trans('Create Voucher') }}">
                <i class="fa fa-plus"></i> &nbsp; {{ trans('Add Voucher') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('post.order.add_to_cart') }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="member">{{ trans("Client") }}</label>
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                    <h5 class="text-success">
                        <a href="{{ route('get.member.update',$member->id) }}" target="_blank">
                            {{ $member->name }} | {{ $member->phone }} | {{ $member->email }}
                        </a>
                    </h5>
                </div>
                <div class="form-group col-md-6"></div>
                <div class="form-group col-md-6">
                    <label for="service-form">{{ trans("Service") }}</label>
                    {!! Form::select('service_id', $prompt + $services, NULL, [
                    'id' => 'service-form',
                    'class' => 'select2 form-control service service-relate',
                    'style' => 'width: 100%']) !!}
                    <input type="hidden" name="order_type" value="{{ \Modules\Order\Model\Order::SERVICE_TYPE }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="voucher">{{ trans("Voucher") }}</label>
                    <select name="voucher_id" id="voucher" class="select2 form-control w-100">
                        <option value="">{{ trans("Please Select Service") }}</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="quantity">{{ trans("Quantity") }} </label>
                    <input type="number" name="quantity" id="quantity" class="form-control">
                </div>
                <div class="form-group col-md-12">
                    <label for="remarks">{{ trans("Remarks") }} </label>
                    <textarea name="remarks" class="form-control" rows="5"></textarea>
                </div>
            </div>
            <div class="mt-3 input-group">
                <button type="submit" class="btn btn-main-color" id="btn-add-service">
                    {{ trans("Add") }}
                </button>
            </div>
        </form>
    </div>
</div>
@push('js')
    {!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberServiceRequest') !!}
    <script>
        /** Get service list by service type */
        $(document).on('change', '#service-form', function () {
            var service = $(this);
            var service_id = service.val();
            if (service_id !== '') {
                $.ajax({
                    url: "{{ route('get.service_voucher.get_list_by_service',"") }}/" + service_id,
                    method: "get"
                }).done(function (response) {
                    service.parents('form').find('#voucher').html(response);
                });
            }
        });
    </script>
@endpush
