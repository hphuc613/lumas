<?php

use App\AppHelpers\Helper;

$prompt       = [null => trans('Select')];
$segment      = Helper::segment(2);
?>
<form action="" method="post" id="voucher-form">
    @csrf
    <div class="row">
        <div class="col-md-6 form-group course">
            <label for="course">{{ trans('Course') }}</label>
            {!! Form::select('course_id', $prompt + $courses, $voucher->course_id ?? null, [
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
            <label for="start-at">{{ trans("Start at") }}</label>
            <input type="text" name="start_at" id="start-at" class="form-control date"
                   value="{{ isset($voucher) ? formatDate(strtotime($voucher->start_at)) : old('start_at') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="end-at">{{ trans("End at") }}</label>
            <input type="text" name="end_at" id="end-at" class="form-control date"
                   value="{{ isset($voucher) ? (!empty($voucher->end_at) ? formatDate(strtotime($voucher->end_at)) : null) : old('end_at') }}">
        </div>
        <div class="form-group col-md-6">
            <label for="status">{{ trans("Status") }}</label>
            {!! Form::select('status', $statuses, $voucher->status ?? null, ["id" => "status", "class" => "form-control select2 w-100"]) !!}
        </div>
        <div class="form-group col-md-6">
            <label for="description">{{ trans("Description") }}</label>
            <textarea name="description" id="description" class="form-control"
                      rows="10">{{ $voucher->description ?? old('description') }}</textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="image">{{ trans("Image") }}</label>
            @if(isset($voucher) && !empty($voucher->image))
                <div class="w-100">
                    <img src="{{ asset($voucher->image) }}" class="w-50" alt="{{ asset($voucher->image) }}">
                </div>
            @endif
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="image" id="image"
                       value="{{ $voucher->image ?? old('image') }}">
                <div class="input-group-prepend">
                    <button class="btn btn-main-color btn-elfinder"
                            type="button">{{ trans("Open File Manager") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="input-group">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans("Save") }}</button>
        <button type="reset" class="btn btn-default">{{ trans("Reset") }}</button>
    </div>
</form>
@if($segment === "create-popup")
    {!! JsValidator::formRequest('Modules\Voucher\Http\Requests\CourseVoucherRequest') !!}
@else
    @push('js')
        {!! JsValidator::formRequest('Modules\Voucher\Http\Requests\CourseVoucherRequest') !!}
    @endpush
@endif
