<?php

use App\AppHelpers\Helper;

$prompt       = [null => trans('Select')];
$segment      = Helper::segment(2);
$request_type = request()->type;
?>
<form action="" method="post" id="voucher-form">
    @csrf
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="service-type">{{ trans('Type') }}</label>
            {!! Form::select('type', $types, $request_type ?? $voucher->type ?? null, [
                'id' => 'type',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group service">
            <label for="service">{{ trans('Service') }}</label>
            {!! Form::select('parent_id', $prompt + $services, $voucher->parent_id ?? null, [
            'id' => 'service',
            'class' => 'select2 form-control',
            'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group course">
            <label for="service">{{ trans('Course') }}</label>
            {!! Form::select('parent_id', $prompt + $courses, $voucher->parent_id ?? null, [
            'id' => 'course',
            'class' => 'select2 form-control',
            'style' => 'width: 100%']) !!}
        </div>
        <div class="form-group col-md-6">
            <label for="code">{{ trans("Code") }}</label>
            <input type="text" name="code" class="form-control" value="{{ $voucher->code ?? old('code') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="price">{{ trans("Price") }}</label>
            <input type="text" name="price" class="form-control" value="{{ $voucher->price ?? old('price') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="start-at">{{ trans("Start day") }}</label>
            <input type="text" name="start_at" id="start-at" class="form-control date"
                   value="{{ isset($voucher) ? formatDate($voucher->start_at) : old('start_at') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="end-at">{{ trans("End day") }}</label>
            <input type="text" name="end_at" id="end-at" class="form-control date"
                   value="{{ isset($voucher) ? (!empty($voucher->end_at) ? formatDate($voucher->end_at) : null) : old('end_at') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="status">{{ trans("Status") }}</label>
            {!! Form::select('status', $statuses, $voucher->status ?? null, ["id" => "status", "class" => "form-control select2 w-100"]) !!}
        </div>
        <div class="form-group col-md-6">
            <label for="image">{{ trans("Image") }}</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="image" id="image"
                       value="{{ $voucher->image ?? old('image') }}">
                <div class="input-group-prepend">
                    <button class="btn btn-primary btn-elfinder"
                            type="button">{{ trans("Open File Manager") }}</button>
                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="description">{{ trans("Description") }}</label>
            <textarea name="description" id="description" class="form-control"
                      rows="10">{{ $voucher->description ?? old('description') }}</textarea>
        </div>
        <div class="col-md-6">
            @if(isset($voucher))
                <img src="{{ asset($voucher->image) }}" class="w-50" alt="{{ asset($voucher->image) }}">
            @endif
        </div>
    </div>

    <div class="input-group">
        <button type="submit" class="btn btn-primary mr-2">{{ trans("Save") }}</button>
        <button type="reset" class="btn btn-default">{{ trans("Reset") }}</button>
    </div>
</form>
@if($segment === "create-popup")
    {!! JsValidator::formRequest('Modules\Voucher\Http\Requests\VoucherRequest') !!}
    <script>
        $('#voucher-form #type').prop('disabled', true);
        @if($request_type === \Modules\Voucher\Model\Voucher::COURSE_TYPE)
        $(".service").hide();
        $('#voucher-form #service').prop('disabled', true);
        @else
        $(".course").hide();
        $('#voucher-form #course').prop('disabled', true);
        @endif
        $(document).on("change", '#type', function () {
            if ($(this).val() === "{{ \Modules\Voucher\Model\Voucher::COURSE_TYPE }}") {
                $('#voucher-form #course').prop('disabled', false);
                $('#voucher-form #service').prop('disabled', true);
                $(".course").show();
                $(".service").hide();
            } else {
                $('#voucher-form #course').prop('disabled', true);
                $('#voucher-form #service').prop('disabled', false);
                $(".service").show();
                $(".course").hide();
            }
        })


        $(".btn-elfinder").click(function () {
            @php
                $locale = session()->get('locale');
                if($locale === 'cn'){
                    $locale = 'zh_TW';
                }
            @endphp
            openElfinder($(this), '{{ route("elfinder.connector") }}', '{{ asset("packages/barryvdh/elfinder/sounds") }}', "{{ $locale }}", '{{ csrf_token() }}');
        })
    </script>
@else
    @push('js')
        {!! JsValidator::formRequest('Modules\Voucher\Http\Requests\VoucherRequest') !!}
        <script>
            @if(isset($voucher) && $voucher->type === \Modules\Voucher\Model\Voucher::COURSE_TYPE)
            $(".service").hide();
            $('#voucher-form #service').prop('disabled', true);
            @else
            $(".course").hide();
            $('#voucher-form #course').prop('disabled', true);
            @endif

            $(document).on("change", '#type', function () {
                if ($(this).val() === "{{ \Modules\Voucher\Model\Voucher::COURSE_TYPE }}") {
                    $('#voucher-form #course').prop('disabled', false);
                    $('#voucher-form #service').prop('disabled', true);
                    $(".course").show();
                    $(".service").hide();
                } else {
                    $('#voucher-form #course').prop('disabled', true);
                    $('#voucher-form #service').prop('disabled', false);
                    $(".service").show();
                    $(".course").hide();
                }
            })
        </script>
    @endpush
@endif
