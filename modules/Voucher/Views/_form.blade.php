<?php

use App\AppHelpers\Helper;

$prompt  = [null => trans('Select')];
$segment = Helper::segment(2)
?>
<form action="" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="service-type">{{ trans('Service Type') }}</label>
            {!! Form::select('', $prompt + $service_types, $voucher->service->type_id ?? null, [
                'id' => 'service-type',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-6 form-group">
            <label for="service">{{ trans('Service') }}</label>
            @if(isset($services))
                {!! Form::select('service_id', $prompt + $services, $voucher->service_id ?? null, [
                'id' => 'service',
                'class' => 'select2 form-control',
                'style' => 'width: 100%']) !!}
            @else
                <select name="service_id" id="service" class="select2 form-control" style="width: 100%">
                    <option value="">{{ trans("Please Select Service Type") }}</option>
                </select>
            @endif
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
@if(\App\AppHelpers\Helper::segment(2) !== 'create')
    {!! JsValidator::formRequest('Modules\Voucher\Http\Requests\VoucherRequest') !!}
    <script>
        /*Get service list by service type*/
        $(document).on('change', '#service-type', function () {
            var service_type = $(this);
            var type_id = service_type.val();
            $.ajax({
                url: "{{ route("get.service.get_list_service_by_type", '') }}/" + type_id,
                method: "get"
            }).done(function (response) {
                service_type.parents('form').find('#service').html(response);
            });
        });
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
            /*Get service list by service type*/
            $(document).on('change', '#service-type', function () {
                var service_type = $(this);
                var type_id = service_type.val();
                $.ajax({
                    url: "{{ route("get.service.get_list_service_by_type", '') }}/" + type_id,
                    method: "get"
                }).done(function (response) {
                    service_type.parents('form').find('#service').html(response);
                });
            });
        </script>
    @endpush
@endif
