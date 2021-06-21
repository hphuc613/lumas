<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>{{ !isset($member_course) ? trans("Add Course") : trans("Update Course") }}</h5>
        <div class="group-btn">
            @if(isset($member_course))
                @if($member_course->getRemaining() > 0 && $member_course->status === \Modules\Member\Model\MemberCourse::PROGRESSING_STATUS)
                    <a href="{{ route('get.member_course.e_sign',$member_course->id) }}"
                       class="btn btn-info" data-toggle="modal" data-target="#form-modal"
                       data-title="{{ trans('E-sign') }}">
                        <i class="fas fa-file-signature"></i>
                    </a>
                @endif
            @endif
            <a href="{{ route('get.course_voucher.create_popup') }}" class="btn btn-main-color"
               data-toggle="modal"
               data-target="#form-modal" data-title="{{ trans('Create Voucher') }}">
                <i class="fa fa-plus"></i> &nbsp; {{ trans('Add Voucher') }}
            </a>
        </div>
    </div>
    @php($route_form = !isset($member_course) ? route('post.member_course.add', $member->id) : route('post.member_course.edit', $member_course->id))
    <div class="card-body">
        <form action="{{$route_form}}" method="post">
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
                <div class="form-group col-md-6">
                    @if(isset($member_course))
                        <label>{{ trans("Code") }}</label>
                        <h5 class="text-info">
                            {{ $member_course->code }}
                        </h5>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="course-form">{{ trans("Course") }}</label>
                    {!! Form::select('course_id', $prompt + $courses, $member_course->course_id ?? null, [
                    'id' => 'course-form',
                    'class' => 'select2 form-control course course-relate',
                    'style' => 'width: 100%']) !!}
                    @if(isset($member_course))
                        <input type="hidden" name="course_id" value="{{ $member_course->course_id }}">
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="voucher">{{ trans("Voucher") }}</label>
                    @if(!isset($member_course))
                        <select name="voucher_id" id="voucher" class="select2 form-control w-100">
                            <option value="">{{ trans("Please Select Course") }}</option>
                        </select>
                    @else
                        {!! Form::select('voucher_id', $prompt + $vouchers, $member_course->voucher_id ?? null, [
                        'id' => 'voucher',
                        'class' => 'select2 form-control course-relate',
                        'style' => 'width: 100%']) !!}
                        @if(isset($member_course))
                            <input type="hidden" name="voucher_id" value="{{ $member_course->voucher_id }}">
                        @endif
                    @endif
                </div>
                @if(isset($member_course))
                    <div class="form-group col-md-6">
                        <label for="remaining-quantity">{{ trans("Total Quantity") }}</label>
                        <h5 class="text-danger">
                            {{ $member_course->quantity }}
                        </h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="remaining-quantity">{{ trans("Remaning Quantity") }}</label>
                        <h5 class="text-danger">
                            {{ $member_course->getRemaining() }}
                        </h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="status">{{ trans("Status") }}</label>
                        {!! Form::select('status', $statuses, $member_course->status ?? null, [
                        'id' => 'status',
                        'class' => 'select2 form-control course-relate',
                        'style' => 'width: 100%']) !!}
                    </div>
                @endif
                <div class="form-group col-md-6">
                    @if(isset($member_course))
                        <label for="add-more-quantity">{{ trans("Add More Quantity") }}</label>
                        <input type="number" name="add_more_quantity" id="add-more-quantity" class="form-control"
                               @if(isset($member_course) && $member_course->getRemaining() == 0) readonly @endif>
                    @else
                        <label for="quantity">{{ trans("Quantity") }} </label>
                        <input type="number" name="quantity" id="quantity" class="form-control">
                    @endif

                </div>
                <div class="form-group col-md-12">
                    <label for="remarks">{{ trans("Remarks") }}</label>
                    <textarea class="form-control" name="remarks" id="remarks"
                              @if(isset($member_course) && $member_course->getRemaining() == 0) readonly @endif
                              rows="5">{{ $member_course->remarks ?? old('remarks') }}</textarea>
                </div>
            </div>
            @if(!isset($member_course) || $member_course->getRemaining() > 0)
                <div class="input-group">
                    <button type="submit" class="btn btn-main-color" id="btn-add-course">
                        @if(isset($member_course)) {{ trans("Update") }} @else {{ trans("Add") }} @endif
                    </button>
                </div>
            @endif
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
        @if(isset($member_course))
        $(".course-relate").prop("disabled", true);
        @endif
    </script>
@endpush