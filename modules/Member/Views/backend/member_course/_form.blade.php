@php
    use Modules\Member\Model\Member;
    use Modules\Member\Model\MemberCourse;
    use Modules\Order\Model\Order;
    /**
     * @var Member $member
     * @var MemberCourse $member_course
     */
    $member = $member ?? $member_course->member;
    $order_type = Order::COURSE_TYPE;
@endphp
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>{{ trans("Add Course") }}</h5>
        <div class="group-btn product-cart">
            <a href="{{ route('get.order.add_to_cart',[$order_type, $member->id]) }}"
               class="btn btn-outline-info p-0"
               data-toggle="modal"
               data-title="{{ trans('Invoice Detail') }}"
               data-target="#form-modal">
                <div class="cart d-flex justify-content-between">
                    <div class="icon">
                        <i class="fas fa-cart-plus"></i> &nbsp;
                    </div>
                    <span
                        class="number-item text-light bg-info">{{ optional(optional($member->getDraftOrder($order_type))->orderDetails)->count() ?? 0}}</span>
                </div>
            </a>
            <a href="{{ route('get.course_voucher.create_popup') }}" class="btn btn-main-color"
               data-toggle="modal"
               data-target="#form-modal" data-title="{{ trans('Create Voucher') }}">
                <i class="fa fa-plus"></i> &nbsp; {{ trans('Add Voucher') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('post.order.add_to_cart_course') }}" method="post">
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
                    <label for="course-form">{{ trans("Course") }}</label>
                    {!! Form::select('course_id', $prompt + $courses, NULL, [
                    'id' => 'course-form',
                    'class' => 'select2 form-control course course-relate',
                    'style' => 'width: 100%']) !!}
                    <input type="hidden" name="order_type" value="{{ $order_type }}">
                </div>
                <div class="form-group col-md-6">
                    <label for="voucher">{{ trans("Voucher") }}</label>
                    <select name="voucher_id" id="voucher" class="select2 form-control w-100">
                        <option value="">{{ trans("Please Select Course") }}</option>
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
    {!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberCourseRequest') !!}
    <script>
        /** Get voucher list by course */
        $(document).on('change', '#course-form', function () {
            var course = $(this);
            var course_id = course.val();
            $.ajax({
                url: "{{ route('get.course_voucher.get_list_by_course',"") }}/" + course_id,
                method: "get"
            }).done(function (response) {
                course.parents('form').find('#voucher').html(response);
            });
        });
    </script>
@endpush
